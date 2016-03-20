<?php

namespace Atw\DdnsUserAdminBundle\Form\Support;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait FormTypeTrait
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class'      => $this->getDataClassName(),
                'csrf_protection' => $this->getCsrfProtection(),
                'csrf_message'    => '不正な画面遷移が行われました。',
            ]);
    }
}
