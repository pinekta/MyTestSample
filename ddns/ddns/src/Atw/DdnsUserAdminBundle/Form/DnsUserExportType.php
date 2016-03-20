<?php

namespace Atw\DdnsUserAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Atw\DdnsUserAdminBundle\Dto\DnsUserExportDto;
use Atw\DdnsUserAdminBundle\Dto\DnsUserImportDto;
use Atw\DdnsUserAdminBundle\Form\Support\FormTypeTrait;

/**
 * Class DnsUserExportType
 */
class DnsUserExportType extends AbstractType
{
    use FormTypeTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('exportType', 'choice', [
                'choices' => DnsUserImportDto::FILE_TYPE_LIST,
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'invalid_message' => 'エクスポート種別の選択が不正です。',
                'attr'  => ['class' => 'form-control'],
                'data' => DnsUserImportDto::FILE_TYPE_CSV,
            ])
        ;
    }

    /**
     * @return string
     */
    protected function getDataClassName()
    {
        return DnsUserExportDto::class;
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
        return 'dnsuser_export';
    }
}
