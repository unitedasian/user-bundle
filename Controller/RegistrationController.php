<?php

namespace UAM\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use UAM\Bundle\UserBundle\Exception\RegistrationDisabledException;

class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        $registration_enabled = $this->container->getParameter('uam_user.registration.enabled');

        if ($registration_enabled) {
            $response = parent::registerAction($request);

            return $response;
        } else {
            throw new RegistrationDisabledException('Registration is currently disabled.');
        }
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        $email = $this->container->get('session')->get('fos_user_send_confirmation_email/email');
        $this->container->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            // [OP 2013-10-14] This is the only change to the original code from FOSUserBundle/Controller/RegistrationController
            // The problem with the original code is that it threw a 404 error if the user refreshed the page. Now it simply
            // redirects to the home page, which is a more natural response to a very likely action by the user.
            return new RedirectResponse(
                $this->generateUrl(
                    $this->container->getParameter('uam_user_home_route')
                ),
                302
            );
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:checkEmail.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $profile_enabled = $this->container->getParameter('uam_user.profile.enabled');

        if ($profile_enabled) {
            if ($user->getProfile() && count($this->container->get('validator')->validate($user->getProfile())) == 0) {
                $url = $this->generateUrl('fos_user_profile_show');

                $response = new RedirectResponse($url);

                return $response;
            }

            $formFactory = $this->container->get('fos_user.profile.form.factory');

            $form = $formFactory->createForm();
            $form->setData($user);

            return $this->container->get('templating')->renderResponse(
                'FOSUserBundle:Registration:confirmed.html.twig',
                array(
                    'user' => $user,
                    'form' => $form->createView(),
                    'profile_enabled' => $profile_enabled,
                    'success_url' => $this->generateUrl(
                        $this->container->getParameter('uam_user_home_route')
                    ),
                )
            );
        } else {
            return new RedirectResponse(
                $this->generateUrl(
                    $this->container->getParameter('uam_user_home_route')
                ),
                302
            );
        }

    }
}
