<?php require_once __DIR__ . "/../vendor/autoload.php";

use codingFive0\Certifier\Voucher;


//CPF 15795276049
//Nome do Usuário: 113D200624413D34 00015795276049
//Senha: 98188dn1w38
//
//CPF 92595571001
//Nome do Usuário: 113D2006244178DC 00092595571001
//Senha: 3a3hv5i7bDC

$vouncher = new Voucher("contaagil", "679fbca573ca1249d604db5104390b05850de612c8a0e1a754726e3fb74db56e", "https://gvs.ca.inf.br/GVS/webservices/GVSServices.jws?wsdl");


$vouncher->getVouncher(
    "1212121",
    23908,
    "15665656",
    null,
    null,
    null,
    false
);

var_dump($vouncher);
