#!/bin/sh
#
# DBデータレストアスクリプト
#
# Usage:
#   ./restore.sh
#
# Description:
#   リストアを行います。
#   バックアップサーバにある当日日付のバックアップファイルからリストアします
#   本スクリプトはリストアを行うサーバ上で実行してください。
#
###########################################################################

ret=0

NOW=`date "+%Y%m%d"`
DB_DIR_PATH="/home/homepage/ddns/app/data"
DB_FILENAME="ddns.sqlite3"
DB_NOWFILE=${DB_FILENAME}.${NOW}
DB_TARFILE="${DB_NOWFILE}.tar"

cd $DB_DIR_PATH

#//-------------------------------------------------------------
#// 処理開始
#//-------------------------------------------------------------
echo "----------------------------------------------------------------"
echo [`date +"%Y/%m/%d %H:%M:%S"`] backup Process Begin.
echo ""

# バックアップファイルの解凍
tar zxvf $DB_TARFILE

# レストア
sqlite3 $DB_FILENAME ".restore $DB_NOWFILE"

# 解凍したファイルを削除
rm -f $DB_NOWFILE

# 古いファイルを削除(1週間前のデータは削除)
find $DB_DIR_PATH/*.tar -type f -mtime +7 | xargs rm -f

#//-------------------------------------------------------------
#// 処理終了
#//-------------------------------------------------------------
echo ""
echo [`date +"%Y/%m/%d %H:%M:%S"`] backup Process End.
echo ""
exit ${ret}
