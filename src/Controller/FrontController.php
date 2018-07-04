<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse ;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Context;

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
        $roles = $this->getDoctrine()
        ->getRepository(Role::class)
        ->findAll();
        $contexts = $this->getDoctrine()
        ->getRepository(Context::class)
        ->findAll();
        return $this->render('front/index.html.twig', [
            "projects" => $projects,
            "contexts" => $contexts,
            "roles" => $roles
        ]);
    }

    /**
     *  @Route ("/project/{id}", name="project")
     */
    public function project(Project $project) {
        return $this->render('front/project.html.twig', [
            "project" => $project
        ]);
    }

    /**
     *  @Route ("/contact", name="contact")
     */
    public function contact(Request $request) {
        $data = [];
        $form = $this->createFormBuilder($data)
            ->add('nom' , TextType::class, ['constraints' => new NotBlank()])
            ->add('email', EmailType::class, ['constraints' => new NotBlank()])
            ->add('objet' , TextType::class, ['constraints' => new NotBlank()])
            ->add('message', TextareaType::class, ['constraints' => new NotBlank()])
            ->add('envoyer' , SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isSubmitted()){
            if ($form->isValid()) {
                $data = $form->getData();
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom($data["email"])
                    ->setTo('bastienbouquin@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'front/email.html.twig',
                            ['nom' => $data['nom'] , 'message' => $data["message"]]
                        ),
                        'text/html'
                    );
                $mailer->send($message);

                return new JsonResponse(["state" => "success", "message" => "Votre email a bien été envoyé."]);
            }
            return new JsonResponse(["state" => "error", "message" => (string)$form->getErrors(true)]);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findAll()[0];
        return $this->render('front/contact.html.twig', [
            "user" => $user,
            "form" =>$form->createView(),
        ]);
    }

    /**
     *  @Route ("/download", name="cv")
     */
    public function cv(){
        $response = new BinaryFileResponse($this->getParameter('cv_path'));
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'pdf.pdf');
        return $response;
    }

}
