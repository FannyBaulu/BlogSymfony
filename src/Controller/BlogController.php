<?php

namespace App\Controller;

use App\Entity\Post;
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
     * @return Response
     */
    public function read(Post $post):Response
    {
        return $this->render('blog/read.html.twig',[
            "post"=>$post
        ]);
    }

}
