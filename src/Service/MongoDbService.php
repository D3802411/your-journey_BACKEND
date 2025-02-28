<?php

namespace App\Service;

use DateTimeImmutable;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MongoDbService {

    private HttpClientInterface $httpClient;

    public function __construct (HttpClientInterface $httpClient)
    {   $this->httpClient = $httpClient;
        
    }
    public function countVisit (string $pageName) {
        $this->httpClient->request('POST', 'https://us-east-2.aws.neurelo.com/rest/visits/__one', [
            'headers' => [
                'X-API-KEY' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImFybjphd3M6a21zOnVzLWVhc3QtMjowMzczODQxMTc5ODQ6YWxpYXMvYjJjYWNlYWItQXV0aC1LZXkifQ.eyJlbnZpcm9ubWVudF9pZCI6IjQxM2ZhYjYyLTM0MTMtNGVhOS05YWQwLTkzMzk1ZDIzNjY4NCIsImdhdGV3YXlfaWQiOiJnd19iMmNhY2VhYi0yYTRlLTQ3YzYtOTlkZS1iNDM3M2I4NWE2MjIiLCJwb2xpY2llcyI6WyJSRUFEIiwiV1JJVEUiLCJVUERBVEUiLCJERUxFVEUiLCJDVVNUT00iXSwiaWF0IjoiMjAyNS0wMi0yOFQxMjozOTo1NC4wOTg5OTYwNzFaIiwianRpIjoiZjMyNTFlNmUtOTJlMC00MjY2LThmZGEtNTY0ZmNlMGUyMDk2In0.NFsVKt1ytBQvIyrKyuuhz4KVNkr5NS__GsWoKcXlWiJlPmyuzqn6_nZcS1x8hUiWOCv2z9buGMz6FRkoM6qhBAtcpcLcMkNbwT34r-PlWYkXVvx0uyZ2A-ULwbpIw99LCakWdNdkQ45SWsMeLLgRwfbkcN49SorC2GdU-Q8p046LykLqWC1-zxL2x8ehH4xCN4EaWnC31II3z6E8J6O93z5X2W7fu7NLQ2BB-dLgVfapB-d0Peae2wSoeLChDEGZIV4pb2njgmaCycyLlOqEFkwQPWBmI59sK3bMz1JoLXZ9xaGgIL1Mc8uO_SFesARcyYJe40quQIdy3TbFvgmdZw',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'pageName' => $pageName,
                'visitedAt' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
        ]);
    }

}