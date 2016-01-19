<?php

namespace UAM\Bundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Redirects to the "fos_user_profile_edit" route after the user profile has been successfully updated.
 */
class ChangePasswordSuccessListener implements EventSubscriberInterface
{
    protected $router;
    protected $session;

    public function __construct(Router $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordSuccess',
        );
    }

    public function onChangePasswordSuccess(FormEvent $event)
    {
        if ($event->getRequest()->isXmlHttpRequest()) {
            $response = new RedirectResponse($this->router->generate('fos_user_change_password'));
            $event->setResponse($response);
        }
    }
}
