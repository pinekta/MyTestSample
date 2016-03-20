<?php

namespace Atw\DdnsUserAdminBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Atw\DdnsUserAdminBundle\Entity\Support\GetterSetterHelperTrait;
use Atw\DdnsUserAdminBundle\Entity\Support\IsExistInConstantsTrait;

/**
 * Class DnsUserCriteriaDto
 */
class DnsUserCriteriaDto
{
    use GetterSetterHelperTrait;
    use IsExistInConstantsTrait;

    const DISPLAYCOUNT_MIN = 10;
    const DISPLAYCOUNT_LOW = 20;
    const DISPLAYCOUNT_HIGH = 50;
    const DISPLAYCOUNT_MAX = 100;

    const DISPLAYCOUNT_LIST = [
        self::DISPLAYCOUNT_MIN => self::DISPLAYCOUNT_MIN . "件",
        self::DISPLAYCOUNT_LOW => self::DISPLAYCOUNT_LOW . "件",
        self::DISPLAYCOUNT_HIGH => self::DISPLAYCOUNT_HIGH . "件",
        self::DISPLAYCOUNT_MAX => self::DISPLAYCOUNT_MAX . "件",
    ];

    /**
     * @var string
     */
    private $criteria;

    /**
     * @var int
     *
     * @Assert\Type(
     *     type="integer",
     *     message="表示件数は数値を入力してください。"
     * )
     */
    private $displaycount;

    /**
     * 表示件数の存在チェック
     *
     * @Assert\IsTrue(message = "表示件数の値が不正です。")
     */
    public function isValidDisplayCount()
    {
        return $this->isExistInConstants($this->displaycount, self::DISPLAYCOUNT_LIST);
    }
}
