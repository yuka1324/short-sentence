<?php
    include 'includes/login.php';
    
    // データベースに接続
    $dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8';
    $user = 'testuser';
    $password = 'password';
    
    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        $stmt = $db->prepare(
                             "SELECT users.id, users.name AS login_name, profiles.name AS name, body, mail
                             FROM users, profiles
                             WHERE users.id=profiles.id"
                             );
        $stmt->execute();
        
    } catch (PDOException $e){
        die('エラー：' . $e->getMessage());
    }
    ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
 <link rel="stylesheet" href="stylesheet.css">
<title>短文投稿掲示板</title>
</head>
<body>
<div class="home-wrapper">
<h1>登録者一覧</h1>
</div>

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<div class="lesson-wrapper">
<div class="container">
<div class = "icon-form">
<div class="icon">
<img src="https://free-style.mkstyle.net/web/wp-content/uploads/Chalkboard-Whiteline-Apps.jpg"width="250" height="200">
<p>
<td><?php echo "ログイン名:" ?></td>
<td><?php echo $row['login_name'] ?></td><br>
<td><?php echo "ニックネーム:" ?></td>
<td><?php echo $row['name'] ?></td><br>
<td><?php echo "ひとこと！:" ?></td>
<td><?php echo nl2br($row['body']) ?></td><br>
<td><?php echo "メールアドレス" ?></td><br>
<td><?php echo $row['mail'] ?></td><br>
</p>
</div>
</div>
</div>
</div>
<?php endwhile; ?>
<div style="position:absolute; top:530px; left:30px;">
<p2><a href="index.php" class="btn kjbn">ホームへ戻る</a></p2>
</div>
</body>

</html>

