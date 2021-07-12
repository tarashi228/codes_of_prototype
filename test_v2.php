<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    
    <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>
    <video class="col-4 m-4" id="video" autoplay controls>
        <source src="sample.mp4">
    </video>
    <h2 class="px-4">チャプター選択</h2>
    <div class="m-4" style="height:400px; width:500px; overflow-x:scroll;">
        <table class="table">
            <tbody class="chaps"></tbody>
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
    
    <script src="sample.js"></script>
    <script src="test_v2.js"></script>
    <script src="https://vjs.zencdn.net/7.11.4/video.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>
</html>