<html>
    <head>
        <title>トップ</title>
    </head>
    <body>

        <h1>課題掲示板 TOP</h1>
        <hr />
        <a href="./top.php">TOP</a> | <a href="./search.php">ワード検索</a>
        <hr />
        <h2>パスワード確認,岡本編集しました。</h2>
        <form action="./edit_or_delete.php" method="POST">

            <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
            <input type="hidden" name="reply_id" value="<?php echo $reply_id; ?>">

            <table>
                <?php if(!empty($board_id)) { ?>
                    <tr>
                    <th>投稿ID</th>
                    <td>
                        <?php echo $board_id; ?>
                    </td>
                </tr>
                <?php } ?>
                <?php if(!empty($reply_id)) { ?>
                <tr>
                    <th>返信ID</th>
                    <td>
                        <?php echo $reply_id; ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <th>パスワード</th>
                    <td>
                        <input type="password" name="password" placeholder="パスワードをどうぞ">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        
                        <!-- 前述の$mysql_modeをname属性にそのまま使い、ルート分岐がズレないようにする -->
                        <input type="submit" name="<?php echo $mysql_mode; ?>" value="投稿" />

                    </td>
                </tr>
            </table>
        </form>
        <hr />

    </body>
</html>