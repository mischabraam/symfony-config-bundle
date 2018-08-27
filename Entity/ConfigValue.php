<?php

namespace WeProvide\ConfigBundle\Entity;

/**
 * ConfigValue
 */
class ConfigValue
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var ConfigVariable
     */
    private $configVariable;

    /**
     * @var ConfigVariableChoice
     */
    private $configVariableChoice;

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
     * Set value
     *
     * @param string $value
     *
     * @return ConfigValue
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
     * Returns the raw value or the value of related ConfigVariableChoice
     *
     * @return null|string
     */
    public function getAggregatedValue()
    {
        return ($this->value != null ? $this->value : ($this->configVariableChoice != null ? $this->configVariableChoice->getValue() : null));
    }

    /**
     * @param ConfigVariable $configVariable
     *
     * @return ConfigValue
     */
    public function setConfigVariable($configVariable)
    {
        $this->configVariable = $configVariable;

        return $this;
    }

    /**
     * @return ConfigVariable
     */
    public function getConfigVariable()
    {
        return $this->configVariable;
    }

    /**
     * @param ConfigVariableChoice $configVariableChoice
     *
     * @return ConfigValue
     */
    public function setConfigVariableChoice($configVariableChoice)
    {
        $this->configVariableChoice = $configVariableChoice;

        return $this;
    }

    /**
     * @return ConfigVariableChoice
     */
    public function getConfigVariableChoice()
    {
        return $this->configVariableChoice;
    }
}

