<?php

namespace Cib\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    private $rolesChoices;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('username');

//        $builder->add('roles', 'choice', array(
//            'choices' => array(
//                'ROLE_ADMIN' => 'Admin',
//                'ROLE_USER' => 'Utilisateur',
//            ),
//            'required'    => false,
//            'label' => false,
//            'empty_value' => 'Choisir le role',
//            'empty_data'  => null
//        ));
        $builder->add('roles','choice',array(
            'choices' => array(
                'choices' => $this->rolesChoices,
            ),
            'multiple' => true,
            'expanded' => true,
//            'mapped' => false,
        ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cib\Bundle\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cib_bundle_userbundle_user';
    }

    public function __construct($rolesChoices)
    {
        $this->rolesChoices = $rolesChoices;
    }
}
