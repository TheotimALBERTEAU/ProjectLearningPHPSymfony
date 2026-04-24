<?php

namespace App\Controller;

use App\Document\Article;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_article')]
    public function index(DocumentManager $dm): Response
    {
        $articles = $dm->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'liste_articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_show')]
    public function show(string $id, DocumentManager $dm): Response
    {
        $article = $dm->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article introuvable');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/delete/{id}', name: 'app_article_delete', methods: ['DELETE'])]
    public function delete(string $id, DocumentManager $dm): Response
    {
        $article = $dm->getRepository(Article::class)->find($id);

        if ($article) {
            $dm->remove($article);
            $dm->flush();
        }

        return $this->redirectToRoute('app_article');
    }

    #[Route('/article/save', name: 'app_article_save', methods: ['POST'])]
    public function save(Request $request, DocumentManager $dm): Response
    {
        // On récupère l'ID s'il existe
        $id = $request->request->get('id');

        // Si l'ID est présent et non vide, on cherche l'article, sinon on en crée un nouveau
        $article = ($id && trim($id) !== '') ? $dm->getRepository(Article::class)->find($id) : new Article();

        if (!$article) {
            throw $this->createNotFoundException('Article introuvable pour modification');
        }

        // Récupération des données (on utilise 'desc' pour correspondre à ton Postman)
        $title = $request->request->get('title');
        $desc = $request->request->get('desc'); // Harmonisé ici
        $author = $request->request->get('author');
        $imgPath = $request->request->get('imgPath');

        // On ne met à jour que si la donnée est fournie
        if ($title) $article->setTitle($title);
        if ($desc) $article->setDesc($desc); // Assure-toi que la méthode s'appelle bien setDesc dans ton Document Article
        if ($author) $article->setAuthor($author);
        if ($imgPath) $article->setImgPath($imgPath);

        // Si c'est un nouvel article (pas d'ID), on persiste
        if (!$id || trim($id) === '') {
            $dm->persist($article);
        }

        $dm->flush();

        return $this->redirectToRoute('app_article');
    }
}
