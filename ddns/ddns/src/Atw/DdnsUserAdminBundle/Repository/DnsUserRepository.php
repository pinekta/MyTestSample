<?php

namespace Atw\DdnsUserAdminBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Atw\DdnsUserAdminBundle\Dto\SortConditionDto;
use Atw\DdnsUserAdminBundle\Repository\Support\TryGetEntityTrait;

/**
 * Class DnsUserRepository
 *
 * @package Atw\DdnsUserAdminBundle\Repository
 */
class DnsUserRepository extends EntityRepository
{
    use TryGetEntityTrait;

    /**
     * テーブルのエイリアス
     * @var string
     */
    const ALIAS = "dnsuser";

    /**
     * 一覧取得
     * @param string $criteria
     * @param string $sort
     * @param string $direction
     * @param integer $limit
     */
    public function findListByCriteria($criteria = "", SortConditionDto $sortCondition = null, $limit = 0)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select(self::ALIAS);
        $qb->from('AtwDdnsUserAdminBundle:DnsUser', self::ALIAS);
        if (!is_null($criteria) && $criteria !== "") {
            $arrayCriteria = explode(' ', str_replace('　', ' ', trim($criteria)));
            foreach ($arrayCriteria as $key => $dividedCriteria) {
                $qb->andWhere($qb->expr()->orX(self::ALIAS.".userName LIKE :criteria{$key}", self::ALIAS.".controlNo LIKE :criteria{$key}", self::ALIAS.".comment LIKE :criteria{$key}"));
                $qb->setParameter("criteria{$key}", "%{$dividedCriteria}%");
            }
        }
        if (!is_null($sortCondition) && $sortCondition->isSortable()) {
            $qb->addOrderBy($sortCondition->getSort(), $sortCondition->getDirection());
        } else {
            $qb->addOrderBy(self::ALIAS.'.id', 'ASC');
        }
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }
        return $qb->getQuery();
    }

    /**
     * ユーザ名一覧を取得
     */
    public function findUserNameAll()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select(self::ALIAS.'.userName');
        $qb->from('AtwDdnsUserAdminBundle:DnsUser', self::ALIAS);
        $list = $qb->getQuery()->getResult();
        return array_column($list, 'userName');
    }
}
