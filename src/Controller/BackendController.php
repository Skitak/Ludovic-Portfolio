<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Project;
use App\Form\ProjectForm;
use App\Form\EmailForm;
use App\Form\PasswordForm;
use App\Entity\Image;
use App\Entity\User;
use App\Form\AboutForm;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\ImageCompressor;

class BackendController extends Controller
{
    

    /**
     * @Route("/admin", name="back_index")
     *  Administration hub page
     *  Lists all projects for the user to edit and organize them
     */
    public function index(ImageCompressor $compressor){
        $projects = $this->getDoctrine()
        ->getRepository(Project::class)
        ->findAll();
        // $this->removeAllArtworks();
        // $this->resizeAllThumbnails($compressor, $projects);
        return $this->render('back/index.html.twig', [
            "projects" => $projects
        ]);
    }

    /**
     * @Route("/admin/projet/{id}", name="back_project")
     *  
     *  Project edit page
     *  Lets the user modify project information as well as manage
     *  artworks linked to that project
     */
    public function project(Project $project, Request $request){
        $projectForm = $this->createForm(ProjectForm::class, $project);
        // $projectForm->setAction($this->generateUrl('back_project', ["id" => $project->getId()]));
        $projectForm->handleRequest($request);

        // $artworkForm = $this->createForm(ArtworkForm::class, $artwork);
        $data = [];
        $artworkForm = $this->createFormBuilder($data)
            ->setAction($this->generateUrl('back_add_images', ["id" => $project->getId()]))
            ->add('images' , FileType::class, ['constraints' => new NotBlank(), 'multiple' => true])
            ->add('send' , SubmitType::class)
            ->getForm();
        $artworkForm->handleRequest($request);

        if ($request->isXmlHttpRequest()){
            
            if ($projectForm->isSubmitted() && $projectForm->isValid()) {
                // $projectForm->getData() holds the submitted values
                // return new Response(json_encode(["result" => "yis"]));
                $project = $projectForm->getData();

                //Saving modifications
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($project);
                $entityManager->flush();
                return new JsonResponse(["state" => "success", "message" => "modifications sauvegardées."]);
            }
            return new JsonResponse(["state" => "error", "message" => "erreur de la sauvegarde."]);
        } //else return new JsonResponse(["result" => "nope"]);

        return $this->render('back/project.html.twig', [
            'project' => $project,
            'project_form' => $projectForm->createView(),
            'artwork_form' => $artworkForm->createView()
        ]);
    }

    /**
     * @Route("/admin/apropos", name="back_about")
     * 
     *  About edit page (AJAX form request)
     *  Lets the user modify informations about himself
     *  
     */
    public function about(Request $request){
        $user = $this->getDoctrine()->getRepository(User::class)->findAll()[0];
        $aboutForm = $this->createForm(AboutForm::class, $user);
        $aboutForm->handleRequest($request);

        if ($request->isXmlHttpRequest()){
            if ($aboutForm->isSubmitted() && $aboutForm->isValid()) {
                $user = $aboutForm->getData();
                $entityManager = $this->getDoctrine()->getManager();
                // $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse(["state" => "success", "message" => "modifications sauvegardées."]);
            }
            return new JsonResponse(["state" => "error", "message" => (string)$aboutForm->getErrors(true)]);
        }

        return $this->render('back/about.html.twig', [
            "about" => $user->getAbout(),
            "title" => $user->getAboutTitle(),
            "form" => $aboutForm->createView()

        ]);
    }

    /**
     * @Route("/admin/add/{id}/images", name="back_add_images")
     *  
     *  Images page (AJAX form request)
     *  Lets the user add images to project
     */
    public function addImages(Project $project, Request $request, ImageCompressor $compressor){
        $data = [];
        $artworkForm = $this->createFormBuilder($data)
            ->setAction($this->generateUrl('back_add_images', ["id" => $project->getId()]))
            ->add('images' , FileType::class, ['constraints' => new NotBlank(), 'multiple' => true])
            ->add('send' , SubmitType::class)
            ->getForm();
        $artworkForm->handleRequest($request);

        $encoders = array( new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $normalizers[0]->setIgnoredAttributes(["project"]);

        $serializer = new Serializer($normalizers, $encoders);

        if ($artworkForm->isSubmitted() && $artworkForm->isValid()) {
            $data = $artworkForm->getData();
            $entityManager = $this->getDoctrine()->getManager();
            
            $files = $data['images'];
            $artworksAdded = [];
            foreach($files as $file){
                $artwork = new Image();
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('images_path'),
                    $fileName
                );

                $compressor->resizeImage($this->getParameter('images_path'), $fileName);
                $thumbnailName = $compressor->createThumbnail($this->getParameter('images_path'), $fileName);

                $artwork->setImage($fileName);
                $artwork->setThumbnail($thumbnailName);
                $artwork->setProject($project);
                
                $entityManager->persist($artwork);

                //$serializer->serialize($artwork, 'json')
                $artworksAdded[] = $artwork->getImage();
            }
            $entityManager->flush();
            $artworks = [];
            $repo = $this->getDoctrine()->getRepository(Image::class);
            foreach ($artworksAdded as $image){
                $artworks[] = $serializer->serialize($repo->findBy(["image" => $image]),'json');
            }
            return new JsonResponse($artworks);   
        }
        return new JsonResponse(["state" => "error", "message" => $artworkForm->getErrors()]); 
    }

