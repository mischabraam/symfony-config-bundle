<?php

namespace WeProvide\ConfigBundle\Admin;

use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\HttpKernel\Kernel;
use WeProvide\ConfigBundle\Dumper\YamlDumper;
use WeProvide\ConfigBundle\Entity\ConfigCache;
use WeProvide\ConfigBundle\Entity\ConfigValue;
use WeProvide\ConfigBundle\Entity\ConfigVariable;

class ConfigValueAdmin extends BaseAdmin
{
    // TODO: maybe add filters, currently disabled

    protected $datagridValues = [
        '_sort_by' => 'configVariable.name',
    ];

    /**
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('create');
        $collection->remove('delete');
        $collection->remove('export');
    }

    /**
     * @param ListMapper $listMapper
     * @throws \ReflectionException
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('configVariable.name', null, array(
                'label' => 'Name',
            ))
            ->add('configVariable.description', null, array(
                'label' => 'Description',
            ))
            ->add('configVariable.type', 'choice', array(
                'choices' => array_flip(ConfigVariable::getTypes()),
                'label'   => 'Type',
            ))
            ->add('aggregatedValue', null, array(
                'label' => 'Value',
            ))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                ),
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var ConfigValue $configValue */
        $configValue = $this->getSubject();
        /** @var ConfigVariable $configVariable */
        $configVariable = $configValue->getConfigVariable();

        // TODO: implement TYPE_LIST
        switch ($configVariable->getType()) {
            case ConfigVariable::TYPE_BOOLEAN:
                $formMapper
                    ->add('value', 'choice', array(
                        'choices' => array(
                            'true'  => 'true',
                            'false' => 'false',
                        ),
                    ));
                break;

            case ConfigVariable::TYPE_INTEGER:
                $formMapper
                    ->add('value', 'integer');
                break;

            case ConfigVariable::TYPE_CHOICE:
                /** @var EntityManager $em */
                $em    = $this->modelManager->getEntityManager('WeProvideConfigBundle:ConfigVariableChoice');
                $query = $em
                    ->createQueryBuilder('cvc')
                    ->select('cvc')
                    ->from('WeProvideConfigBundle:ConfigVariableChoice', 'cvc')
                    ->join('cvc.configVariable', 'cv')
                    ->where('cv.id = :configVariableId')
                    ->orderBy('cvc.position', 'ASC')
                    ->setParameter('configVariableId', $configVariable->getId());

                $formMapper
                    ->add('configVariableChoice', 'sonata_type_model', array(
                        'query'   => $query,
                        'btn_add' => false,
                        'label'   => 'Value',
                    ));
                break;

            default:
                $formMapper
                    ->add('value');
        }
    }

    /**
     * @param ShowMapper $showMapper
     * @throws \ReflectionException
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('configVariable.name', null, array(
                'label' => 'Name',
            ))
            ->add('configVariable.description', null, array(
                'label' => 'Description',
            ))
            ->add('configVariable.type', 'choice', array(
                'choices' => array_flip(ConfigVariable::getTypes()),
                'label'   => 'Type',
            ))
            ->add('aggregatedValue', null, array(
                'label' => 'Value',
            ));
    }

    /**
     * @param ConfigValue $configValue
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate($configValue)
    {
        parent::postUpdate($configValue);


        // TODO: move to another class, dunno which one but not supposed to be here.
        /** @var EntityManager $em */
        $em           = $this->modelManager->getEntityManager('WeProvideConfigBundle:ConfigValue');
        $repo         = $em->getRepository('WeProvideConfigBundle:ConfigValue');
        $configValues = $repo->findAll();

        $container = $this->getConfigurationPool()->getContainer();
        /** @var Kernel $kernel */
        $kernel       = $container->get('kernel');
        $resourceDir  = $kernel->locateResource('@WeProvideConfigBundle/Resources/config');
        $resourceFile = $resourceDir.'/parameters.yml';

        $yamlDumper = new YamlDumper();
        $yamlDumper->dump($configValues, $resourceFile);


        // Mark cache as invalid if not already.
        if (!$this->invalidCache) {
            $container   = $this->getConfigurationPool()->getContainer();
            $em          = $container->get('doctrine.orm.entity_manager');
            $configCache = new ConfigCache();
            $configCache->setValid(false);
            $em->persist($configCache);
            $em->flush();
        }
    }

    /**
     * @param ConfigValue $configValue
     * @return string
     */
    public function toString($configValue)
    {
        return $configValue->getConfigVariable()->getName();
    }
}
