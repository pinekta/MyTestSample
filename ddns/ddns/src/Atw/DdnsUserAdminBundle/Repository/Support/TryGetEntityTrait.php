<?php

namespace Atw\DdnsUserAdminBundle\Repository\Support;

use Doctrine\ORM\EntityNotFoundException;

trait TryGetEntityTrait
{
    /**
     * id でEntityの取得を試みる。データがなければEntityNotFoundExceptionをスローする
     * @param int $id
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function tryGetEntityById($id)
    {
        $entity = $this->find($id);
        if (!$entity) {
            throw new EntityNotFoundException('指定したデータが見つかりませんでした。');
        }
        return $entity;
    }
}
