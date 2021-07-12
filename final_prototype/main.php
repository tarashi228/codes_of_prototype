<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    
    <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>
    <p><?php
    $pdo=new PDO('mysql:host=localhost;dbname=main;charset=utf8',	'root', 'root');
    $sql=$pdo->prepare('insert into shiori values(null, ?, ?)');
    if (!empty($_REQUEST['index']) and !empty($_REQUEST['time'])){
        $sql->execute([$_REQUEST['index'], $_REQUEST['time']]);
        header('Location: main.php?sec=' . $_REQUEST['time']);
    }
    ?></p>
    <video class="col-4 m-4" id="video" autoplay controls>
        <source src="sample.mp4">
    </video>
    <?php
        $sql3=$pdo->prepare('delete from shiori where id=?');
        if (!empty($_REQUEST['id'])){
            $sql3->execute([$_REQUEST['id']]);
        }
        $sql2=$pdo->prepare('select * from shiori');
        $sql2->execute([]);
        foreach ($sql2 as $row) {
            $comment_id = 1000 + $row['id'];
            $time_id = $row['id'];
            $txt1=$row['comment'];
            $time=$row['time'];
            $hours = floor($time/3600);
            $minutes = floor(($time/60)%60);
            $seconds = $time%60;
            echo '<div id="',$comment_id,'" class="bookmarkmemo">';
            echo $txt1;
            echo '<a href="main.php?id=', $row['id'], '">削除</a>';
            echo '</div>';
            echo '<button id="',$time_id,'" class="bookmark" onclick="jump(',$row['id'],')">';
            echo $hours,':',$minutes,':',$seconds;
            // echo $time;
            echo '</button>';
        }
    ?>
    <br>
    <form name="myform" method="POST" action="main.php">
         <p>index：<input type="text" name="index" id="index"></p>
         <input type="hidden" name="time" id="time" value="">
         <button type="button" onclick="getMdTime()" value="">しおり</button>
    </form>
    <h2 class="px-4">チャプター選択</h2>
    <div class="m-4" style="height:400px; width:500px; overflow-x:scroll;">
        <table class="table">
            <tbody class="chaps"></tbody>
        </table>
    </div>
    <div class="comments m-4" style="width:500px;">
    <table class="table">
    <thead><tr><th>時間</th><th>コメント</th><th>削除</th></tr></thead>
    <tbody style="height:400px; width:500px; overflow-x:scroll;">
<?php
$pdo=new PDO('mysql:host=localhost;dbname=main;charset=utf8', 'root', 'root');
$sql=$pdo->prepare('select * from comment order by LPAD(time,8,0)');
$sql->execute([]);

echo '<p>';
echo '<form name="comment_form" action="comment_add.php" method="post">';
echo '<input type="hidden" name="com_time" id="com_time">';
echo '<p>';
echo '<input type="text" name="comment">';
echo '<button type="button" onclick="getMdTime_comment()">新規コメント</button>';
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
        echo '<tr><td class="text-center"><i class="fas fa-reply"></i></td>';
        echo '<td>', $row['text'], '</td>';
        echo '<td><a href="reply_delete.php?id=', $row['reply_id'], '">削除</a></td>';
        echo '</tr>';
    }
    echo '<tr><td class="text-center"><i class="fas fa-reply"></i></td>';
    echo '<td><form action="reply_add.php" method="post">';
    echo '<input type="hidden" name="id" value="',$time_id,'">';
    echo '<input type="text" name="reply">';
    echo '<input type="submit" value="返信">';
    echo '</form></td>';
    echo '</tr>';
}
?>
</tbody>
</table>
    </div>
    
    <form name="data_form" id="datas" method="POST" action="insert_data.php">
        <input type="hidden" name="chapter" id="chapter" value="">
        <input type="hidden" name="N" value="">
        <input type="hidden" name="sec" value="">
    </form>
    
    <?php
    $sec = 0;
    if (isset($_GET['sec'])) {
        $sec = $_GET['sec'];
    }
    ?>
    <script>
    var video = $('video').get(0);
    video.currentTime = <?php echo $sec; ?>
    </script>
    <script type="text/javascript">
        var video=document.getElementById('video');
        function getMdTime(){
            var playtime=video.currentTime;
            var time=document.getElementById('time');
            time.value=Math.floor(playtime);
            document.myform.submit();
        }
        function getMdTime_comment(){
            var playtime=video.currentTime;
            var time=document.getElementById('com_time');
            time.value=Math.floor(playtime);
            document.comment_form.submit();
            // console.log(playtime);
        }
        function jump(i){
            var video=document.getElementById('video');
            var time =document.getElementById(i);
            var time2=time.textContent.split(":");
            var sec = 3600 * parseInt(time2[0]) + 60 * parseInt(time2[1]) + parseInt(time2[2]);
            video.currentTime = sec;
        }
    </script>
    
    <script src="sample.js"></script>
    <script src="test_v2.js"></script>
    <script src="https://vjs.zencdn.net/7.11.4/video.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>
</html>