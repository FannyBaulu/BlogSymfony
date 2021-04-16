<?php

namespace App\Tests;

use App\Application\Entity\Post;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testSuccessfulWithoutAuthentication()
    {
        $client=static::createClient();

        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get("router");

        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        /**
         * @var Post $post
         */
        $post= $entityManager->getRepository(Post::class)->findOneBy([]);

        $count = $post->getComments()->count();

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("blog_read",["id" => $post->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('html h1',$post->getTitle());

        $form = $crawler->filter('form[name=comment]')->form([
            'comment[author]' => 'Author',
            'comment[content]' => 'Nouveau Commentaire'
        ]);
        
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('html','Nouveau Commentaire');

        $this->assertCount(
            $count+1,
            $crawler->filter('html main h6')
        );
    }

    public function testSuccessfulWithAuthentication()
    {
        $client=static::createAuthenticatedClient();

        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get("router");

        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        /**
         * @var Post $post
         */
        $post= $entityManager->getRepository(Post::class)->findOneBy([]);

        $count = $post->getComments()->count();

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("blog_read",["id" => $post->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('html h1',$post->getTitle());

        $form = $crawler->filter('form[name=comment]')->form([
            'comment[content]' => 'Nouveau Commentaire'
        ]);
        
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('html','Nouveau Commentaire');

        $this->assertCount(
            $count+1,
            $crawler->filter('html main h6')
        );
    }

    /**
     * @dataProvider provideFailed
     * @param array $formData
     * @param string $errorMessage
     */
    public function testFail(array $formData,string $errorMessage)
    {
        $client=static::createClient();

        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get("router");

        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        /**
         * @var Post $post
         */
        $post= $entityManager->getRepository(Post::class)->findOneBy([]);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("blog_read",["id" => $post->getId()])
        );

        $form = $crawler->filter('form[name=comment]')->form($formData);
        
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('html', $errorMessage);
    }

    /**
     * @return Generator
     */
    public function provideUri():Generator
    {
        yield ['/'];
        yield ['/?page=2'];
    }

    public function provideFailed(): Generator
    {
        yield [
            [
                'comment[author]' => '',
                'comment[content]' => 'Nouveau Commentaire'
            ],
            'This value should not be blank.'
        ];
        yield [
            [
                'comment[author]' => 'Author',
                'comment[content]' => ''
            ],
            'This value should not be blank.'
        ];
        yield [
            [
                'comment[author]' => 'A',
                'comment[content]' => 'Nouveau Commentaire'
            ],
            'This value is too short. It should have 2 characters or more.'
        ];
        yield [
            [
                'comment[author]' => 'Author',
                'comment[content]' => 'Fail'
            ],
            'This value is too short. It should have 5 characters or more.'
        ];
    }
}