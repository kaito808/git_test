<!-- ここから投稿表示 処理 -->
<hr />
<?php
    // データベースに接続する
    $link = mysqli_connect('db', 'root', 'root', 'boards_lesson');

    // SELECTクエリ（返信投稿もjoinで一発で取ってくる）

    /**
     * 解説
     * JOINを使えば一つのクエリだけで表示が可能
     * 普段 * (=全て） で省略している取得カラムの部分は
     * idやname等同じ名前が被ってしまうので AS（エイリアス=別名）を使って別個として扱うように書く
     */

    // $sqlが作られてない場合 ※search.phpからの遷移対策
    if(empty($sql)) {

        // デフォルトのクエリを入れておく
        $sql = "
    
        SELECT
                
            boards.id AS b_id,
            boards.name AS b_name,
            boards.subject AS b_subject,
            boards.message AS b_message,
            boards.image_path AS b_image_path,
            boards.email AS b_email,
            boards.url AS b_url,
            boards.text_color AS b_text_color,
            boards.delete_key AS b_delete_key,
            boards.created_at AS b_created_at,
    
            replies.id AS r_id,
            replies.name AS r_name,
            replies.subject AS r_subject,
            replies.message AS r_message,
            replies.image_path AS r_image_path,
            replies.email AS r_email,
            replies.url AS r_url,
            replies.text_color AS r_text_color,
            replies.delete_key AS r_delete_key,
            replies.created_at AS r_created_at
    
        FROM boards
        
        LEFT JOIN replies
            ON boards.id = replies.board_id
        
        ";
    }
  

    // クエリを流して結果をresultsに入れとく
    $results = mysqli_query($link,$sql);


    /**
     * 解説
     * JOINを使えば一つのクエリだけで表示が可能だが、
     * 1つの親投稿に複数の子投稿がある場合があるので（＝親投稿がだぶってloopするので）
     * その場合を考慮してif文を書きながら一度でwhileする
     */


    // ループで回した親投稿主キーを覚えておく為の変数
    $board_id = "";

    // 取得した結果を一列ずつ取り出し$boardに入れる
    while ($board = mysqli_fetch_assoc($results)) {

        // 初回もしくは、前回表示した親投稿主キーと今の親投稿主キーが異なる場合
        if( empty($board_id) || $board_id !== $board["b_id"]) {

            // 初回じゃない場合は前回分のテーブルを閉めておく
            if( !empty($board_id) ) {
?>
                <tr>
                    <td class="button" colspan="2">
                        <form action="./edit_or_delete.php" method="POST">
                            <input type="hidden" name="board_id" value="<?php echo $board_id; ?>">
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

        <!-- 新しい親投稿の表示を開始 -->
        <table style="margin-bottom:20px; border:1px solid #ccc; padding:10 20px;">  
            <tr>
                <td>
                    名前
                </td>
                <td><?php echo $board["b_name"]; ?> さん</td>
            </tr>
            <tr>
                <td>
                    件名
                </td>
                <td><?php echo $board["b_subject"]; ?></td>
            </tr>
            <tr>
                <td>
                    メッセージ
                </td>
                <td style="color:<?php echo $board["b_text_color"]; ?>">
                    <?php echo $board["b_message"]; ?>
                </td>
            </tr>
            <?php
                // もし画像のアップロードがあれば
                if(!empty($board["b_image_path"])) {
            ?>
            <tr>
                <td>画像</td>
                <td>
                    <img src="./image/<?php echo $board["b_image_path"]; ?>">
                </td>
            </tr>
            <?php
                }
            ?>
            <tr>
                <td>メールアドレス</td>
                <td><?php echo $board["b_email"]; ?></td>
            </tr>
            <tr>
                <td>URL</td>
                <td>
                    <a href="<?php echo $board["b_url"]; ?>">
                        <?php echo $board["b_url"]; ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-top:1px solid #000; text-align:right;">
                    <?php echo date("Y年m月d日 H時i分s秒", strtotime($board["b_created_at"])); ?>
                </td>
            </tr>
        
        <?php

        } // 新規の親投稿表示終わり

        // 子投稿が存在する場合
        if( !empty($board["r_id"])) {

        ?>
            <!-- ここから返信表示 -->
            <tr style="background-color:#eee;">
                <td colspan="2"><?php echo $board["r_name"]; ?> さんの返信</td>
            </tr>
            <tr style="background-color:#eee;">
                <td>
                    件名
                </td>
                <td><?php echo $board["r_subject"]; ?></td>
            </tr>
            <tr style="background-color:#eee;">
                <td>
                    メッセージ
                </td>
                <td style="color:<?php echo $board["r_text_color"]; ?>">
                    <?php echo $board["r_name"]; ?>
                </td>
            </tr>
            <?php
                // もし画像のアップロードがあれば
                if(!empty($board["r_image_path"])) {
            ?>
            <tr style="background-color:#eee;">
                <td>画像</td>
                <td>
                    <img src="./image/<?php echo $board["r_image_path"]; ?>">
                </td>
            </tr>
            <?php
                }
            ?>
            <tr style="background-color:#eee;">
                <td>メールアドレス</td>
                <td><?php echo $board["r_email"]; ?></td>
            </tr>
            <tr style="background-color:#eee;">
                <td>URL</td>
                <td>
                    <a href="<?php echo $board["r_url"]; ?>">
                        <?php echo $board["r_url"]; ?>
                    </a>
                </td>
            </tr>
            <tr style="background-color:#eee;">
                <td>
                    書き込み時間
                </td>
                <td><?php echo date("Y年m月d日 H時i分s秒", strtotime($board["r_created_at"])); ?></td>
            </tr>
            <tr>
                <td class="button" colspan="2" align="right">
                    <form action="./edit_or_delete.php" method="POST">
                        <input type="hidden" name="board_id" value="<?php echo $board["b_id"]; ?>">
                        <input type="hidden" name="reply_id" value="<?php echo $reply["r_id"]; ?>">
                        <input name="reply_update" type="submit" value="編集">
                        <input name="reply_delete" type="submit" value="削除">
                    </form>
                </td>
            </tr>
    <?php
        } // 子投稿表示終わり

        // 今回回した親投稿IDを覚えておく
        $board_id = $board["b_id"];
    }

        // whileループ抜けた後もテーブル閉めておく
    ?>
            <tr>
                <td class="button" colspan="2">
                    <form action="./edit_or_delete.php" method="POST">
                        <input type="hidden" name="board_id" value="<?php echo $board["b_id"]; ?>">
                        <input name="board_update" type="submit" value="編集">
                        <input name="reply_insert" type="submit" value="返信">
                        <input name="board_delete" type="submit" value="削除">
                    </form>
                </td>
            </tr>
        </table>