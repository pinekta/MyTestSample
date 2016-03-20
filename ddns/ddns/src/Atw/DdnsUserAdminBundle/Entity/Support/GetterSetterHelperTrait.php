<?php

namespace Atw\DdnsUserAdminBundle\Entity\Support;

trait GetterSetterHelperTrait
{
    /**
     * 未定義の関数コール時のマジックメソッド
     *
     * @param string $methodName
     * @param array $arguments
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function __call($methodName, array $arguments)
    {
        try {
            $methodPrefix = "";
            if (strlen($methodName) > 3) {
                $methodPrefix = substr($methodName, 0, 3);
                $propertyName = lcfirst(substr($methodName, 3));
            }

            switch ($methodPrefix) {
                case "get":
                    if (property_exists($this, $propertyName)) {
                        return $this->$propertyName;
                    }
                    throw new \BadMethodCallException();
                    break;
                case "set":
                    if (count($arguments) == 0) {
                        throw new \InvalidArgumentException("setメソッドに引数が設定されていません。");
                    }
                    if (property_exists($this, $propertyName)) {
                        $this->$propertyName = $arguments[0];
                        return $this;
                    }
                    throw new \BadMethodCallException();
                    break;
                default:
                    // twigからはプロパティ名でメソッドがコールされる(※メソッド名: hoge)のでそれを考慮する
                    if (property_exists($this, $methodName)) {
                        return $this->$methodName;
                    }
                    throw new \BadMethodCallException();
            }
        } catch (\BadMethodCallException $e) {
            throw new \BadMethodCallException("不正なメソッド({$methodName}) がコールされました。そのようなメソッドは存在していません。");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 未定義のプロパティget時のマジックメソッド
     *
     * @param string $propertyName
     * @throws BadMethodCallException
     * @return mixed
     */
    public function __get($propertyName)
    {
        if (property_exists($this, $propertyName)) {
            return $this->$propertyName;
        }
        throw new \BadMethodCallException("不正なプロパティ({$propertyName}) にアクセスされました。そのようなプロパティは存在していません。");
    }

    /**
     * 未定義のプロパティset時のマジックメソッド
     *
     * @param string $propertyName
     * @param string $value
     * @throws BadMethodCallException
     * @return DnsUser
     */
    public function __set($propertyName, $value)
    {
        if (property_exists($this, $propertyName)) {
            $this->$propertyName = $value;
            return $this;
        }
        throw new \BadMethodCallException("不正なプロパティに({$propertyName}) が値のセットが試みられました。そのようなプロパティは存在していません。");
    }
}
