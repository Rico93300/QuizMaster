<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Repository\ThemeRepository;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

#[Route('/admin', name: 'app_admin')]
public function admin(ThemeRepository $themeRepository, QuestionRepository $questionRepository,ReponseRepository $reponseRepository): Response
{
    return $this->render('admin/index.html.twig', [
        'admin_name' => 'adminController',
        'themes' => $themeRepository->findAll(),
        'questions' => $questionRepository->findAll(),
        'reponses' => $reponseRepository->findAll()
    ]);
}
}


