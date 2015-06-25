<?php

namespace Cib\Bundle\DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TelecollecteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date','text',array(
                'label' => 'DATE'
            ))
//            ->add('pathFile')
//            ->add('tpe','entity',array(
//                'label'
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
            'data_class' => 'Cib\Bundle\DataBundle\Entity\Telecollecte'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_databundle_telecollecte';
    }
}
