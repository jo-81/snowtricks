<?php

namespace App\Controller;

use App\Entity\User;
use App\EventSubscriber\UserEventSubscriber;
use App\Form\User\RegisterType;
use App\Repository\UserRepository;
use App\Service\User\PasswordHashService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'user.register')]
    public function index(
        Request $request,
        PasswordHashService $passwordHashService,
        UserRepository $userRepository,
        EventDispatcherInterface $dispatcher
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user = $passwordHashService->passwordHash($user);

            $userRepository->save($user, true);

            $registrationEvent = new UserEventSubscriber($user);
            $dispatcher->dispatch($registrationEvent, UserEventSubscriber::REGISTRATION);

            $this->addFlash('success', 'Votre inscription a bien été ajoutée');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
