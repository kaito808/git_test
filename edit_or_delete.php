<?php
// 和田が触りました！！

$link = mysqli_connect('db', 'root', 'root', 'boards_lesson');

// var_dump($_POST);
// exit;



// 各々使う変数の初期化 ※undefined対策

// 投稿画面に存在する各パラメータ
$name;
$subject;
$mes;
$email;
$url;
$image;
$color;
$edit_key;
$preview;


// 親投稿と子投稿のid（主キー）
$board_id;
$reply_id;

// 存在する場合は代入しておく
if(!empty($_POST["board_id"])) {
    $board_id = $_POST["board_id"];
}
if(!empty($_POST["reply_id"])) {
    $reply_id = $_POST["reply_id"];
}


// 今が投稿なのか編集なのかルートを知る為の変数
$mysql_mode;

// 親の新規投稿はありえない

// 子投稿の新規投稿の場合
if(!empty($_POST["reply_insert"])) {
    $mysql_mode = "reply_insert";
}


// 親投稿の更新投稿の場合
if(!empty($_POST["board_update"])) {
    $mysql_mode = "board_update";
}
// 子投稿の更新投稿の場合
if(!empty($_POST["reply_insert"])) {
    $mysql_mode = "reply_insert";
}

// 親投稿の削除の場合
if(!empty($_POST["board_delete"])) {
    $mysql_mode = "board_delete";
}
// 子投稿の削除の場合
if(!empty($_POST["reply_delete"])) {
    $mysql_mode = "reply_delete";
}



// board_idが存在してたら該当データを取得し各々の変数に代入しておく（編集で使う）
if(!empty($board_id) && $mysql_mode !== "reply_insert") {
    $board_result = mysqli_query($link, "SELECT * FROM boards WHERE id = '{$board_id}';");

    // [WHERE id =] の条件である以上必ず１行なのでwhile通さず一行だけ貰う
    $board = mysqli_fetch_assoc($board_result);

    $name = $board["name"];
    $subject = $board["subject"];
    $mes = $board["message"];
    $email = $board["email"];
    $url = $board["url"];
    $image = $board["image"];
    $color = $board["color"];
    $edit_key = $board["delete_key"];
    
// reply_idが存在してたら該当データを取得し各々の変数に代入しておく（編集で使う&&新規の場合は不要なので条件に含めておく）
} else if(!empty($reply_id) && $mysql_mode !== "reply_insert") {
    $re_result = mysqli_query($link, "SELECT * FROM replies WHERE id = '{$reply_id}';");

    // [WHERE id =] の条件である以上必ず１行なのでwhile通さず一行だけ貰う
    $reply = mysqli_fetch_assoc($re_result);

    $name = $reply["name"];
    $subject = $reply["subject"];
    $mes = $reply["message"];
    $email = $reply["email"];
    $url = $reply["url"];
    $image = $reply["image"];
    $color = $reply["color"];
    $edit_key = $reply["delete_key"];
    
}

// ユーザーが打ったパスワード
$u_passwd = "";

// 存在する場合は代入しておく
if(!empty($_POST["password"])) {
    $u_passwd = $_POST["password"];
}

// パスワードが合致しているかどうかの変数 デフォはfalse
$security_auth = false;


// var_dump($edit_key);
// var_dump($u_passwd);
// exit;

// var_dump($board_id);
// var_dump($reply_id);
// var_dump($mysql_mode);
// exit;

// ユーザーが打ったパスワードが$_POSTに存在し、かつ合致していればtrue
if(!empty($u_passwd) && $u_passwd === $edit_key) {
    // 以降パスワードが合致しているものとして扱う
    $security_auth = true;
}

// ユーザーが打ったパスワードが$_POSTに存在するのに、合致していない場合は論外
if(!empty($u_passwd) && $u_passwd !== $edit_key) {
    echo "<h2>もしもし。<br>ワンチャンでパスワードを打つ行為は犯罪です。<br>仕事が増えますやめてください。<small>切実です</small></h2>";
    echo "反省したら<a href='./top.php'>ここ</a>からTOPに戻りましょう";
    exit;
    // ※ ↑のようなハックまがいのリクエストをキャッチするif文には、
    // メールや携帯ワンコールでも何でもいい、アラートが飛ぶ処理を書いておくと、異変に即気付けるのでGOOD。
}


// 準備完了 画面表示(viewって言う）の処理を書く


// 親投稿 更新処理
if(!empty($board_id) && $mysql_mode === "board_update") {

    // 投稿画面の親更新画面verへ
    if($security_auth === true) {
        include("./top_table.php");

    // まずはパスワード入力画面へ
    } else {
        include("./password.php");
    }
    

}

// 子投稿 新規追加
if(!empty($board_id) && $mysql_mode === "reply_insert") {

    // 投稿画面の返信新規追加verへ
    include("./top_table.php");

}

// 子投稿 更新処理
if(!empty($board_id) && !empty($reply_id) && $mysql_mode === "reply_update") {

    // 投稿画面の子更新画面へ
    if($security_auth === true) {
        include("./top_table.php");

    // まずはパスワード入力画面へ
    } else {
        include("./password.php");
    }

}

// 親投稿 削除処理
if(!empty($board_id) && empty($reply_id) && $mysql_mode === "board_delete") {

    // 投稿画面の子削除画面へ
    if($security_auth === true) {
        include("./mysql_functions.php");

    // まずはパスワード入力画面へ
    } else {
        include("./password.php");
    }
}

// 子投稿 削除処理
if(!empty($board_id) && !empty($reply_id) && $mysql_mode === "reply_delete") {

    // 投稿画面の子削除画面へ
    if($security_auth === true) {
        include("./mysql_functions.php");

    // まずはパスワード入力画面へ
    } else {
        include("./password.php");
    }
}


?>

