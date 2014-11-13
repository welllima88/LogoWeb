<?php

namespace Cib\Bundle\CustomerBundle\Form;

use Cib\Bundle\CustomerBundle\Entity\bankAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class bankAccountType extends AbstractType
{

    /**
     * @var bankAccount
     *
     */
    public $bank ;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rum','text',array(
                'label' => 'RUM',
            ))
            ->add('debtorName','text',array(
                'label' => 'Nom',
            ))
            ->add('debtorAddress','text',array(
                'label' => 'Adresse',
            ))
            ->add('debtorZipCode','text',array(
                'label' => 'Code Postal',
                'attr' => array(
                    'maxlength' => 5,
                ),
            ))
            ->add('debtorCity','text',array(
                'label' => 'Ville',
            ))
            ->add('debtorCountry','choice',array(
                'label' => 'Pays',
                'choices' => array(
                    'France' => 'France'
                ),
                'data' => 'FR',
            ))
            ->add('creditorCode','text',array(
                'label' => 'Identifiant créancier SEPA :',
            ))
            ->add('creditorName','text',array(
                'label' => 'Nom',
            ))
            ->add('creditorAddress','text',array(
                'label' => 'Adresse',
            ))
            ->add('creditorZipCode','text',array(
                'label' => 'Code Postal',
                'attr' => array(
                    'maxlength' => 5,
                ),
            ))
            ->add('creditorCity','text',array(
                'label' => 'Ville',
            ))
            ->add('creditorCountry','choice',array(
                'label' => 'Pays',
                'choices' => array(
                    'France' => 'France',
                ),
            ))
            ->add('iban','text',array(
                'label' => 'IBAN',
            ))
            ->add('bic','text',array(
                'label' => 'BIC',
            ))
            ->add('dateSign','date',array(
                'label' => 'le : ',
            ))
            ->add('placeSign','text',array(
                'label' => 'A :',
            ))
            ->add('comment','textarea',array(
                'label' => 'commentaires',
                'attr' => array(
                    'rows' => 4,
                    'cols' => 100,
                )
            ))
            ->add('frequency','choice',array(
                'label' => 'Paiement :',
                'choices' => array(
                    'Récurrent' => 'Récurrent',
                    'Ponctuel' => 'Ponctuel',
                ),
                'multiple' => false,
                'expanded' => true,
                'data' => 'Récurrent',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\CustomerBundle\Entity\bankAccount',
//            'empty_value' => new bankAccount(),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bankAccount';
    }
}