    /**
     * @Route("/admin/delete/artwork/{id}", name="back_delete_artwork")
     * 
     *  Remove an artwork page (AJAX form request)
     */
    public function removeArtwork(Image $artwork){
        $project = $artwork->getProject();
        $entityManager = $this->getDoctrine()->getManager();
        if($project->getFrontImage() == $artwork)
            $project->setFrontImage(null);
        $this->deleteImages([$artwork]);
        $entityManager->remove($artwork);
        $entityManager->flush();
        
        return $this->redirectToRoute('back_project', ["id"=> $project->getId()]);
    }

    public function deleteImages($artworks){
        foreach ($artworks as $artwork){
            if (file_exists($this->getParameter('images_path') . $artwork->getImage()))
                unlink($this->getParameter('images_path') . $artwork->getImage());
            if (file_exists($this->getParameter('images_path') . $artwork->getThumbnail()))
                unlink($this->getParameter('images_path') . $artwork->getThumbnail());
        }
    }

    /**
     * @Route("/admin/add/project", name="back_add_project")
     * 
     *  Add empty project and reroute user to the edit page
     */
    public function addProject(){
        $project = new Project();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($project);
        $entityManager->flush();

        $projectId = $this->getDoctrine()->getRepository(Project::class)->findOneByName($project->getName())->getId();

        return $this->redirectToRoute("back_project", ["id" => $projectId]);

    }

    /**
     * @Route("/admin/delete/project/{id}", name="back_delete_project")
     * 
     *  Removes a project (AJAX form request)
     */
    public function removeProject(Project $project){
        $entityManager = $this->getDoctrine()->getManager();
        $project->setFrontImage();
        $this->deleteImages($project->getImages());
        foreach ($project->getImages() as $artwork){
            // $project->removeArtwork($artwork);
            $entityManager->remove($artwork);
        }
        $entityManager->flush();
        $entityManager->remove($project);
        $entityManager->flush();
        return new JsonResponse(["state" => "success", "message" => "Projet supprimé avec success."]);
    }

    /**
     * @Route("/admin/project/frontArtwork/{id}", name="back_project_front_artwork")
     * 
     *  Modify front image from a project (AJAX form request)
     */
    public function addProjectFrontImage(Image $artwork){
        $artwork->getProject()->setFrontImage($artwork);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([$artwork->getImage(), $artwork->getThumbnail()]);
    }

    /**
     * @Route("/admin/project/swapOrder/{idA}/{idB}", name="back_project_swapOrder")
     * 
     *  Modify project order (AJAX form request)
     */
    public function swapOrder($idA, $idB){
        $repo = $this->getDoctrine()->getRepository(Project::class);
        $projectA = $repo->find($idA);
        $projectB = $repo->find($idB);
        $swap = $projectA->getDisplayOrder();
        $projectA->setDisplayOrder($projectB->getDisplayOrder());
        $projectB->setDisplayOrder($swap);
        $this->getDoctrine()->getManager()->flush();
        
        return new JsonResponse(["state" => "success", "message" => "Ordre modifié avec success."]);
    }

    /**
     * @Route("/admin/image/swapOrder/{idA}/{idB}", name="back_image_swapOrder")
     * 
     *  Modify project order (AJAX form request)
     */
    public function swapOrderimage($idA, $idB){
        $repo = $this->getDoctrine()->getRepository(Image::class);
        $projectA = $repo->find($idA);
        $projectB = $repo->find($idB);
        $swap = $projectA->getDisplayOrder();
        $projectA->setDisplayOrder($projectB->getDisplayOrder());
        $projectB->setDisplayOrder($swap);
        $this->getDoctrine()->getManager()->flush();
        
        return new JsonResponse(["state" => "success", "message" => "Ordre modifié avec success."]);
    }

    /**
     * @Route("/admin/compte", name="back_account")
     * 
     *  Accout management page
     *  Lets the user modify his email adress and his password
     */
    public function accountSettings (Request $request, UserPasswordEncoderInterface $encoder){
        $user = $this->getDoctrine()->getRepository(User::class)->findAll()[0];
        $emailForm = $this->createForm(EmailForm::class, $user);
        $emailForm->handleRequest($request);

        $passwordForm = $this->createForm(PasswordForm::class, $user);
        $passwordForm->handleRequest($request);

        return $this->render("back/account.html.twig", [
            "email_form" => $emailForm->createView(),
            "password_form" => $passwordForm->createView()
        ]);
    }


//  Utility functions
//  These functions were used because i'm too lazy to learn proper sql request
//  Or because they migt me usefull in some actions
//  Do not use these functions unless you have a good reason to

    private function addLotsOfProjects(){
        $entityManager = $this->getDoctrine()->getManager();
        for ($i = 0; $i < 5; $i++)
            $entityManager->persist(new Project("Name " . $i, "Description vide", "#00" . ($i * 2) ));
        $entityManager->flush();
    }

    // One line function because md5(uniqid()) is not very easy to understand 
    private function generateUniqueFileName(){
        return md5(uniqid());
    }

    private function resizeAllThumbnails($compressor, $projects){
        foreach ( $projects as $project)
            foreach ( $project->getArtworks() as $artwork)
                $compressor->resizeThumbnail($this->getParameter('images_path') . $artwork->getThumbnail());
    }

    //will break the database.
    private function removeAllArtworks(){
        $entityManager = $this->getDoctrine()->getManager();
            
        $projects = $this->getDoctrine()
        ->getRepository(Project::class)
        ->findAll();
        foreach($projects as $project){
            $project->setFrontArt();
            foreach($project->getArtworks() as $artwork)
                $entityManager->remove($artwork);
        }
        $entityManager->flush();
    }

}
