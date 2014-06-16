<?php

namespace Cib\Bundle\ActivityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignboardType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('signboardName','text',array(
                'label' => 'Enseigne',
            ))
            ->add('signboardNumber','text',array(
                'label' => 'numéro enseigne',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\ActivityBundle\Entity\Signboard'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_activitybundle_signboard';
    }
}
