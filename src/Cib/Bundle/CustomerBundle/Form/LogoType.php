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
            ->add('intitule1', 'text', array(
                'label' => 'Ligne 1',
                'max_length' => "21",
            ))
            ->add('intitule2', 'text', array(
                'label' => 'Ligne 2',
                'max_length' => "21",
            ))
            ->add('intitule3', 'text', array(
                'label' => 'Ligne 3',
                'max_length' => "21",
            ))
            ->add('intitule4', 'text', array(
                'label' => 'Ligne 4',
                'max_length' => "21",
            ))
            ->add('intitule5', 'text', array(
                'label' => 'Ligne 5',
                'max_length' => "21",
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
