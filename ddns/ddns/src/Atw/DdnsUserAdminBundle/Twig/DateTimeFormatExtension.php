<?php

namespace Atw\DdnsUserAdminBundle\Twig;

/**
 * class DateTimeFormatExtension
 */
class DateTimeFormatExtension extends \Twig_Extension
{
    /**
     * @return array \Twig_SimpleFilter
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('datetime_format', [$this, 'datetimeFormatFilter']),
        ];
    }

    /**
     * @param string $value
     * @return string
     */
    public function datetimeFormatFilter($value)
    {
        if (isset($value)) {
            if ($value instanceof \DateTime) {
                return $value->format('Y/m/d H:i:s');
            }
        }
        return $value;
    }

    /*
     * Get name
     */
    public function getName()
    {
        return 'datetime_format_extension';
    }
}
