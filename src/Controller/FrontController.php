<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\Project;

class FrontController extends Controller
{
    /**
     * @Route("/", name="index")
     * 
     *  Front page
     */
    public function index() {
        $projects = $this->getDoctrine()
        ->getRepository(Project::class)
        ->findAll();
        return $this->render('front/index.html.twig', [
            "projects" => $projects
        ]);
    }

    /**
     *  @Route ("/article/{id}", name="article")
     */
    public function article() {
        return $this->render('front/article.html.twig', [
            // "project" => $project
        ]);
    }

    // /**
    //  * @Route("/galerie", name="portfolio_gallery")
    //  * 
    //  *  Gallery page
    //  *  Displays all the projects with all the artwork linked to them
    //  */
    // public function gallery() {
    //     $projects = $this->getDoctrine()
    //     ->getRepository(Project::class)
    //     ->findAll();
    //     return $this->render('front/gallery.html.twig', [
    //         'projects' => $projects,
    //     ]);
    // }

    // /**
    //  * @Route("/projet/{id}", name="portfolio_project")
    //  * 
    //  *  Project gallery page
    //  *  Displays the project and artworks linked to it
    //  */
    // public function project(Project $project){
    //     //Retrieve project from database
    //     if (!$project) {
    //         throw $this->createNotFoundException("Le projet n'existe pas");
    //     }
    //     return $this->render('front/project.html.twig', [
    //         'project' => $project,
    //     ]);
    // }

    // /**
    //  * @Route("/apropos", name="portfolio_about")
    //  * 
    //  *  Displays informations about the artist
    //  */
    // public function about() {
    //     $user = $this->getDoctrine()->getRepository(User::class)
    //     ->findAll()[0];
    //     return $this->render('front/about.html.twig', [
    //         "about" => $user->getAbout(),
    //         "title" => $user->getAboutTitle()
    //     ]);
    // }


    // /**
    //  * @Route("/contact", name="portfolio_contact")
    //  * 
    //  *  Contact page (Ajax form request)
    //  *  Displays the artist contact informations as welle as an interactive form
    //  *  that the user can fill and send to the artist
    //  */
    // public function contact(\Swift_Mailer $mailer, Request $request) {
    //     $data = [];
    //     $form = $this->createFormBuilder($data)
    //         ->add('nom' , TextType::class, ['constraints' => new NotBlank()])
    //         ->add('email', EmailType::class, ['constraints' => new NotBlank()])
    //         ->add('message', TextareaType::class, ['constraints' => new NotBlank()])
    //         ->add('envoyer' , SubmitType::class)
    //         ->getForm();
    //     $form->handleRequest($request);

    //     if ($request->isXmlHttpRequest()){
    //         if ($form->isSubmitted() && $form->isValid()) {
    //             $data = $form->getData();
    //             $message = (new \Swift_Message('Hello Email'))
    //                 ->setFrom($data["email"])
    //                 ->setTo('bas2205@live.com')
    //                 ->setBody(
    //                     $this->renderView(
    //                         'included/email.html.twig',
    //                         ['nom' => $data['nom'] , 'message' => $data["message"]]
    //                     ),
    //                     'text/html'
    //                 );
    //             $mailer->send($message);

    //             return new JsonResponse(["state" => "success", "message" => "Votre email a bien été envoyé."]);
    //         }
    //         return new JsonResponse(["state" => "error", "message" => (string)$form->getErrors(true)]);
    //     }
    //     return $this->render('front/contact.html.twig', [
    //         "formMessage" => $form->createView()
    //     ]);
    // }


}
