<?php

namespace Atw\DdnsUserAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Atw\DdnsUserAdminBundle\Entity\DnsUser;
use Atw\DdnsUserAdminBundle\Form\Support\FormatDatetimeTransformer;
use Atw\DdnsUserAdminBundle\Form\Support\FormTypeTrait;

class DnsUserType extends AbstractType
{
    use FormTypeTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isNew = is_null($options['data']->getId());
        $builder
            ->add('userName', 'text', [
                'attr'  => ['class' => 'form-control', 'placeholder' => 'example000', 'maxlength' => 20]
            ])
            ->add('password', 'repeated', [
                'type' => 'password',
                'invalid_message' => 'パスワードが確認用の値と異なっています',
                'required' => $isNew,
                'options' => ['attr' => ['class' => 'form-control', 'maxlength' => 50], 'always_empty' => false],
            ])
            ->add('controlNo', 'text', [
                'attr'  => ['class' => 'form-control', 'placeholder' => 'serial000', 'maxlength' => 50],
                'required' => false,
            ])
            ->add('comment', 'text', [
                'attr'  => ['class' => 'form-control', 'placeholder' => '株式会社○○　東京本社設置', 'maxlength' => 100],
                'required' => false,
            ])
            ->add('encryptType', 'choice', [
                'choices' => DnsUser::ENCRYPT_TYPE_LIST,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'invalid_message' => '暗号化方式の選択が不正です。',
                'attr'  => ['class' => 'form-control'],
                'data' => $isNew ? DnsUser::ENCRYPT_TYPE_MD5 : $options['data']->getEncryptType(),
            ])
        ;
    }

    /**
     * @return string
     */
    protected function getDataClassName()
    {
        return DnsUser::class;
    }

    /**
     * @return string
     */
    protected function getCsrfProtection()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'atw_ddnsuseradminbundle_dnsuser';
    }
}
