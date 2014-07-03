<?php

namespace Cib\Bundle\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientNumber','text',array(
                'label' => 'numéro',
            ))
            ->add('clientName','text',array(
                'label' => 'nom',
            ))
            ->add('clientFirstName','text',array(
                'label' => 'prénom',
            ))
            ->add('clientGender','choice',array(
                'label' => 'sexe',
                'choices' => array(
                    'm' => 'Homme',
                    'f' => 'Femme'
                )
            ))
            ->add('clientBirthDate','birthday',array(
                'label' => 'date de naissance',

            ))
            ->add('clientAddress','text',array(
                'label' => 'adresse',
            ))
            ->add('clientZipCode','text',array(
                'label' => 'Code Postal',
                'attr' => array(
                    'maxlength' => 5,
                )
            ))
            ->add('clientCity','text',array(
                'label' => 'ville',
            ))
            ->add('homePhone','text',array(
                'label' => 'tel. fixe',
                'attr' => array(
                    'maxlength' => 15,
                )
            ))
            ->add('cellPhone','text',array(
                'label' => 'tel. mobile',
                'attr' => array(
                    'maxlength' => 15,
                )
            ))
            ->add('officePhone','text',array(
                'label' => 'tel. bureau',
                'attr' => array(
                    'maxlength' => 15,
                )
            ))
            ->add('mailAddress','email',array(
                'label' => 'mail',
            ))
            ->add('pictureFile','file',array(
                'label' => 'Photo',
            ))
            ->add('card','collection',array(
                'type' => new CardType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'label' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\CustomerBundle\Entity\Client',
            'cascade_validation' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_customerbundle_client';
    }
}
