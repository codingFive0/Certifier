<?php

namespace codingFive0\soluti;

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

    public function __construct()
    {
        $this->user = "contaagil";
        $this->key = "3778616349b2cf315806b245b35b122907d77bad82cd5bb14463fc4c405edf72";
        $this->nonce1 = time() . random_int(1000, 9999);
        $this->serviceUrl = "https://gvshom.ca.inf.br/GVS/webservices/GVSServices.jws?wsdl";
    }

    private function hmac()
    {
        $data = implode("", $this->data);
        var_dump($this->data);
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
            "negociacao" => $negociacao,
            "sequencia" => $sequencia,
            "Codvenda" => $codVenda,
            "restricao" => ($restricao == true ? "true" : "false"),
            "sujestao" => $nomeCliente,
            "cpfcnpj" => $cpfcnpj,
        ];

        $this->hmac();

        $this->requestBody = [
            "usuario" => $this->user,
            "nonce" => $this->nonce1,
            "codProduto" => $produto,
            "Negociacao" => $negociacao,
            "sequencia" => $sequencia,
            "Codvenda" => $codVenda,
            "restricao" => ($restricao == true ? "true" : "false"),
            "sujestao" => $nomeCliente,
            "cpf-cnpj" => $cpfcnpj,
            "hmac" => $this->hmac
        ];

        var_dump($this);

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
//        $this->callback = $soap->__call($this->endpoint, $this->requestBody);
        var_dump(call_user_func_array([$soap, $this->endpoint], $this->requestBody));
//        $this->request();
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
//        $this->callback = $soap->__call($this->endpoint, $this->requestBody);
        var_dump(call_user_func_array([$soap, $this->endpoint], $this->requestBody));

    }
}