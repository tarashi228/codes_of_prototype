<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP Sample Programs</title>
</head>
<body>
<?php
$pdo=new PDO('mysql:host=localhost;dbname=main;charset=utf8','root', 'root');
$sql=$pdo->prepare('insert into comment values(null, ?, ?)');
if (empty($_REQUEST['comment'])) {
	echo 'コメントを入力してください。';
}
elseif(!preg_match('/[0-9]+/', $_REQUEST['com_time'])){
    echo '時刻を入力してください。時刻は数値である必要があります。';
}
else{
    if ($sql->execute([$_REQUEST['comment'], $_REQUEST['com_time']])) {
        echo '追加に成功しました。';
    } 
    else {
        echo '追加に失敗しました。';
    }
}
echo '<p>';
echo '<a href="main.php">戻る</a>';
echo '</p>';
?>
</body>
</html>