<?php require_once __DIR__ . "/../vendor/autoload.php";

use codingFive0\Certifier\Emitter;
use codingFive0\certifier\Certificate;

$solicitationTxt = "113D200624413D34 00015795276049";

$emissor = new Emitter($solicitationTxt);
$emissor->setPreSharedKey("98188dn1w38");

$solicitacao = $emissor->verifyEmission()->getCallback();
if (empty($solicitacao->mensagem->certificado)) {
    var_dump($solicitacao->mensagem->erro);
    return;
}
$emissor->setSolicitationData($solicitacao, "BR", "Rio Grande do Sul","Gravataí");
//var_dump($emissor);
echo "<hr>";
//https://knowledge.digicert.com/solution/SO26449

$emissor->getCertificate();
var_dump($emissor->getCallback()->mensagem);
die;
$emissor->getFile()->fileP7b($emissor->getCallback()->mensagem->data);
var_dump($emissor->getFile()->getCertDir() . "/p7b/113D200624413D34-00015795276049.p7b");
var_dump($emissor->getFile()->getSolicitationDir() . "/privateKey.key");

var_dump($emissor->getCallback());
