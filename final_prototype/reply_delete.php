<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP Sample Programs</title>
</head>
<body>
<?php
$pdo=new PDO('mysql:host=localhost;dbname=main;charset=utf8', 'root', 'root');
$sql=$pdo->prepare('delete from reply where reply_id=?');
if ($sql->execute([$_REQUEST['id']])) {
	echo '削除に成功しました。';
} else {
	echo '削除に失敗しました。';
}
echo '<p>';
echo '<a href="main.php">戻る</a>';
echo '</p>';
?>
</body>
</html>