<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function home(): Response
    {
        $user = $this->getUser();
        return $this->render('main/home.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/about-us', name: 'aboutUs')]
    public function about(): Response
    {
        $filesystem = new Filesystem();

        $jsonFilePath = $this->getParameter('kernel.project_dir') . '/data/team.json';

        if (!$filesystem->exists($jsonFilePath)) {
            throw $this->createNotFoundException('JSON file not found');
        }

        $jsonContent = file_get_contents($jsonFilePath);
        $team = json_decode($jsonContent, true);

        return $this->render('main/about.html.twig', [
            'team' => $team,
        ]);
    }
}
