<?php

namespace Cib\Bundle\DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EncloseType extends AbstractType
{

    private $store;

    private $dateStart;

    private $dateStop;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateStopEnclose','text',array(
                'label' => 'Date de cloture',
//                'widget' => 'choice',
            ))
//            ->add('store','choice',array(
//                'choices' => array(
//                    'choices' => $this->store,
//                ),
//                'multiple' => true,
//                'expanded' => true,
//            ))
            ->add('store','entity',array(
                'class' => 'CibActivityBundle:Store',
                'attr' => array(
                    'size' => 10,
                    'class' => 'test',
                ),
                'multiple' => true,
                'property' => 'storeName',
                'mapped' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\DataBundle\Entity\Enclose'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_databundle_enclose';
    }

    public function __construct($store)
    {
        $this->store = $store;
    }
}
