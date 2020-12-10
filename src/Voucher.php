<?php

namespace codingFive0\Certifier;

/**
 * Class Voucher
 * @package codingFive0\Certifier
 */
class Voucher
{
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $nonce1;
    /**
     * @var string
     */
    private $serviceUrl;
    /**
     * @var
     */
    private $endpoint;
    /**
     * @var
     */
    private $hmac;
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $requestBody;
    /**
     * @var
     */
    private $callback;

    /**
     * Voucher constructor.
     * @param string $user
     * @param string $key
     * @param string $serviceUrl
     * @throws \Exception
     */
    public function __construct(string $user, string $key, string $serviceUrl)
    {
        $this->user = $user;
        $this->key = $key;
        $this->nonce1 = time() . random_int(1000, 9999);
        $this->serviceUrl = $serviceUrl;
    }

    /**
     *
     */
    private function hmac()
    {
        $data = implode("", $this->data);
        $hkey = $this->nonce1 . $this->key;
        $hash_hkey = hash("sha256", $hkey);
        $hash_hkey_dados = hash("sha256", $hkey . $data);
        $hmac = hash("sha256", $hash_hkey . $hash_hkey_dados);
        $this->hmac = $hmac;
    }

    /**
     * <b>Gerador de Voucher</b>
     * Gera um novo Voucher para o cliente conforme as infa=ormações foram passadas.
     *
     * @param int $produto
     * @param int $negociacao
     * @param int $sequencia
     * @param string|null $cpfcnpj
     * @param string|null $nomeCliente
     * @param int|null $codVenda
     * @param bool $restricao
     */
    public function getVouncher(int $produto, int $negociacao, int $sequencia, string $cpfcnpj = null, string $nomeCliente = null, int $codVenda = null, $restricao = true)
    {
        $this->endpoint = "getVoucherNegociacao";

        $this->data = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "codProduto" => $produto,
            "codvenda" => ($codVenda ?? ""),
            "negociacao" => $negociacao,
            "sequencia" => $sequencia,
            "sujestao" => ($nomeCliente ?? ""),
            "cpfcnpj" => ($cpfcnpj ?? ""),
            "restrito" => ($restricao == true ? "true" : "false"),
        ];

        $this->hmac();

        $this->requestBody = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "codProduto" => $produto,
            "Codvenda" => ($codVenda ?? ""),
            "Negociacao" => $negociacao,
            "sequencia" => $sequencia,
            "sujestao" => ($nomeCliente ?? ""),
            "cpf-cnpj" => ($cpfcnpj ?? ""),
            "restrito" => ($restricao == true ? "true" : "false"),
            "hmac" => $this->hmac
        ];

        $this->request();

        return $this;
    }

    /**
     * @param string $voucher
     *
     * <b>Status Codes</b>
     * <p><i>2</i> - Alocado e Disponivel para utilização</p>
     * <p><i>3</i> - Voucher vencido ou utilizado</p>
     */
    public function verifyVoucher(string $voucher)
    {
        $this->endpoint = "situacaoVoucher";

        $this->data = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "voucher" => $voucher
        ];

        $this->hmac();

        $this->requestBody = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "voucher" => $voucher,
            "hmac" => $this->hmac
        ];

        $this->request();
        return $this;
    }

    /**
     * <b>Cancela o Voucher informado</b>
     *
     * @param string $voucher
     */
    public function cancelVoucher(string $voucher)
    {
        $this->endpoint = "cancelarvoucher";

        $this->data = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "voucher" => $voucher
        ];

        $this->hmac();

        $this->requestBody = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "voucher" => $voucher,
            "hmac" => $this->hmac
        ];

        $this->request();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @throws \SoapFault
     */
    private function request()
    {
        $soap = new \SoapClient($this->serviceUrl, [
            "trace" => 1,
            "soap_version" => SOAP_1_2,
            "cache_wsdl" => WSDL_CACHE_NONE,
            "stream_context" => stream_context_create(
                [
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false
                    ]
                ])
        ]);

        $this->callback = call_user_func_array([$soap, $this->endpoint], $this->requestBody);
    }
}