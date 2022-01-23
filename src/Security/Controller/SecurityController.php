<?php

declare(strict_types=1);

namespace App\Security\Controller;

use App\Security\Form\RegistrationType;
use App\Security\Message\Registration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: 'security_')]
final class SecurityController extends AbstractController
{
    #[Route('/register', name: 'register', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function register(Request $request, MessageBusInterface $messageBus): Response
    {
        $registration = new Registration();

        $form = $this->createForm(RegistrationType::class, $registration)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBus->dispatch($registration);

            return $this->redirectToRoute('security_login');
        }

        return $this->renderForm('security/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/login', name: 'login', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    #[Route('/logout', name: 'logout', methods: [Request::METHOD_GET])]
    public function logout(): void
    {
    }
}