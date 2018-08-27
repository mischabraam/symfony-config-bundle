<?php

namespace WeProvide\ConfigBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use WeProvide\ConfigBundle\Entity\ConfigValue;
use WeProvide\ConfigBundle\Entity\ConfigVariable;

class ConfigVariableAdmin extends BaseAdmin
{
    // TODO: name of config value is unique in database, prevent throw of exception when duplicate name is inserted
    // TODO: maybe add filters, currently disabled
    // TODO: when inserting and type is changed to or from 'choice' show or hide the choices sub-entity (ConfigVariableChoice)

    protected $datagridValues = [
        '_sort_by' => 'name',
    ];

    /**
     * @param ListMapper $listMapper
     * @throws \ReflectionException
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('name')
            ->add('description')
            ->add('type', 'choice', array(
                'choices' => array_flip(ConfigVariable::getTypes()),
            ))
            ->add('_action', null, array(
                'actions' => array(
                    'show'   => array(),
                    'edit'   => array(),
                    'delete' => array(),
                ),
            ));
    }

    /**
     * @param FormMapper $formMapper
     * @throws \ReflectionException
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var ConfigVariable $configVariable */
        $configVariable = $this->getSubject();

        $formMapper
            ->add('name')
            ->add('description');

        if ($configVariable->getId() === null) {
            $formMapper
                ->add('type', 'choice', array(
                    'choices' => ConfigVariable::getTypes(),
                ));
        }

        if ($configVariable->getType() == ConfigVariable::TYPE_CHOICE) {
            $formMapper
                ->add('choices', 'sonata_type_collection', array(
                    'by_reference' => false,
                    'required'     => false,
                ), array(
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                ));
        }
    }

    /**
     * @param ShowMapper $showMapper
     * @throws \ReflectionException
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        /** @var ConfigVariable $configVariable */
        $configVariable = $this->getSubject();

        $showMapper
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('type', 'choice', array(
                'choices' => array_flip(ConfigVariable::getTypes()),
            ));

        if ($configVariable->getType() == ConfigVariable::TYPE_CHOICE) {
            $showMapper
                ->add('choices', null, array(
                    'route' => array('name' => 'show') // TODO: instead of show, remove link, but how?
                ));
        }
    }

    /**
     * Validate if name is according to naming conventions.
     *
     * @param ErrorElement $errorElement
     * @param              $configVariable
     */
    public function validate(ErrorElement $errorElement, $configVariable)
    {
        if (!$configVariable->hasValidName()) {
            $errorElement
                ->with('name')
                ->addViolation('Name not according to naming conventions')
                ->end();
        }
    }

    /**
     * When creating a ConfigVariable also create a ConfigValue related to this new ConfigVariable
     *
     * @param ConfigVariable $configVariable
     */
    public function postPersist($configVariable)
    {
        parent::postPersist($configVariable);

        // Create a ConfigValue related to this ConfigVariable
        $container   = $this->getConfigurationPool()->getContainer();
        $em          = $container->get('doctrine.orm.entity_manager');
        $configValue = new ConfigValue();
        $configValue->setConfigVariable($configVariable);
        $em->persist($configValue);
        $em->flush();
    }

    /**
     * @param ConfigVariable $configVariable
     * @return string
     */
    public function toString($configVariable)
    {
        return $configVariable->getName();
    }
}
