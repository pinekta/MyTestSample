<?php

namespace Atw\DdnsUserAdminBundle\Twig\Support;

/**
 * trait EscapeHelperTrait
 */
trait EscapeHelperTrait
{
    /**
     * HTML用にエスケープされた文字列を返却
     * @return string
     */
    private function escapeForHtml($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
