<?php

namespace Atw\DdnsUserAdminBundle\Dto;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Atw\DdnsUserAdminBundle\Entity\Support\GetterSetterHelperTrait;
use Atw\DdnsUserAdminBundle\Entity\Support\IsExistInConstantsTrait;

/**
 * Class DnsUserImportDto
 */
class DnsUserImportDto
{
    use GetterSetterHelperTrait;
    use IsExistInConstantsTrait;

    /**
     * インポート・エクスポートファイルのカラム数
     * ※エクスポートファイルのカラム数が変更になった場合
     * この定数の値も修正すること
     * @var int
     */
    const FILE_COLUMN_COUNT = 9;

    const FILE_TYPE_CSV = 1;
    const FILE_TYPE_JSON = 2;

    const IS_IGNORE_HEADER_NO = 0;
    const IS_IGNORE_HEADER_YES = 1;

    const IS_UPDATED_NO = 0;
    const IS_UPDATED_YES = 1;

    const FILE_TYPE_LIST = [
        self::FILE_TYPE_CSV => 'CSV',
        //self::FILE_TYPE_JSON => 'JSON',
    ];

    const IS_IGNORE_HEADER_LIST = [
        self::IS_IGNORE_HEADER_NO => '無視しない',
        self::IS_IGNORE_HEADER_YES => '無視する',
    ];

    const IS_UPDATED_LIST = [
        self::IS_UPDATED_NO => '上書きしない',
        self::IS_UPDATED_YES => '上書きする',
    ];

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *      message = "インポート種別を選択してください。"
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="インポート種別は数値を入力してください。"
     * )
     */
    private $importType;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *      message = "ヘッダ行（１行目）を無視するを選択してください。"
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="ヘッダ行（１行目）を無視するは数値を入力してください。"
     * )
     */
    private $isIgnoreHeaderLine;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *      message = "同一キーデータの上書き可否を選択してください。"
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="同一キーデータの上書き可否は数値を入力してください。"
     * )
     */
    private $isUpdated;

    /**
     * @var File
     *
     * @Assert\NotBlank(
     *      message = "インポートファイルを選択してください。"
     * )
     * @Assert\File(
     *     mimeTypes = {"text/csv", "text/plain"},
     *     mimeTypesMessage = "CSVファイルをアップロードしてください。",
     *     disallowEmptyMessage = "空のファイルを選択しているかファイルを選択していません。CSVファイルをアップロードしてください。"
     * )
     */
    private $importFile;

    /**
     * インポート種別の存在チェック
     *
     * @Assert\IsTrue(message = "インポート種別の値が不正です。")
     */
    public function isValidImportType()
    {
        return $this->isExistInConstants($this->importType, self::FILE_TYPE_LIST);
    }

    /**
     * ヘッダ行（１行目）を無視するの存在チェック
     *
     * @Assert\IsTrue(message = "ヘッダ行（１行目）を無視するの値が不正です。")
     */
    public function isValidIsIgnoreHeaderLine()
    {
        return $this->isExistInConstants($this->isIgnoreHeaderLine, self::IS_IGNORE_HEADER_LIST);
    }

    /**
     * 同一キーデータの上書き可否の存在チェック
     *
     * @Assert\IsTrue(message = "同一キーデータの上書き可否の値が不正です。")
     */
    public function isValidIsUpdated()
    {
        return $this->isExistInConstants($this->isUpdated, self::IS_UPDATED_LIST);
    }

    public function setImportFile(File $importFile = null)
    {
        $this->importFile = $importFile;
        return $this;
    }

    public function getImportFile()
    {
        return $this->importFile;
    }
}
