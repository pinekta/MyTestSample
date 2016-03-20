<?php

namespace Atw\DdnsUserAdminBundle\Entity\Support;

trait CreateUpdateDtLifeCycleHelperTrait
{
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $now = new \DateTime();
        $this->setCreatedAt($now->format('Y-m-d H:i:s'));
        $this->setUpdatedAt($now->format('Y-m-d H:i:s'));
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $now = new \DateTime();
        $this->setUpdatedAt($now->format('Y-m-d H:i:s'));
    }
}
