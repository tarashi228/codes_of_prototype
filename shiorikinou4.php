<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p><?php
    $pdo=new PDO('mysql:host=localhost;dbname=shiori;charset=utf8',	'root', 'root');
    $sql=$pdo->prepare('insert into shiori values(null, ?, ?)');
    if (!empty($_REQUEST['index']) and !empty($_REQUEST['time'])){
        $sql->execute([$_REQUEST['index'], $_REQUEST['time']]);
    }
    ?></p>

    <video controls width="1000" id="video"  autoplay >
        <source src="sample.mp4"
            type="video/mp4">
            
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
            //$hours = floor($time/3600);
            //$minutes = floor(($time/60)%60);
            //$seconds = $time%60;
            echo '<div id="',$comment_id,'" class="bookmarkmemo">';
            echo $txt1;
            echo '<a href="shiorikinou3.php?id=', $row['id'], '">削除</a>';
            echo '</div>';
            echo '<button id="',$time_id,'" class="bookmark" onclick="jump(',$row['id'],')">';
            //echo $hours,':',$minutes,':',$seconds;
            echo $time;
            echo '</button>';
        }
    ?>
    <br>
    <form name="myform" method="POST" action="siorikinou3.php">
         <p>index：<input type="text" name="index" id="index"></p>
         <input type="hidden" name="time" id="time" value="">
         <button type="button" onclick="getMdTime()" value="">しおり</button>
    </form>

    <script type="text/javascript">
        var video=document.getElementById('video');
        function getMdTime(){
            var playtime=video.currentTime;
            var time=document.getElementById('time');
            //var index=document.getElementById('index');
            //const div = document.getElementById('bookmarkmemo');
            //const div2=document.getElementById('bookmark');

            time.value=playtime;
            //div.textContent = index.value;
            //div2.textContent = time.value;
            
              
            

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