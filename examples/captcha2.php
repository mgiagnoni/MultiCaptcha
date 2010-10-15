<?php
session_start();
require dirname(__FILE__) . '/../MultiCaptcha.class.php';
$c = new MultiCaptcha();
$c->setAssetsPath(dirname(__FILE__) . '/../assets');
$c->setWidth(130);
$c->setHeight(40);
$c->setColor(array(0,255,0));
//One of the 2 fonts set is choosen randomly every time the captcha code is generated.
$c->setFonts(array(
'VeraBd.ttf' => array(
  'min_size' => 16,      //min font size
  'max_size' => 24,      //max font size
  'min_rotation' => -10, //min character rotation
  'max_rotation' => 8,  //max character rotation
  'min_spacing' => -3,   //min character spacing
  'max_spacing' => -1    //max character spacing
),
'Vera.ttf' => array(
  'min_size' => 20,
  'max_size' => 28,
  'min_rotation' => -7,
  'max_rotation' => 9,
  'min_spacing' => -3 //no max = fixed value for spacing
)));
$c->show();
