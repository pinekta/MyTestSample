# 本システム について

本システムが利用している要素技術は以下のとおりです。

## サーバーサイド

### 言語
PHP5.6

### フレームワーク
Symfony2.7

### O/R Mapper
Doctrine

### テンプレートエンジン
Twig

### DB
sqlite3

## フロントエンド

### CSSフレームワーク
bootstrap3

### CSSテンプレート
x-light-bootstrap-dashboard

### javascriptフレームワーク
jQuery (フレームワークではありませんが…)

フレームワークを導入する規模でもないのと、
bootstrapがjQueryに依存しているので、
jQueryを使用

### タスクランナー
gulp

本システムではCSS・JSのminifyと一つのファイルに結合することで
画面のレンダリング速度の向上を図っています。

CSSファイルやJSを修正すると、
gulpの監視タスクが変更をキャッチして自動的に[ファイル名].min.[拡張子]ファイルを作成し
minファイルを結合して一つのファイルにしています。<br>
CSSならbundle.min.css、JSならmain.min.jsに結合しています。

bundle.min.cssはすべてのファイルを結合していますが、main.min.jsは共通するファイルだけを結合し、各画面個別で呼ばれるものは結合していません。

#### もしCSSやJSを修正する場合
ファイルを修正した後、以下のコマンドを実行してminファイルの作成、結合を行ってください。

* CSSを修正した場合
```
$ cd ~/ddns
$ gulp csscon
```
~/ddns/web/bundles/atwddnsuseradmin/css にbundle.min.css が作成されます。


* JSを修正した場合
```
$ cd ~/ddns
$ gulp concat
```
~/ddns/web/bundles/atwddnsuseradmin/js にmain.min.js が作成されます。

# 注意事項

~/ddns/web/.htaccessですが、
AuthUserFileを絶対パスで指定しているので、
正しい値を環境によって指定してください。


## gulpのインストール

# node.js and gulp install

## epel repository install
```
rpm -ivh http://ftp.riken.jp/Linux/fedora/epel/6/x86_64/epel-release-6-8.noarch.rpm
```

## node.js and npm install
```
yum install -y nodejs npm --enablerepo=epel
```

## install gulp from package.json
```
npm install
```

## gulp version
```
gulp -v
```

If it is an error, please run command the following...
```
sudo npm install --global gulp
```
