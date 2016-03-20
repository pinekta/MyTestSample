<?php

namespace Atw\DdnsUserAdminBundle\Entity\Support;

/**
* Class EntitySupport
*/
class EntitySupport
{
    /**
    * Entityの汎用セッター
    *
    * @param $entity
    * @param array $data
    *
    * @return mixed
    * @throws \Exception
    */
    public static function autoSet($entity, array $data)
    {
        foreach ($data as $col => $val) {
            $func = "set" . ucfirst($col);
            $entity->$func($val);
        }
        return $entity;
    }

    /**
    * $propsで指定したプロパティをEntityから一括取得する
    * @param $entity
    * @param array $props
    *
    * @return array
    */
    public static function autoGet($entity, array $props)
    {
        $getData = [];
        foreach ($props as $prop) {
            $func = "get" . ucfirst($prop);
            $getData[$prop] = $entity->$func();
        }
        return $getData;
    }

    /**
    * 自動マッピング処理
    * $propsで指定された$mapFormのプロパティを$mapToへマッピングする
    *
    * @param $mapFrom
    * @param $mapTo
    * @param array $props ex) array("id", "name")
    *
    * @return mixed
    */
    public static function autoMap($mapFrom, $mapTo, array $props)
    {
        $mapData = self::autoGet($mapFrom, $props);
        $pmEntity = self::autoSet($mapTo, $mapData);
        return $pmEntity;
    }
}
