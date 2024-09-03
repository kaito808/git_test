<html>
    <head>
        <title>---トップ-修正しました</title>
    </head>
    <body>

        <h1>課題掲示板 TOP</h1>
        <hr />
        <a href="./top.php">TOP</a> | <a href="./search.php">ワード検索</a>
        <hr />
        <h2>投稿フォーム</h2>

        <?php

        // 使う変数の初期化
        $name = "";
        $subject = "";
        $mes = "";
        $email = "";
        $url = "";
        $color = "";
        $edit_key = "";
        $preview = "";

        $board_id = "";
        $reply_id = "";

        ?>

        <form action="./confirm.php" method="POST">
            <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
            <input type="hidden" name="reply_id" value="<?php echo $reply_id; ?>">

            <table>
                <tr>
                    <td>
                        <label for="name">名前</label>
                    </td>
                    <td>
                        <input id="name" type="text" name="name" size="30" required/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="subject">件名</label>
                    </td>
                    <td>
                        <input id="subject" type="text" name="subject" size="30" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="message">メッセージ</label>
                    </td>
                    <td>
                        <textarea id="message" name="message" cols="30" rows="5" required></textarea>
                    </td>
                </tr>
                <tr>
                    <td>画像</td>
                    <td><input type="file" name="upload_image"></td>
                </tr>
                <tr>
                    <td><label for="email">メールアドレス</label></td>
                    <td><input id="email" type="mail" name="email" size="30"></td>
                </tr>
                <tr>
                    <td><label for="user_url">URL</label></td>
                    <td><input id="user_url" type="url" name="url" placeholder="http://" size="30"></td>
                </tr>

                <?php

                    // 以下、投稿フォームの”文字”が処理過多（面倒くさい）なので楽する為の準備


                    // 文字色の配列を定義 ※HTML色見本で調べて適当にチョイス
                    $colors = array(
                        "#CC0000",
                        "#008000",
                        "#0000FF",
                        "#CC00CC",
                        "#FF00CC",
                        "#FF9933",
                        "#000099",
                        "#666666"
                    );

                    // デフォルトチェック処理（編集を押した場合などを考慮）
                    $checked = array();

                    // デフォルトチェック処理が走らなかった事を知る為に用意 初期値はfalse
                    $default_checked = false;
                    
                    foreach($colors as $key => $c) {

                        if($c === $color) {

                            $checked[$key] = "checked";

                            // trueにしてデフォのチェック処理が走ったフラグを立てとく
                            $default_checked = true;

                        } else {
                            $checked[$key] = "";
                        }

                    }

                    // デフォのチェック処理が走ってない場合をifでキャッチ
                    if(!$default_checked) {

                        // 先頭のradioにcheckedさせとく
                        $checked[0] = "checked";
                    }

                    // 楽する為の準備完了 以上の定義を使って文字色も簡潔に書く
                ?>
                <tr>
                    <td>文字色</td>
                    <td>
                        <?php foreach($colors as $key => $c) { ?>
                            <label>
                                <input type="radio" name="color" value="<?php echo $c; ?>" <?php echo $checked[$key]; ?> />
                                <small><span class="text-color" style="color: <?php echo $c; ?>;">■</span></small>
                            </label>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="pw">編集/削除キー</label></td>
                    <td>
                        <input id="pw" type="password" name="edit_key" minlength="4" maxlength="8" pattern="[a-zA-Z0-9]+" required>
                        <small>（半角英数字のみで４～８文字）</small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label><input type="checkbox" name="preview" value="1" />プレビューする （投稿前に、内容をプレビューして確認できます）</label>
                    </td>
                </tr>
                <tr>
                    <td class="button" colspan="2">
                        <input name="board_insert" type="submit" value="投稿">
                        <input type="reset" value="リセット">
                    </td>
                </tr>
            </table>
        </form>

        <!-- ここから投稿表示 処理 -->
        <hr />
        <?php

            // ※ JOINを使えば一回のクエリーで済むのでselect_join_ver.phpを読んで参考にしてみて 
            // include("./select_join_ver.php");
            // echo "<hr />ここまでjoin_ver<br />ここから単発ver<hr />";


            // データベースに接続する ※ 今は以下ソースのやり方でOK
            $link = mysqli_connect('db', 'root', 'root', 'boards_lesson');

            // SELECTクエリ
            $sql = "SELECT * FROM boards ORDER BY created_at DESC;";
            // クエリを流して結果をresultsに入れとく
            $results = mysqli_query($link,$sql);

            // 取得した結果を一列ずつ取り出し$boardに入れる
            while ($board = mysqli_fetch_assoc($results)) {
        ?>

            <!-- 最低限のtableのデザイン定義をstyle=""の中に書いてる。CSSに興味があれば調べてみて！SE実務では（今のとこ）不要です -->
            <table style="margin-bottom:20px; border:1px solid #ccc; padding:10 20px;">
                <tr>
                    <td>
                        名前
                    </td>
                    <td><?php echo $board["name"]; ?> さん</td>
                </tr>
                <tr>
                    <td>
                        件名
                    </td>
                    <td><?php echo $board["subject"]; ?></td>
                </tr>
                <tr>
                    <td>
                        メッセージ
                    </td>
                    <td style="color:<?php echo $board["text_color"]; ?>">
                        <?php echo $board["message"]; ?>
                    </td>
                </tr>
                <?php
                    // もし画像のアップロードがあれば
                    if(!empty($board["image_path"])) {
                ?>
                <tr>
                    <td>画像</td>
                    <td>
                        <img src="./image/<?php echo $board["image_path"]; ?>">
                    </td>
                </tr>
                <?php
                    }
                ?>
                <tr>
                    <td>メールアドレス</td>
                    <td><?php echo $board["email"]; ?></td>
                </tr>
                <tr>
                    <td>URL</td>
                    <td>
                        <a href="<?php echo $board["url"]; ?>">
                            <?php echo $board["url"]; ?>
                        </a>
                    </td>
                </tr>

                <!-- ここから返信表示 -->
                <?php

                    // 子取得用のSELECTクエリ
                    $re_sql = "SELECT * FROM replies WHERE board_id = '{$board["id"]}' ORDER BY created_at DESC;";
                    // クエリを流して結果をre_resultsに入れとく
                    $re_results = mysqli_query($link,$re_sql);

                    // 取得した結果を一列ずつ取り出し$replyに入れる
                    while ($reply = mysqli_fetch_assoc($re_results)) {
                ?>

                        <tr style="background-color:#eee;">
                            <td colspan="2"><?php echo $reply["name"]; ?> さんの返信</td>
                        </tr>
                        <tr style="background-color:#eee;">
                            <td>
                                件名
                            </td>
                            <td><?php echo $reply["subject"]; ?></td>
                        </tr>
                        <tr style="background-color:#eee;">
                            <td>
                                メッセージ
                            </td>
                            <td style="color:<?php echo $reply["text_color"]; ?>">
                                <?php echo $reply["name"]; ?>
                            </td>
                        </tr>
                        <?php
                            // もし画像のアップロードがあれば
                            if(!empty($reply["image_path"])) {
                        ?>
                        <tr style="background-color:#eee;">
                            <td>画像</td>
                            <td>
                                <img src="./image/<?php echo $reply["image_path"]; ?>">
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                        <tr style="background-color:#eee;">
                            <td>メールアドレス</td>
                            <td><?php echo $reply["name"]; ?></td>
                        </tr>
                        <tr style="background-color:#eee;">
                            <td>URL</td>
                            <td>
                                <a href="<?php echo $reply["name"]; ?>">
                                    <?php echo $reply["name"]; ?>
                                </a>
                            </td>
                        </tr>
                        <tr style="background-color:#eee;">
                            <td>
                                書き込み時間
                            </td>
                            <td><?php echo date("Y年m月d日 H時i分s秒", strtotime($reply["created_at"])); ?></td>
                        </tr>
                        <tr>
                            <td class="button" colspan="2" align="right">
                                <form action="./edit_or_delete.php" method="POST">
                                    <input type="hidden" name="board_id" value="<?php echo $board["id"]; ?>">
                                    <input type="hidden" name="reply_id" value="<?php echo $reply["id"]; ?>">
                                    <input name="reply_update" type="submit" value="編集">
                                    <input name="reply_delete" type="submit" value="削除">
                                </form>
                            </td>
                        </tr>
                <?php
                    }
                ?>
                <tr>
                    <td colspan="2" style="border-top:1px solid #000; text-align:right;">
                        <?php echo date("Y年m月d日 H時i分s秒", strtotime($board["created_at"])); ?>
                    </td>
                </tr>
                <tr>
                    <td class="button" colspan="2">
                        <form action="./edit_or_delete.php" method="POST">
                            <input type="hidden" name="board_id" value="<?php echo $board["id"]; ?>">
                            <input name="board_update" type="submit" value="編集">
                            <input name="reply_insert" type="submit" value="返信">
                            <input name="board_delete" type="submit" value="削除">
                        </form>
                    </td>
                </tr>
            </table>
        <?php
            }
        ?>

    </body>
</html>