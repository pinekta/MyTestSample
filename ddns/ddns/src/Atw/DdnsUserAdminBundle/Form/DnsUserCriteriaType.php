<?php

namespace Atw\DdnsUserAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Atw\DdnsUserAdminBundle\Dto\DnsUserCriteriaDto;
use Atw\DdnsUserAdminBundle\Form\Support\FormTypeTrait;

/**
 * Class DnsUserCriteriaType
 */
class DnsUserCriteriaType extends AbstractType
{
    use FormTypeTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('criteria', 'text', [
                'required' => false,
                'attr'  => ['class' => 'form-control input-criteria', 'placeholder' => 'ユーザ名またはコメントを入力してください。', 'maxlength' => 256]
            ])
            ->add('displaycount', 'choice', [
                'choices' => DnsUserCriteriaDto::DISPLAYCOUNT_LIST,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'invalid_message' => '表示件数の選択が不正です。',
                'attr'  => ['class' => 'form-control'],
            ])
        ;
    }

    /**
     * @return string
     */
    protected function getDataClassName()
    {
        return DnsUserCriteriaDto::class;
    }

    /**
     * @return string
     */
    protected function getCsrfProtection()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dnsuser_index';
    }
}
