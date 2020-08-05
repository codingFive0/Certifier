<?php

namespace codingFive0\Certifier;

class Voucher
{
    private $user;
    private $key;
    private $nonce1;
    private $serviceUrl;
    private $endpoint;
    private $hmac;
    private $data;
    private $requestBody;
    private $callback;

    public function __construct(string $user, string $key, string $serviceUrl)
    {
        $this->user = $user;
        $this->key = $key;
        $this->nonce1 = time() . random_int(1000, 9999);
        $this->serviceUrl = $serviceUrl;
    }

    private function hmac()
    {
        $data = implode("", $this->data);
        $hkey = $this->nonce1 . $this->key;
        $hash_hkey = hash("sha256", $hkey);
        $hash_hkey_dados = hash("sha256", $hkey . $data);
        $hmac = hash("sha256", $hash_hkey . $hash_hkey_dados);
        $this->hmac = $hmac;
    }

    public function getVouncher(string $produto, string $negociacao, string $sequencia, string $cpfcnpj = null, string $nomeCliente = null, int $codVenda = null, bool $restricao = true)
    {
        $this->endpoint = "getVoucherNegociacao";

        $this->data = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "codProduto" => $produto,
            "Codvenda" => ($codVenda ?? ""),
            "negociacao" => $negociacao,
            "sequencia" => $sequencia,
            "restricao" => ($restricao == true ? "true" : ""),
            "sujestao" => ($nomeCliente ?? ""),
            "cpfcnpj" => ($cpfcnpj ?? ""),
        ];

        $this->hmac();

        $this->requestBody = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "codProduto" => $produto,
            "Codvenda" => ($codVenda ?? ""),
            "Negociacao" => $negociacao,
            "sequencia" => $sequencia,
            "restricao" => ($restricao == true ? "true" : ""),
            "sujestao" => ($nomeCliente ?? ""),
            "cpf-cnpj" => ($cpfcnpj ?? ""),
            "hmac" => $this->hmac
        ];

        $this->request();
    }


    public function getCallback()
    {
        return $this->callback;
    }

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