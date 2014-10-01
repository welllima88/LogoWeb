<?php

namespace Cib\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParametersType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('Pme1','checkbox',array(
//                'label' => 'Pme 1',
//            ))
//            ->add('Pme2','checkbox',array(
//                'label' => 'Pme 2',
//            ))
//            ->add('Pme3','checkbox',array(
//                'label' => 'Pme 3',
//            ))
//            ->add('Pme4','checkbox',array(
//                'label' => 'Pme 4',
//            ))
//            ->add('Pme5','checkbox',array(
//                'label' => 'Pme 5',
//            ))
            ->add('ftpUrl','text',array(
                'label' => 'url ftp'
            ))
            ->add('ftpPort','text',array(
                'label' => 'port ftp',
                'attr' => array(
                    'maxlength' => 5,
                )
            ))
            ->add('ftpUser','text',array(
                'label' => 'login'
            ))
            ->add('ftpPassword','text',array(
                'label' => 'mot de passe'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\CoreBundle\Entity\Parameters'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_corebundle_parameters';
    }
}
