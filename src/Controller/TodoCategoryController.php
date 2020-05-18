<?php

namespace App\Controller;

use App\Entity\TodoCategory;
use App\Form\TodoCategoryType;
use App\Repository\TodoCategoryRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo/category")
 */
class TodoCategoryController extends AbstractController
{
    /**
     * @Route("/", name="todo_category_index", methods={"GET"})
     * @param TodoCategoryRepository $todoCategoryRepository
     * @return Response
     */
    public function index(TodoCategoryRepository $todoCategoryRepository): Response
    {
        return $this->render('todo_category/index.html.twig', [
            'todo_categories' => $todoCategoryRepository->findByOwner($this->getUser()),
        ]);
    }

    /**
     * @Route("/new", name="todo_category_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $todoCategory = new TodoCategory();
        $form = $this->createForm(TodoCategoryType::class, $todoCategory);
        $form->handleRequest($request);

        $todoCategory->setCreatedAt(new DateTime());
        /** @noinspection PhpParamsInspection */
        $todoCategory->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todoCategory);
            $entityManager->flush();

            return $this->redirectToRoute('todo_category_index');
        }

        return $this->render('todo_category/new.html.twig', [
            'todo_category' => $todoCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todo_category_show", methods={"GET"})
     * @param TodoCategory $todoCategory
     * @return Response
     */
    public function show(TodoCategory $todoCategory): Response
    {
        return $this->render('todo_category/show.html.twig', [
            'todo_category' => $todoCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="todo_category_edit", methods={"GET","POST"})
     * @param Request $request
     * @param TodoCategory $todoCategory
     * @return Response
     */
    public function edit(Request $request, TodoCategory $todoCategory): Response
    {
        $form = $this->createForm(TodoCategoryType::class, $todoCategory);
        $form->handleRequest($request);
        $todoCategory->setEditedAt(new DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todo_category_index');
        }

        return $this->render('todo_category/edit.html.twig', [
            'todo_category' => $todoCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todo_category_delete", methods={"DELETE"})
     * @param Request $request
     * @param TodoCategory $todoCategory
     * @return Response
     */
    public function delete(Request $request, TodoCategory $todoCategory): Response
    {
        if ($this->isCsrfTokenValid('delete' . $todoCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todoCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('todo_category_index');
    }
}
