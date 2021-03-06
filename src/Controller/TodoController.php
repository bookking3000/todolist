<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\User;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        /** @var User $user */
        $user = $this->getUser();
        $id =$user->getId();

        return $this->render('todo/index.html.twig', [
            'todos' => $todoRepository->findByOwner($id),
        ]);
    }

    /**
     * @Route("/own/archived", name="todo_archived_index", methods={"GET"})
     * @param TodoRepository $todoRepository
     * @return Response
     */
    public function archived(TodoRepository $todoRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $id =$user->getId();

        return $this->render('archived/index.html.twig', [
            'todos' => $todoRepository->findArchivedByOwner($id),
        ]);
    }

    /**
     * @Route("/contributingTo", name="todo_contributng", methods={"GET"})
     * @return Response
     */
    public function contributingTo(): Response
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
            $todo->setIsArchived(false);

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
     * @Route("/{id}/archive", name="todo_archive", methods={"GET","POST"})
     * @param Todo $todo
     * @return Response
     */
    public function archive(Todo $todo): Response
    {
        if($todo->getIsArchived() == false){
            $todo->setIsArchived(true);
        } else {
            $todo->setIsArchived(false);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('todo_index');
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
