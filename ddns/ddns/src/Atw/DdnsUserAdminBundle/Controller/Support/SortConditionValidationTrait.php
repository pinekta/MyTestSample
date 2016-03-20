<?php

namespace Atw\DdnsUserAdminBundle\Controller\Support;

use Atw\DdnsUserAdminBundle\Dto\SortConditionDto;
use Atw\DdnsUserAdminBundle\Exception\SortConditionValidationException;

trait SortConditionValidationTrait
{
    /**
     * ソート条件のバリデーションを行う
     * @param SortConditionDto $sortCondition
     * @throws SortConditionValidationException
     */
    private function trySortConditionValidate(SortConditionDto $sortConditionDto)
    {
        $validator = $this->get('validator');
        $errors = $validator->validate($sortConditionDto);
        if (count($errors)) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            throw new SortConditionValidationException(implode(' ', $messages));
        }
    }
}
