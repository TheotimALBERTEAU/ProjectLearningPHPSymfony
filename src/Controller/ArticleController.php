<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_article')]
    public function index(): Response
    {
        $articles = [
            ['id' => 1, 'title' => 'Premier Article', 'desc' => 'Description du premier article', 'author' => 'Isaac', 'imgPath' => 'https://dogtime.com/wp-content/uploads/sites/12/2011/01/GettyImages-653001154-e1691965000531.jpg'],
            ['id' => 2, 'title' => 'Deuxième Article', 'desc' => 'Description du deuxième article', 'author' => 'Sanchez', 'imgPath' => 'https://dogtime.com/wp-content/uploads/sites/12/2011/01/GettyImages-653001154-e1691965000531.jpg'],
            ['id' => 3, 'title' => 'Troisième Article', 'desc' => 'Description du troisième article', 'author' => 'Toto', 'imgPath' => 'https://dogtime.com/wp-content/uploads/sites/12/2011/01/GettyImages-653001154-e1691965000531.jpg'],
        ];

        return $this->render('article/index.html.twig', [
            'liste_articles' => $articles,
        ]);
    }
}
