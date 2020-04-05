<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $repo)

    {
        $articles = $repo->findAll();

        return $this->render('home/home.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/article/{id}", name="read_article")
     */
    public function readArticle(Article $article)

    {
        return $this->render('home/article.html.twig', [
            "article" => $article
        ]);
    }


    

}
