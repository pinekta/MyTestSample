<?php

namespace Atw\DdnsUserAdminBundle\Form\Support;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class FormatDatetimeTransformer
 */
class FormatDatetimeTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y/m/d H:i:s');
        }
        return str_replace('-', '/', $value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        return str_replace('/', '-', $value);
    }
}
