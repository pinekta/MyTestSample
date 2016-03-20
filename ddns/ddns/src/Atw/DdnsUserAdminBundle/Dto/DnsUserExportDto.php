<?php

namespace Atw\DdnsUserAdminBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Atw\DdnsUserAdminBundle\Entity\Support\GetterSetterHelperTrait;
use Atw\DdnsUserAdminBundle\Dto\DnsUserImportDto;
use Atw\DdnsUserAdminBundle\Entity\Support\IsExistInConstantsTrait;

/**
 * Class DnsUserExportDto
 */
class DnsUserExportDto
{
    use GetterSetterHelperTrait;
    use IsExistInConstantsTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *      message = "エクスポート種別を選択してください。"
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="エクスポート種別は数値を入力してください。"
     * )
     */
    private $exportType;

    /**
     * エクスポート種別の存在チェック
     *
     * @Assert\IsTrue(message = "エクスポート種別の値が不正です。")
     */
    public function isValidExportType()
    {
        return $this->isExistInConstants($this->exportType, DnsUserImportDto::FILE_TYPE_LIST);
    }
}
