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
        <source src="zoom_0.mp4"
            type="video/mp4">
            
    </video>
    <div id="bookmarkmemo" class="bookmarkmemo" > </div>
    <button id="bookmark" class="bookmark" onclick="jump()"> </button>
    <br>

    <form name="myform" method="POST" action="siorikinou3.php">
         <p>index：<input type="text" name="index" id="index" ></p>
         <input type="hidden" name="time" id="time">
         <button type="button" onclick="getMdTime()">しおり</button>
    </form>
    <script type="text/javascript">
        var video=document.getElementById('video');
        function getMdTime(){
            var playtime=video.currentTime;
            var time=document.getElementById('time');
            var index=document.getElementById('index');
            const div = document.getElementById('bookmarkmemo');
            const div2=document.getElementById('bookmark');
        
            time.value=playtime;
            div.textContent = index.value;
            div2.textContent = time.value;
              
            

            //document.myform.submit();

            

        }
        function jump(){
            var video=document.getElementById('video');
            var time =document.getElementById('bookmark');
            var time2=time.textContent;
            video.currentTime = time2;
        }
    </script>


    <?php
    if($_POST(['index']!=NULL)){
        $index = $_POST['index'];
        $time = $_POST['time'];
        $filename = 'sample3.txt'; /*保存先にファイル名を$filenameに代入*/
        $fp = fopen($filename,'a'); /*ファイルを追記モードで開く*/
        fwrite($fp,$index.' <> '.$time."\n"); /*情報をファイルに書き込む*/
        fclose($fp); /*ファイルを閉じる*/
    }
    ?>
</body>
</html>