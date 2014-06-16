<?php

namespace Cib\Bundle\ActivityBundle\Form;

use Cib\Bundle\ActivityBundle\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreType extends AbstractType
{

    private $signboard;

     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('storeName','text',array(
                'label' => 'Nom Magasin',
                'attr' => array(
                    'maxlength' => 50,
                ),
            ))
            ->add('storeAddress','text',array(
                'label' => 'Adresse du magasin',
                'attr' => array(
                    'maxlength' => 50,
                )
            ))
            ->add('storeZipCode','text',array(
                'label' => 'Code postal',
                'attr' => array(
                    'maxlength' => 5,
                )
            ))
            ->add('storeCity','text',array(
                'label' => 'Ville',
                'attr' => array(
                    'maxlength' => 50,
                )
            ))
            ->add('storePhone','text',array(
                'label' => 'Tel :',
                'attr' => array(
                    'maxlength' => 15,
                )
            ))
            ->add('signboard','entity',array(
                'label' => 'enseigne',
                'class' => 'CibActivityBundle:Signboard',
                'property' => 'signboardName',
                'data' => $this->signboard,
            ))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\ActivityBundle\Entity\Store',
            'empty_value' => new Store($this->signboard)
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_activitybundle_store';
    }


    public function __construct(array $arrayOtpions = null)
    {
        if($arrayOtpions['signboard'] != null)
            $this->signboard = $arrayOtpions['signboard'];
        else
            $this->signboard = '';



    }
}
