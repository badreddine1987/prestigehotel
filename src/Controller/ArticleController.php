<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ArticleController extends AbstractController
{
    #[Route('/gerer-les-articles', name: 'article_dashboard', methods: ['GET'])]
    public function articleDashboard(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findBy(['deletedAt' => null]);

        return $this->render('admin/article_dashboard.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/creer-un-article', name: 'create_article', methods: ['GET', 'POST'])]
    public function createArticle(ArticleRepository $repository, SluggerInterface $slugger, Request $request): Response
    {
        $article = new Article;

        $form = $this->createForm(ArticleFormType::class, $article)
            ->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {

            $article->setCreatedAt(new DateTime());
            $article->setUpdatedAt(new DateTime());
            $article->setAlias($slugger->slug($article->getTitle()));
            $article->setAuthor($this->getUser());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if($photo){
                $this->handleFile($photo, $slugger, $article);
            } // end if $photo

            $repository->add($article, true);

            $this->addFlash('success', "L'article a bien été ajouté, il est en ligne !");
            return $this->redirectToRoute('article_dashboard');

        } // end if $form

        return $this->render('admin/form/article.html.twig', [
            'form' => $form->createView()
        ]);
    } // end function createArticle()

    
    #[Route('/modifier-un-article/{id}', name: 'update_article', methods: ['GET', 'POST'])]
    public function updateArticle(Article $article, Request $request, ArticleRepository $repository, SluggerInterface $slugger): Response
    {
        $originalPhoto = $article->getPhoto();

        $form = $this->createForm(ArticleFormType::class, $article, [
            'photo' => $originalPhoto
        ])->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {

            $article->setUpdatedAt(new DateTime());
            $article->setAlias($slugger->slug($article->getTitle()));
            $article->setAuthor($this->getUser());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if($photo){
                $this->handleFile($photo, $slugger, $article);
            }
            else {
                $article->setPhoto($originalPhoto);
            } // end if $photo

            $repository->add($article, true);

            $this->addFlash('success', "L'article a bien été modifié !");
            return $this->redirectToRoute('article_dashboard');

        } // end if $form

        return $this->render('admin/form/article.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    } // end function updateArticle()

    #[Route('/archiver-un-article/{id}', name: 'soft_delete_article', methods: ['GET'])]
    public function softDeleteArticle(Article $article, ArticleRepository $repository): RedirectResponse
    {
        $article->setDeletedAt(new DateTime());

        $repository->add($article, true);

        $this->addFlash('success', "L'article a bien été archivé. Voir les archives !");
        return $this->redirectToRoute('article_dashboard');
    } // end function softDelete()

    #[Route('/archives-des-articles', name: 'articles_archives', methods: ['GET'])]
    public function articlesArchives(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAllArchived();

        return $this->render('admin/articles_archives.html.twig', [
            'articles' => $articles,
        ]);
    } // end function articlesArchives

    #[Route('/restaurer-un-article/{id}', name: 'restore_article', methods: ['GET'])]
    public function restoreArticle(Article $article, ArticleRepository $repository): RedirectResponse
    {
        $article->setDeletedAt(null);

        $repository->add($article, true);

        $this->addFlash('success', "L'article a bien été restauré !");
        return $this->redirectToRoute('articles_archives');
    } // end function restoreArticle()

    #[Route('/supprimer-un-article/{id}', name: 'hard_delete_article', methods: ['GET'])]
    public function hardDeleteArticle(Article $article, ArticleRepository $repository): RedirectResponse
    {
        $photo = $article->getPhoto();

        if($photo){
            // Pour supprimer un fichier dans le système, on utilise la fonction native de PHP unlink().
            unlink($this->getParameter('uploads_dir') . '/' . $photo);
        }

        $repository->remove($article, true);

        $this->addFlash('success', "L'article a bien été supprimé definitivement du système !");
        return $this->redirectToRoute('articles_archives');
    } // end function hardDeleteArticle()

    
    #[Route('/voir-les-articles', name: 'show_articles', methods: ['GET'])]
    public function showArticlesFromCategory(ArticleRepository $repository): Response
    {
        $articles = $repository->findBy([
            'deletedAt' => null,
        ]);

        return $this->render('article/show_articles.html.twig', [
            'articles' => $articles,
        ]);
    } // end function showArticles

    #[Route('{art_alias}_{id}', name: 'show_article', methods: ['GET'])]
    public function showArticle(Article $article): Response
    {
        return $this->render('article/show_article.html.twig', [
            'article' => $article
        ]);
    } // end function showArticle

    private function handleFile(UploadedFile $photo, SluggerInterface $slugger, Article $article): void
    {
        $extension = '.' . $photo->guessExtension();
        $safeFilename = $slugger->slug($article->getTitle());

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $article->setPhoto($newFilename);
        } catch (FileException $exception) {
            // code à exécuter si erreur.
        }
    } // end function handleFile()
}
