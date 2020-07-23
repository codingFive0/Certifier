<?php

namespace codingFive0\soluti;

class Emitter extends Certifyer
{
    private $csr;
    private $privateKey;
    private $publicKey;
    private $signature;
    private $emissionData;

    private $solicitation;

    private $file;

    private $certificate;

    public function __construct(string $solicitation)
    {
        parent::__construct();
        $this->solicitation = $solicitation;
        $this->file = new Files($this->solicitation);
    }

    public function emission()
    {
        if (empty($this->emissionData)) {
            return false;
        }

        if (!$this->createSignature()) {
            return false;
        }

        if (empty($this->emissionData) || empty($this->privateKey) || empty($this->csr) || empty($this->signature)) {
            return false;
        }

        $this->sessionStart($this->solicitation);

        $arrData = [
            "solicitacao" => $this->solicitation,
            "tipo" => "PKCS7",
            "computador" => "Nome do computador e inf",
            "sistema_operacional" => "Linux Ubuntu S.O.",
            "versao" => "Soluti AC v1.2.2 - java - v1.7.0",
            "interfaces" => [
                [
                    "nome" => "Realtek RLT8899 (Wlan0)",
                    "ip" => "192.168.1.2",
                    "mac" => "12-34-56-78-9A-BC"
                ]
            ],
            "csr" => $this->csr,
            "digitalterm_signature" => $this->signature
        ];

        $this->setPostMethod();
        $this->setMessage($arrData);
        $this->hmacUpdate(false, 1);
        $this->setMessage(
            [
                "solicitacao" => $this->solicitation,
                "hmac" => $this->getHmac(),
                "mensagem" => json_encode($this->getMessage()),
                "nonce1" => $this->getNonce(),
                "nonce2" => $this->getNonceResponse()
            ]
        );

        $this->request("webservice/emitir-certificado");
        return $this;
    }

    public function verifyEmission()
    {
        //OBS: ENVIAR SENHA NO LUGAR DA [PRE-SHARED KEY] !!!!!!
        $this->setPostMethod();
        $this->setMessage(["mensagem" => $this->solicitation]);
        $this->hmacUpdate();
        $this->setMessage(
            [
                "solicitacao" => $this->solicitation,
                "hmac" => $this->getHmac(),
                "mensagem" => json_encode($this->getMessage()),
                "nonce1" => $this->getNonce()
            ]
        );
        $this->request("webservice/valida-dados-emissao");

        return $this;
    }

    public function getCertificate()
    {
        //OBS: ENVIAR SENHA NO LUGAR DA [PRE-SHARED KEY] !!!!!!
        $this->setPostMethod();
        $this->setMessage(["mensagem" => $this->solicitation]);
        $this->hmacUpdate();
        $this->setMessage(
            [
                "solicitacao" => $this->solicitation,
                "hmac" => $this->getHmac(),
                "mensagem" => json_encode($this->getMessage()),
                "nonce1" => $this->getNonce()
            ]
        );
        $this->request("webservice/recuperar-certificado");
        return $this;
    }
    
    public function setSolicitationData(object $mensagem, string $country, string $state, string $city)
    {
        $this->emissionData = [
            "countryName" => $country,
            "stateOrProvinceName" => $state,
            "localityName" => $city,
            "organizationName" => $mensagem->mensagem->certificado->categoria,
            "organizationalUnitName" => $mensagem->mensagem->certificado->categoria,
            "commonName" => $mensagem->mensagem->certificado->categoria . " UNIT",
            "emailAddress" => $mensagem->mensagem->certificado->email,
            "digitalterm_hash" => $mensagem->mensagem->emissao->digitalterm_hash
        ];
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    private function createSignature()
    {
        $config = [
            "key_config" => [
                "digest_alg" => "sha512",
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ],
            "csr_config" => [
                "digest_alg" => "sha256"
            ],
            "dn" => $this->emissionData
        ];

        //Verifica existencia de uma chave privada criada anteriormente
        if (!$this->file->heavePublicKey()) {
            $private = openssl_pkey_new($config["key_config"]);
            openssl_pkey_export($private, $this->privateKey);
            $this->file->filePrivateKey($this->privateKey); // salvar em arquivo ou banco
        } else {
            $this->privateKey = $this->file->getPrivateKey();
        }

        //Verifica existencia de uma chave publica criada anteriormente
        if (!$this->file->heavePublicKey()) {
            $this->publicKey = openssl_pkey_get_details($private)["key"];
            $this->file->filePublicKey($this->publicKey); // salvar em arquivo ou banco
        } else {
            $this->privateKey = $this->file->getPublicKey();
        }

        //verifica existencia de um CSR gerado anteriormente
        if (!$this->file->heaveCSR()) {
            $this->csr = openssl_csr_new($config["dn"], $this->privateKey, $config["csr_config"]);
            openssl_csr_export($this->csr, $csr); //mandar na requisição
            $this->file->fileCSR($csr);
        } else {
            $this->csr = $this->file->getCSR();
        }


        if (!empty($config["dn"]["digitalterm_hash"])) {

            if (!$this->file->getTempFile("digitalterm")) {
                $this->file->fileTempDir("digitalterm", hex2bin($config["dn"]["digitalterm_hash"]));
            }

            exec("openssl rsautl -sign -inkey {$this->file->getSolicitationDir()}/privateKey.key -out {$this->file->getSolicitationDir()}/signature.der -in {$this->file->getTempDir()}/digitalterm.txt", $output);

            if (!$this->file->heaveSignature()) {
                return false;
            }

            $bytesAssinatura = $this->file->getSignature();

            $this->signature = base64_encode($bytesAssinatura); //formatar linhas e cabeçalho
            $strLen = mb_strlen($this->signature);

            $signatureContent = "";

            for ($i = 0; $i < $strLen; $i++) {
                if ($i != 0 && $i % 64 == 0) {
                    $this->signature .= "\n";
                }
                $signatureContent .= $this->signature[$i];
            }

            $this->signature = "-----BEGIN PKCS1-----\n" . $signatureContent . "\n-----END PKCS1-----";

            exec("openssl rsautl -verify -pubin -inkey {$this->file->getSolicitationDir()}/publicKey.key -keyform pem -in {$this->file->getSolicitationDir()}/signature.der > {$this->file->getTempDir()}/verifyOutput.txt", $output);

            $signatureBytes = file_get_contents("{$this->file->getTempDir()}/verifyOutput.txt");

            $byteshash = bin2hex($signatureBytes);
            if ($byteshash !== $config["dn"]["digitalterm_hash"]) {
                return false;
            }

            $this->file->flushTempFiles(true);
            return true;
        }

        return false;
    }
}