<?php

namespace App\Controller;

use App\Enum\Category;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $repo): Response
    {
        return $this->render('home/index.html.twig', [
            'recentDrops'  => $repo->findRecentDrops(5),
            'bestsellers'  => $repo->findBestsellers(4),
            'categories'   => Category::cases(),
        ]);
    }
}
