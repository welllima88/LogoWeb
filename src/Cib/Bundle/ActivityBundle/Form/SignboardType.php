<?php

namespace Cib\Bundle\ActivityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class SignboardType extends AbstractType
{

    private $securityContext;

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
                'attr' => array(
                    'maxlength' => 4,
                )
            ))
            ->add('user', 'entity', array(
                'class' => 'CibUserBundle:User',
                'property' => 'username',
                'label' => 'Utilisateur',
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


    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_activitybundle_signboard';
    }
}
