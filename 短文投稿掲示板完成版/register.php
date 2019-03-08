<?php
  if (isset($_POST['data'])){
    // 新規登録の場合

    // データの受信
    $data = $_POST['data'];
    // ログインユーザ名、ログインパスワード、氏名がなければ登録不可
    if (empty($data['login_name']) || empty($data['password']) || empty($data['name'])){
      header('Location: register.php');
      exit();
    }

      // データベースに接続
      $dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8';
      $user = 'testuser';
      $password = 'password';
    
    try {
      $db = new PDO($dsn, $user, $password);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
      // usersテーブルにデータを挿入
      $stmt = $db->prepare(
        "INSERT INTO users (name, password) VALUES(:name, :pass)"
      );
      $stmt->bindParam(':name', $data['login_name'], PDO::PARAM_STR);
      $stmt->bindParam(':pass', sha1($data['password']), PDO::PARAM_STR);
      $stmt->execute();

      // usersテーブルに挿入したデータのIDを取得
      $id = intval($db->lastInsertId());

      // usersテーブルに挿入したレコードのIDを元にprofilesテーブルにデータを挿入
      $stmt = $db->prepare(
        "INSERT INTO profiles(id, name, body, mail)
         VALUES(:id, :name, :body, :mail)"
      );
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
      $stmt->bindParam(':body', $data['body'], PDO::PARAM_STR);
      $stmt->bindParam(':mail', $data['mail'], PDO::PARAM_STR);
      $stmt->execute();

      header('Location: login.php');
      exit();
    } catch (PDOException $e){
      die('エラー：' . $e->getMessage());
    }

  } else {
    // 登録用フォームを表示する場合
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" href="stylesheet.css">
<div style="position:absolute; top:100px; left:140px">
<img src="https://image.freepik.com/free-photo/no-translate-detected_1194-7666.jpg"width="800" height="500">
 </div>
  <title>短文投稿掲示板</title>
</head>
<header>
</header>
<div class="register-wrapper">
<div class="container">
<body>

<h1>ユーザ新規登録はこちらから</h1>
<h2>こちらは短文投稿掲示板です。<br>140字以内のも短文の投稿が可能です。<br>指定文字数にお気をつけください。<br>登録情報は全ユーザーが閲覧できます。</h2>
  <form action="register.php" method="post">
<div class = "register-form">
<div class="register">
    <p1 >ユーザ名：<input maxlength='10' placeholder='ユーザ名を10文字で入力' type="text" name="data[login_name]" /></p1>
    <p2>パスワード：<input placeholder='パスワードを入力' type="password" name="data[password]" /></p2>
    <p3>氏名：<input maxlength='6' placeholder='名前を6文字で入力' type="text" name="data[name]" /></p3>
    <p4>自己紹介</p4>
    <textarea   input maxlength='7' placeholder='自己紹介を一言でどうぞ！(７文字)' name="data[body]"></textarea>
    <p5>メール：<input placeholder='〇〇〇〇@example.com' type="text" name="data[mail]" /></p5>
    <p6><input type="submit" value="新規登録"  class="btn users" /></p6>
    <p7><a href="login.php" class="btn kjbn">ログイン</a></p7>
</div>
</div>
 </form>
</div>
</div>
</body>
</html>

<?php } ?>
