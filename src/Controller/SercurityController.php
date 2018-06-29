<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use App\Form\EmailForm;
use App\Form\PasswordForm;

class SercurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * 
     *  Display a form
     *  Lets the user connect as the artist
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker){
        if ($authChecker->isGranted('ROLE_ADMIN'))
        return $this->redirectToRoute('back_index');
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * 
     *  Let's the artist disconnect
     */
    public function logout(){
        // Deconnection is handeled by symfony :I
        // Just let that action be
    }

    /**
     * @Route("/admin/email", name="account_modify_email")
     * 
     *  Email modification page (Ajax form request)
     */
    public function modifyEmail(Request $request){
        $user = $this->getDoctrine()->getRepository(User::class)->findAll()[0];
        $emailForm = $this->createForm(EmailForm::class, $user);
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $user = $emailForm->getData();
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(["state" => "success", "message" => "Modification email enregistrée."]);
        }
        return new JsonResponse(["state" => "error", "message" => (string)$emailForm->getErrors(true)]);

    }

    /**
     * @Route("/admin/password", name="account_modify_password")
     * 
     *  Password modification page (Ajax form request)
     */
    public function modifyPassword(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator){
        $user = $this->getDoctrine()->getRepository(User::class)->findAll()[0];
        $passwordForm = $this->createForm(PasswordForm::class, $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()){

            if ($passwordForm->get('new_password')->getData() != $passwordForm->get('new_password_retyped')->getData()){
                return new JsonResponse(["state" => "error", "message" => "Les nouveaux mots de passe ne correspondent pas."]);
            }
            $newPassword = $encoder->encodePassword($user, $passwordForm->get('new_password')->getData());
            $user->setPassword($newPassword);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(["state" => "success", "message" => "Nouveau mot de passe sauvegardée."]);
        }
        return new JsonResponse(["state" => "error", "message" => (string)($passwordForm->getErrors(true))]);

    }

    /**
     * Route("/register", name="register")
     * 
     *  Was used to register the unique user
     *  Yes, for a moment the user was "admin" "admin"
     *  but at least it wasn't "football" "football" 
     */
    // public function register(UserPasswordEncoderInterface $encoder){

    //     $entityManager = $this->getDoctrine()->getManager();
    //     $user = new User();
    //     $plainPassword = 'admin';
    //     $username = 'admin';
    //     $encoded = $encoder->encodePassword($user, $plainPassword);

    //     $user->setPassword($encoded);
    //     $user->setUsername($username);
    //     $user->setAbout("Lorem Ipsum ...");
    //     $user->setAboutTitle("A propos de moi");

    //     $entityManager->persist($user);
    //     $entityManager->flush();

    //     return new Response('New user created');
    // }
}
