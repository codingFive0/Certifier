<?php

namespace codingFive0\certifier;

abstract class Certifyer
{
    /** @var string Hash (composta) de criptografia para segurança criada a partir do cliente */
    private $hmac;

    /** @var string Hash (composta) de criptografia para segurança enviada com a resposata do servidor */
    private $hmacResponse;

    /** @var int Numero pseudoaleatorio gerado com base em timestamp pelo cliente */
    private $nonce;

    /** @var int Numero pseudoaleatorio gerado com base em timestamp obtido na resposta do servirdor */
    private $nonceResponse;

    /** @var string String JSON onde circula parametros de solicitação e resposta */
    protected $message;

    /** @var bool|string Iniciada ou não a sessão de comunicação entre servidor e cliente */
    private $sessionStatus = false;

    /** @var string (Endpoint) Url base para requisições. */
    private $serviceUrl;

    /** @var string (Chave de API) Chave unica para consumo da API. Cedida pelo fornecedor do serviço. */
    private $preSharedKey;

    /** @var string <b>Endpoint</b> Independende a cada solicitação e cada objetivo. */
    private $endpoint;

    /** @var string <b>Metodo<b/> da requisição */
    private $method;

    /** @var array <b>Campos</b> para postagem no metodo <em>POST</em> */
    private $fields;

    /** @var array <b>headers</b> para envio junto a requisição CURL */
    private $headers;

    /** @var null|object Retorno de dados por parte da octadesk */
    private $callback;

    /** @var string <b>Erro</b> por mensagem do <em>Componente</em>. Sem interação com a API */
    private $sessionId;


    public function __construct()
    {
        $this->preSharedKey = "[PRE-SHARED KEY]";
        $this->serviceUrl = "https://arsolutihom.acsoluti.com.br";

        $this->headers[] = "Content-Type: application/json";
        $this->headers[] = "accept: application/json";
    }

    protected function sessionStart($solicitation)
    {
        $this->setMessage(["session" => "_Sinerg_HMAC_Session_Request_"]);
        $this->hmacUpdate();
        $this->fields = [
            "hmac" => $this->getHmac(),
            "nonce1" => $this->getNonce(),
            "mensagem" => json_encode($this->getMessage()),
            "solicitacao" => $solicitation
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->serviceUrl}/webservice/emitir-certificado",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->fields,
            CURLOPT_HEADER => true
        ));

        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        curl_close($curl);

        $header = substr($response, 0, $header_size);
        $phpSesId = explode(" ", substr($header, strpos($header, "Set-Cookie: "), strpos($header, ";") - strpos($header, "Set-Cookie: ")))[1];
        $this->sessionId = $phpSesId;
        $this->callback = json_decode(substr($response, $header_size));

        $this->nonceResponse = $this->callback->nonce2;
        $this->hmacResponse = $this->callback->hmac;

        if ($this->callback->mensagem->session === "_Sinerg_HMAC_Session_Response_") {
            $this->sessionStatus = true;
        }

        $this->msgClear();
    }

    /* #####################################
     ########## APP RULE METHODS  ##########
     ####################################### */
    protected function setMessage($data)
    {
        $this->message = $data;
        return $this;
    }

    protected function getMessage()
    {
        return $this->message;
    }

    protected function msgClear()
    {
        $this->message = null;
    }

    protected function hmacUpdate(bool $updateNonce = true, $counter = null)
    {
        if ($updateNonce) {
            $this->generateNonce();
        }

        $this->generateHmac($counter);
    }

    protected function getHmac()
    {
        return $this->hmac;
    }

    protected function getNonce()
    {
        return $this->nonce;
    }

    protected function getNonceResponse()
    {
        return $this->nonceResponse;
    }

    public function setPreSharedKey(string $key)
    {
        $this->preSharedKey = $key;
        return $this;
    }

    protected function getHeaders()
    {
        return $this->headers;
    }

    /* ###### PRIVATES METHODS ###### */

    /**
     * Generate HMAC Authentication code
     * @return string
     */
    private function generateHmac(string $counter = null)
    {
        $preHmac = hash("sha256", "{$this->nonce}{$this->preSharedKey}{$counter}{$this->nonceResponse}");
        $this->hmac = hash(
            "sha256",
            $preHmac . json_encode($this->message)
        );

        return $this->hmac;
    }

    /**
     * Generate updated nonce code
     * @return $this
     * @throws \Exception
     */
    private function generateNonce()
    {
        $this->nonce = random_int(1000, 9999) . time();
        return $this;
    }

    /* #####################################
     ########## STANDARD METHODS  ##########
     ####################################### */

    public function getCallback()
    {
        $call = $this->callback;
        $this->callback = null;
        return $call;
    }

    /**
     * Informa credencias de utilização da API para ambiente de PRODUÇÃO
     * @param string $preSharedKey
     * @param string $serviceUrl
     * @param bool $setDebugMode
     */
    public function setCredentials(string $preSharedKey = "[PRE-SHARED KEY]", string $serviceUrl = "https://crm-dev.solutiny.br", bool $setDebugMode = true)
    {
        if ($setDebugMode) {
            $this->preSharedKey = "[PRE-SHARED KEY]";
            $this->serviceUrl = "https://crm-dev.solutiny.br";
        } else {
            $this->preSharedKey = $preSharedKey;
            $this->serviceUrl = $serviceUrl;
        }
    }

    /**
     * Seta metodo de envio da requisição via GET
     * @return $this
     */
    protected function setGetMethod()
    {
        $this->method = "GET";
        return $this;
    }

    /**
     * Seta metodo de envio da requisição via POST
     * @return $this
     */
    protected function setPostMethod()
    {
        $this->method = "POST";
        return $this;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array|null $fields
     * @param array|null $headers
     */
    protected function request(string $endpoint, array $headers = null)
    {
        $this->endpoint = $endpoint;
        $this->setHeaders($headers);

        $this->dispatch();
    }

    /**
     * @param array|null $headers
     */
    private function setHeaders($headers = null)
    {
        if (!$headers) {
            return;
        }

        foreach ($headers as $key => $header) {
            $this->headers[] = "{$key}: {$header}";
        }
    }

    /**
     * Executa a requisição
     */
    private function dispatch()
    {
        $this->fields = $this->message;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->serviceUrl}/{$this->endpoint}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($this->fields),
            CURLOPT_COOKIE => $this->sessionId,
        ));

        $this->callback = json_decode(curl_exec($curl));
        curl_close($curl);
    }

    public function __clone()
    {
    }
}