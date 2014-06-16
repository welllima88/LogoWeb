<?php

namespace Cib\Bundle\ActivityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TpeType extends AbstractType
{

    private $store;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tpeNumber','text',array(
                'label' => 'numéro de tpe',
                'attr' => array(
                    'maxlength' => 8
                )
            ))
            ->add('store','entity',array(
                'class' => 'CibActivityBundle:Store',
                'property' => 'storeName',
                'data' => $this->store,
                'label' => 'Magasin',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\ActivityBundle\Entity\Tpe'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_activitybundle_tpe';
    }

    public function __construct(array $arrayOtpions = null)
    {
            $this->store = $arrayOtpions['store'];
    }
}
