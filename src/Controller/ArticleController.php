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

    #[Route('/article/save', name: 'app_article_save', methods: ['POST'])]
    public function save(Request $request, DocumentManager $dm): Response
    {
        if ($request->request->has('id') && !empty($request->request->get('id'))) {
            $id = $request->request->get('id');
            dump("id : ", $id);
            $article = $dm->getRepository(Article::class)->find($id);
            dump("article : ", $article);

            if (!$article) {
                throw $this->createNotFoundException('Article introuvable pour l\'ID : ' . $id);
            }
        } else {
            $article = new Article();
            $dm->persist($article);
        }

        $title = $request->request->get('title');
        $desc = $request->request->get('desc');
        $author = $request->request->get('author');
        $imgPath = $request->request->get('imgPath');

        if ($title !== null) $article->setTitle($title);
        if ($desc !== null) $article->setDesc($desc);
        if ($author !== null) $article->setAuthor($author);
        if ($imgPath !== null) $article->setImgPath($imgPath);

        try {
            $dm->flush();
        } catch (\Exception $e) {
            return new Response("Erreur lors de la sauvegarde : " . $e->getMessage(), 500);
        }

        return $this->redirectToRoute('app_article');
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
}
