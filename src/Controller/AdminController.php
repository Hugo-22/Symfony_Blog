<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('admin/admin.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/admin/create", name="create_article")
     * @Route("/admin/create/{id}", name="update_article", methods="GET|POST")
     */
    public function create_article(Article $article = null, EntityManagerInterface $manager, HttpFoundationRequest $request)
    {

        if(!$article) {

            $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTime());

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute("admin");
        }

        return $this->render('admin/create.html.twig', [
            "article" => $article,
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/admin/{id}", name="delete_article", methods="SUP")
     */
    public function delete(Article $article, HttpFoundationRequest $request, EntityManagerInterface $objectManager) {

        if($this->isCsrfTokenValid("SUP".$article->getId(), $request->get('_token'))) {
            $objectManager->remove($article);
            $objectManager->flush();
            $this->addFlash('success', "L'action a été effectué");
             return $this->redirectToRoute("admin");
        }
     }
}
