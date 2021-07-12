<?php
$pdo = new PDO('mysql:host=localhost;dbname=shiori;charset=utf8','root', 'root');
$clicked_chapter = (int) $_REQUEST['chapter'];
$sth = $pdo->query('SELECT * FROM click_count')->rowCount();
if ($sth == 0) {
    $N = $_REQUEST['N'];
    for ($i=1; $i<=$N; $i++) {
        $create_table = $pdo->prepare('INSERT INTO click_count (id, count) VALUES (?, ?)');
        $create_table->execute([$i, 0]);
    }
}
$get_chap = $pdo->prepare('SELECT count FROM click_count WHERE id=?');
$res = $get_chap->execute([$clicked_chapter]);
$pre_count = $get_chap->fetch()[0];
echo $pre_count;
$set_count = $pdo->prepare('UPDATE click_count SET count=? WHERE id=?');
$set_count->execute([$pre_count + 1, $clicked_chapter]);
header('Location: main.php?sec=' . $_REQUEST['sec']);

?>