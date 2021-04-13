<?php

namespace App\Tests;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testSuccessful()
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_DELETE,'/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form([
            'login[username]' => 'email1@email.com',
            'login[password]' => 'password'
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        
        $client->followRedirect();

        $this->assertRouteSame('home');
    }

    /**
     * @dataProvider provideFailed
     * @param array $formData
     * @param string $errorMessage
     */
    public function testFail(array $formData, string $errorMessage)
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_DELETE,'/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter("form[name=login]")->form($formData);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        
        $client->followRedirect();

        $this->assertSelectorTextContains('html', $errorMessage);
    }

    public function provideFailed(): Generator
    {
        yield[
            [
                'login[username]' => 'email1@email.com',
                'login[password]' => 'fail'
            ],
            'Password not valid'
        ];
        yield[
            [
                'login[username]' => 'fail',
                'login[password]' => 'password'
            ],
            'User "fail" not found'
        ];
        
        yield[
            [
                'login[username]' => '',
                'login[password]' => ''
            ],
            'Email and/or password is not valid.'
        ];
    }
}