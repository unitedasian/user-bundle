<?php

namespace UAM\Bundle\UserBundle\Twig\Extension;

use Twig_Extension;
use Twig_SimpleFunction;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SignupExtension extends Twig_Extension
{
    protected $container;

    /**
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'signup_enabled' => new Twig_SimpleFunction(
                'signup_enabled',
                array($this, 'isSignupEnabled'),
                array('is_safe' => array('html'))
            )
        );
    }

    public function isSignupEnabled()
    {
        return $this->container->getParameter('uam_user.registration.enabled');
    }

    /**
     *
     * @return string The name of the extension
     */
    public function getName()
    {
        return 'signup_enabled';
    }
}
