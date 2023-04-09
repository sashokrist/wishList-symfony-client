<?php

namespace App\Controller;

use App\Services\LibraryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    /**
     * @Route("/libraries", name="libraries_list")
     */
    public function list(LibraryService $libraryService)
    {
        $libraries = $libraryService->all();

        return $this->render('library/list.html.twig', [
            'libraries' => $libraries,
        ]);
    }

    /**
     * @Route("/libraries/new", name="library_new")
     */
    public function new(Request $request, LibraryService $libraryService)
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $libraryService->create($data);

            return $this->redirectToRoute('libraries_list');
        }

        return $this->render('library/new.html.twig');
    }

    /**
     * @Route("/libraries/{id}/delete", name="library_delete")
     */
    public function delete($id, LibraryService $libraryService)
    {
        $libraryService->delete($id);

        return $this->redirectToRoute('libraries_list');
    }
}