<?php

namespace UAM\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class UAMUserExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('uam_user_home_route', $config['home_route']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));

        if (!empty($config['profile'])) {
            $this->loadProfile($config['profile'], $container, $loader);
        }

        if (!empty($config['registration'])) {
            $this->loadRegistration($config['registration'], $container, $loader);
        }

        $loader->load('change_password.yml');
        $loader->load('resetting.yml');
        $loader->load('twig.yml');

//        $container->setParameter('uam_user.resetting.email.template', $config['resetting']['email']['template']);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        if (true === isset($bundles['FOSUserBundle'])) {
            $this->configureFOSUserBundle($container);
        }
    }

    /**
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    protected function configureFOSUserBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig(
            'fos_user',
            array(
                'registration' => array(
                    'form'  => array(
                        'type' => 'uam_user_registration',
                        'validation_groups' => array('Register')
                    )
                ),
                'resetting' => array(
                    'email' => array(
                        'template' => 'UAMUserBundle:Resetting:email.html.twig'
                    ),
                    'token_ttl' => 60*5
                )
            )
        );
    }

    private function loadProfile(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        if ($config['enabled'] === false) {
            $container->setParameter('uam_user.profile.enabled', false);

            return;
        }

        if (!isset($config['class'])) {
             throw new InvalidConfigurationException(
                'The "uam_user.profile.class" configuration option must be set or "uam_user.profile.enabled" must be set to false.'
            );
        }

        $loader->load('profile.yml');

        $container->setParameter('uam_user.profile.enabled', true);
        $container->setParameter('uam_user.profile.class', $config['class']);
        $container->setParameter('uam_user.profile.form.type', $config['form']['type']);
        $container->setParameter('uam_user.profile.form.validation_groups', $config['form']['validation_groups']);
    }

    private function loadRegistration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setParameter('uam_user.registration.enabled', $config['enabled']);

        if ($config['enabled'] === true) {
            $container->setParameter('uam_user.registration.form.validation_groups', $config['form']['validation_groups']);
            $loader->load('registration.yml');
        }
    }
}
