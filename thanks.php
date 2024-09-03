<html>
    <head>
        <title>東ありがとうございました</title>
    </head>
    <body>
    <?php

        // 使う変数の初期化 ※undefined対策
        $name;
        $subject;
        $mes;
        $email;
        $url;
        $image;
        $color;
        $edit_key;
        $preview;

        $board_id;
        $reply_id;
        

        // 存在する場合は代入しておく
        if(!empty($_POST["name"])) {
            $name = $_POST["name"];
        }
        if(!empty($_POST["subject"])) {
            $subject = $_POST["subject"];
        }
        if(!empty($_POST["message"])) {
            $mes = $_POST["message"];
        }
        if(!empty($_POST["email"])) {
            $email = $_POST["email"];
        }
        if(!empty($_POST["url"])) {
            $url = $_POST["url"];
        }
        if(!empty($_POST["color"])) {
            $color = $_POST["color"];
        }
        if(!empty($_POST["edit_key"])) {
            $edit_key = $_POST["edit_key"];
        }
        if(!empty($_POST["preview"])) {
            $preview = $_POST["preview"];
        }
        if(!empty($_POST["board_id"])) {
            $board_id = $_POST["board_id"];
        }
        if(!empty($_POST["reply_id"])) {
            $reply_id = $_POST["reply_id"];
        }

        // 画像アップロード処理 ※プレビューからいきなり来る場合もある為必要
        if(!empty($_FILES["upload_image"]["tmp_name"])) {

            $filename = $_FILES["upload_image"]["name"];
            $uploaded_path = "./image/" . $filename;
            move_uploaded_file($_FILES["upload_image"]["tmp_name"],$uploaded_path);

            // insertに使用する文字（ファイルパス）を代入しておく
            $image = $filename;

        }
    ?>

    <?php

        /**
         * 以上、準備完了。
         * データベースへの接続処理は全てmysql_functions.phpに任せる形で書いたので
         * includeしてmysql_functions.phpのソースを使い回す = オブジェクト指向
         */

        // MySQLデータベースへの接続と書き込み（INSERT or UPDATE or DELETE）の処理を呼び出す
        include("./mysql_functions.php");

        // ↑ 全てmysql_functionsに集約しておきincludeして使い回す。（＝楽。）
        // ※ 一度書いたmysql_functions.phpのソースを使いまわし効率的に仕事を進める。（＝タイピング数少ない事は絶対正義と心得る）
     
    ?>

        <hr />

        <p>
            データベースの更新を受け付けました。
            <br />
            <a href="./top.php">トップ</a>へ戻って確認しましょう！
        </p>
    </body>
</html>