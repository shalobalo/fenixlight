<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

var_dump(ini_get('allow_url_fopen'));
$f=gethostbyname("fenix-russia.ru");
echo $f;

var_dump(fopen('http://37.1.193.157/ipc', 'r'));
var_dump(fopen('http://google.com/', 'r'));
var_dump(fopen('http://www.yandex.ru/', 'r'));
