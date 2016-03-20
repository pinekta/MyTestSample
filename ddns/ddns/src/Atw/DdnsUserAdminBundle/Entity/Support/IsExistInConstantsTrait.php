<?php

namespace Atw\DdnsUserAdminBundle\Entity\Support;

trait IsExistInConstantsTrait
{
    /**
     * 引数のリストに指定した値が存在するかチェックする
     * @param mixed $value
     * @param array $constants
     * @return bool
     */
    private function isExistInConstants($value, array $constants = [])
    {
        if (!is_null($value) && $value !== "") {
            if (!array_key_exists($value, $constants)) {
                return false;
            }
        }
        return true;
    }
}
