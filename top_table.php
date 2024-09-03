<html>
    <head>
        <title>トップ</title>
    </head>
    <body>

        <h1>課題掲示板 TOP</h1>
        <hr />
        <a href="./top.php">TOP</a> | <a href="./search.php">ワード検索</a>
        <hr />
        <h2>投稿フォーム</h2>
        
        <form action="./thanks.php" method="POST">

            <!-- hiddenでthanksに渡す -->
            <input type="hidden" name="name" value="<?php echo $name; ?>">
            <input type="hidden" name="subject" value="<?php echo $subject; ?>">
            <input type="hidden" name="message" value="<?php echo $mes; ?>">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="url" value="<?php echo $url; ?>">
            <input type="hidden" name="color" value="<?php echo $color; ?>">
            <input type="hidden" name="edit_key" value="<?php echo $edit_key; ?>">
            <input type="hidden" name="image" value="<?php echo $image; ?>">

            <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
            <input type="hidden" name="reply_id" value="<?php echo $reply_id; ?>">

            <table>
                <tr>
                    <td>
                        <label for="name">名前</label>
                    </td>
                    <td>
                        <input id="name" type="text" name="name" size="30" value="<?php echo $name; ?>" required/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="subject">件名</label>
                    </td>
                    <td>
                        <input id="subject" type="text" name="subject" size="30" value="<?php echo $subject; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="message">メッセージ</label>
                    </td>
                    <td>
                        <textarea id="message" name="message" cols="30" rows="5" required>
                            <?php echo $mes; ?>
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td>画像</td>
                    <td><input type="file" name="upload_image"></td>
                </tr>
                <tr>
                    <td><label for="email">メールアドレス</label></td>
                    <td><input id="email" type="mail" name="email" size="30" value="<?php echo $email; ?>" ></td>
                </tr>
                <tr>
                    <td><label for="user_url">URL</label></td>
                    <td><input id="user_url" type="url" name="url" placeholder="http://" size="30" value="<?php echo $url; ?>" ></td>
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

                            // $colorが一致した場合、そこをchecked属性にする
                            $checked[$key] = "checked";

                            // trueにしてデフォのチェック処理が走ったフラグも立てとく
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
                        <input id="pw" type="password" name="edit_key" minlength="4" maxlength="8" pattern="[a-zA-Z0-9]+" value="<?php echo $edit_key; ?>" required>
                        <small>（半角英数字のみで４～８文字）</small>
                    </td>
                </tr>
                <tr>
                    <td class="button" colspan="2">

                        <!-- 前述の$mysql_modeをname属性にそのまま使い、ルート分岐がズレないようにする -->
                        <input name="<?php echo $mysql_mode; ?>" type="submit" value="投稿">
                        <input type="reset" value="リセット">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>