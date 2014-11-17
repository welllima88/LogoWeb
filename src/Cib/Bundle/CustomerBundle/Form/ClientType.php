<?php

namespace Cib\Bundle\CustomerBundle\Form;

use Cib\Bundle\ActivityBundle\Form\ClubType;
use Cib\Bundle\CustomerBundle\Entity\bankAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientType extends AbstractType
{

    private $year;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientNumber','text',array(
                'label' => 'Numéro',
            ))
            ->add('clientName','text',array(
                'label' => 'Nom',
            ))
            ->add('clientFirstName','text',array(
                'label' => 'Prénom',
            ))
            ->add('clientGender','choice',array(
                'label' => 'Sexe',
                'choices' => array(
                    'm' => 'Homme',
                    'f' => 'Femme'
                )
            ))
            ->add('clientCivility','choice',array(
                'label' => 'Civilité',
                'choices' => array(
                    'M' => 'M.',
                    'Mme' => 'Mme',
                    'Mlle' => 'Mlle',
                ),
            ))
            ->add('clientBirthDate','birthday',array(
                'label' => 'Date de naissance',

            ))
            ->add('clientAgeFfg','text',array(
                'label' => 'Age(complément)',
            ))
            ->add('clientAddress','text',array(
                'label' => 'Adresse',
            ))
            ->add('clientZipCode','text',array(
                'label' => 'Code Postal',
                'attr' => array(
                    'maxlength' => 5,
                )
            ))
            ->add('clientCity','text',array(
                'label' => 'Ville',
            ))
            ->add('homePhone','text',array(
                'label' => 'Tel. fixe',
                'attr' => array(
                    'maxlength' => 15,
                )
            ))
            ->add('cellPhone','text',array(
                'label' => 'Tel. mobile',
                'attr' => array(
                    'maxlength' => 15,
                )
            ))
            ->add('officePhone','text',array(
                'label' => 'Tel. bureau',
                'attr' => array(
                    'maxlength' => 15,
                )
            ))
            ->add('mailAddress','email',array(
                'label' => 'Mail',
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
            ->add('bankAccount',new bankAccountType($this))
            ->add('club','entity',array(
                'class' => 'CibActivityBundle:Club',
                'property' => 'clubName',
            ))
            ->add('clientLicense','text',array(
                'label' => 'Num. licence',
            ))
            ->add('registerPrice','entity',array(
                'label' => 'Tarif adhesion',
                'class' => 'CibActivityBundle:registerPrice',
                'property' => 'priceLabel',
            ))
            ->add('licensePrice','entity',array(
                'label' => 'Tarif licence',
                'class' => 'CibActivityBundle:licensePrice',
                'property' => 'priceLabel',
            ))
            ->add('checkPayYear','checkbox',array(
                'label' => 'Paiement annuel ('.date('Y',$this->date).')',
                'required' => false,
            ))
            ->add('checkCivility','checkbox',array(
                'label' => 'Etat civil',
                'mapped' => false,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Cochez la case Etat-Civil pour signifier que vous avez terminé la modification des informations concernant l\'Etat Civil du client'
                    )),
                ),
            ))
            ->add('checkBankAccount','checkbox',array(
                'label' => 'Bancaire',
                'mapped' => false,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Cochez la case Bancaire pour signifier que vous avez terminé la modification des informations bancaires du client'
                    )),
                )
            ))
            ->add('checkClub','checkbox',array(
                'label' => 'Club',
                'mapped' => false,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Cochez la case Club pour signifier que vous avez terminé la modification des informations relatives au club du client'
                    )),
                )
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

    public function __construct()
    {
        $this->date = strtotime('+1 year');
    }

}
