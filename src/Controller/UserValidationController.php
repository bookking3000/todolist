<?php


namespace App\Controller;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

class UserValidationController extends AbstractController
{

    /**
     * @Route("/validate/user/{id}", name="user_validation")
     * @param User $user
     * @return RedirectResponse|Response
     */
    public function validate(User $user)
    {
        $user->setIsValidated(true);
        $user->addRole('ROLE_VALIDATED_USER');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new RedirectResponse('/');
    }

    /**
     * @Route("/validate/user/resendValidationMail/{id}", name="user_validation_resend_mail")
     * @param User $user
     * @param Swift_Mailer $mailer
     * @return RedirectResponse|Response
     */
    public function resendMail(User $user, Swift_Mailer $mailer)
    {
        $this->sendRegistrationMail($mailer, $user);

        return $this->render(
            'user_validation/validation_unconfirmed_resend_mail.html.twig',
            [
                'userID' => $user->getId(),
            ]
        );
    }

    /**
     * @Route("/validate/user/checkIfValid/{id}", name="check_user_validation")
     * @param User $user
     * @return int|RedirectResponse|Response
     * @noinspection PhpUnused
     */
    public function checkValidation(User $user)
    {
        $userIsValid = $user->getIsValidated();

        if ($userIsValid) {
            return new RedirectResponse("/todo/own");
        } else {
            return $this->render(
                'user_validation/validation_unconfirmed.html.twig',
                [
                    'userID' => $user->getId(),
                ]
            );
        }
    }

    /**
     * @param Swift_Mailer $mailer
     * @param $user User
     */
    public function sendRegistrationMail(Swift_Mailer $mailer, $user)
    {
        $message = (new Swift_Message('Registrierung auf TodoList'))
            ->setFrom('todolist@localhost')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/registration.html.twig',
                    [
                        'name' => $user->getUsername(),
                        'userID' => $user->getId(),
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);
    }
}