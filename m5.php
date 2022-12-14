<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-20</title>
</head>
<?php
//データベースへの接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//データベース内にテーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "str TEXT,"
    . "date char(32),"
    . "pass1 char(32)"
    .");";
    $stmt = $pdo->query($sql);

//データベースのテーブル一覧を表示
    $sql ='SHOW TABLES';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[0];
        echo '<br>';
    }
    echo "<hr>";

//作成したテーブルの構成詳細を確認する
    $sql ='SHOW CREATE TABLE tbtest';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[1];
    }
    echo "<hr>";
    
//変数を定義
    $date = date("Y/m/d H:i:s");
    $str = $_POST["str"];
    $name = $_POST["name"];
    $del = $_POST["del"];
    $edit = $_POST["edit"];
    $num = $_POST["num"];
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];
    $pass3 = $_POST["pass3"];
    
//データの挿入（通常）
    if(!empty($str) && !empty($name) && empty($num) && !empty($pass1)){
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, str, date, pass1) VALUES (:name, :str, :date, :pass1)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':str', $str, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass1', $pass1, PDO::PARAM_INT);
        $sql -> execute();

//データの挿入（編集）
    }elseif(!empty($str) && !empty($name) && !empty($num)){
        $id = $num; 
        $sql = 'UPDATE tbtest SET name=:name,str=:str,date=:date WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':str', $str, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

//削除機能
    if(!empty($del) && !empty($pass2)){
        $id = $del;
        $sql = 'SELECT * FROM tbtest WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();                             
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            if($row['pass1'] == $pass2){
                $id = $del;
                $sql = 'delete from tbtest where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }

//編集準備
    if(!empty($edit) && !empty($pass3)){
        $id = $edit;
        $sql = 'SELECT * FROM tbtest WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();                             
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            if($row['pass1'] == $pass3){
                $editstr = $row['str'];
                $editname = $row['name'];
                $num = $edit;
                }
        }
    }

//ブラウザに表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['str'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
    }
?>
<body>
    <form action="" method="post">
        <input type="text" name="str" placeholder="コメント" value="<?php
        if(!empty($edit) && !empty($pass3)){
        echo $editstr;} ?>"> <br>
        <input type="text" name="name" placeholder="名前" value="<?php
        if(!empty($edit) && !empty($pass3)){
        echo $editname;} ?>">
        <input type="text" name="pass1" placeholder="パスワード">
        <input type="submit" name="submit"> <br> 
        <input type="text" name="del" placeholder="削除番号">
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="submit" value="削除"> <br>
        <input type="text" name="edit" placeholder="編集対象番号">
        <input type="text" name="pass3" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
        <input type="hidden" name="num" value="<?php 
        if(!empty($edit)){
        echo $num;} ?>"> <br>
    </form>
</body>
</html>

