<?php
declare(strict_types=1);
//データベース接続
$username = "root";
$password = "";
$hostname = "localhost";
$db = "phpstaffmanagement";
$pdo = new PDO("mysql:host={$hostname};dbname={$db};charset=utf8",$username,$password);


session_start();

if(!isset($_SESSION["id"])){
    header("Location:login.php");
    exit;
}

$id = '';
$nameKana = '';
$gender = '';
$whereSql = '';
$param = [];
$errorMessage = '';
$successMessage = '';

//deleteUserのsubmit()を押すと、IDを取得できる
if (mb_strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
  //trueならば削除ボタンが押されたということ
  $isDelete = (isset($_POST['delete']) && $_POST['delete'] === '1') ? true : false;

  if ($isDelete === true) {
      //POSTされた社員番号の入力チェック
      $deleteId = isset($_POST['id']) ? $_POST['id'] : '';
      if ($deleteId === '') { //空白でないか
          $errorMessage .= '社員番号が不正です。<br>';
      } else if (!preg_match('/\A[0-9]{6}\z/', $deleteId)) { //6桁の数値か
          $errorMessage .= '社員番号が不正です。<br>';
      } else {
          //存在する社員番号か
          $sql = "SELECT COUNT(*) AS count FROM users WHERE id = :id";
          $param = array("id" => $deleteId);
          $stmt = $pdo->prepare($sql);
          $stmt->execute($param);
          $count = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($count['count'] === '0') {
              $errorMessage .= '社員番号が不正です。<br>';
          }
      }

      //入力チェックOK?
      if ($errorMessage === '') {
          $pdo->beginTransaction();
          $sql = "DELETE FROM users WHERE id = :id";
          $param = array("id" => $deleteId);
          $stmt = $pdo->prepare($sql);
          $stmt->execute($param);
          $pdo->commit();
          $successMessage = "削除完了しました。";
      } else {
          // エラー有り
          echo $errorMessage;
      }
  }
}

$param = [];
// 検索条件が指定されている
if (isset($_GET['id']) && isset($_GET['name_kana'])) {
    $id = $_GET['id'];
    $nameKana = $_GET['name_kana'];
    $gender = isset($_GET['gender']) ? $_GET['gender'] : '';

// 社員番号が入力されている
if ($id !== '') {
    // 検索条件に社員番号を追加
    $whereSql .= 'AND id = :id ';
    $param['id'] = $id;
}
// 社員名カナが入力されている
if ($nameKana !== '') {
    // 検索条件に社員名カナを追加
    $whereSql .= 'AND name_kana LIKE :name_kana ';
    $param['name_kana'] = $nameKana . '%';
}
// 性別が入力されている
if ($gender !== '') {
    // 検索条件に性別を追加
    $whereSql .= 'AND gender = :gender ';
    $param['gender'] = $gender;
}
}
//件数取得SQLの実行
$sql = "SELECT COUNT(*) As count FROM users where 1 = 1 {$whereSql}";

$stmt = $pdo->prepare($sql);
$stmt->execute($param);
$count = $stmt->fetch(PDO::FETCH_ASSOC);

//社員情報取得SQLの実行
$sql = "SELECT * FROM users where 1 = 1 {$whereSql} ORDER BY id";
$stmt = $pdo->prepare($sql);
$stmt->execute($param);

?>
<?php require_once('template/search.php');?>