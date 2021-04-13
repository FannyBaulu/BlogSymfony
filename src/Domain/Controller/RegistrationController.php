<?php

namespace App\Domain\Controller;

use App\Application\Entity\User;
use App\Domain\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegistrationController
 * @package App\Domain\Controller
 */
class RegistrationController extends AbstractController
{

    /**
     * @Route("/inscription",name="registration")
     * @return Response
     */
    public function __invoke(Request $request, UserPasswordEncoderInterface $userPasswordEncoder) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class,$user)->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid())
        {
            $user->setPassword($userPasswordEncoder->encodePassword($user,$form->get("plainPassword")->getData()));
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("security_login");
        }
        return $this->render('security/registration.html.twig',[
            'form'=> $form->createView()
        ]);
    }
}