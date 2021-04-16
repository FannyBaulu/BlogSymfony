<?php

namespace App\Tests;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends WebTestCase
{
    public function testSuccessful()
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET,'/inscription');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=user]")->form([
            'user[email]' => 'email@email.com',
            'user[pseudo]' => 'pseudo',
            'user[plainPassword][first]' => 'password',
            'user[plainPassword][second]' => 'password'
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        
        $client->followRedirect();

        $this->assertRouteSame('security_login');
    }

    /**
     * @dataProvider provideFailed
     * @param array $formData
     * @param string $errorMessage
     */
    public function testFailed(array $formData, string $errorMessage)
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_DELETE,'/inscription');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=user]")->form($formData);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('html', $errorMessage);
    }

    public function provideFailed(): Generator
    {
        yield[
            [
                'user[email]' => '',
                'user[pseudo]' => 'pseudo',
                'user[plainPassword][first]' => 'password',
                'user[plainPassword][second]' => 'password'
            ],
            'This value should not be blank.'
        ];
        yield[
            [
                'user[email]' => 'fail',
                'user[pseudo]' => 'pseudo',
                'user[plainPassword][first]' => 'password',
                'user[plainPassword][second]' => 'password'
            ],
            'This value is not a valid email address.'
        ];
        
        yield[
            [
                'user[email]' => 'email1@email.com',
                'user[pseudo]' => 'pseudo',
                'user[plainPassword][first]' => 'password',
                'user[plainPassword][second]' => 'password'
            ],
            'Cet E-mail existe déjà.'
        ];

        yield[
            [
                'user[email]' => 'email@email.com',
                'user[pseudo]' => 'pseudo1',
                'user[plainPassword][first]' => 'password',
                'user[plainPassword][second]' => 'password'
            ],
            'Ce pseudo existe déjà.'
        ];

        yield[
            [
                'user[email]' => 'email@email.com',
                'user[pseudo]' => '',
                'user[plainPassword][first]' => 'password',
                'user[plainPassword][second]' => 'password'
            ],
            'This value should not be blank.'
        ];

        yield[
            [
                'user[email]' => 'email@email.com',
                'user[pseudo]' => 'pseudo',
                'user[plainPassword][first]' => '',
                'user[plainPassword][second]' => ''
            ],
            'This value should not be blank.'
        ];

        yield[
            [
                'user[email]' => 'email@email.com',
                'user[pseudo]' => 'pseudo',
                'user[plainPassword][first]' => 'password',
                'user[plainPassword][second]' => 'fail'
            ],
            'Les deux saisies du mot de passe ne correspondent pas.'
        ];

        
        yield[
            [
                'user[email]' => 'email@email.com',
                'user[pseudo]' => 'pseudo',
                'user[plainPassword][first]' => 'fail',
                'user[plainPassword][second]' => 'fail'
            ],
            'This value is too short. It should have 8 characters or more.'
        ];
    }
}