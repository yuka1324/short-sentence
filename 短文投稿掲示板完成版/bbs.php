<?php
  // 1ページに表示されるコメントの数
  $num = 10;

  // データベースに接続
  $dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8';
  $user = 'testuser';
  $password = 'password';

  // ページ数が指定されているとき
  $page = 0;
  if (isset($_GET['page']) && $_GET['page'] > 0){
    $page = intval($_GET['page']) -1;
  }

  try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
    // プリペアドステートメントを作成
    $stmt = $db->prepare(
      "SELECT * FROM bbs ORDER BY date DESC LIMIT :page, :num"
    );
    // パラメータを割り当て
    $page = $page * $num;
    $stmt->bindParam(':page', $page, PDO::PARAM_INT);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    // クエリの実行
    $stmt->execute();
  } catch(PDOException $e){
    echo "エラー：" . $e->getMessage();
  }
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
 <link rel="stylesheet" href="bbs.css">
  <title>短文投稿掲示板</title>
</head>
<body>
  <h1>短文投稿掲示板</h1>
  <p><a href="index.php">トップページに戻る</a></p>
  <form action="write.php" method="post">
    <p>名前：<input type="text" name="name"></p>
    <p>本文：<textarea maxlength='140'placeholder='140字以上入力できません' name="body"></textarea></p>
    <p>削除パスワード(数字4桁)：<input maxlength='4' type="text" name="pass"></p>
    <p><input type="submit" value="書き込む"></p>
  </form>
  <hr>
<?php
  while ($row = $stmt->fetch()):
?>
    <p><a>名前：<?php echo $row['name'] ?></a></p>
    <p><?php echo nl2br($row['body'], false) ?></p>
    <p><?php echo $row['date'] ?></p>
    <form action="delete.php" method="post">
      <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
      削除パスワード：<input type="password" name="pass">
      <input type="submit" value="削除">
    </form>
<hr>
<?php
  endwhile;

  // ページ数の表示
  try {
    // プリペアドステートメント作成
    $stmt = $db->prepare("SELECT COUNT(*) FROM bbs");
    // クエリの実行
    $stmt->execute();
  } catch (PDOException $e){
    echo "エラー：" . $e->getMessage();
  }

  // コメントの件数を取得
  $comments = $stmt->fetchColumn();
  // ページ数を計算
  $max_page = ceil($comments / $num);
  echo '<p>';
  for ($i = 1; $i <= $max_page; $i++){
    echo '<a href="bbs.php?page=' . $i . '">' . $i . '</a>&nbsp;';
  }
  echo '</p>';
?>
</body>
</html>
