<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
</head>
<body>
<video controls width="1000" id="video"  autoplay >
    <source src="sample.mp4"
        type="video/mp4">      
</video>
<table class="table">
<?php
$pdo=new PDO('mysql:host=localhost;dbname=main;charset=utf8', 'root', 'root');
$sql=$pdo->prepare('select * from comment order by LPAD(time,8,0)');
$sql->execute([]);

echo '<p>';
echo '<form name="myform" action="comment_add.php" method="post">';
echo '<input type="hidden" name="time" id="time">';
echo '<p>';
echo '<input type="text" name="comment">';
echo '<button type="button" onclick="getMdTime()">新規コメント</button>';
echo '</p>';
echo '</form>';
echo '</p>';
foreach ($sql as $row) {
	$time_id = $row['id'];
    $time = $row['time'];
    $hours = floor($time/3600);
    $minutes = floor(($time/60)%60);
    $seconds = $time%60;
    echo '<tr>';
	echo '<td id="',$time_id,'" class="bookmark" onclick="jump(',$row['id'],')">';
    echo $hours,':',$minutes,':',$seconds;
    echo '</td>';
	echo '<td>', $row['text'], '</td>';
    echo '<td><a href="comment_delete.php?id=', $time_id, '">削除</a></td>';
    echo '</tr>';
    $sql2=$pdo->prepare('select * from reply where place_id =?');
    $sql2->execute([$time_id]);
    foreach ($sql2 as $row){
        echo '<tr><td></td>';
        echo '<td>', $row['text'], '</td>';
        echo '<td><a href="reply_delete.php?id=', $row['reply_id'], '">削除</a></td>';
        echo '</tr>';
    }
    echo '<tr><td></td>';
    echo '<td><form action="reply_add.php" method="post">';
    echo '<input type="hidden" name="id" value="',$time_id,'">';
    echo '<input type="text" name="reply">';
    echo '<input type="submit" value="返信">';
    echo '</form></td>';
    echo '</tr>';
}
?>
</table>
<script type="text/javascript">
    var video=document.getElementById('video');
    function getMdTime(){
        var playtime=video.currentTime;
        var time=document.getElementById('time');
        time.value=Math.floor(playtime);
        document.myform.submit();
    }
    function jump(i){
        var video=document.getElementById('video');
        var time =document.getElementById(i);
        var time2=time.textContent.split(":");
        var sec = 3600 * parseInt(time2[0]) + 60 * parseInt(time2[1]) + parseInt(time2[2]);
        video.currentTime = sec;
    }
</script>
</body>
</html>