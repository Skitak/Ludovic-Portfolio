<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     *  @Route ("/project/{id}", name="project")
     */
    public function project(Project $project) {
        return $this->render('front/project.html.twig', [
            "project" => $project
        ]);
    }

}
