<?php

namespace codingFive0\soluti;

class Certificate
{
    private $privateKey;

    private $p7b;

    public function __construct()
    {
//        openssl pkcs7 -print_certs -in certificate.p7b -out certificate.cer openssl pkcs12 -export -in certificate.cer -inkey privateKey.key -out certificate.pfx -certfile CACert.cer

        $this->p7b = $p7b;
        $this->privateKey = $privateKey;
        $this->directoryTarget = dirname(__DIR__, 1) . "/certicates/";
        $this->directoryResurces = dirname(__DIR__, 1) . "/tempDirFiles/";
    }

    public function setDirectoryTarget(string $dirTarget)
    {
        $this->directoryTarget = $dirTarget;
        return $this;
    }

    public function setDirectoryResurce(string $dirResurce)
    {
        $this->directoryTarget = $dirResurce;
        return $this;
    }

    public function createPFX(string $solicitation)
    {
        $privateKeyFile = $this->directoryResurces . "{$solicitation}-privateKey.pem";
        $certP7B = $this->directoryTarget . "p7b/{$solicitation}-cert.p7b";
        $certificate = $this->directoryTarget . "{$solicitation}.cer";
        $pfx = $this->directoryTarget . "{$solicitation}.pfx";
        exec("openssl pkcs7 -print_certs -in {$certP7B} -out {$certificate}");
//        exec("openssl pkcs12 -export -in {$certificate} -inkey {$privateKeyFile} -out {$pfx} -certfile {$this->directoryTarget}{$solicitation}-CACert.cer");
    }

    public function saveAsFileP7B(string $p7b, string $solicitation)
    {
        if (!is_dir("{$this->directoryTarget}/p7b/")) {
            mkdir("{$this->directoryTarget}/p7b");
        }
        file_put_contents($this->directoryTarget . "/p7b/{$solicitation}-cert.p7b", $p7b);
        return null;
    }
}