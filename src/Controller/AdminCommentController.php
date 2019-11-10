<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repo->findAll();

        return $this->render('admin/comment/index.html.twig', [
           'comments' => $comments,
        ]);
    }

    /**
     * Permet de modifier un commentaire
     *
     * @Route("admin/comments/{id}/edit" , name="admin_comment_edit")
     *
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager){

      //  $comment = new Comment();

        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        //Vérification champs formulaire
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire numéro {$comment->getId()} a ien été modifié !"
            );
        }

        return $this->render('admin/comment/edit.html.twig',[
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager){

        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_comment_index');

    }
}
