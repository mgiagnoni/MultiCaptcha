<?php

/*
 * This file is part of the MultiCaptcha package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * MultiCaptcha
 *
 * A class to manage different kind of captchas.
 *
 * @package MultiCaptcha
 * @copyright Copyright (C) 2010 Massimo Giagnoni.
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class MultiCaptcha
{
  const
    CHARSET_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz',
    CHARSET_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    CHARSET_NUMBERS = '123456789',
    TYPE_RANDOM_CODE = 0,
    TYPE_MATH = 1;

  /**
   * Captcha type (random code, math).
   *
   * @var int.
   */
  protected $type = self::TYPE_RANDOM_CODE;

  /**
   * Captcha code/math operation
   *
   * @var string.
   */
  protected $code = null;

  /**
   * Code to guess or result of math operation depending by captcha type.
   *
   * @var string|int.
   */
  protected $answer;
  
  /**
   * Code lenght, used only if captcha type is TYPE_RANDOM_CODE.
   * 
   * @var int number of characters.
   */
  protected $code_length = 5;

  /**
   * Captcha image width.
   *
   * @var int pixels.
   */
  protected $width = 110;

  /**
   * Captcha image height.
   *
   * @var int pixels.
   */
  protected $height = 35;

  /**
   * Captcha image resource.
   *
   * @var resource.
   */
  protected $image;

  /**
   * Captcha image background color.
   *
   * @var array (R,G,B).
   */
  protected $bg_color = array(255, 255, 255);

  /**
   * Captcha image foreground color.
   *
   * @var array (R,G,B).
   */
  protected $color = array(0, 0, 255);

  /**
   * Charset used to generate random code.
   *
   * @var string.
   */
  protected $charset = self::CHARSET_LOWERCASE;

  /**
   * Minimum character rotation.
   *
   * @var float degrees.
   */
  protected $min_char_rotation = 8;

  /**
   * Maximum character rotation.
   *
   * @var float degrees.
   */
  protected $max_char_rotation = null;

  /**
   * Minimum character spacing.
   *
   * @var int pixels
   */
  protected $min_char_spacing = -2;

  /**
   * Maximum character spacing.
   *
   * @var int pixels
   */
  protected $max_char_spacing = null;

  /**
   * Minimum font size.
   *
   * @var float points
   */
  protected $min_font_size = 19;

  /**
   * Maximum font size.
   *
   * @var float points
   */
  protected $max_font_size = null;

  /**
   * Blur effect.
   *
   * @var boolean on/off.
   */
  protected $blur_effect = true;

  /**
   * Wave effect.
   *
   * @var boolean on/off.
   */
  protected $wave_effect = true;

  /**
   * Horizontal amplitude.
   *
   * @var float.
   */
  protected $horz_amplitude = 4;

  /**
   * Horizontal period.
   *
   * @var float.
   */
  protected $horz_period = 16;

  /**
   * Vertical amplitude.
   *
   * @var float.
   */
  protected $vert_amplitude = 3.5;

  /**
   * Vertical period.
   *
   * @var float.
   */
  protected $vert_period = 8;

  /**
   * Available fonts.
   *
   * @var array
   */
  protected $fonts = array('VeraBd.ttf' => array());

  /**
   * Assets path.
   *
   * @var string server path.
   */
  protected $assets_path = 'assets';

  /**
   * Session key.
   *
   * @var string.
   */
  protected $session_key = 'multi_captcha';

  /**
   * Maximum operand value (math captcha).
   * 
   * @var int
   */
  protected $max_operand_value = 9;

  /**
   * Number of operands (math captcha).
   *
   * @var int
   */
  protected $num_operands = 3;

  /**
   * Gets captcha image width.
   * 
   * @return int.
   */
  public function getWidth()
  {
    return $this->width;
  }

  /**
   * Sets captcha image width.
   *
   * @param int $v (pixels).
   */
  public function setWidth($v)
  {
    $this->width = $v;
  }

  /**
   * Gets captcha image height.
   *
   * @return int.
   */
  public function getHeight()
  {
    return $this->height;
  }

  /**
   * Sets captcha image height.
   *
   * @param int $v (pixels).
   */
  public function setHeight($v)
  {
    $this->height = $v;
  }
  /**
   * Gets maximum character rotation value.
   *
   * @return float
   */
  public function getMaxCharRotation()
  {
    return $this->getMaxRangedProperty('CharRotation', $this->max_char_rotation);
  }

  /**
   * Sets maximum character rotation value.
   *
   * @param float $v degrees.
   */
  public function setMaxCharRotation($v)
  {
    $this->max_char_rotation = $v;
  }

  /**
   * Gets minimum character rotation value.
   *
   * @return float
   */
  public function getMinCharRotation()
  {
    return $this->min_char_rotation;
  }

  /**
   * Sets minimum character rotation value.
   *
   * @param float $v degrees.
   */
  public function setMinCharRotation($v)
  {
    $this->min_char_rotation = $v;
  }

  /**
   * Sets character rotation values.
   *
   * Determines angle of rotation of any single character in captcha code.
   * If only $min is passed rotation is set to $min (fixed value). If both $min
   * and $max are passed rotation is set to a random value between $min and $max
   * for each character.
   *
   * @param float $min minimum value (degrees).
   * @param float $max maximum value (degrees).
   */
  public function setCharRotation($min, $max = null)
  {
    $this->setRangedProperty('CharRotation', $min, $max);
  }

  /**
   * Gets minimum character spacing value.
   *
   * @return int.
   */
  public function getMinCharSpacing()
  {
    return $this->min_char_spacing;
  }

  /**
   * Sets minimum character spacing value.
   *
   * @param int $v (pixels, can be negative).
   */
  public function setMinCharSpacing($v)
  {
    $this->min_char_spacing = $v;
  }

  /**
   * Gets maximum character spacing value.
   *
   * @return int
   */
  public function getMaxCharSpacing()
  {
    return $this->getMaxRangedProperty('CharSpacing', $this->max_char_spacing);
  }

  /**
   * Sets maximum character spacing value.
   *
   * @param int $v (pixels, can be negative).
   */
  public function setMaxCharSpacing($v)
  {
    $this->max_char_spacing = $v;
  }

  /**
   * Sets character spacing values.
   *
   * Determines the space (in pixels) between adjacent characters of captcha code.
   * If only $min is passed spacing is set to $min (fixed value). If both $min
   * and $max are passed spacing is set to a random value between $min and $max
   * for each character.
   *
   * @param int $min minimum value (pixels, can be negative).
   * @param int $max maximum value (pixels, can be negative).
   */
  public function setCharSpacing($min, $max = null)
  {
    $this->setRangedProperty('CharSpacing', $min, $max);
  }

  /**
   * Gets minimum font size value.
   *
   * @return float.
   */
  public function getMinFontSize()
  {
    return $this->min_font_size;
  }

  /**
   * Sets minimum font size value.
   *
   * @param float $v.
   */
  public function setMinFontSize($v)
  {
    $this->min_font_size = $v;
  }

  /**
   * Gets maximum font size value.
   *
   * @return float.
   */
  public function getMaxFontSize()
  {
    return $this->getMaxRangedProperty('FontSize', $this->max_font_size);
  }

  /**
   * Sets maximum font size value.
   *
   * @param float $v.
   */
  public function setMaxFontSize($v)
  {
    $this->max_font_size = $v;
  }

  /**
   * Sets font size values.
   *
   * Determines the font size (in points) used for characters of captcha code.
   * If only $min is passed font size is set to $min (fixed value). If both $min
   * and $max are passed font size is set to a random value between $min and $max
   * for each character.
   *
   * @param float $min minimum value.
   * @param float $max maximum value.
   */
  public function setFontSize($min, $max = null)
  {
    $this->setRangedProperty('FontSize', $min, $max);
  }

  /**
   * Gets captcha code lenght.
   *
   * @return int
   */
  public function getCodeLength()
  {
    return $this->code_length;
  }

  /**
   * Sets captcha code lenght.
   * 
   * Used only if captcha type is TYPE_RANDOM_CODE.
   *
   * @param int $v number of characters.
   */
  public function setCodeLength($v)
  {
    $this->code_length = $v;
  }
  /**
   * Gets captcha code.
   *
   * @return string.
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * Sets captcha code.
   * Used if we want to bypass class random code generation function and set
   * captcha from client code.
   *
   * @param string $v captcha code.
   */
  public function setCode($v)
  {
    $this->code = $this->answer = $v;
  }

  /**
   * Gets horizontal amplitude (wave effect).
   *
   * @return float.
   */
  public function getHorzAmplitude()
  {
    return $this->horz_amplitude;
  }

  /**
   * Sets horizontal amplitude (wave effect).
   *
   * @param float $v.
   */
  public function setHorzAmplitude($v)
  {
    $this->horz_amplitude = $v;
  }

  /**
   * Gets horizontal period (wave effect).
   *
   * @return float.
   */
  public function getHorzPeriod()
  {
    return $this->horz_period;
  }
  /**
   * Sets horizontal period (wave effect).
   * 
   * @param float $v.
   */
  public function setHorzPeriod($v)
  {
    $this->horz_period = $v;
  }

  /**
   * Gets vertical amplitude (wave effect).
   *
   * @return float.
   */
  public function getVertAmplitude()
  {
    return $this->vert_amplitude;
  }

  /**
   * Sets vertical amplitude (wave effect).
   *
   * @param float $v.
   */
  public function setVertAmplitude($v)
  {
    $this->vert_amplitude = $v;
  }

  /**
   * Gets vertical period (wave effect).
   *
   * @return float.
   */
  public function getVertPeriod()
  {
    return $this->vert_period;
  }

  /**
   * Sets vertical period (wave effect).
   *
   * @param float $v.
   */
  public function setVertPeriod($v)
  {
    $this->vert_period = $v;
  }

  /**
   * Sets wave effect.
   *
   * @param bool $v true = effect on, false = effect off.
   */
  public function setWaveEffect($v)
  {
    $this->wave_effect = (bool)$v;
  }

  /**
   * Sets blur effect.
   *
   * @param bool $v true = effect on, false = effect off.
   */
  public function setBlurEffect($v)
  {
    $this->blur_effect = (bool)$v;
  }

  /**
   * Sets assets (fonts, audio files) folder path.
   *
   * @param string $v absolute server path.
   */
  public function setAssetsPath($v)
  {
    $this->assets_path = $v;
  }

  /**
   * Gets assets folder path.
   *
   * @return string.
   */
  public function getAssetsPath()
  {
    return rtrim($this->assets_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
  }

  /**
   * Sets fonts to be used for captcha generation.
   *
   * @param array $font_data contains font related property values.
   * @see setFont()
   */
  public function setFonts(array $font_data)
  {
    $this->fonts = array();
    foreach($font_data as $key => $data)
    {
      $this->setFont($key, $data);
    }
  }

  /**
   * Sets font to be used for captcha generation.
   *
   * Example:
   * $c = new MultiCaptcha();
   * $c->setFont('VeraBd.ttf', array(
   *  'min_size' => 20,      //min font size
   *  'max_size' => 26,      //max font size
   *  'min_rotation' => -12, //min character rotation
   *  'max_rotation' => 15,  //max character rotation
   *  'min_spacing' => -3,   //min character spacing
   *  'max_spacing' => -1    //max character spacing
   * ));
   *
   * @param string $name font name.
   * @param array $font_data
   */
  public function setFont($name, array $font_data)
  {
    $font_data['name'] = $name;
    $this->fonts[$name] = $font_data;
  }

  /**
   * Chooses a font among those available and sets font related properties.
   *
   * If $name is null a font is choosen randomly among available fonts.
   *
   * @param string $name font name
   * @return string
   */
  public function selectFont($name = null)
  {
    if(empty($name))
    {
      $name = array_rand($this->fonts);
    }
    $font = $this->fonts[$name];

    if(isset($font['min_size']))
    {
      $this->setMinFontSize($font['min_size']);
    }
    if(isset($font['max_size']))
    {
      $this->setMaxFontSize($font['max_size']);
    }
    if(isset($font['min_rotation']))
    {
      $this->setMinCharRotation($font['min_rotation']);
    }
    if(isset($font['max_rotation']))
    {
      $this->setMaxCharRotation($font['max_rotation']);
    }
    if(isset($font['min_spacing']))
    {
      $this->setMinCharSpacing($font['min_spacing']);
    }
    if(isset($font['max_spacing']))
    {
      $this->setMaxCharSpacing($font['max_spacing']);
    }

    return $name;
  }

  /**
   * Gets charset used to generate random captcha code.
   *
   * @return string.
   */
  public function getCharset()
  {
    return $this->charset;
  }

  /**
   * Sets charset used to generate random captcha code.
   *
   * @param string $v charset.
   * @param string $exclude characters excluded from charset.
   */
  public function setCharset($v, $exclude = '')
  {
    $this->charset = str_replace(str_split($exclude), '', $v);
  }

  /**
   * Gets captcha background color.
   *
   * @return array (R,G,B) values.
   */
  public function getBGColor()
  {
    return $this->bg_color;
  }

  /**
   * Sets captcha background color.
   *
   * @param array $v (R,G,B) values.
   */
  public function setBGColor(array $v)
  {
    $this->bg_color = $v;
  }

  /**
   * Gets captcha code color.
   *
   * @return array (R,G,B) values.
   */
  public function getColor()
  {
    return $this->color;
  }

  /**
   * Sets captcha code color.
   *
   * @param array $v (R,G,B) values.
   */
  public function setColor(array $v)
  {
    $this->color = $v;
  }

  /**
   * Gets key used to store correct answer in $_SESSION.
   *
   * @return string.
   */
  public function getSessionKey()
  {
    return $this->session_key;
  }

  /**
   * Sets key used to store correct answer in $_SESSION.
   *
   * @param string $v.
   */
  public function setSessionKey($v)
  {
    $this->session_key = $v;
  }

  /**
   * Gets captcha type.
   *
   * @return int
   */
  public function getCaptchaType()
  {
    return $this->type;
  }

  /**
   * Sets captcha type.
   * 
   * @param int $v
   */
  public function setCaptchaType($v)
  {
    $this->type = $v;
  }

  /**
   * Gets maximum value allowed for operands in math captcha.
   * 
   * @return int
   */
  public function getMaxOperandValue()
  {
    return $this->max_operand_value;
  }

  /**
   * Sets maximum value allowed for operands in math captcha.
   *
   * @return int
   */
  public function setMaxOperandValue($v)
  {
    $this->max_operand_value = $v;
  }

  /**
   * Gets number of operands in math captcha.
   *
   * @return int
   */
  public function getNumOperands()
  {
    return $this->num_operands;
  }

  /**
   * Sets number of operands in math captcha.
   *
   * @param int $v
   */
  public function setNumOperands($v)
  {
    $this->num_operands = $v < 2 ? 2 : $v;
  }
  
  /**
   * Displays captcha code.
   */
  public function show()
  {
    switch($this->type)
    {
      case self::TYPE_RANDOM_CODE:
        if(!$this->getCode())
        {
          $this->generateRandomCaptcha();
        }
        break;
      case self::TYPE_MATH:
        $this->generateMathCaptcha();
        break;
    }
    
    
    $this->createImage();
    $this->setColors();
    $this->drawBackground();
    $this->drawCaptcha();
    if($this->wave_effect)
    {
      $this->addWaveEffect();
    }
    if($this->blur_effect)
    {
      $this->addBlurEffect();
    }
    $this->storeAnswer();
    $this->outputImage();
  }

  /**
   * Generates random captcha code based on charset.
   */
  public function generateRandomCaptcha()
  {
    $chars = $this->charset;
    $this->code = '';
    for($i = 1; $i <= $this->code_length; $i++)
    {
      $p = mt_rand(0, strlen($chars)-1);
      $this->code .= substr($chars, $p, 1);
      $chars = substr_replace($chars, '', $p, 1);
    }
    $this->answer = $this->code;
  }

  /**
   * Generates math captcha code.
   */
  public function generateMathCaptcha()
  {
    $ops = array();
    $r = mt_rand(1, $this->max_operand_value);
    $this->code = $r;

    for($i = 0; $i < $this->num_operands - 1; $i++)
    {
      if($r == 1)
      {
        $op = '+';
      }
      else
      {
        $op = mt_rand(0,1) ? '+' : '-';
      }

      if($op == '+')
      {
        $n = mt_rand(1, $this->max_operand_value);
        $r += $n;
      }
      else
      {
        $n = mt_rand(1, ($r > $this->max_operand_value ? $this->max_operand_value : $r-1));
        $r -= $n;
      }
      $this->code .= $op . $n;
    }
    $this->answer = $r;

  }

  /**
   * Retrieves correct answer.
   *
   * Return value depends by captcha type:
   *
   * 1) TYPE_RANDOM_CODE: method returns the code displayed in captcha image(string);
   * 2) TYPE_MATH: method returns the result of math operation displayed in captcha image(int).
   *
   * @return int|string
   */
  public function retrieveAnswer()
  {
    $r = $_SESSION[$this->session_key];
    $_SESSION[$this->session_key] = '';
    return $r;
  }

  /**
   * Sends captcha image to the browser.
   */
  protected function outputImage()
  {
    header('Content-type: image/png');

    imagepng($this->image);
    imagedestroy($this->image);
  }

  /**
   * Creates image resource used for captcha.
   */
  protected function createImage()
  {
    $this->image = imagecreatetruecolor($this->width, $this->height);
    
  }

  /**
   * Allocate captcha foreground and background colors.
   */
  protected function setColors()
  {
    $this->bg_color = imagecolorallocate($this->image, $this->bg_color[0], $this->bg_color[1], $this->bg_color[2]);
    $this->color = imagecolorallocate($this->image, $this->color[0], $this->color[1], $this->color[2]);
  }

  /**
   * Fills captcha image with background color.
   */
  protected function drawBackground()
  {
    imagefill($this->image, 0, 0, $this->bg_color);
  }

  /**
   * Draws captcha code (or math operation) on image.
   */
  protected function drawCaptcha()
  {
    $temp_im = imagecreatetruecolor($this->width, $this->height);
    imagefill($temp_im, 0, 0, $this->bg_color);
    
    $tx = 5;
    $ty = round(($this->height * 0.70));
    
    $l = strlen($this->code);
    $font = $this->getAssetsPath() . 'fonts' . DIRECTORY_SEPARATOR . $this->selectFont();
    for($i = 0; $i < $l; $i++)
    {
      $box = imagettftext($temp_im,
        mt_rand($this->getMinFontSize(), $this->getMaxFontSize()),
        mt_rand($this->getMinCharRotation(), $this->getMaxCharRotation()),
        $tx, $ty,
        $this->color ,
        $font,
        substr($this->code, $i, 1)
        );
      $s = $box[4] > $box[2] ? $box[4] : $box[2];
      $tx += $s - $tx + mt_rand($this->getMinCharSpacing(), $this->getMaxCharSpacing());
    }
    $tx += 5;
    imagecopyresized($this->image, $temp_im, $this->width / 2 - $tx / 2, 0, 0, 0, $tx, $this->height, $tx, $this->height);
    imagedestroy($temp_im);
  }

  /*
   * Adds a blur effect on captcha image.
   */
  protected function addBlurEffect()
  {
    if(function_exists('imagefilter'))
    {
      imagefilter($this->image, IMG_FILTER_GAUSSIAN_BLUR);
    }
    else
    {
      $this->blurImage();
    }
  }

  /*
   * Adds a wave effect on captcha image.
   *
   * Code comes from examples of imagecopy function on PHP site with some changes.
   *
   * @link http://www.php.net/manual/en/function.imagecopy.php#72393
   */
  protected function addWaveEffect()
  {
    $x = 0;
    $y = 0;
    $period = 11;
    $amplitude = 5;

    $img2 = imagecreatetruecolor($this->width * 2, $this->height * 2);
    imagecopyresampled ($img2, $this->image, 
      0, 0, $x, $y,
      $this->width * 2, $this->height * 2,
      $this->width, $this->height
    );
    
    for ($i = 0; $i < ($this->width * 2); $i += 2)
    {
       imagecopy($img2, $img2,
        $i - 2, sin($i / $this->horz_period) * $this->horz_amplitude,
        $i, 0,
        2, $this->height * 2
       );
    }
    
    for ($i = 0; $i < ($this->height * 2); $i += 2)
    {
      imagecopy($img2, $img2,
        sin($i / $this->vert_period) * $this->vert_amplitude, $i - 2,
        0, $i,
        $this->width * 2, 2
      );
    }
    
    imagecopyresampled ($this->image, $img2, 
      $x, $y, 0, 0,
      $this->width, $this->height,
      $this->width * 2, $this->height * 2
    );
    imagedestroy($img2);
  }

  /**
   * Internal function to gennerate a blur effect.
   *
   * @author Howard Yeend
   * @link http://www.puremango.co.uk/2009/04/php-4-and-5-image-blur/
   */
  protected function blurImage()
  {
    $width = $this->width;
    $height = $this->height;
    $im = $this->image;

    $distance = 1;

    $temp_im = ImageCreateTrueColor($width, $height);
    imagecopy($temp_im, $im, 0, 0, 0, 0, $width, $height);
    
    $pct = 60; 
    imagecopymerge($temp_im, $im, 0, 0, 0, $distance,
      $width - $distance, $height - $distance, $pct
    );
    imagecopymerge($im, $temp_im, 0, 0, $distance, 0,
      $width - $distance, $height, $pct
    );
    imagecopymerge($temp_im, $im, 0, $distance, 0, 0,
      $width, $height, $pct
    );
    imagecopymerge($im, $temp_im, $distance, 0, 0, 0,
      $width, $height, $pct
    );

    imagedestroy($temp_im);
  }

  /**
   * Sets value for properties that support a min/max value.
   *
   * @param string $property.
   * @param mixed $min.
   * @param mixed $max.
   */
  protected function setRangedProperty($property, $min, $max)
  {
    if($max == $min)
    {
      $max = null;
    }
    $this->{"setMin$property"}($min);
    $this->{"setMax$property"}($max);
  }

  /**
   * Returns maximum value for properties that support a min/max value.
   *
   * @param mixed $property.
   * @param mixed $v.
   * @return mixed.
   */
  protected function getMaxRangedProperty($property, $v)
  {
    if(null === $v)
    {
      return $this->{"getMin$property"}();
    }
    else
    {
      return $v;
    }
  }

  /**
   * Stores correct answer (code or result of math operation).
   */
  protected function storeAnswer()
  {
    $_SESSION[$this->session_key] = $this->answer;
  }
}
