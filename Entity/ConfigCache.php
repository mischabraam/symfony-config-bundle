<?php

namespace WeProvide\ConfigBundle\Entity;

/**
 * ConfigCache
 */
class ConfigCache
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var bool
     */
    private $valid;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     *
     * @return ConfigCache
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return bool
     */
    public function getValid()
    {
        return $this->valid;
    }
}

