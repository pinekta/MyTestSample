<?php

namespace Atw\DdnsUserAdminBundle\Service\DnsUser;

use Atw\DdnsUserAdminBundle\Dto\DnsUserImportDto;
use Atw\DdnsUserAdminBundle\Entity\DnsUser;
use Atw\DdnsUserAdminBundle\Exception\FormValidationException;

/**
 * Interface DnsUserManagerInterface
 */
interface DnsUserManagerInterface
{
    /**
     * @param DnsUser $dnsUser
     * @throws FormValidationException
     */
    public function tryUpdateInsert(DnsUser $dnsUser);

    /**
     * @param DnsUser $dnsUser
     * @throws \Exception
     */
    public function tryDelete(DnsUser $dnsUser);

    /**
     * @param DnsUser $dnsUser
     * @throws FormValidationException
     */
    public function tryValidate(DnsUser $dnsUser);

    /**
     * @param DnsUserImportDto $dto
     * @throws FormValidationException
     */
    public function tryImportAndGetResultCount(DnsUserImportDto $dto);
}
