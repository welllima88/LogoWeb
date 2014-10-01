<?php

namespace Cib\Bundle\DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResultsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('month','choice',array(
                'choices' => array(
                    '01' => 'Janvier',
                    '02' => 'Février',
                    '03' => 'Mars',
                    '04' => 'Avril',
                    '05' => 'Mai',
                    '06' => 'Juin',
                    '07' => 'Juillet',
                    '08' => 'Aout',
                    '09' => 'Septembre',
                    '10' => 'Octobre',
                    '11' => 'Novembre',
                    '12' => 'Decembre'
                ),
                'required' => false,
//                'attr' => array(
//                    'id' => 'month',
//                )
            ))
            ->add('dateStart','text',array(
//                'attr' => array(
//                    'id' => 'dateStart',
//                    'class' => 'datepicker'
//                )
            ))
            ->add('dateStop','text',array(
//                'attr' => array(
//                    'id' => 'dateStop',
//                    'class' => 'datepicker'
//                )
            ))
            ->add('card','text',array(
                'attr' => array(
                    'maxlength' => 10,
                )
            ))
            ->add('client','text',array(
//                'attr' => array(
//                    'id' => 'client',
//                )
            ))
            ->add('store','entity',array(
                'class' => 'CibActivityBundle:Store',
                'property' => 'storeName',
                'empty_value' => '',
//                'attr' => array(
////                    'id' => 'store'
//                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\DataBundle\Entity\Results'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_databundle_results';
    }
}
