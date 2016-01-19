<?php

namespace UAM\Bundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdateUserEmailListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => array(
                'onRegistrationSuccess',
                255
            )
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();

        $this->updateUserEmail($user);
    }

    protected function updateUserEmail(UserInterface $user)
    {
        $user->setEmail($user->getUsername());
    }
}
