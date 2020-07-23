<?php require_once __DIR__ . "/../vendor/autoload.php";


use codingFive0\soluti\Files;

$solicitationTxt = "113D200624413D34 00015795276049";
$file = new Files($solicitationTxt);

$csr = "-----BEGIN CERTIFICATE REQUEST-----
MIIDDDCCAfQCAQAwgcYxCzAJBgNVBAYTAkJSMRowGAYDVQQIDBFSaW8gR3JhbmRl
IGRvIFN1bDESMBAGA1UEBwwJR3JhdmF0YcOtMRswGQYDVQQKDBJBQyBTT0xVVEkg
TXVsdGlwbGExGzAZBgNVBAsMEkFDIFNPTFVUSSBNdWx0aXBsYTEgMB4GA1UEAwwX
QUMgU09MVVRJIE11bHRpcGxhIFVOSVQxKzApBgkqhkiG9w0BCQEWHGx1Y2lhbmEu
bWFjaWVsQHNvbHV0aS5jb20uYnIwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEK
AoIBAQC3RllLSpZyCAolEkmu4/Vnl8W/aS+3rfQEGPTyY+jM5hMlhSEgx7fF+y/t
CXeCBI1kniactrKBNvzIAHAonTPpTltx63XgFd6BpcCSR7/gkInFJybYnYPRUio+
GAczEMOiESpgIa9ZeAehyMNHlIvsREC4dsHRDtD3gMQdImd++JGDekmDPtAk83j9
eK9o0rMP5FppbKut39ZiQ1+89s+EiAoybtETSEZVe9ovwCJXQPE1bhj8LWrz1MDZ
qAav1VNV/7XlnAo9dBUb/F2XFfvgOiMTnX9+UGCWKlwXNzXLhWfncZZBZ+H9eKnQ
dkdION2LyiP/RzrPlTNeqFteEqRpAgMBAAGgADANBgkqhkiG9w0BAQsFAAOCAQEA
I+d6o3I7D/7PD4k69OfkvunQqbUu1SBPIh64mj5VthwwdQdgrbJOxHFtAhaH14Hw
H3+kYO0zZsyjuF6UvsxG8ZkE9oXpd9hDwFt5H34NaeBQoZCs6kGqa4B1MbidlHYG
4Mn+z/nl7IF78YLpr+InwpwbbCNEChtcxo+AbyUnJXhZXEOtFOpngPlcMVQBdgtS
r3QUVQzcWtezq8TQwHY5twPJQ0uZFbyb1OeS4oXj2IfKd8n+jghJTRo6Xuk2hNkH
B0T5Cot5gzNhu2/5PdLagD5mWR7g4X6K2JAS+qjZJE1fsv7Br6f5zthaOuWYAYvX
GQ6WNyZCD1FqroxP0wj7pA==
-----END CERTIFICATE REQUEST-----
";

$file->fileCSR($csr);

$sing = "�*}�sB�@��9��t/��8R�O�	������xc-_���K.
�x����*\v��A9zyl]��اi�S�_-�w�����_�u�{��)�y����NoV�X���9D�b��vp���$�C�7\e_`�\b�=��c$�i��r�`�gӵ%aP$��\b}�Th%bvmC�'g�T��a\"�w���~r�i$ͼr��܍��hi�.t����*��.wq�%����?�A���1|)������N�����";

$file->fileSignature($sing);

