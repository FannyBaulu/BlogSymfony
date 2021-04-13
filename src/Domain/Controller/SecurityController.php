<?php

namespace App\Domain\Controller;

use App\Domain\DataTransferObject\Credentials;
use App\Domain\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login",name="security_login")
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class, new Credentials($authenticationUtils->getLastUsername()));

        
        if($authenticationUtils->getLastAuthenticationError(false) !== null){
            $form->addError(
            new FormError($authenticationUtils->getLastAuthenticationError()->getMessage())
            );
        }
        
        return $this->render("security/login.html.twig",[
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/logout",name="security_logout")
     */
    public function logout():void
    {

    }
}