<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TodoRepository;
use App\Entity\Todo;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TodoType;


class TodoController extends AbstractController
{
    /**
     * @var TodoRepository
     */
    private $repository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->repository = $todoRepository;
    }


    /**
     * @Route("/todo", name="todo")
     */
    public function index(): Response
    {
        $todos = $this->repository->findAll();
        $title = "Toutes mes tâches";
        $tacheCompleted = $this->repository->findOneBy((['completed'=>1]))!== null;
        return $this->render('todo/index.html.twig', compact('todos', 'title','tacheCompleted'));
    }


    /**
     * @Route("/todo/create/", name="create")
     * @Route("/todo/edit/{id}/", name="edit")
     */
    public function form(?Todo $todo,Request $request, EntityManagerInterface $manager): Response
    {
        $todo= $todo??new Todo();


        $formulaire=$this->createForm(TodoType::class,$todo);

        // on demande au formulaire d'analyser la requete
        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid()){

            $manager->persist($todo);
            $manager->flush();

            return $this->redirectToRoute('todo');
        }


        return $this->render('todo/edit.html.twig', [
            'formulaire' => $formulaire->createView(),
            'editMode' => $todo->getId() !== null,
            'todo' => $todo,
        ]);
    }

    /**
     * @Route("todo/delete_all_completed/", name="delete_all_completed")
     */
    public function delete_all_completed(): Response
    {
        $this->repository->delete_all_completed();
        return $this->redirectToRoute('todo');
    }



    /**
     * @Route("toggle_completed/{id}", name="toggle_completed")
     */

    public function toggle_completed(?Todo $todo,EntityManagerInterface $manager)
    {
        if($todo){
            $todo->setCompleted(!$todo->getCompleted());
            $manager->persist($todo);
            $manager->flush();

            return $this->redirectToRoute('todo');
        }

        return $this->render('404.html.twig');


    }




}
