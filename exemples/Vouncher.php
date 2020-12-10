<?php require_once __DIR__ . "/../vendor/autoload.php";

use codingFive0\Certifier\Voucher;


//CPF 15795276049
//Nome do Usuário: 113D200624413D34 00015795276049
//Senha: 98188dn1w38
//
//CPF 92595571001
//Nome do Usuário: 113D2006244178DC 00092595571001
//Senha: 3a3hv5i7bDC
$inTest = true;

if ($inTest) {
    $vouncher = new Voucher("contaagil", "9725978d5d1a96ce93c33245d736c0f2c6c9e48d2191da360fa7b3f96b30a38e", "https://gvshom.ca.inf.br/GVS/webservices/GVSServices.jws?wsdl");
} else {
//    $vouncher = new Voucher("contaagil", "679fbca573ca1249d604db5104390b05850de612c8a0e1a754726e3fb74db56e", "https://gvs.ca.inf.br/GVS/webservices/GVSServices.jws?wsdl");
}


//$vouncher->getVouncher(
//    10,
//    23908,
//    1,
//    "03027529005",
//    "Gabriel Caldeira da Silva",
//    23908,
//    true
//);

//$vouncher->verifyVoucher("13179d04bb86");
$vouncher->cancelVoucher("0003ed4e373c");
var_dump($vouncher);
var_dump(json_decode($vouncher->getCallback()));