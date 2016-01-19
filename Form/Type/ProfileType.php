<?php

namespace UAM\Bundle\UserBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends BaseAbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function getName()
    {
        return 'uam_user_profile';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'translation_domain' => 'FOSUserBundle',
            'intention' => 'profile',
        ));
    }

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('gender', 'choice', array(
            'required' => true,
            'choices' => array(
                0 => 'profile.edit.gender.female',
                1 => 'profile.edit.gender.male'
            ),
            'expanded' => true,
            'label_attr' => array(
                'class' => 'radio-inline'
            ),
            'label' => 'profile.edit.gender.label'
        ));

        $builder->add('given_names', 'text', array(
            'label' => 'form.given_names',
            'required' => false,
            'attr' => array(
                'class' => 'input-block-level'
            )
        ));

        $builder->add('surname', 'text', array(
            'label' => 'form.surname',
            'required' => true,
            'attr' => array(
                'class' => 'input-block-level'
            )
        ));
    }
}
