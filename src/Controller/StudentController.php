<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/student")
 */
class StudentController extends AbstractController
{
    /**
     * @Route("/", name="app_student_index", methods={"GET"})
     */
    public function index(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();

        return $this->render('student/index.html.twig', [
            'students' => array_slice($students, 0, count($students)),
        ]);
    }

    /**
     * @Route("/json", name="app_student_index_json", methods={"GET"})
     */
    public function indexJson(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();

        return $this->json($students);
    }

    /**
     * @Route("/new", name="app_student_new", methods={"GET", "POST"})
     */
    public function new(Request $request, StudentRepository $studentRepository): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRepository->add($student, true);

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_student_show", methods={"GET"})
     */
    public function show($id, StudentRepository $studentRepository): Response
    {
        $student = $studentRepository->find($id);

        if ($student == null) {
            throw $this->createNotFoundException("The student does not exist");
        }
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    /**
     * @Route("/{id}", name="app_student_delete", methods={"POST"})
     */
    public function delete(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $studentRepository->remove($student, true);
        }

        return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/json", name="app_student_json", methods={"GET"})
     */
    public function getJson($id, StudentRepository $studentRepository): Response
    {
        $student = $studentRepository->find($id);

        if ($student == null) {
            throw $this->createNotFoundException("The student does not exist");
        }
        return $this->json($student);
    }

    /**
     * @Route("/{id}/edit", name="app_student_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Student $student, StudentRepository $studentRepository): Response
    {

        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $studentRepository->add($student, true);

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }
}
