<?php

namespace WeProvide\ConfigBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * ConfigVariable
 */
class ConfigVariable
{
    // TODO: maybe move these constants to a new/other entity like ConfigVariableType
    const TYPE_BOOLEAN = 1;
    const TYPE_INTEGER = 2;
    const TYPE_STRING = 3;
    const TYPE_CHOICE = 4;
    // TODO: implement   const TYPE_LIST = 5;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var
     */
    private $description;

    /**
     * @var int
     */
    private $type;

    /**
     * @var ArrayCollection
     */
    private $choices;

    /**
     * ConfigVariable constructor.
     */
    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return ConfigVariable
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns true when the name is according to naming conventions.
     */
    public function hasValidName()
    {
        return (!preg_match('/[^a-z0-9\.\_\-]/', $this->getName()));
    }

    /**
     * @param mixed $description
     *
     * @return ConfigVariable
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return ConfigVariable
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns a list of all possible types.
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getTypes()
    {
        $self = new \ReflectionClass(__CLASS__);

        // TODO: maybe move this to a new/other entity like ConfigVariableType
        return $self->getConstants();
    }

    /**
     * @return ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @param ArrayCollection $choices
     *
     * @return ConfigVariable
     */
    public function setChoices(ArrayCollection $choices)
    {
        $this->choices = new ArrayCollection();
        foreach ($choices as $choice) {
            $this->addChoice($choice);
        }

        return $this;
    }

    /**
     * @param ConfigVariableChoice $choice
     *
     * @return ConfigVariable
     */
    public function addChoice(ConfigVariableChoice $choice)
    {
        $this->choices->add($choice);
        $choice->setConfigVariable($this);

        return $this;
    }

    /**
     * @param ConfigVariableChoice $choice
     *
     * @return ConfigVariable
     */
    public function removeChoice(ConfigVariableChoice $choice)
    {
        $this->choices->removeElement($choice);

        return $this;
    }
}

