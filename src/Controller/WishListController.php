<?php

namespace App\Controller;

use App\Services\WishListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WishListController extends AbstractController
{
    private $wishListService;

    public function __construct(WishListService $wishListService)
    {
        $this->wishListService = $wishListService;
    }

    public function index(): Response
    {
        $wishLists = $this->wishListService->getAll();
        return $this->render('wish_list/index.html.twig', [
            'wishLists' => $wishLists,
        ]);
    }

    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $success = $this->wishListService->create($data);
            if ($success) {
                return $this->redirectToRoute('wish_list_index');
            }
        }
        return $this->render('wish_list/create.html.twig');
    }

    public function delete(int $id): Response
    {
        $success = $this->wishListService->delete($id);
        if ($success) {
            return $this->redirectToRoute('wish_list_index');
        }
        return new Response('Failed to delete wishlist.');
    }
}