$private = "-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC3RllLSpZyCAol
Ekmu4/Vnl8W/aS+3rfQEGPTyY+jM5hMlhSEgx7fF+y/tCXeCBI1kniactrKBNvzI
AHAonTPpTltx63XgFd6BpcCSR7/gkInFJybYnYPRUio+GAczEMOiESpgIa9ZeAeh
yMNHlIvsREC4dsHRDtD3gMQdImd++JGDekmDPtAk83j9eK9o0rMP5FppbKut39Zi
Q1+89s+EiAoybtETSEZVe9ovwCJXQPE1bhj8LWrz1MDZqAav1VNV/7XlnAo9dBUb
/F2XFfvgOiMTnX9+UGCWKlwXNzXLhWfncZZBZ+H9eKnQdkdION2LyiP/RzrPlTNe
qFteEqRpAgMBAAECggEBAKbj2qAjFYhVJdfIJWyqjGd+30WD5T3fm5q3lyW6MN2U
jM7Xwej1tLUGHQg+XKL6vj5nfUWrYDUl+12seHWjYQgMoFzGSxp5D5sDcq5Bw8oc
FDiBhHAwTz6nEWxluPiZRWwpMWtEgUZ5dImwJZLjA99r73uKOMfENCmNCgh1scT9
y/xOWH0bJuSSkS3ckSQu0CLYd7kmfrQZc1k8FE016W0OK9kGO1XoTh96NkBKKQtS
6vBRF4jD7vI6gafald4hQMWIoFMc6/VwUkgBSziJC4qPcuOrgOP1gKAdhkUTZ8CO
c6Mhak860JJKFjJ5sVF+zKaeEgTJ1dhLKGJvn85QVQECgYEA8Cd3Yeg6RG+OuFjQ
jojSX7KF0NrKC/1XlRF+rJFV7PP8qO1sc4rB2Aq7pg6qRhCHUq5iTGz48uc3QhD2
qVw5k5U8ZY2Dev7Ug36UkGB3X8OILXXtgR7Nid6Q39kx01hSU6i8+SrvqcqB4RD2
n32IWF36oQfRvWMsW9kaJYPdcBECgYEAw14co7GMejUz7ahHQR8lSvVZsAL2Sl15
gnJ6q6HXv1VkMa7ndXpNU40PMfKrUOgRqOCmIlP5qJc3pmS1Lv6xiWqaVK2GJdzT
aBbbYLGNZANj7H3FQD1AgCCPGkWUsxIQHgb4KVUCKNq7GpwrVGL0rshoaRZp+UQm
my9IARkaRtkCgYEA0L09Izi7DKj6oHlyLbH3SX7emT7Sx8EY01U1icF5slAQmLRl
w7gj7SjHGfs9PVE4jkFM56kAagFXInGkNGkZEpJwwMRUOCAvj2wCrctrdy15vCn4
mnavqNpvimI60LzAMj9Eoj8Tub1vTrVz6AUw8b4eDsHBKKB++gaml10aqEECgYAR
hcFf1S7aTydHK46ogpifrn3K0ZkxdkzNrGLgPfXRzWDdK+jKmpuQ4Nf6DN/cs9PR
p8R+07VSr61oGSQ/AMz/nDYXXjDn+HRlo5cthv89dyhhL66fYl8Enub23kLGBNq5
NrrPSjaVX3jknBkuymlTu9nRgxciKkCkLotwvcWjkQKBgAy9MwOOho651HZ4EwZd
wwJWhcmPMCmdJxPP5prMpkiXunuXOKhKk6qAADXAHqyt8QUQZZQhB52xfRCWWKXN
56wEbBAX2CFBwGY2z72FtxR9b6ivbctTyWNcgXohBeVeas26Qe+KcxGdjDL5bY8G
qAzDxuVes7KiKIuYc+ZG6pNS
-----END PRIVATE KEY-----
";

$file->filePrivateKey($private);

$public = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAt0ZZS0qWcggKJRJJruP1
Z5fFv2kvt630BBj08mPozOYTJYUhIMe3xfsv7Ql3ggSNZJ4mnLaygTb8yABwKJ0z
6U5bcet14BXegaXAkke/4JCJxScm2J2D0VIqPhgHMxDDohEqYCGvWXgHocjDR5SL
7ERAuHbB0Q7Q94DEHSJnfviRg3pJgz7QJPN4/XivaNKzD+RaaWyrrd/WYkNfvPbP
hIgKMm7RE0hGVXvaL8AiV0DxNW4Y/C1q89TA2agGr9VTVf+15ZwKPXQVG/xdlxX7
4DojE51/flBglipcFzc1y4Vn53GWQWfh/Xip0HZHSDjdi8oj/0c6z5UzXqhbXhKk
aQIDAQAB
-----END PUBLIC KEY-----
";

$file->filePublicKey($public);

//$file->tempDir("assing", "teste de aquivo temporário");
//$file->tempDir("teste", "outro teste de aquivo temporário para fazer o flush em escala depois");

//$file->flushTempFiles();

$privatKey =  $file->getPrivateKey();
$publicKey =  $file->getPublicKey();
$csr =  $file->getCSR();
$signature =  $file->getSignature();

var_dump($file->heaveCSR());
var_dump($file->heavePrivateKey());
var_dump($file->heavePublicKey());
var_dump($file->heaveSignature());