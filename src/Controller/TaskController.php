<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskTypeForm;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

final class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(TaskRepository $repo, Request $request): Response
    {
        $form = $this->createForm(TaskTypeForm::class, new Task());
        $form->handleRequest($request);

        return $this->render('task/index.html.twig', [
            'form' => $form->createView(),
            'tasks' => $repo->findBy([
                'user' => $this->getUser(),
            ]),
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/task/create', name: 'task_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaskTypeForm::class, new Task());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setUser($this->getUser());
            $task->setStatus('todo');
            $em->persist($task);
            $em->flush();

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat() || $request->isXmlHttpRequest()) {
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->renderBlock('task/_task_stream.html.twig', 'new_success', [
                    'task' => $task,
                ]);
            }
        }

        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat() || $request->isXmlHttpRequest()) {
            // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('task/_task_stream.html.twig', 'new_task', [
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('task_index');
    }

    #[Route('/task/{id}/move', name: 'task_move', methods: ['POST'])]
    public function move(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $task->setStatus($request->request->get('status'));
        $em->flush();

        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat() || $request->isXmlHttpRequest()) {
            // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('task/_task_stream.html.twig', 'move_task', [
                'task' => $task,
            ]);
        }

        return $this->redirectToRoute('task_index');
    }

    #[Route('/task/{id}/update', name: 'task_update', methods: ['POST'])]
    public function update(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaskTypeForm::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $em->persist($task);
            $em->flush();

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                // Reset form in modal
                $emptyForm = $this->createForm(TaskTypeForm::class, new Task());
                return $this->renderBlock('task/_task_stream.html.twig', 'update_success', [
                    'task' => $task,
                    'form' => $emptyForm->createView(),
                ]);
            }
        }

        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat() || $request->isXmlHttpRequest()) {
            // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('task/_task_stream.html.twig', 'update_form', [
                'task' => $task,
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('task_index');
    }

    #[Route('/task/{id}/delete', name: 'task_delete', methods: ['POST'])]
    public function delete(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $taskId = $task->getId();
        $em->remove($task);
        $em->flush();

        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('task/_task_stream.html.twig', 'delete_task', [
                'taskId' => $taskId
            ]);
        }

        return $this->redirectToRoute('task_index');
    }

    #[Route('/task/search', name: 'task_search', methods: ['POST'])]
    public function search(Request $request, TaskRepository $repo): Response
    {
        $keyword = $request->get('keyword');
        $tasks = $repo->searchTasks($this->getUser(), $keyword);

        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->renderBlock('task/_task_stream.html.twig', 'tasks_list', [
                'tasks' => $tasks,
            ]);
        }

        return $this->redirectToRoute('task_index');
    }

}
