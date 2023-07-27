<?php
declare(strict_types=1);
require_once(dirname(__DIR__) . "/library/common.php");

$id = '';
$name = '';
$nameKana = '';
$birthday = '';
$gender = '';
$organization = '';
$post = '';
$startDate = '';
$tel = '';
$mailAddress = '';
$errorMessage = '';
$successMessage = '';
$isEdit = false;
$isSave = false;



//POST通信
if (mb_strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
  // var_dump($_POST);
  $id = isset($_POST['id']) ? $_POST['id'] : '';
  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $nameKana = isset($_POST['name_kana']) ? $_POST['name_kana'] : '';
  $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
  $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
  $organization = isset($_POST['organization']) ? $_POST['organization'] : '';
  $post = isset($_POST['post']) ? $_POST['post'] : '';
  $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
  $tel = isset($_POST['tel']) ? $_POST['tel'] : '';
  $mailAddress = isset($_POST['mail_address']) ? $_POST['mail_address'] : '';

  //trueならば登録ボタンが押されたということ
  $isSave = (isset($_POST['save']) && $_POST['save'] === '1') ? true : false; 

  //trueならば既存データの更新ということ
  $isEdit = (isset($_POST['edit']) && $_POST['edit'] === '1') ? true : false;

  //社員検索画面の編集ボタン押下
  if ($isEdit === true && $isSave === false) {
      //POSTされた社員番号の入力チェック
      if (!validateRequired($id)) { //空白でないか
          $errorMessage .= 'エラーが発生しました。もう一度やり直してください。<br>';
      } else if (!validateId($id)) { //6桁の数値か
          $errorMessage .= 'エラーが発生しました。もう一度やり直してください。<br>';
      } else {
          //存在する社員番号か
          $sql = "SELECT COUNT(*) AS count FROM users WHERE id = :id";
          $param = array("id" => $id);
          $count = DataBase::fetch($sql,$param);
          if ($count['count'] === '0') {
              $errorMessage .= 'エラーが発生しました。もう一度やり直してください。<br>';
          }
      }

      //入力チェックOK?
      if ($errorMessage === '') {
          //社員情報取得SQLの実行
          $sql = "SELECT * FROM users WHERE id = :id";
          $param = array('id' => $id);
          $user = Database::fetch($sql,$param);

          $id = $user['id'];
          $name = $user['name'];
          $nameKana = $user['name_kana'];
          $birthday = $user['birthday'];
          $gender = $user['gender'];
          $organization = $user['organization'];
          $post = $user['post'];
          $startDate = $user['start_date'];
          $tel = $user['tel'];
          $mailAddress = $user['mail_address'];
      } else {
          //エラー画面表示
          require_once(TEMPLATE_DIR. "error.php"); 
?>
<?php
          exit; //処理終了
      }
  }

  //登録ボタン押下
  if ($isSave === true) {
      //POSTされた社員番号の入力チェック
      if (!validateRequired($id)) { //空白でないか
          $errorMessage .= '社員番号を入力してください。<br>';
      } else if (!validateId($id)){ //6桁の数値か
          $errorMessage .= '社員番号は6桁の数値で入力してください。<br>';
      } else {
          //(新規登録時)存在しない社員番号か
          //(更新時)存在する社員番号か
          $sql = "SELECT COUNT(*) AS count FROM users WHERE id = :id";
          $param = array("id" => $id);
          $count = DataBase::fetch($sql,$param);
          if ($isEdit === false && $count['count'] >= 1) {
              //新規登録時に同一社員番号が存在したらエラー
              $errorMessage .= '登録済みの社員番号です。<br>';
          } else if ($isEdit === true && $count['count'] === "0") {
              //更新時に同一社員番号が存在しなかったらエラー
              $errorMessage .= '存在しない社員番号です。<br>';
          }
      }

      //POSTされた社員名の入力チェック
      if (!validateRequired($name)) { //空白でないか
          $errorMessage .= '社員名を入力してください。<br>';
      } else if (!validateMaxLength($name,50)) { //50文字以内か
          $errorMessage .= '社員名は50文字以内で入力してください。<br>';
      }

      //POSTされた社員名カナの入力チェック
      if (!validateRequired($nameKana)) { //空白でないか
          $errorMessage .= '社員名カナを入力してください。<br>';
      } else if (!validateMaxLength($nameKana,50)) { //50文字以内か
          $errorMessage .= '社員名カナは50文字以内で入力してください。<br>';
      }

      //POSTされた生年月日の入力チェック
      if (!validateRequired($birthday)) { //空白でないか
          $errorMessage .= '生年月日を入力してください。<br>';
      }else if (!validateDate($birthday)){
        $errorMessage .= '生年月日を正しく入力してください。<br>';
      }

      //POSTされた性別の入力チェック
      if (!validateGender($gender)) {
          $errorMessage .= '性別を選択してください。<br>';
      }

      //POSTされた部署の入力チェック
      if (!validateOrganization($organization)) {
          $errorMessage .= '部署を選択してください。<br>';
      }

      //POSTされた役職の入力チェック
      if (!validatePost($post)) {
          $errorMessage .= '役職を選択してください。<br>';
      }

      //POSTされた入社年月日の入力チェック
      if (!validateRequired($startDate)) { //空白でないか
          $errorMessage .= '入社年月日を入力してください。<br>';
      } else if(!validateDate($startDate)) {
          $errorMessage .= '入社年月日を正しく入力してください。<br>';
        }
      

      //POSTされた電話番号の入力チェック
      if (!validateRequired($tel)) { //空白でないか
          $errorMessage .= '電話番号を入力してください。<br>';
      } else if (!validateTel($tel)) { //15桁以内の数値か
          $errorMessage .= '電話番号は15桁以内の数値で入力してください。<br>';
      }

      //POSTされたメールアドレスの入力チェック
      if (!validateRequired($mailAddress)) { //空白でないか
          $errorMessage .= 'メールアドレスを入力してください。<br>';
      } else if (!validateMailAddress($mailAddress)){
          $errorMessage .= 'メールアドレスを正しく入力してください。<br>';
      }

      //入力チェックOK?
      if ($errorMessage === '') {
          // echo "エラーなし";
          //トランザクション開始
           DataBase::beginTransaction();

          //新規登録?
          if ($isEdit === false) {
              //新規登録
              //社員情報登録SQLの実行
              $sql  = "INSERT INTO users ( ";
              $sql .= "  id, ";
              $sql .= "  name, ";
              $sql .= "  name_kana, ";
              $sql .= "  birthday, ";
              $sql .= "  gender, ";
              $sql .= "  organization, ";
              $sql .= "  post, ";
              $sql .= "  start_date, ";
              $sql .= "  tel, ";
              $sql .= "  mail_address, ";
              $sql .= "  created, ";
              $sql .= "  updated ";
              $sql .= ") VALUES (";
              $sql .= "  :id, ";
              $sql .= "  :name, ";
              $sql .= "  :name_kana, ";
              $sql .= "  :birthday, ";
              $sql .= "  :gender, ";
              $sql .= "  :organization, ";;
              $sql .= "  :post, ";
              $sql .= "  :start_date, ";
              $sql .= "  :tel, ";
              $sql .= "  :mail_address, ";
              $sql .= "  NOW(), "; //作成日時
              $sql .= "  NOW() ";  //更新日時
              $sql .= ")";
          } else {
              //更新
              //社員情報更新SQLの実行
              $sql  = "UPDATE users ";
              $sql .= "SET name = :name, ";
              $sql .= "  name_kana = :name_kana, ";
              $sql .= "  birthday = :birthday, ";
              $sql .= "  gender = :gender, ";
              $sql .= "  organization = :organization, ";
              $sql .= "  post = :post, ";
              $sql .= "  start_date = :start_date, ";
              $sql .= "  tel = :tel, ";
              $sql .= "  mail_address = :mail_address, ";
              $sql .= "  updated = NOW() "; //更新日時
              $sql .= "WHERE id = :id ";
          }
          $param = array(
              "id" => $id,
              "name" => $name,
              "name_kana" => $nameKana,
              "birthday" => $birthday,
              "gender" => $gender,
              "organization" => $organization,
              "post" => $post,
              "start_date" => $startDate,
              "tel" => $tel,
              "mail_address" => $mailAddress,
          );
          DataBase::execute($sql,$param);

          //コミット
          DataBase::commit();

          $successMessage = "登録完了しました。";
          $isEdit = true;
      // } else {
      //     // エラー有り
      //     echo $errorMessage;
      }
  }
}
$title = "社員登録";
require_once(TEMPLATE_DIR. "input.php");
?>
