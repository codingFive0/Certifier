<?php require_once __DIR__ . "/../vendor/autoload.php";

use codingFive0\soluti\Emitter;
use codingFive0\soluti\Certificate;

$solicitationTxt = "113D200624413D34 00015795276049";
$emissor = new Emitter($solicitationTxt);
$emissor->setPreSharedKey("98188dn1w38");

//CPF 15795276049
//Nome do Usuário: 113D200624413D34 00015795276049
//Senha: 98188dn1w38

//CPF 92595571001
//Nome do Usuário: 113D2006244178DC 00092595571001
//Senha: 3a3hv5i7bDC

$solicitacao = $emissor->verifyEmission()->getCallback();
if (empty($solicitacao->mensagem->certificado)) {
    var_dump($solicitacao->mensagem->erro);
    return;
}
$emissor->setSolicitationData($solicitacao, "BR", "Rio Grande do Sul","Gravataí");
//var_dump($emissor);
echo "<hr>";


$emissor->getCertificate();

$emissor->getFile()->fileP7b($emissor->getCallback()->mensagem->data);
var_dump($emissor->getFile()->getCertDir() . "/p7b/113D200624413D34-00015795276049.p7b");
var_dump($emissor->getFile()->getSolicitationDir() . "/privateKey.key");

var_dump($emissor->getCallback());

// /Applications/XAMPP/xamppfiles/htdocs/components/soluti/files/certicates/p7b/113D200624413D34-00015795276049.p7b
// /Applications/XAMPP/xamppfiles/htdocs/components/soluti/exemples/testes/certificate.cert
// /Applications/XAMPP/xamppfiles/htdocs/components/soluti/files/solicitations/113D200624413D34-00015795276049/privateKey.key

// openssl pkcs7 -print_certs -in /Applications/XAMPP/xamppfiles/htdocs/components/soluti/files/certicates/p7b/113D200624413D34-00015795276049.p7b -out certificate.pem
// openssl pkcs12 -inkey /Applications/XAMPP/xamppfiles/htdocs/components/soluti/files/solicitations/113D200624413D34-00015795276049/privateKey.key -in /Applications/XAMPP/xamppfiles/htdocs/components/soluti/exemples/testes/certificate.cert -export -out bob_pfx.pfx