<?php

namespace Atw\DdnsUserAdminBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Atw\DdnsUserAdminBundle\Twig\Support\EscapeHelperTrait;

/**
 * class GetDnsUserListExtension
 */
class GetDnsUserListExtension extends \Twig_Extension
{
    use EscapeHelperTrait;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*
     * Get name
     */
    public function getName()
    {
        return 'get_dnsuser_list_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getDnsUserName', [$this, 'getDnsUserName']),
            new \Twig_SimpleFunction('getDnsUserList', [$this, 'getDnsUserList']),
        ];
    }

    /**
     * @param string $value
     * @return string
     */
    public function getDnsUserName($id)
    {
        if (!preg_match('/^[0-9]+$/', $id)) {
            throw InvalidArgumentException("IDではない値が指定されました");
        }
        $entity = $this->em->getRepository('AtwTestBundle:DnsUser')->find($id);
        return is_null($entity) ? null : $entity->getName();
    }

    /**
     * @param string $selectValue
     * @param boolean $blank
     * @return string
     */
    public function getDnsUserList($selectValue = null, $blank = true)
    {
        $entities = $this->em->getRepository('AtwTestBundle:DnsUser')->findListByCriteria();

        $buf = '<select id="dnsuserList" name="dnsuserList" class="dropdown">';
        if ($blank) {
            $buf .= '<option value=""></option>';
        }
        foreach ($entities as $entity) {
            $selected = $selectValue === $entity->getId() ? 'selected' : '';
            $buf .= sprintf('<option value="%d" %s>%s</option>', $entity->getId(), $selected, $this->escapeForHtml($entity->getName()));
        }
        $buf .= '</select>';
        return $buf;
    }
}
