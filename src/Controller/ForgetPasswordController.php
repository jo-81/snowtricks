<?php

namespace App\Controller;

use App\Entity\User;
use App\EventSubscriber\ResetPasswordEventSubscriber;
use App\Service\Password\ForgetPasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgetPasswordController extends AbstractController
{
    #[Route('/forget-password', name: 'user.forget.password')]
    public function index(
        Request $request,
        ForgetPasswordService $forgetPasswordService,
        EventDispatcherInterface $dispatcher
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        if ('POST' == $request->getMethod()) {
            /** @var string $submittedToken */
            $submittedToken = $request->request->get('_csrf_token');
            if (!$this->isCsrfTokenValid('forget_password', $submittedToken)) {
                return $this->redirectToRoute('user.forget.password');
            }

            /** @var string $email */
            $email = $request->request->get('email');
            $user = $forgetPasswordService->existUserByEmail($email);

            if (!$user instanceof User) {
                $this->addFlash('danger', 'Cette adresse email n\'existe pas');

                return $this->redirectToRoute('user.forget.password');
            }

            $resetPassword = $forgetPasswordService->get($user);

            // Aucune demande d'enregistré
            if (is_null($resetPassword)) {
                $newResetPassword = $forgetPasswordService->persist($user);
                $registrationEvent = new ResetPasswordEventSubscriber($newResetPassword);
                $dispatcher->dispatch($registrationEvent, ResetPasswordEventSubscriber::FORGET_PASSWORD);

                $this->addFlash('success', 'Votre demande à bien été enregistrée');

                return $this->redirectToRoute('homepage');
            }

            // Si une demande existe et que la date n'est pas expiré
            if (!$forgetPasswordService->isExpired($resetPassword)) {
                $this->addFlash('info', 'Une demande existe déjà pour cette adresse email');

                return $this->redirectToRoute('user.forget.password');
            }

            // Modification des dates de création et expiration
            $forgetPasswordService->update($resetPassword);
            $registrationEvent = new ResetPasswordEventSubscriber($resetPassword);
            $dispatcher->dispatch($registrationEvent, ResetPasswordEventSubscriber::FORGET_PASSWORD);

            $this->addFlash('success', 'Votre demande à bien été enregistrée');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('password/forget.html.twig');
    }
}
