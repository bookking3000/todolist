<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\User;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/own", name="todo_index", methods={"GET"})
     * @param TodoRepository $todoRepository
     * @return Response
     */
    public function index(TodoRepository $todoRepository): Response
    {
        $user = $this->getUser();
        return $this->render('todo/index.html.twig', [
            'todos' => $todoRepository->findByOwner($user->getId()),
        ]);
    }

    /**
     * @Route("/contributingTo", name="todo_contributng", methods={"GET"})
     * @param TodoRepository $todoRepository
     * @return Response
     */
    public function contributingTo(TodoRepository $todoRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $todosContributingTo = $user->getTodosContributingTo();

        return $this->render('contributing/index.html.twig', [
            'todos' => $todosContributingTo,
        ]);
    }

    /**
     * @Route("/new", name="todo_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $todo->setCreationDate(new DateTime());

            /** @noinspection PhpParamsInspection */
            $todo->setOwner($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todo_show", methods={"GET"})
     * @param Todo $todo
     * @return Response
     */
    public function show(Todo $todo): Response
    {
        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="todo_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Todo $todo
     * @return Response
     */
    public function edit(Request $request, Todo $todo): Response
    {
        $form = $this->createForm(TodoType::class, $todo, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();
        if ($todo->getOwner()->getId() !== $user->getId()) {
            return $this->redirectToRoute('todo_index');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todo_delete", methods={"DELETE"})
     * @param Request $request
     * @param Todo $todo
     * @return Response
     */
    public function delete(Request $request, Todo $todo): Response
    {
        if ($this->isCsrfTokenValid('delete' . $todo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('todo_index');
    }
}
