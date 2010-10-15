<?php
session_start();
require dirname(__FILE__) . '/../MultiCaptcha.class.php';
$c = new MultiCaptcha();
$c->setAssetsPath(dirname(__FILE__) . '/../assets');
$c->setWidth(130);
$c->setHeight(50);
$c->setCaptchaType(MultiCaptcha::TYPE_MATH);
$c->setNumOperands(2);
$c->setMaxOperandValue(20);
$c->setFonts(array('Vera.ttf'=> array(
  'min_size' => 22,
  'max_size' => 28,
  'min_rotation' => -10,
  'max_rotation' => 13,
  'min_spacing' => -5
)));
$c->setWaveEffect(false);
$c->show();
