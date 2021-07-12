<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP Sample Programs</title>
</head>
<body>
<?php
$pdo=new PDO('mysql:host=localhost;dbname=main;charset=utf8',	'root', 'root');
$sql=$pdo->prepare('insert into reply values(null, ?, ?)');
if (empty($_REQUEST['reply'])) {
	echo 'コメントを入力してください。';
} else

if ($sql->execute([$_REQUEST['id'], $_REQUEST['reply']])) {
	echo '追加に成功しました。';
} else {
	echo '追加に失敗しました。';
}
echo '<p>';
echo '<a href="main.php">戻る</a>';
echo '</p>';
?>
<?php require 'footer.php'; ?>