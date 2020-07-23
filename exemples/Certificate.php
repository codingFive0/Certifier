<?php require_once __DIR__ . "/../vendor/autoload.php";

use codingFive0\soluti\Emitter;
use codingFive0\soluti\Certificate;

$solicitationTxt = "113D200624413D34 00015795276049";

$certy = new Certificate();
$certy->createPFX($solicitationTxt);
var_dump($certy);