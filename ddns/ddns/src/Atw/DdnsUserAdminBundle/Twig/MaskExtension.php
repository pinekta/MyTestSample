<?php

namespace Atw\DdnsUserAdminBundle\Twig;

/**
 * class MaskExtension
 */
class MaskExtension extends \Twig_Extension
{
    /**
     *
     * @return array \Twig_SimpleFilter
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('mask', [$this, 'maskFilter']),
        ];
    }

    /**
     *
     * @param string $value
     * @param int $start
     * @param int $char
     * @return string
     */
    public function maskFilter($value, $start = 0, $char = '*')
    {
        $ret = $value;
        if ($ret) {
            // 指定位置以降置き換え
            $ret = str_pad(substr($ret, 0, $start), strlen($ret), $char);
        }
        return $ret;
    }

    /*
     * Get name
     */
    public function getName()
    {
        return 'mask_extension';
    }
}
