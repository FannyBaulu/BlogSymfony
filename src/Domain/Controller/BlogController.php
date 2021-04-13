<?php

namespace App\Domain\Controller;

use App\Application\Entity\Comment;
use App\Application\Entity\Post;
use App\Domain\Handler\CommentHandler;
use App\Domain\Handler\PostHandler;
use App\Application\Security\Voter\PostVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * Class BlogController
     * @package App\Domain\Controller
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
    public function read(Post $post,Request $request, CommentHandler $commentHandler):Response
    {
        $comment = new Comment;
        $comment->setPost($post);

        if($this->isGranted('ROLE_USER')){
            $comment->setUser($this->getUser());
        }

        $options = [
            "validation_groups" => $this->isGranted("ROLE_USER") ? "Default":["Default","anonymous"]
        ];
        if($commentHandler->handle($request, $comment,$options)){
            return $this->redirectToRoute("blog_read",["id" => $post->getId()]);
        }
        return $this->render('blog/read.html.twig',[
            "post"=>$post,
            "form"=>$commentHandler->createView()
        ]);
    }

    /**
     * @Route("/publier-article", name="blog_create")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function create(
        Request $request,
        PostHandler $postHandler
    ) : Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = new Post();
        $post->setUser($this->getUser());
        $options = [ "validation_groups"=> ["Default","create"] ];
        if($postHandler->handle($request,$post,$options)){
            return $this->redirectToRoute("blog_read",["id" => $post->getId()]);
        }

        return $this->render("blog/create.html.twig",[
            "form" => $postHandler->createView()
        ]);

    }

    /**
     * @Route("/modifier-article/{id}", name="blog_update")
     * @param Request $request
     * @param Post $post
     * @return Response
     * @throws Exception
     */
    public function update(
        Request $request,
        Post $post,
        PostHandler $postHandler
    ) : Response {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);
        
        if($postHandler->handle($request,$post)){
            return $this->redirectToRoute("blog_read",["id" => $post->getId()]);
        }

        return $this->render("blog/update.html.twig",[
            "form" => $postHandler->createView()
        ]);

    }

}
