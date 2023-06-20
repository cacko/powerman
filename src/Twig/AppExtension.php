<?php

namespace App\Twig;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        protected HttpClientInterface $client,
    )
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('error_message', [$this, 'errorMessage']),
        ];
    }

    public function errorMessage()
    {
        $response = $this->client->request(
            'GET',
            'https://commit.cacko.net/index.txt'
        );

        return $response->getContent();
    }
}