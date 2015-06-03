<?php

namespace Cib\Bundle\CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LogoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logoName', 'text', array(
                'label' => 'Nom du Logo',
            ))
            ->add('logoTypeTPE', 'choice' , array(
                'label' => 'Type de TPE',
                'choices' => array(
                    'ICT220' => 'ICT 220',
                    'IWL250' => 'IWL 250',
                    'EFT930' => 'EFT 930',
                    'ICT250' => 'ICT 250',
                )
            ))
            ->add('logoGoal', 'checkbox', array(
                'label' => 'Mode Goal',

            ))
            ->add('societyName', 'text', array(
                'label' => 'Nom de la Societe',
            ))
            ->add('societyAddress', 'text', array(
                'label' => 'Adresse de la Societe'
            ))
            ->add('societyTel', 'text', array(
                'label' => 'Numéro de telephone de la Societe'
            ))
            ->add('societyWebAddr', 'text', array(
                'label' => 'Site internet de l\'entreprise'
            ))
            ->add('societyCity', 'text', array(
                'label' => 'Ville'
            ))
            ->add('societyCp', 'text', array(
                'label' => 'Code Postal'
            ))
            ->add('logoTopTicket', 'file', array(
                'label'     => 'Logo Ticket',
            ))
            ->add('logoWallpaper', 'file', array(
                'label'     => 'Fond Ecran'
            ))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\CustomerBundle\Entity\Logo',
            'cascade_validation' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'logo';
    }
}
