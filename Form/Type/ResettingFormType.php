<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UAM\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ResettingFormType as BaseType;

use Symfony\Component\Form\FormBuilderInterface;

class ResettingFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array(
                'label' => 'form.new_password',
                'attr' => array(
                    'class' => 'input-block-level'
                )
            ),
            'second_options' => array(
                'label' => 'form.new_password_confirmation',
                'attr' => array(
                    'class' => 'input-block-level'
                )
            ),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }
}
