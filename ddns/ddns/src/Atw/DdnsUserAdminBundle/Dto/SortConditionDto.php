<?php

namespace Atw\DdnsUserAdminBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Atw\DdnsUserAdminBundle\Entity\DnsUser;
use Atw\DdnsUserAdminBundle\Entity\Support\GetterSetterHelperTrait;
use Atw\DdnsUserAdminBundle\Repository\DnsUserRepository;

/**
 * Class SortConditionDto
 */
class SortConditionDto
{
    use GetterSetterHelperTrait;

    /**
     * knp-paginatorの複数ソート列の区切り文字
     * ライブラリでハードコードされており
     * ライブラリの値を利用できなかったので
     * ここで定義する
     */
    const SORTS_SEPARATE_CHAR = '+';

    /**
     * @var string
     *
     * ソート条件が複数個指定される場合があり、
     * その場合の正規表現が難解になるためチェックはここでは行わない
     * かわりにisExistPropertyでentityにプロパティが存在するか
     * チェックを行う
     */
    private $sort;

    /**
     * @var string
     *
     * @Assert\Regex(
     *     pattern="/^(asc|desc){1}$/",
     *     match=true,
     *     message="不正なソート方向が指定されました。"
     * )
     */
    private $direction;

    /**
     * sortの列名が存在するかチェックを行う
     *
     * @Assert\IsTrue(message = "不正なソート列名が指定されました。")
     * @todo プロパティのチェックが固定になっているので動的に変更できるようにする
     */
    public function isExistProperty()
    {
        if (!is_null($this->sort) && $this->sort !== '') {
            $sorts = explode(self::SORTS_SEPARATE_CHAR, $this->sort);
            foreach ($sorts as $sort) {
                $spritSorts = explode('.', $sort);
                if (count($spritSorts) !== 2) {
                    // [テーブルのalias].[テーブルのプロパティ名]形式でない場合はエラーとする
                    return false;
                }
                if ($spritSorts[0] !== DnsUserRepository::ALIAS) {
                    return false;
                }
                if (!property_exists(new DnsUser(), $spritSorts[1])) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * sortとdirectionのいずれか一方だけ値がある場合エラーとする
     *
     * @Assert\IsTrue(message = "不正なソート列名・ソート方向が指定されました。")
     */
    public function isBothEmptyOrBothNotEmpty()
    {
        if ((is_null($this->sort) || $this->sort === '') && (is_null($this->direction) || $this->direction === '') ||
            (!is_null($this->sort) && $this->sort !== '') && (!is_null($this->direction) && $this->direction !== '')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $sort
     * @param string $direction
     */
    public function __construct($sort, $direction)
    {
        $this->sort = $sort;
        $this->direction = $direction;
    }

    /**
     * ソートにプロパティが使用できるか判定する
     */
    public function isSortable()
    {
        if ((!is_null($this->sort) && $this->sort !== '') && (!is_null($this->direction) && $this->direction !== '')) {
            return true;
        }
        return false;
    }
}
