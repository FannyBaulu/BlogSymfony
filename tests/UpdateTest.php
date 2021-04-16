<?php

namespace App\Tests;

use App\Application\Entity\Post;
use App\Application\Entity\User;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateTest extends WebTestCase
{
    use AuthenticationTrait, UploadTrait;

    

    public function testAccessDenied()
    {
        $client = static::createAuthenticatedClient();

        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get("router");

        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager"); 

        /**
         * @var User $user
         */
        $user= $entityManager->getRepository(User::class)->findOneByEmail("email1@email.com");

        $post= $entityManager->createQueryBuilder()
            ->select("p")
            ->from(Post::class,"p")
            ->where("p.user != :user")
            ->setParameter("user",$user)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("blog_update",["id" => $post->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testWithoutAuthentication()
    {
        $client = static::createClient();

        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get("router");

        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        $post= $entityManager->getRepository(Post::class)->findOneBy([]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("blog_update",["id" => $post->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame('security_login');
    }

    public function testSuccessful()
    {
        $client = static::createAuthenticatedClient();

        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get("router");

        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        /**
         * @var User $user
         */
        $user= $entityManager->getRepository(User::class)->findOneByEmail("email1@email.com");

        $post= $entityManager->getRepository(Post::class)->findOneBy(["user" => $user]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("blog_update",["id" => $post->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=post]")->form([
            "post[title]" => "Title",
            "post[content]" => "Content article",
            "post[file]" => static::createImage()
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame("blog_read");
    }

    /**
     * @dataProvider provideFailed
     * @param array $formData
     * @param string $errorMessage
     */
    public function testFailed(array $formData,string $errorMessage)
    {
        $client = static::createAuthenticatedClient();

        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get("router");

        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        /**
         * @var User $user
         */
        $user= $entityManager->getRepository(User::class)->findOneByEmail("email1@email.com");

        $post= $entityManager->getRepository(Post::class)->findOneBy(["user" => $user]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("blog_update",["id" => $post->getId()])
        );

        $form = $crawler->filter("form[name=post]")->form($formData);

        $client->submit($form);

        $this->assertSelectorTextContains('html', $errorMessage);
    }

    public function provideFailed(): Generator
    {
        yield
        [   
            [
                "post[title]" => "",
                "post[content]" => "Content article",
                "post[file]" => static::createImage()
            ],
            'This value should not be blank.'
        ];
        yield
        [   
            [
                "post[title]" => "Title",
                "post[content]" => "",
                "post[file]" => static::createImage()
            ],
            'This value should not be blank.'
        ];
        yield
        [   
            [
                "post[title]" => "T",
                "post[content]" => "Coment article",
                "post[file]" => static::createImage()
            ],
            'This value is too short. It should have 2 characters or more.'
        ];
        yield
        [   
            [
                "post[title]" => "Title",
                "post[content]" => "Fail",
                "post[file]" => static::createImage()
            ],
            'This value is too short. It should have 10 characters or more.'
        ];
        yield
        [   
            [
                "post[title]" => "Title",
                "post[content]" => "Content article",
                "post[file]" => static::createTextFile()
            ],
            'This file is not a valid image.'
        ];
    }
}