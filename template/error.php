<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>エラー</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>

<div id="header">
<h1>社員管理システム</h1>
</div>

<div class="clearfix">
<div id="menu">
  <h3>メニュー</h3>
  <div class="sub_menu"><a href="./search.php">社員検索</a></div>
  <div class="sub_menu"><a href="./input.php">社員登録</a></div>
</div>

<div id="main">
  <div class="error_message"><?php echo $errorMessage; ?></div>
</div>
</div>
</body>
</html>