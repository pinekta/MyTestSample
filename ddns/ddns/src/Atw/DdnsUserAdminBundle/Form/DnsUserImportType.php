<?php

namespace Atw\DdnsUserAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Atw\DdnsUserAdminBundle\Dto\DnsUserImportDto;
use Atw\DdnsUserAdminBundle\Form\Support\FormTypeTrait;

/**
 * Class DnsUserImportType
 */
class DnsUserImportType extends AbstractType
{
    use FormTypeTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('importType', 'choice', [
                'choices' => DnsUserImportDto::FILE_TYPE_LIST,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'invalid_message' => 'インポート種別の選択が不正です。',
                'attr'  => ['class' => 'form-control'],
                'data' => DnsUserImportDto::FILE_TYPE_CSV,
            ])
            ->add('isIgnoreHeaderLine', 'choice', [
                'choices' => DnsUserImportDto::IS_IGNORE_HEADER_LIST,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'invalid_message' => 'ヘッダ行（１行目）を無視するの選択が不正です。',
                'attr'  => ['class' => 'form-control'],
                'data' => DnsUserImportDto::IS_IGNORE_HEADER_YES,
            ])
            ->add('isUpdated', 'choice', [
                'choices' => DnsUserImportDto::IS_UPDATED_LIST,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'invalid_message' => '同一キーデータの上書き可否の選択が不正です。',
                'attr'  => ['class' => 'form-control'],
                'data' => DnsUserImportDto::IS_UPDATED_NO,
            ])
            ->add('importFile', 'file', [
                'required' => true,
                'attr'  => ['class' => 'dropify', 'data-default-file' => '']
            ])
        ;
    }

    /**
     * @return string
     */
    protected function getDataClassName()
    {
        return DnsUserImportDto::class;
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
        return 'dnsuser_import';
    }
}
