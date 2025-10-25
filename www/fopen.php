<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

var_dump(ini_get('allow_url_fopen'));
$f=gethostbyname("fenix-russia.ru");
echo $f;

var_dump(fopen('/wa-data/public/shop/products/20/08/820/images/1919/1919.290x0.jpg', 'r'));die;
?>
