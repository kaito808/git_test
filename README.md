# 初めてのGitHub！

このドキュメントは、初めてGitHubを使う方向けに基本的な操作の流れを説明します。  
プロジェクトを開始するための手順を、以下のステップに沿って進めてください。

---

## 1. GitHubアカウントを作成

まずはGitHubにアカウントを作成します。

1. [GitHubの公式サイト](https://github.com)にアクセスし、右上の「Sign Up」ボタンをクリック。
2. 必要な情報を入力し、アカウントを作成してください。

---

## 2. SSH接続を設定

GitHubに安全に接続するために、SSHの設定を行います。  
詳しい設定手順は、[こちらのガイド](https://www.kagoya.jp/howto/it-glossary/develop/github_ssh/)を参考にしてください。

1. SSHキーを作成します。
2. 作成したSSHキーをGitHubアカウントに登録します。

---

## 3. リポジトリをクローンし、コードを修正

リポジトリをローカルにクローンし、コードを編集します。

1. GitHubからクローンしたいリポジトリのページに移動。
2. 「Code」ボタンをクリックし、「SSH」を選択して表示されるURLをコピー。
3. 次のコマンドでリポジトリをクローン：
   ```bash
   git clone git@github.com:ユーザー名/リポジトリ名.git
   ```
4. VSCodeでクローンしたフォルダを開き、必要な修正を行います。

---

## 4. 変更をGitHubに反映する (add -> commit -> push)

修正した内容をGitHubに反映させます。以下のコマンドを順に実行してください。

1. 変更内容をステージに追加：
   ```bash
   git add .
   ```
2. 変更内容をコミット：
   ```bash
   git commit -m "修正内容を説明するメッセージ"
   ```
3. 変更をリモートリポジトリにプッシュ：
   ```bash
   git push origin ブランチ名
   ```

詳細な手順については、[こちらのガイド](https://backlog.com/ja/git-tutorial/pull-request/05/)を参考にしてください。

---

## 5. GitHubでプルリクエストを作成

最後に、GitHub上でプルリクエストを作成します。

1. GitHub上でリポジトリにアクセスし、変更をプッシュしたブランチを確認。
2. 「Pull request」タブに移動し、「New pull request」ボタンをクリック。
3. 変更内容を確認し、「Create pull request」ボタンをクリックして完了です。

---

これで、GitHubを使った基本的な流れは終了です。お疲れ様でした！質問があれば、気軽に聞いてください。