<?php

namespace codingFive0\Certifier;

class Files
{
    private $solicitation;

    private $filesDir;

    private $certificatesDir;

    private $solicitationsDir;

    private $solicitationDir;

    private $temporaryDir;

    private $dir;

    public function __construct($solicitation = null)
    {
        $this->solicitation = (!empty($solicitation) ? str_replace(" ", "-", $solicitation) : null);

        $this->filesDir = dirname(__DIR__, 1) . "/files";
        $this->certificatesDir = $this->filesDir . "/certicates";
        $this->solicitationsDir = $this->filesDir . "/solicitations";
        $this->solicitationDir = (!empty($this->solicitation) ? $this->solicitationsDir . "/{$this->solicitation}" : null);
        $this->temporaryDir = (!empty($this->solicitationDir) ? $this->solicitationDir . "/temp" : null);

        $this->directories();

        $this->dir = [
            "files" => scandir($this->filesDir),
            "certificates" => scandir($this->certificatesDir),
            "solicitations" => scandir($this->solicitationsDir),
            "solicitation" => (!empty($this->solicitationDir) ? scandir($this->solicitationDir) : null),
            "temp" => (!empty($this->temporaryDir) ? scandir($this->temporaryDir) : null)
        ];
    }

    /**
     * SETTERS METHODS
     */

    public function setSolicitation(string $solicitation)
    {
        $this->solicitation = $solicitation;
        return $this;
    }

    /**
     * CREATES METHODS
     */

    public function fileCSR($csrContent)
    {
        if (!$this->solicitationFolder()) {
            return false;
        }

        $csr = fopen($this->solicitationDir . "/csr.pem", "w");
        fwrite($csr, $csrContent);

        return true;
    }

    public function fileSignature($signatureContent)
    {
        if (!$this->solicitationFolder()) {
            return false;
        }

        $csr = fopen($this->solicitationDir . "/signature.der", "w");
        fwrite($csr, $signatureContent);

        return true;
    }

    public function filePrivateKey($privateKeyContent)
    {
        if (!$this->solicitationFolder()) {
            return false;
        }

        $csr = fopen($this->solicitationDir . "/privateKey.key", "w");
        fwrite($csr, $privateKeyContent);

        return true;
    }

    public function filePublicKey($publicKeyContent)
    {
        if (!$this->solicitationFolder()) {
            return false;
        }

        $csr = fopen($this->solicitationDir . "/publicKey.key", "w");
        fwrite($csr, $publicKeyContent);

        return true;
    }

    public function fileTempDir(string $name, $content)
    {
        if (!$this->solicitationFolder()) {
            return false;
        }

        $csr = fopen($this->temporaryDir . "/{$name}.txt", "w");
        fwrite($csr, $content);

        return true;
    }

    public function fileP7b(string $content)
    {
        if (!$this->p7bFolder()) {
            return false;
        }

        $p7b = fopen($this->certificatesDir . "/p7b/{$this->solicitation}.p7b", "w");
        fwrite($p7b, $content);

        return true;
    }

    public function flushTempFiles(bool $solicitationOnly = false)
    {
        if ($solicitationOnly) {
            if (!$this->solicitation) {
                return false;
            }

            foreach ($this->dir["temp"] as $file) {
                if ($file !== "." && $file !== ".." && is_file($this->temporaryDir . "/{$file}")) {
                    unlink($this->temporaryDir . "/{$file}");
                }
            }

            return true;
        }

        foreach ($this->dir["solicitations"] as $folder) {
            if ($folder !== "." && $folder !== ".." && is_dir($this->solicitationsDir . "/{$folder}")) {
                foreach (scandir($this->solicitationsDir . "/{$folder}/temp") as $file) {
                    if ($file !== "." && $file !== ".." && is_file($this->solicitationsDir . "/{$folder}/temp/{$file}")) {
                        unlink($this->solicitationsDir . "/{$folder}/temp/{$file}");
                    }
                }
            }
        }

        return true;
    }

    /**
     * VERIFYERS METHODS
     */
    public function heaveCSR()
    {
        if (!$this->solicitation) {
            return false;
        }

        return file_exists($this->solicitationDir . "/csr.pem");
    }

    public function heavePrivateKey()
    {
        if (!$this->solicitation) {
            return false;
        }

        return file_exists($this->solicitationDir . "/privateKey.key");
    }

    public function heavePublicKey()
    {
        if (!$this->solicitation) {
            return false;
        }

        return file_exists($this->solicitationDir . "/publicKey.key");
    }

    public function heaveSignature()
    {
        if (!$this->solicitation) {
            return false;
        }

        return file_exists($this->solicitationDir . "/signature.der");
    }

    public function heaveP7B()
    {
        if (!$this->solicitation) {
            return false;
        }

        return file_exists($this->certificatesDir . "/p7b/{$this->solicitation}.p7b");
    }

    public function heaveTempFile(string $fileName)
    {
        if (!$this->solicitation) {
            return false;
        }

        return file_exists($this->solicitationDir . "/{$fileName}");
    }


    /**
     * GETTERS METHODS
     */

    public function getPrivateKey()
    {
        if (!$this->solicitation) {
            return false;
        }
        $handle = fopen($this->solicitationDir . "/privateKey.key", "r");
        return fread($handle, 8192);
    }

    public function getPublicKey()
    {
        if (!$this->solicitation) {
            return false;
        }
        $handle = fopen($this->solicitationDir . "/publicKey.key", "r");
        return fread($handle, 8192);
    }

    public function getCSR()
    {
        if (!$this->solicitation) {
            return false;
        }
        $handle = fopen($this->solicitationDir . "/csr.pem", "r");
        return fread($handle, 8192);
    }

    public function getSignature()
    {
        if (!$this->solicitation) {
            return false;
        }
        $handle = fopen($this->solicitationDir . "/signature.der", "r");
        return fread($handle, 8192);
    }

    public function getTempFile($fileName)
    {
        if (!$this->solicitation) {
            return false;
        }
        $handle = fopen($this->temporaryDir . "/{$fileName}", "r");
        return fread($handle, 8192);
    }

    public function getDirs()
    {
        return $this->dir;
    }

    public function getSolicitationDir()
    {
        return $this->solicitationDir;
    }

    public function getTempDir()
    {
        return $this->temporaryDir;
    }

    public function getCertDir()
    {
        return $this->certificatesDir;
    }


    /**
     * PRIVATE METHODS
     */

    private function directories()
    {
        if (!is_dir($this->filesDir)) {
            mkdir($this->filesDir);
        }

        if (!is_dir($this->certificatesDir)) {
            mkdir($this->certificatesDir);
        }

        $this->solicitationFolder();
    }

    private function solicitationFolder()
    {
        if (!is_dir($this->solicitationsDir)) {
            mkdir($this->solicitationsDir);
        }

        if (empty($this->solicitation)) {
            return false;
        }

        if (!is_dir($this->solicitationDir)) {
            mkdir($this->solicitationDir);
        }

        if (is_dir($this->solicitationDir) && !is_dir($this->temporaryDir)) {
            mkdir($this->temporaryDir);
        }

        return true;
    }

    private function p7bFolder()
    {
        if (!is_dir($this->certificatesDir)) {
            return false;
        }

        if (!is_dir($this->certificatesDir . "/p7b")) {
            mkdir($this->certificatesDir . "/p7b");
        }

        if (!is_dir($this->certificatesDir . "/p7b")) {
            return false;
        }

        return true;
    }
}