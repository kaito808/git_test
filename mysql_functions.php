<?php

/**
 * 解説：
 * 
 * 幾つものパターンのMySQLを毎回書くの大変だったと思いますが、楽する為に！
 * 
 *  *全身全霊を込めて１回”だけ”書き、【それを何度も使い】”楽しよう” *　という方向性が存在します
 * 【過去に払った労働時間】を使い回す事で【今の労働時間】を短縮する。
 *  こういった考え方を　【オブジェクト指向】　と言います。
*/

/**
 * 解説2：
 * 
 *  労働時間を集約した便利機能（=オブジェクト）を、己の都合で勝手に作ってくと、
 * 「ぼくの考えた、さいきょうのピー・エイチ・ピーファイル。ばーじょん自分」が各人で出来上がっていきます。
 * 
 * これを各々が自己の都合で続けると、システムがサグラダファミリア化（スパゲッティsource化）します。 ※1
 * その事を意識し、周り（＝仕様を理解している信頼できる方）と相談する事も大事です。最悪書き損になるので書く前の確認マジ大事です。
 *
 * 
 * 補足：
 * 
 * 作る際は 以下↓ のように他のエンジニアに使い方を教えてあげてると好印象
 * 2024/04/28 y.kubo
 * 投稿や編集のリクエスト通信(POSTに限る）が飛んできている状態でincludeしてくれれば使えます
 * 必須のPOST内容は仕様書ver4.3のp12[必須パラメータ要件]に準じています。
 * 
 *  
 * 補足の補足：
 * こういったコメントの書き方は"@paramを使え"など現場でフォーマットが決まってるので仕様書・上席に準じましょう
 * それも無い場合はその現場の先人のソースを見て真似しておきましょう。（何か言われたら「先人がそうだったので。」という最強の返しの用意にもなります）
 * 
 * ※1 = サグラダファミリア化した金融システムが、国内（！）で実際に存在します。調べてみよう！
 * 
 */



// データベースに接続する
$link = mysqli_connect('db', 'root', 'root', 'boards_lesson');

// エラー処理
if(!$link) {
    echo "<h1>ただいま技術的な問題が発生しています</h1>";
    echo "<a href='./top.php'>TOP</a>";
    exit;
}


/**
 * 以下、どのボタンから来たかをキャッチする処理
 * 
*/

//  * ※ input type='submit' のHTMLタグにname属性をつけておく事で"どのボタンから来たか"をキャッチできる
//  * 
//  * top.phpの以下↓のように。
//  * <input name="board_insert" type="submit" value="投稿">
//  * ↑この場合、$_POSTをvar_dump()とexitすれば分かるが配列にboard_insertが存在する。
//  * その為、「ここからきた。」と確信の上でキャッチできる（他のボタンでは存在しない事もvar_dumpで確認してみれば分かる）


/**
 * あらためて以下、どのボタンから来たかをキャッチする処理
 * 
*/

