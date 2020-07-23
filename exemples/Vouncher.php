<?php require_once __DIR__ . "/../vendor/autoload.php";

use codingFive0\soluti\Voucher;


//CPF 15795276049
//Nome do Usuário: 113D200624413D34 00015795276049
//Senha: 98188dn1w38
//
//CPF 92595571001
//Nome do Usuário: 113D2006244178DC 00092595571001
//Senha: 3a3hv5i7bDC

$vouncher = new Voucher();


$vouncher->getVouncher(
    "1000000",
    20190311,
    1,
    "03027529005"
);

//var_dump($vouncher);
