<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\EventSubscriber\UserEventSubscriber;
use App\Form\User\ResetPasswordType;
use App\Service\Password\ForgetPasswordService;
use App\Service\Password\UpdatePasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset-password/{token}', name: 'user.reset.password')]
    public function index(
        ResetPassword $resetPassword,
        Request $request,
        ForgetPasswordService $forgetPasswordService,
        UpdatePasswordService $updatePasswordService,
        EventDispatcherInterface $dispatcher,
    ): Response {
        $form = $this->createForm(ResetPasswordType::class, $resetPassword->getPerson());
        $form->handleRequest($request);

        // Si la demande a expiré alors suppression de celle-ci et redirection vers la page de mot de passe oublié
        if ($forgetPasswordService->isExpired($resetPassword)) {
            $forgetPasswordService->remove($resetPassword);
            $this->addFlash('info', 'Votre demande de modification de mot de passe a expiré.');

            return $this->redirectToRoute('user.forget.password');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $updatePasswordService->password($user);
            $forgetPasswordService->remove($resetPassword);

            $userEvent = new UserEventSubscriber($user);
            $dispatcher->dispatch($userEvent, UserEventSubscriber::RESET_PASSWORD);

            $this->addFlash('success', 'Votre mot de passe a bien été modifié');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('password/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
