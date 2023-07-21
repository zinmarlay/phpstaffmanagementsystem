<?php
declare(strict_types=1);
//データベース接続
$username = "root";
$password = "";
$hostname = "localhost";
$db = "phpstaffmanagement";
$pdo = new PDO("mysql:host={$hostname};dbname={$db};charset=utf8",$username,$password);


//件数取得SQLの実行
$sql = "SELECT COUNT(*) As count FROM users";
$param = [];
$stmt = $pdo->prepare($sql);
$stmt->execute($param);
$count = $stmt->fetch(PDO::FETCH_ASSOC);

//社員情報取得SQLの実行
$sql = "SELECT * FROM users ORDER BY id";
$stmt = $pdo->prepare($sql);
$stmt->execute();

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<title>社員検索</title>
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
    <div class="sub_menu">社員検索</div>
    <div class="sub_menu"><a href="input.php">社員登録</a></div>
  </div>

  <div id="main">
    <h3 id="title">社員検索画面</h3>
  
    <div id="search_area">
      <div id="sub_title">検索条件</div>
      <form action="search.php" method="GET">
        <div id="form_area">
          <div class="clearfix">
            <div class="input_area">
              <span class="input_label">社員番号(完全一致)</span>
              <input type="text" name="id" value="" />
            </div>
            <div class="input_area">
              <span class="input_label">社員名カナ(前方一致)</span>
              <input type="text" name="name_kana" value="" />
            </div>
            <div class="input_area"><span class="input_label">性別</span>
              <input type="radio" name="gender" value="男性" id="gender_male" >
              <label for="gender_male">男性</label>
              <input type="radio" name="gender" value="女性" id="gender_female" >
              <label for="gender_female">女性</label>
            </div>
          </div>

          <div class="clearfix">
            <div class="input_area_right"><input type="submit" id="search_button" value="検索"></div>
          </div>
        </div>
      </form>
    </div>

    <?php //件数表示 ?>
    <div id="page_area">
      <div id="page_count"><?php echo $count["count"];?>件ヒットしました</div>
    </div>

    <div id="search_result">
      <table>
        <thead>
          <tr>
            <th>社員番号</th>
            <th>社員名</th>
            <th>性別</th>
            <th>部署</th>
            <th>役職</th>
            <th>電話番号</th>
            <th>メールアドレス</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php //件数が1件以上 ?>
          <?php if ($count["count"] >= 1):?>
          <?php //社員情報取得結果を1行ずつ読込、終端まで繰り返し ?>
          <?php while($row =  $stmt->fetch(PDO::FETCH_ASSOC)):?>
          <tr>
            <?php //社員情報の表示 ?>
            <td><?php echo $row["id"];?></td>
            <td><?php echo $row["name"];?>(<?php echo $row["name_kana"];?>)</td>
            <td><?php echo $row["gender"];?></td>
            <td><?php echo $row["organization"];?></td>
            <td><?php echo $row["post"];?></td>
            <td><?php echo $row["tel"];?></td>
            <td><?php echo $row["mail_address"];?></td>
            <td class="button_area">
              <button class="edit_button">編集</button>
              <button class="delete_button">削除</button>
            </td>
          </tr>
          <?php endwhile;?>
          <?php endif;?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>