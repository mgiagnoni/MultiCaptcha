<?php
session_start();
require dirname(__FILE__) . '/../MultiCaptcha.class.php';
$c = new MultiCaptcha();
$c->setAssetsPath(dirname(__FILE__) . '/../assets');
$c->show();
