<?php

namespace Atw\DdnsUserAdminBundle\Exception;

use Atw\DdnsUserAdminBundle\Exception\DdnsUserException;

/**
 * フォームのヴァリデーション用の例外クラス
 * 内部のプロパティにエラーメッセージを配列で格納します。
 */
class FormValidationException extends DdnsUserException
{
    private $errorMessages;

    public function addErrorMessage($errorMessage)
    {
        $this->errorMessages[] = $errorMessage;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}
