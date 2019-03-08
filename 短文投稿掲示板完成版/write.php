<?php
  include 'includes/login.php';
  // データの受け取り
  $name = $_POST['name'];
  $body = $_POST['body'];
  $pass = $_POST['pass'];

  // 必須項目チェック(名前か本文が空ではないか?)
  if ($name == '' || $body == ''){
    header('Location: bbs.php'); // bbs.phpへ移動
    exit(); // 終了
  }
  // 必須項目チェック(パスワードは4桁の数字か?)
  if (!preg_match("/^[0-9]{4}$/", $pass)){
    header('Location: bbs.php');
    exit();
  }
  // 名前をクッキーにセット
  setcookie('name', $name, time() + 60 * 60 * 24 * 30);

    // データベースに接続
    $dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8';
    $user = 'testuser';
    $password = 'password';

  try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // プリペアドステートメントを作成
    $stmt = $db->prepare("
      INSERT INTO bbs (name, body, date, pass)
      VALUES (:name, :body, now(), :pass)"
    );
    // パラメータを割り当て
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':body', $body, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    // クエリの実行
    $stmt->execute();

    // bbs.phpに戻る
    header('Location: bbs.php');
    exit();
  } catch(PDOException $e) {
    die ('エラー:' . $e->getMessage());
  }
?>
