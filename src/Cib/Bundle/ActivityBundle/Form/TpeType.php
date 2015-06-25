<?php

namespace Cib\Bundle\ActivityBundle\Form;

use Cib\Bundle\ActivityBundle\Entity\tpeParameters;
use Cib\Bundle\CustomerBundle\Form\LogoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TpeType extends AbstractType
{

    private $store;

    private $tpeParameters;
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
                    'maxlength' => 8,
                    'placeholder' => 'numéro de TPE',
                )
            ))
            ->add('store','entity',array(
                'class' => 'CibActivityBundle:Store',
                'property' => 'storeName',
                'data' => $this->store,
                'label' => 'Magasin',
            ))
//            ->add('cib_bundle_activitybundle_tpeparameters',new tpeParametersType());
            ->add('tpeParameters',new tpeParametersType($this->tpeParameters),array(
                'required' => false,
                )
            )
            ->add('logo', new LogoType(), array(
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
            'data_class' => 'Cib\Bundle\ActivityBundle\Entity\Tpe',
            'cascade_validation' => true,
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
        if($arrayOtpions['tpeParameters'])
            $this->tpeParameters = $arrayOtpions['tpeParameters'];
        else
            $this->tpeParameters = new tpeParameters();
    }
}
