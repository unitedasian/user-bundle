<?php

namespace UAM\Bundle\UserBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use UAM\Bundle\UserBundle\Model\Profile;

class UserProfileType extends BaseAbstractType
{
    protected $class;

    protected $profile_type;

    public function __construct($class, $profile_type)
    {
        $this->class = $class;
        $this->profile_type = $profile_type;
    }

    public function getName()
    {
        return 'fos_user_profile';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'translation_domain' => 'FOSUserBundle',
            'intention'  => 'user_profile',
            'cascade_validation' => true
        ));
    }

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'email', array(
            'label' => 'form.username',
            'attr' => array(
                'class' => 'input-block-level'
            )
        ));

        $builder->add('profile', $this->profile_type, array(
            'label' => false
        ));
    }
}
