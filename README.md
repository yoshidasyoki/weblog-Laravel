# weblogアプリ（Laravel版）

## 概要
Laravelの勉強用に作成したブログアプリです。  
認証機能とCRUD操作を実装し、Featureテストで簡単な動作確認を行いました。  
前回作成したフルスクラッチでのブログアプリ開発と比較を行うことでLaravelの内部動作をイメージしながら学習を進め、フレームワークをブラックボックス化しないよう意識しました。

フルスクラッチでの開発はこちら  
https://github.com/yoshidasyoki/weblog

また、以下の記事で今回作成したLaravel版weblogアプリの詳細を記述しています。  
https://qiita.com/yoshikingcorporation/items/19cf201c34c5223802e6

## 使用技術
- PHP 8.4
- Laravel 12.x
- MySQL 8.0
- Tailwind CSS v4.1
- Docker

## 機能一覧
- ユーザーアカウントの作成 / ログイン機能
- 記事の投稿 / 編集 / 削除
- バリデーション
- ゴミ箱機能（論理削除）
- 投稿者本人以外の操作制限（アクセス権管理）

## ER図

<div align="center">
    <img width="700" height="500" alt="image" src="https://github.com/user-attachments/assets/84ba333e-4350-45ae-9ac6-b16cbbf0163a" />
</div>

ユーザーの登録情報を管理する`Users`テーブルと投稿記事を管理する`Articles`テーブルは `user_id` を外部キーとしてリレーションを持たせています。  
今回は実装していないのでほとんど関係ありませんが、ユーザーアカウント削除時に投稿記事も一緒に削除されるように外部キー制約は`CASCADE`としています。

## 工夫したポイント
- `middleware`による認証処理の実装
- `Policy`による認可処理で投稿者本人以外の記事の投稿 / 編集 / 削除を制限
- バリデーション処理を`Request`クラスで定義し責務を委譲
- `Eagerローディング`を用いてN+1問題を回避することでパフォーマンスを向上
- `Featureテスト`を実施し認証・認可とCRUD操作の動作確認実施

## 今後に向けて
より実務に必要なスキルを身に着けるため、以下の点を今後学習していきたいです。
- トランザクションの実装
- クラウド（AWS）を用いたデプロイ
- API化

## 起動方法

> [!WARNING]
> アプリ起動には**Docker**と**Linux**環境が必要になります

### ライブラリのインストール
まずGitHubからクローンを行いローカル環境にコードを取り込み、`weblog-Laravel`ディレクトリ下へ移動します。
```bash
git clone https://github.com/yoshidasyoki/weblog-Laravel.git && cd weblog-Laravel
```

そして以下のコマンドを実行し、必要なライブラリのインストールを実行します。
```bash
docker run --rm \
  -v $(pwd):/opt \
  -w /opt \
  laravelsail/php84-composer:latest \
  composer install
```

### 環境構築
次にDockerを使用して環境構築を行います（今回は`sail`を用いて簡単に環境構築を行いました）  
まず環境変数の設定を行います。
以下のコマンドを実行して`.env`ファイルを作成します。
```bash
cp .env.example .env
```
予め`.env.example`ファイルに、動作に必要な環境変数は記述してあるのでこれで環境変数の設定は終了ですが、必要に応じて内部の環境変数は変更していただいて構いません。  

環境変数の設定が完了したら以下のコマンドを実行して環境構築を行います（初回は時間がかなりかかります）。
```bash
sail up -d
```

なお、上記コマンド実行時にはエイリアスの登録が必要となります。以下にその手順を記述しています。  

<details>
<summary>補足：エイリアスの登録方法</summary>
    まずはエイリアスを設定して簡単に`sail`コマンドが実行できるように前準備を行います。  
以下のコマンドを実行してシェルの種類を確認します。

```bash
echo $SHELL
```

出力結果に応じて以下のコマンドを実行します。なお、実行しても何も出力は表示されません。
```bash
# /bin/zshの場合
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.zshrc

$ /bin/bashの場合
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bashrc
```

そして以下のコマンドを実行して設定を反映させます。  
```bash
exec $SHELL -l
```

最後に以下コマンドで登録できているか確認します。
```bash
sail artisan -V
```

以下のような出力が出れば正常に設定できています。
```bash
Sail is not running.
You may Sail using the following commands: './vendor/bin/sail up' or './vendor/bin/sail up -d'
```
</details>

正常にコンテナが立ち上がればDocker部分の環境構築は完了です。

### マイグレーションとTailwind CSSのビルド
アプリ動作に必要なDBの作成とCSSのスタイル当てを行います。  
まずはターミナル上で以下のコマンドを入力してマイグレーションを実行します。  
```bash
sail artisan migrate
```
正常に実行されればDB構築は完了です。  

次にTailwind CSSの設定ですが、前準備として`npm`が必要となるので以下のコマンドを実行します。
```bash
sail npm install
```

> [!WARNING]
> インストールは完了したものの脆弱性に関するメッセージが表示されることがあります。  
> その場合は以下のコマンドを追加実施してください
> ```bash
> sail npm audit fix
> ```

インストールが完了したら以下のコマンドを実行してCSSが当たるようにします。
```bash
sail npm run build
```

`localhost`とURLを入力し、weblogサイトのトップページが表示されれば一連の環境構築は完了になります。

### テスト実行方法
ターミナル上で以下のコマンドを実行すると、予め動作確認用に用意しておいたFeatureテストを実行することができます。  
特に問題がなければすべてOKとなります。
```bash
sail artisan test
```

以上で起動方法の説明は終わりになります。
