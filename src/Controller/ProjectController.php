<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\CreateProjectType;
use App\Services\thymobruceClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * @Route("/project", name="project_")
 */
class ProjectController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    private $em;
    private $client;
    public function __construct(EntityManagerInterface $entityManager, thymobruceClient $client)
    {
        $this->em= $entityManager;
        $this->client = $client;
    }

    /**
     * @Route("", name="index")
     */
    public function index()
    {
        return $this->render('Features/Project/index.html.twig', [

        ]);
    }

    /**
     * @Route("/rows/{id}", name="rows")
     */
    public function rows($id = null)
    {
        $projects = $this->em->getRepository(Project::class)->findBy(['isDeleted' => false]);

        return $this->render('Features/Project/rows.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(CreateProjectType::class, $project);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $project->setUser($this->getUser()->getId());
            if(!empty($form->get('description')->getData()))
                $project->setDescription($form->get('description')->getData());

            //uploading an image
            $file = $form->get('image')->getData();
            if($file){
                $filename = pathinfo($file->getClientOriginalName() . "." . $file->guessExtension(), PATHINFO_FILENAME);
                try {
                    $file->move(
                        "uploads/project/image",
                        $filename
                    );
                    $project->setImage($filename);
                }
                catch (\Exception $exception){
                    dd($exception->getMessage());
                }
            }
            //end uploading
            $this->em->persist($project);
            $this->em->flush();
            try {
                $this->client->createProject($request->request->get('create_project'));
            }
            catch(\Exception $exception){
                dd($exception->getMessage());
            }
           return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('Features/Project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="show")
     */
    public function show($id)
    {
        $project = $this->em->getRepository(Project::class)->findOneBy(['id' => $id]);
        if($project->getIsDeleted())
            throw new NotFoundResourceException("No project found");

        return $this->render('Features/Project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit($id, Request $request){
        $project = $this->em->getRepository(Project::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(CreateProjectType::class, $project);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            dd($form->getData());
            $project->setUser($this->getUser()->getId());
            if(!empty($form->get('description')->getData()))
                $project->setDescription($form->get('description')->getData());

            //uploading an image
            $file = $form->get('image')->getData();
            if($file){
                $filename = pathinfo($file->getClientOriginalName() . "." . $file->guessExtension(), PATHINFO_FILENAME);
                try {
                    $file->move(
                        "uploads/project/image",
                        $filename
                    );
                    $project->setImage($filename);
                }
                catch (\Exception $exception){
                    dd($exception->getMessage());
                }
            }
            //end uploading
            $this->em->persist($project);
            $this->em->flush();
            try {
                $result = $this->client->createProject($request->request->get('create_project'));
            }
            catch(\Exception $exception){
                dd($exception->getMessage());
            }
            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('Features/Project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}