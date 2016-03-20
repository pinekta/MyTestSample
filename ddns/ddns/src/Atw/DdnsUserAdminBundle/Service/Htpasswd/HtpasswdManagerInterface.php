<?php

namespace Atw\DdnsUserAdminBundle\Service\Htpasswd;

use Atw\DdnsUserAdminBundle\Exception\HtpasswdException;

/**
 * Interface HtpasswdManagerInterface
 */
interface HtpasswdManagerInterface
{
    /**
     * htpasswdファイルを生成する
     * 本メソッドがワークファイルの削除・生成・置き換えのメソッドをコールする
     * @throws HtpasswdException
     */
    public function tryGenerateHtpasswd();
}
