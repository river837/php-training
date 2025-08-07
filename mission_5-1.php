<html>
<mata charset="utf-8">
<lang="ja">
<head><title>フォーム</title></head>

<body>
<h1>入力フォーム</h1>
<form action="" method="post">
    名  前：<input type="text" name="name"><br>
    コメント：<input type="text" name="comment"><br>
    パスワード：<input type="text" name="password"><br>
    <input type="submit" value="送信する">
</form>

<br>

<h1>削除フォーム</h1>
<font color ="blue">※半角でお願いします。</font><br>
<form action="" method="post">
    削除：<input type="text" name="delete"><br>
    パスワード：<input type="text" name="password2"><br>
    <input type="submit" name="sakuzyo" value="削除する"><br>
</form>

<br>

<h1>編集フォーム</h1>
<font color ="blue">※半角でお願いします。</font><br>
<form action = "" method="post">
    編集対象番号:<input type="text" name="number"><br>
    パスワード：<input type="text" name="password3"><br>
    <input type="submit" name="hensyu"value="編集する">
</form>

<?php
// データベースと接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//データベースにテーブルを作成する
$sql = "CREATE TABLE IF NOT EXISTS keijiban"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "time TEXT,"
. "pw TEXT"
.");";
$stmt = $pdo->query($sql);

if(!empty($_POST['name'])){
    $name = $_POST['name'];
    $name = htmlspecialchars($name);    
}

if(!empty($_POST['comment'])){
    $comment = $_POST['comment'];
    $comment = htmlspecialchars($comment);
}

if(!empty($_POST['password'])){
    $pw = $_POST['password'];
    $pw = htmlspecialchars($pw);
}

$time = date("Y/m/d H:i:s");

//データ書き込み
if (!empty($name) && !empty($comment) &&!empty($pw)) {
    $sql = $pdo -> prepare("INSERT INTO keijiban (name, comment, time, pw) VALUES (:name, :comment, :time, :pw)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':time', $time, PDO::PARAM_STR);
    $sql -> bindParam(':pw', $pw, PDO::PARAM_STR);
    $time = date("Y/m/d H:i:s");
    $sql -> execute();
}


//データ消去
if (!empty($_POST['delete'])) {
    $delete = $_POST['delete'];
}
if (!empty($_POST['sakuzyo'])){
    $sakuzyo = $_POST['sakuzyo'];
}

if (!empty($_POST['password2'])){
    $pas2 = $_POST['password2'];
}


//削除機能
if (!empty($sakuzyo)) {

    $id =$delete;
    $sql = 'SELECT * FROM keijiban';
	$stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();//queryの結果は配列で帰ってくる
    foreach ($results as $row){//queryの結果は配列で帰ってくる
        $pw = $row['pw'];
    }
    if ($pw == $pas2) {
        $id =$delete;
        $sql = 'delete from keijiban where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    
}

//データ編集
if(!empty($_POST['hensyu'])){
    $hensyu = $_POST['hensyu'];
}
if(!empty($_POST['number'])){
    $bnum = $_POST['number'];
}
if(!empty($_POST['password3'])){
    $pas3 = $_POST['password3'];
}


if (!empty($hensyu)) {

    $id = $hensyu;
    $sql = 'SELECT * FROM keijiban';
	$stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();//queryの結果は配列で帰ってくる
    foreach ($results as $row){//queryの結果は配列で帰ってくる
        $pw = $row['pw'];
    }

       if($pw == $pas3){

        echo "No".$row['id']."の書き込みを編集してください<br>";
        echo "<form method=POST action=mission_5-1.php>";
        echo "名  前：<input type='text' name='name2' size='20' value='" . $row['name'] . "'><br>";
        echo "コメント：<input type='text' name='comment2' size='60' value='" . $row['comment'] . "'><br>";
        echo "<input type='submit' name='uwagaki' value='上書き保存'><input type='hidden' name='bnumber' value='" . $bnum . "'>";
        echo "</form>";
       }     

//編集上書き
if (!empty($_POST["uwagaki"])){

    $id = $_POST["bnumber"];
    $bnum = $_POST["bnumber"];
    $name = $_POST['name2'];
    $comment = $_POST['comment2'];
    $time = date("Y/m/d H:i:s");

    $sql = 'SELECT * FROM keijiban';
	$stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();//queryの結果は配列で帰ってくる
    foreach ($results as $row){//queryの結果は配列で帰ってくる
        $pw = $pas3;

   if ($pw == $bnum) { //置き換え対象行を探す
        // $newline = $bnum . "<>" . $name . "<>" . $comment . "<>" . $time . "\n";
        $sql = 'update keijiban set name=:name,comment=:comment,time=:time,pw=pw where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pw', $pw, PDO::PARAM_INT);
        $stmt->execute();
    }//executeはprepareで用意されたクエリを実行する
}
}
}

// prepareメソッドはqueryメソッドと似たような機能を提供しますが、SQL文の基本部分が同じで値だけ異なるような場合
//(例えば同じテーブルに値だけ変えて何回もデータを挿入するような場合です)に効率よく行える機能を提供してくれます。
//引数に指定したSQL文をデータベースに対して発行してくれます。queryメソッドと違う点は、SQL文の一部を変数のように記述しておき、その部分に当てはめる値を後から指定できる点です。
//またパラメータを自動的にエスケープ処理をしてくれるため、
//個別のパラメータについてエスケープ処理を行う必要が無くなります。その為、値が固定で無いSQLを使う場合には、queryメソッドではなくprepareメソッドを使うのが基本となると思います。


// //queryの役割って何でしょうか。
// そもそもなぜ必要なのでしょうか。
// queryはqueryメソッドなどと呼ばれます。
// 指定したSQL文をデータベースに対して発行してくれる役割を持っています
// fetchAll() は、 結果セットに残っている全ての行を含む配列を返します。 この配列は、カラム値の配列もし
//くは各カラム名に対応するプロパティを持つオブジェクトとして各行を表します。 取得結果がゼロ件だった場合は空の配列を返し、
// 失敗した場合は FALSE を返します。
// 大きな結果セットをフェッチするためにこのメソッドを使用することは、 システムとネットワークリソースに
//大量の要求を行うことになります。 PHP で全てのデータ処理と操作を行うよりも、
//データベースサーバー側で 結果セットを操作することを検討してください。例えば、PHP 
//で処理を行う前に SQL で WHERE 句や ORDER BY 句を使用し、結果を制限することです。
echo "__________________掲示板欄______________________<br>";
//表示機能
$sql = 'SELECT * FROM keijiban';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){//queryの結果は配列で帰ってくる
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].'.';
    echo $row['time'].'<br>';
    echo "<hr>";
}
?>
</body>
</html>


