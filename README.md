# 🚀 初めてのGitHub！

このドキュメントは、**GitHubを初めて使う方向け**に、基本的な使い方と操作手順を分かりやすく説明します。
以下のステップに沿って進めることで、リポジトリのクローンから修正・プルリクエストまでの一連の流れが分かります。

---

## 1. GitHubアカウントを作成する

まずはGitHubの公式サイトでアカウントを作成しましょう。

1. [GitHub](https://github.com) にアクセスし、右上の「Sign up」をクリック
2. メールアドレス、ユーザー名、パスワードなどを入力してアカウントを作成

---

## 2. SSH接続を設定する（初回のみ）

安全にGitHubと接続するため、SSHキーの設定を行います。

1. ターミナルでSSHキーを作成：

   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   ```
2. 公開鍵の内容をコピー：

   ```bash
   cat ~/.ssh/id_rsa.pub
   ```
3. GitHubの[SSH設定ページ](https://github.com/settings/keys)にアクセスし、「New SSH key」から貼り付けて登録

> 🔗 詳細な手順はこちら：[SSH接続ガイド（KAGOYA）](https://www.kagoya.jp/howto/it-glossary/develop/github_ssh/)

---

## 3. リポジトリをクローンする

GitHub上のプロジェクトを自分のPCにコピー（クローン）します。

1. クローンしたいリポジトリのページを開く
2. 「Code」ボタン → 「SSH」を選んでURLをコピー
3. ターミナルで以下を実行：

   ```bash
   git clone git@github.com:ユーザー名/リポジトリ名.git
   cd リポジトリ名
   ```

---

## 4. ブランチを作成し、作業する

他の人のコードに影響を与えず、自分の作業ブランチで変更を行います。

1. 最新の状態にしておく：

   ```bash
   git pull
   ```
2. 今のブランチを確認：

   ```bash
   git branch
   ```
3. 新しいブランチを作成して移動：

   ```bash
   git checkout -b ブランチ名
   ```
4. 再度ブランチを確認：

   ```bash
   git branch
   ```
5. VSCodeなどでフォルダを開いて修正を行う

---

## 5. 変更をGitHubに反映する（add → commit → push）

ファイルを編集したら、以下の順でGitHubにアップロードします。

1. 変更内容を確認：

   ```bash
   git status
   ```
2. 変更をステージに追加：

   ```bash
   git add .
   ```
3. 変更を保存（コミット）：

   ```bash
   git commit -m "変更内容の説明"
   ```
4. GitHubにアップロード（プッシュ）：

   ```bash
   git push origin ブランチ名
   ```

> 🔗 詳しくはこちら：[Gitチュートリアル - Backlog](https://backlog.com/ja/git-tutorial/pull-request/05/)

---

## 6. 最新のコードを取り込む（pull）

他の人がリモートリポジトリに変更を加えている場合は、自分の作業を始める前に最新の状態を取り込んでおきましょう。

```bash
git pull
```

このコマンドは以下を行います：

* GitHubから最新の変更を取得（fetch）
* それを自分のコードと合体（merge）

> 💡 **Pullを使うタイミング例**：
>
> * 作業を始める前
> * Pushする直前
> * Pull Requestを出す直前

※ コンフリクト（衝突）が起きた場合は、ファイルを手動で修正し、再度 `git add` → `git commit` してください。

---

## 7. GitHubでプルリクエストを作成する

最後に、GitHub上でPull Request（PR）を作成して、変更を共有・レビューしてもらいましょう。

1. GitHub上で対象のリポジトリを開く
2. 「Pull requests」タブ → 「New pull request」ボタンをクリック
3. 作業ブランチを選び、変更内容を確認
4. 「Create pull request」ボタンで作成完了！

---

## ✅ お疲れさまでした！

これでGitHubの基本操作はバッチリです。
困ったときは `git status` で状況を確認してみましょう。

不明点があれば、気軽に質問してください👍
