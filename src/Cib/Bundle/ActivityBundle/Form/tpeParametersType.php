<?php

namespace Cib\Bundle\ActivityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class tpeParametersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ftpHost','text',array(
                'label' => 'adresse ftp',
            ))
            ->add('ftpPort','text',array(
                'label' => 'port',
                'attr' => array(
                    'maxlength' => 5
                )
            ))
            ->add('ftpLogin','text',array(
                'label' => 'login',
            ))
            ->add('ftpPassword','text',array(
                'label' => 'mot de passe'
            ))
            ->add('ftpMode','choice',array(
                'label' => 'mode',
                'choices' => array(
                    'P' => 'Passif',
                    'A' => 'Actif'
                ),
                'preferred_choices' => array(
                    'P',
                ),
            ))
            ->add('typeConnexion','choice',array(
                'label' => 'type de connexion',
                'choices' => array(
                    'I' => 'Ip/Ethernet',
                    'G' => 'GPRS',
                ),
                'expanded' => true,
                'multiple' => false,
                'preferred_choices' => array(
                    'I',
                )
            ))
            ->add('apnGprs','text',array(
                'label' => 'APN Gprs'
            ))
            ->add('loginGprs','text', array(
                'label' => 'login'
            ))
            ->add('passwordGprs','text',array(
                'label' => 'mot de passe',
            ))
/*            ->add('urlSoap','text',array(
                'label' => 'Url SOAP',
            ))
            ->add('portSoap','text',array(
                'label' => 'Port SOAP'
            ))*/
/*            ->add('header1','text',array(
                'label' => 'ligne 1',
                'attr' => array(
                    'maxlength' => 12,
                )
            ))
            ->add('header2','text',array(
                'label' => 'ligne 2',
                'attr' => array(
                    'maxlength' => 12,
                )
            ))
            ->add('header3','text',array(
                'label' => 'ligne 3',
                'attr' => array(
                    'maxlength' => 25,
                )
            ))
            ->add('header4','text',array(
                'label' => 'ligne 4',
                'attr' => array(
                    'maxlength' => 25,
                )
            ))
            ->add('header5','text',array(
                'label' => 'ligne 5',
                'attr' => array(
                    'maxlength' => 25
                )
            ))
            ->add('footer1','text',array(
                'label' => 'ligne 1',
                'attr' => array(
                    'maxlength' => 12
                )
            ))
            ->add('footer2','text',array(
                'label' => 'ligne 2',
                'attr' => array(
                    'maxlength' => 12
                )
            ))
            ->add('footer3','text',array(
                'label' => 'ligne 3',
                'attr' => array(
                    'maxlength' => 25
                )
            ))
            ->add('footer4','text',array(
                'label' => 'ligne 4',
                'attr' => array(
                    'maxlength' => 25
                )
            ))
            ->add('footer5','text',array(
                'label' => 'ligne 5',
                'attr' => array(
                    'maxlength' => 25
                )
            ))*/
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\ActivityBundle\Entity\tpeParameters'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tpeParameters';
    }
}
