#!/bin/sh
#
# DBデータバックアップスクリプト
#
# Usage:
#   ./backup.sh
#
# Description:
#   バックアップを行います。
#   バックアップサーバにダンプしたsqliteファイルをコピーします
#   本スクリプトはバックアップを取るサーバ上で実行してください。
#
###########################################################################

ret=0

NOW=`date "+%Y%m%d"`
DB_DIR_PATH="/home/homepage/ddns/app/data"
DB_FILENAME="ddns.sqlite3"
DB_NOWFILE=${DB_FILENAME}.${NOW}
DB_TARFILE="${DB_NOWFILE}.tar"
DEST_SERVER="ns2"

cd $DB_DIR_PATH

#//-------------------------------------------------------------
#// 処理開始
#//-------------------------------------------------------------
echo "----------------------------------------------------------------"
echo [`date +"%Y/%m/%d %H:%M:%S"`] backup Process Begin.
echo ""

# sqliteデータのバックアップ
sqlite3 $DB_FILENAME ".backup $DB_NOWFILE"
tar zcvf $DB_TARFILE $DB_NOWFILE
chmod 660 $DB_TARFILE

# バックアップサーバにコピー
scp -p $DB_TARFILE homepage@$DEST_SERVER:$DB_DIR_PATH/$DB_TARFILE

# ファイルを削除
rm -f $DB_NOWFILE
rm -f $DB_TARFILE

#//-------------------------------------------------------------
#// 処理終了
#//-------------------------------------------------------------
echo ""
echo [`date +"%Y/%m/%d %H:%M:%S"`] backup Process End.
echo ""
exit ${ret}
