<html>
    <head>
        <title>確認画面 修正済みです</title>
    </head>
    <body>
        <?php

            // var_dump($_POST);
            // exit;



            // 使う変数の初期化 ※undefined対策

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


            // 今が投稿なのか編集なのかルートを知る為の変数 ※mysql_functions.phpで使用している
            $mysql_mode;

            // バグが怖いのでデフォルトは親投稿の新規投稿にしておく ※ 37行目を省いてこれだけ書いておけば本来OK
            $mysql_mode = "board_insert";


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

        ?>

        <?php

            // 画像アップロード処理
            if(!empty($_FILES["upload_image"]["tmp_name"])) {
                $filename = $_FILES["upload_image"]["name"];
                $uploaded_path = "./image/" . $filename;
                move_uploaded_file($_FILES["upload_image"]["tmp_name"],$uploaded_path);

                // insertに使用する文字（ファイルパス）を代入しておく
                $image = $filename;

            }



            // 準備完了 画面表示(viewって言う）の処理を書く
            // 都合上コメントはhtml側に書くけど実務ではphpブロックで//の方が好ましい。 ※ htmlのコメントはブラウザ上で右クリック→ソース表示で表に出るからダメ

        ?>
        <!-- プレビュー画面ver -->
        <?php if($preview === "1") { ?>

            <h2>投稿確認</h2>
        
            <form action="./thanks.php" method="POST">

                <!-- hiddenでthanksに渡す -->
                <input type="hidden" name="name" value="<?php echo $name; ?>">
                <input type="hidden" name="subject" value="<?php echo $subject; ?>">
                <input type="hidden" name="message" value="<?php echo $mes; ?>">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <input type="hidden" name="url" value="<?php echo $url; ?>">
                <input type="hidden" name="color" value="<?php echo $color; ?>">
                <input type="hidden" name="edit_key" value="<?php echo $edit_key; ?>">
                <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
                <input type="hidden" name="reply_id" value="<?php echo $reply_id; ?>">
                <input type="hidden" name="image" value="<?php echo $image; ?>">

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

                    <?php if(!empty($image)) { ?>
                    <tr>
                        <th>画像</th>
                        <td>

                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th>メールアドレス</th>
                        <td><?php echo $email; ?></td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td><?php echo $url; ?></td>
                    </tr>
                    <tr>
                        <th>編集/削除キー</th>
                        <td><?php echo $delete_key; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center;">

                            <!-- 戻るボタンで戻った時、入力した値を復元する為のコードはonclick="history.back();"だけで実装できる -->
                            <input type="button" value="戻る" onclick="history.back();">
                            <input type="submit" name="board_insert" value="投稿する">
                        </td>
                    </tr>
                </table>
            </form>

        <?php } ?>



        <!-- プレビューしないver -->
        <?php
            if(empty($preview)) {

                include("./mysql_functions.php");
                
            }
        ?>

    </body>
</html>