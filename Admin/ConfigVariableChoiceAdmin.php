<?php

namespace WeProvide\ConfigBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;

class ConfigVariableChoiceAdmin extends BaseAdmin
{
    protected $parentAssociationMapping = 'configVariable';

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('value')
            ->add('position', 'hidden', array('attr' => array("hidden" => true)));
    }
}
