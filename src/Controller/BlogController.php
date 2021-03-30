<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
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
        $limit = $request->get("limit",10);
        $page = $request->get("page",1);
        /** @var Paginator $posts */
        $posts = $this->getDoctrine()->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );
        $pages = ceil($posts->count()/10);
        $range = range(
            max($page - 3,1),
            min($page + 3,$pages)
        );
        return $this->render('blog/index.html.twig',[
            "posts"=>$posts,
            "pages"=>$pages,
            "page" => $page,
            "limit" => $limit,
            "range" => $range
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

    /**
     * @Route("/publier-article", name="blog_create")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function create(Request $request) : Response {
        $post = new Post;

        $form = $this->createForm(PostType::class, $post)->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("blog_read",["id" => $post->getId()]);
        }

        return $this->render("blog/create.html.twig",[
            "form" => $form->createView()
        ]);

    }

}
