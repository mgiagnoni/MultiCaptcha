<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Test MultiCaptcha</title>
<style type="text/css">
  body {
    font-family: verdana, arial, sans-serif;
    font-size: 75%;
  }
  h1 {
    font-size: 1.3em;
  }
  p {
    margin: 0;
    padding: 10px 0;
  }
  span.answer
  {
    padding: 5px;
    font-weight: bold;
  }
  span.error
  {
    color: #ff0000;
  }
  #captcha {
    border: 1px solid #e5e5e5;
  }
</style>

</head>
<body>
<h1>Math captcha</h1>
<p>Math operation with 2 operands with values between 1 - 20 (see source code of <em>captcha4.php</em>)</p>
<?php
  if(strtolower($_SERVER['REQUEST_METHOD']) == 'post')
  {
    if(trim($_REQUEST['captcha']) == '')
    {
      echo '<span class="answer error">Please enter captcha code</span>';
    }
    else
    {
      require dirname(__FILE__) . '/../MultiCaptcha.class.php';
      $c = new MultiCaptcha();
      if($_REQUEST['captcha'] == $c->retrieveAnswer())
      {
        echo '<span class="answer">Correct answer!</span>';
      }
      else
      {
        echo '<span class="answer error">Wrong answer!</span>';
      }
    }
  }
?>

<p>Enter the result of math operation displayed below</p>
<form action="index4.php" method="post">
<img id="captcha" src="captcha4.php" alt="captcha" /><br />
<input type="text" size="6" name="captcha" /><br />
<input type="submit" value="Submit" />
</form>
</body>
</html>
