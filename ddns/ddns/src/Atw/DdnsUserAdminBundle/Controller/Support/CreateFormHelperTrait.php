<?php

namespace Atw\DdnsUserAdminBundle\Controller\Support;

use Symfony\Component\Form\Form;
use Atw\DdnsUserAdminBundle\Exception\FormValidationException;

trait CreateFormHelperTrait
{
    /**
     * Creates a form to create a Category entity.
     * @param $entity The entity
     * @param $formType The formType
     * @param $route The routing name
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity, $formType, $route)
    {
        $form = $this->createForm($formType, $entity, [
            'action' => $this->generateUrl($route),
            'method' => 'POST',
        ]);
        $form->add('submit', 'submit', [
            'label' => '登録',
            'attr'  => ['class' => 'btn btn-info btn-fill pull-left'],
        ]);
        return $form;
    }

    /**
     * Creates a form to edit a Category entity.
     * @param $entity The entity
     * @param $formType The formType
     * @param $route The routing name
     * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity, $formType, $route)
    {
        $form = $this->createForm($formType, $entity, [
            'action' => $this->generateUrl($route, ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);
        $form->add('submit', 'submit', [
            'label' => '更新',
            'attr'  => ['class' => 'btn btn-info btn-fill pull-left'],
        ]);
        return $form;
    }

    /**
     * Creates a form to delete a Category entity by id.
     * @param mixed $id The entity id
     * @param $route The routing name
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($route, ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', [
                'label' => '削除',
                'attr'  => ['class' => 'btn btn-xs btn-danger'],
            ])
            ->getForm()
        ;
    }

    /**
     * エクスポートフォーム作成
     * @param $entity The entity
     * @param $formType The formType
     * @param $route The routing name
     * @return \Symfony\Component\Form\Form The form
     */
    private function createExportForm($entity, $formType, $route)
    {
        $form = $this->createForm($formType, $entity, [
            'action' => $this->generateUrl($route),
            'method' => 'GET',
        ]);
        $form->add('submit', 'submit', [
            'label' => 'エクスポート',
            'attr'  => ['class' => 'btn btn-info btn-fill pull-left'],
        ]);
        return $form;
    }

    /**
     * インポートフォーム作成
     * @param $entity The entity
     * @param $formType The formType
     * @param $route The routing name
     * @return \Symfony\Component\Form\Form The form
     */
    private function createImportForm($entity, $formType, $route)
    {
        $form = $this->createForm($formType, $entity, [
            'action' => $this->generateUrl($route),
            'method' => 'POST',
        ]);
        $form->add('submit', 'submit', [
            'label' => 'インポート',
            'attr'  => ['class' => 'btn btn-info btn-fill pull-left'],
        ]);
        return $form;
    }

    /**
     * フォームのバリデーションを行う
     * ※ServiceクラスでEntityのバリデーションを行っているが、
     * FormTypeクラスで設定したチェックや、CSRFトークンのチェックは
     * $form->isValid()でないと検証できないため、
     * フォームの検証の場合、本メソッドを使用すること
     *
     * @param Form $form
     * @throws FormValidationException
     */
    private function tryFormValidate(Form $form)
    {
        if (!$form->isValid()) {
            $exception = new FormValidationException();
            foreach ($form->getErrors(true, true) as $error) {
                $exception->addErrorMessage($error->getMessage());
            }
            throw $exception;
        }
    }
}