// 親投稿の新規投稿の場合
if(!empty($_POST["board_insert"])) {
    $mysql_mode = "board_insert";
}
// 子投稿の新規投稿の場合
if(!empty($_POST["reply_insert"])) {
    $mysql_mode = "board_insert";
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
if(!empty($_POST["repley_delete"])) {
    $mysql_mode = "repley_delete";
}

/** ↑のようにしておき、type='submit'のname属性次第で勝手に動くようにしてある */


/**
 * 書き込み時間 = データベースのカラム"created_at"の時間を定義
 */

// 書き込み時間の取得
date_default_timezone_set('Asia/Tokyo');
$created_at = date("Y-m-d H:i:s");


/**
 * ここからMySQLクエリーの生成処理
 * 
*/


// 親投稿 新規追加
if($mysql_mode === "board_insert") {

    $sql = "INSERT INTO boards (name ,subject ,message ,image_path ,email ,url ,text_color ,delete_key ,created_at)
            VALUES ('{$name}','{$subject}','{$mes}','{$image}','{$email}','{$url}','{$color}','{$edit_key}','{$created_at}');"; 

}
// 親投稿 更新処理
if(!empty($board_id) && $mysql_mode === "board_update") {

    $sql = "UPDATE boards SET
            name = '{$name}' , subject = '{$subject}' , message = '{$message}' , email = '{$email}' , url = '{$url}',
            image_path = '{$image}' , text_color = '{$color}' , delete_key = '{$edit_key}'
            WHERE id = '{$board_id}';";

}

// 子投稿 新規追加
if(!empty($board_id) && $mysql_mode === "reply_insert") {

    $sql = "INSERT INTO replies (board_id, name ,subject ,message ,image_path ,email ,url ,text_color ,delete_key ,created_at)
            VALUES ('{$board_id}', '{$name}' ,'{$subject}' ,'{$mes}' ,'{$image}' ,'{$email}' ,'{$url}' ,'{$color}' ,'{$edit_key}' ,'{$created_at}');"; 

}
// 子投稿 更新処理
if(!empty($board_id) && !empty($reply_id) && $mysql_mode === "reply_update") {

    $sql = "UPDATE replies SET
            board_id = '{$board_id}',
            name = '{$name}' , subject = '{$subject}' , message = '{$message}' , email = '{$email}' , url = '{$url}',
            image_path = '{$image}' , text_color = '{$color}' , delete_key = '{$edit_key}'
            WHERE id = '{$reply_id}';";

}


/**
 * 
 * ここから以下、削除処理
 * 
 */

// 子投稿の削除クエリーがあるかもしれないと思い出し用意
$child_delete_sql = "";

// 親投稿 削除処理
if(!empty($board_id) && $mysql_mode === "board_delete") {

    // 親にぶらさがってる子投稿の削除クエリー生成
    $child_delete_sql = "DELETE FROM replies WHERE board_id ='{$board_id}';";

    // 子の削除はまとめられないのでこの段階で発行
    mysqli_query($link, $child_delete_sql);

    // 改めて親投稿の削除クエリー生成
    $sql = "DELETE FROM boards WHERE id ='{$board_id}';";

}

// 子投稿 削除処理
if(!empty($reply_id) && $mysql_mode === "reply_delete") {

    // 子投稿の削除クエリー生成
    $sql = "DELETE FROM replies WHERE id ='{$reply_id}';";

}


// 上で作ったMySQLクエリーが存在すれば ※2
if(!empty($sql)) {

    // 上で作ったMySQLクエリーを発行
    $result = mysqli_query($link, $sql);

    // 念の為エラー処理
    if (!$result) {
        echo "<h1>現在技術的な問題が発生しています。投稿や削除に失敗した可能性があります</h1>";
        echo "<a href='./top.php'>TOP</a>";
        exit;
    }

}

// var_dump($mysql_mode);
// var_dump($sql);
// exit;

?>


<h1>投稿完了</h1>
<hr />

<a href="./top.php">TOP</a> | <a href="./search.php">ワード検索</a>
<hr />

<table>
    <tr>
        <th>名前</th>
        <td><?php echo $name; ?></td>
    </tr>
    <tr>
        <th>件名</th>
        <td><?php echo $subject; ?></td>
    </tr>
    <tr>
        <th>文字色</th>
        <td>
            <p style="color:<?php echo $color; ?>;">■</p>
        </td>
    </tr>
    <tr>
        <th>メッセージ</th>
        <td style="color:<?php echo $color; ?>;">
            <?php echo nl2br($mes); ?>
        </td>
    </tr>
    <tr>
        <th>画像</th>
        <td>
            <?php echo $image; ?>
        </td>
    </tr>
    <tr>
        <th>メールアドレス</th>
        <td><?php echo $email; ?></td>
    </tr>
    <tr>
        <th>URL</th>
        <td><?php echo $url; ?></td>
    </tr>
    <tr>
        <th>
            編集/削除キー<br />

            <span class="warning">
                ↑passなので本来出さない。<br />
                講座用にわざと出してるだけ
            </span>

            <!-- START 解説：CSS（デザイン）の定義は<style>～</style>に書く or 別にまとめる -->
            <style>
                

                <?php //解説: <span class="warning">～</span>に対してのデザイン(css)の定義 はじまり ?>
                .warning {

                    <?php // 解説: 文字色を#8b0000にして。（HTML色見本で調べてみて） ?>
                    color:#8b0000;

                    <?php // 解説: 太文字にして。 ?>
                    font-weight:bold;

                    <?php // 解説: 文字のサイズを10pxにして。 ?>
                    font-size:10px;

                <?php //解説: 以上、<span class="warning">～</span>に対してのデザイン(css)定義 終わり ?>
                }

            </style>
            <!-- END 解説：CSS（デザイン）の定義は<style>～</style>に書く or 別にまとめる -->

        
        </th>
        <td><?php echo $edit_key; ?></td>
    </tr>
</table>




<?php /** 以下、呼び出し元の処理に戻る（ <?php が続くイメージ */ ?>