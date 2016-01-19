<?php

namespace UAM\Bundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CreateUserProfileListener implements EventSubscriberInterface
{
    protected $profile_class;

    public function __construct($profile_class)
    {
        $this->profile_class = $profile_class;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => array(
                'onRegistrationSuccess',
                254
            )
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();

        $locale = $event->getRequest()->getLocale();

        $this->initProfile($user, $locale);
    }

    protected function initProfile(UserInterface $user, $locale)
    {
        $profile_class = $this->getProfileClass();

        $profile = new $profile_class();

        $profile->setUser($user);

        $profile->init(
            array(
                'locale' => $locale
            )
        );
    }

    protected function getProfileClass()
    {
        return $this->profile_class;
    }

}
