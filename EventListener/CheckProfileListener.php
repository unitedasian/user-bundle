<?php

namespace UAM\Bundle\UserBundle\EventListener;

use Symfony\Bundle\AsseticBundle\Controller\AsseticController;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Validator\ValidatorInterface;

use UAM\Bundle\UserBundle\Exception\ProfileNotAvailableException;

class CheckProfileListener
{
    protected $utils;
    protected $context;

    public function __construct(ValidatorInterface $validator, HttpUtils $utils, SecurityContext $context)
    {
        $this->validator = $validator;
        $this->utils = $utils;
        $this->context = $context;
    }

    public function onFilterController(FilterControllerEvent $event)
    {
        if ($this->context->getToken() && $this->context->isGranted('ROLE_USER')) {

            $controller = $event->getController();

            if (!is_array($controller)) {
                return;
            }

            if ($controller[0] instanceof AsseticController ||
                $controller[0] instanceof ProfilerController) {
                return;
            }

            $request = $event->getRequest();

            if ($this->utils->checkRequestPath($request, 'fos_user_registration_confirmed') ||
                    $this->utils->checkRequestPath($request, 'fos_user_profile_edit') ||
                    $request->attributes->get('_controller') == 'FOSUserBundle:Profile:edit') {
                return;
            }

            $user = $this->context->getToken()->getUser();

            if (!$user->getProfile() || count($this->validator->validate($user->getProfile())) > 0) {
                throw new ProfileNotAvailableException('You need to fill in your profile.');
            }
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!($exception instanceof ProfileNotAvailableException)) {
            return;
        }

        $response = $this->utils->createRedirectResponse($event->getRequest(), 'fos_user_registration_confirmed');

        $event->setResponse($response);
    }
}
