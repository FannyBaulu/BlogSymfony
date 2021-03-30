<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * Class BlogController
     * @package App\Controller
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->getAllPosts();
        return $this->render('blog/index.html.twig',[
            "posts"=>$posts
        ]);
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     * @param Post $post
     * @param Request $request
     * @return Response
     */
    public function read(Post $post,Request $request):Response
    {
        $comment = new Comment;
        $comment->setPost($post);
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("blog_read",["id" => $post->getId()]);
        }
        return $this->render('blog/read.html.twig',[
            "post"=>$post,
            "form"=>$form->createView()
        ]);
    }

}
