<?php

namespace WeProvide\ConfigBundle\Entity;

/**
 * ConfigVariableChoice
 */
class ConfigVariableChoice
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var ConfigVariable
     */
    private $configVariable;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $position;


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
     * @param ConfigVariable $configVariable
     *
     * @return ConfigVariableChoice
     */
    public function setConfigVariable(ConfigVariable $configVariable)
    {
        $this->configVariable = $configVariable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfigVariable()
    {
        return $this->configVariable;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return ConfigVariableChoice
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return ConfigVariableChoice
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Returns this entity as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}

