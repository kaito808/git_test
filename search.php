<html>
    <head>
        <!-- 倉林編集しました -->
        <title>ワード検索編集</title>
    </head>
    <body>

        <?php

            /** 初期処理 */

            // 検索語句
            $search_word;

            // AND or OR
            $search_type;

            // ラヂオボタンのchecked判定
            $checked["or"] = "";
            $checked["and"] = "";


            // 語句があれば代入
            if(!empty($_POST["search_word"])) {
                $search_word = $_POST["search_word"];
            }

            // 検索タイプがあれば代入
            if(!empty($_POST["search_type"])) {
                $search_type = $_POST["search_type"];

                // ANDとORのどちらかの配列行に文字列checkedを設定
                $checked[$search_type] = "checked";
            }


            // checkedがない場合（ = 初期表示の場合）
            if( empty($checked["or"]) && empty($checked["and"]) ) {

                // or側をcheckedしとく
                $checked["or"] = "checked";

            }



            /** 検索処理 開始 */


            // SQL文用の変数を用意＆最低限のクエリーを設定しておく（パラメータが無い場合も全検索として動かせるように）
            $sql = "SELECT
            
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


            /**
             * 検索語句が存在する場合の処理
             */
            if(!empty($search_word)) {

                // 半角スペースか全角スペースで配列にする ※正規表現を使わずexplodeとかでもOK
                $keywords = preg_split("/ |　/", $search_word);


                // 語句が１つだけの場合の処理 ※ズルです。
                if( empty( $keywords[1]) ) {
                    // 語句2を語句1と同一にしてしまう ※ズルです
                    $keywords[1] = $keywords[0];
                }


                /**
                 * ここから検索に使用するクエリの生成処理
                 */


                 // OR検索パターンの場合
                if(!empty($search_type) && $search_type === "or") {

                    /**
                     * 解説：
                     * OR検索は全てORで繋げればOK
                     */

                    // OR検索のWHERE条件を$sqlに追加する ( $sql .= ) 
                    $sql .= "
                
                         WHERE

                        boards.message LIKE '%{$keywords[0]}%' OR
                        boards.message LIKE '%{$keywords[1]}%' OR
                        replies.message LIKE '%{$keywords[0]}%' OR
                        replies.message LIKE '%{$keywords[1]}%' OR

                        boards.subject LIKE '%{$keywords[0]}%' OR
                        boards.subject LIKE '%{$keywords[1]}%' OR
                        replies.subject LIKE '%{$keywords[0]}%' OR
                        replies.subject LIKE '%{$keywords[1]}%' OR

                        boards.name LIKE '%{$keywords[0]}%' OR
                        boards.name LIKE '%{$keywords[1]}%' OR
                        replies.name LIKE '%{$keywords[0]}%' OR
                        replies.name LIKE '%{$keywords[1]}%';
                        
                    ";



                
                 
                // AND検索パターンの場合
                } else if(!empty($search_type) && $search_type === "and") {

                    /**
                     * 解説：
                     * AND検索は語句1と語句2どちらも含む必要があるのでANDでつなげる
                     * ただし全てをANDで繋げると
                     * 検索対象全てが条件を満たさないといけなくなるので、
                     * ~~~~~~~~~~~~~
                     * 
                     * 検索対象 = ANDで繋げる
                     * 検索対象同士 = ORで繋げる
                     * ...というクエリになります（＝上記パターンじゃない仕様もあるので何でそうなるか？は考える必要有り）
                     */


                    // WHERE条件をAND検索で追加する
                    $sql .= "
                
                        WHERE

                        (
                            boards.message LIKE '%{$keywords[0]}%'
                            AND
                            boards.message LIKE '%{$keywords[1]}%'
                        )
                        OR
                        (
                            replies.message LIKE '%{$keywords[0]}%'
                            AND
                            replies.message LIKE '%{$keywords[1]}%'
                        )

                        OR

                        (
                            boards.subject LIKE '%{$keywords[0]}%'
                            AND
                            boards.subject LIKE '%{$keywords[1]}%'
                        )
                        OR
                        (
                            replies.subject LIKE '%{$keywords[0]}%'
                            AND
                            replies.subject LIKE '%{$keywords[1]}%'
                        )

                        OR

                        (
                            boards.name LIKE '%{$keywords[0]}%'
                            AND
                            boards.name LIKE '%{$keywords[1]}%'
                        )
                        OR
                        (
                            replies.name LIKE '%{$keywords[0]}%'
                            AND
                            replies.name LIKE '%{$keywords[1]}%'
                        );
            
                    ";
                }
            }

            // 以上、初期処理完了


            // ここから画面表示（viewっていう）処理開始
        ?>

        <h1>ワード検索</h1>
        <hr />
        <a href="./top.php">TOP</a> | <a href="./search.php">ワード検索</a>
        <hr />
        <h2>ワード検索</h2>
        <br />
        <form action="./search.php" method="POST">
            <input type="text" name="search_word" value="<?php echo $search_word; ?>" />
            <label>
                <input type="radio" name="search_type" value="or" <?php echo $checked["or"]; ?>>OR
            </label>
            <label>
                <input type="radio" name="search_type" value="and" <?php echo $checked["and"]; ?>>AND
            </label>

            <input type="submit" value="検索">
        </form>


        <hr />

        <?php

            // 出来上がったMySQLクエリのカンニング用（本来不要）
            echo "発行したSQL:<br />{$sql}<hr />";

            // ※ 検索の仕様上JOIN必須なのでselect_join_ver使ってます（単発ずつ取る方法でもOK） 
            // DB接続や作ったクエリーの発行はselect_join_verの中でやってます
            include("./select_join_ver.php");

        ?>
    </body>
</html>