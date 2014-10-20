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
            ->add('isPme1','checkbox',array(
                'label' => 'PME1 actif',
                'required' => false,
            ))
            ->add('isPme1Unit','choice',array(
                'choices' => array(
                    'M' => 'monetaire'
                ),
                'label' => 'Type PME',
            ))
            ->add('isPme2','checkbox',array(
                'label' => 'PME2 actif',
                'required' => false,
            ))
            ->add('isPme2Unit','choice',array(
                'choices' => array(
                    'M' => 'monetaire',
                    'U' => 'unitaire',
                ),
                'label' => 'Type PME',
            ))
            ->add('isPme3','checkbox',array(
                'label' => 'PME3 actif',
                'required' => false,
            ))
            ->add('isPme3Unit','choice',array(
                'choices' => array(
                    'M' => 'monetaire',
                    'U' => 'unitaire',
                ),
                'label' => 'Type PME',
            ))
            ->add('isPme4','checkbox',array(
                'label' => 'PME4 actif',
                'required' => false,
            ))
            ->add('isPme4Unit','choice',array(
                'choices' => array(
                    'M' => 'monetaire',
                    'U' => 'unitaire',
                ),
                'label' => 'Type PME',
            ))
            ->add('isPme5','checkbox',array(
                'label' => 'PME5 actif',
                'required' => false,
            ))
            ->add('isPme5Unit','choice',array(
                'choices' => array(
                    'U' => 'unitaire',
                ),
                'label' => 'Type PME',
            ))
            ->add('isPrime1','checkbox',array(
                'label' => 'prime 1 active',
                'required' => false,
            ))
            ->add('typePrime1','choice',array(
                'label' => 'type de prime',
                'choices' => array(
                    'M' => 'monetaire',
                    'P' => 'pourcentage'
                )
            ))
            ->add('amountPrime1','text',array(
                'label' => 'montant de la prime',
            ))
            ->add('levelPrime1','text',array(
                'label' => 'palier de la prime',
            ))
            ->add('isPrime2','checkbox',array(
                'label' => 'prime 2 active',
                'required' => false,
            ))
            ->add('typePrime2','choice',array(
                'label' => 'type de prime',
                'choices' => array(
                    'M' => 'monetaire',
                    'P' => 'pourcentage',
                    'U' => 'unitaire',
                )
            ))
            ->add('amountPrime2','text',array(
                'label' => 'montant de la prime',
            ))
            ->add('levelPrime2','text',array(
                'label' => 'palier de la prime',
            ))
            ->add('isPrime3','checkbox',array(
                'label' => 'prime 3 active',
                'required' => false,
            ))
            ->add('typePrime3','choice',array(
                'label' => 'type de prime',
                'choices' => array(
                    'M' => 'monetaire',
                    'P' => 'pourcentage',
                    'U' => 'unitaire',
                )
            ))
            ->add('amountPrime3','text',array(
                'label' => 'montant de la prime',
            ))
            ->add('levelPrime3','text',array(
                'label' => 'palier de la prime',
            ))
            ->add('isPrime4','checkbox',array(
                'label' => 'prime 4 active',
                'required' => false,
            ))
            ->add('typePrime4','choice',array(
                'label' => 'type de prime',
                'choices' => array(
                    'M' => 'monetaire',
                    'P' => 'pourcentage',
                    'U' => 'unitaire',
                )
            ))
            ->add('amountPrime4','text',array(
                'label' => 'montant de la prime',
            ))
            ->add('levelPrime4','text',array(
                'label' => 'palier de la prime',
            ))
            ->add('isPrime5','checkbox',array(
                'label' => 'prime 5 active',
                'required' => false,
            ))
            ->add('typePrime5','choice',array(
                'label' => 'type de prime',
                'choices' => array(
                    'U' => 'unitaire',
                )
            ))
            ->add('amountPrime5','text',array(
                'label' => 'montant de la prime',
            ))
            ->add('levelPrime5','text',array(
                'label' => 'palier de la prime',
            ))
            ->add('urlSoap','text',array(
                'label' => 'Url SOAP',
            ))
            ->add('portSoap','text',array(
                'label' => 'Port SOAP'
            ))
            ->add('header1','text',array(
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
            ))
        ->add('minPurchase','money',array(
            'label' => 'Montant minimum de chargement',
        ))
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
