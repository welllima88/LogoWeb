<?php

namespace Cib\Bundle\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CardType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cardNumber','text',array(
                'label' => 'numéro de carte',
                'required' => false,
                'attr' => array(
                    'maxlength' => 10
                )
            ))
            ->add('cardValidity','date',array(
                'label' => 'date de validité',
                'data' => new \DateTime('+1 year'),

            ))
//            ->add('Pme1')
//            ->add('Pme2')
//            ->add('Pme3')
//            ->add('Pme4')
//            ->add('Pme5')
//            ->add('moneyAmount1')
//            ->add('unitAmount1')
//            ->add('moneyAmount2')
//            ->add('unitAmount2')
//            ->add('moneyAmount3')
//            ->add('unitAmount3')
//            ->add('moneyAmount4')
//            ->add('unitAmount4')
//            ->add('moneyAmount5')
//            ->add('unitAmount5')
            ->add('isActive','checkbox',array(
                'label' => 'carte active',
                'required' => false,
            ))
            ->add('signboard','entity',array(
                'class' => 'CibActivityBundle:Signboard',
                'property' => 'signboardName',
                'label' => 'enseigne',
            ))
            ->add('client','entity',array(
                'class' => 'CibCustomerBundle:Client',
                'property' => 'clientNumber',
                'label' => 'numéro client',
                'required' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\CustomerBundle\Entity\Card'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_customerbundle_card';
    }
}
