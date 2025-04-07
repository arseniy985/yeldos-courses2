<?php

namespace App\Services;

use HelgeSverre\Mistral\Mistral;

class MistralService
{
    private Mistral $mistralClient;

    public function __construct(Mistral $mistralClient)
    {
        $this->mistralClient = $mistralClient;
    }

    public function query(string $context, string $text)
    {
        // echo(json_encode([
        //         [
        //             'role' => 'system',
        //             'content' => "$context"
        //         ],
        //         [
        //             'role' => 'user',
        //             'content' => "$text"]]
        //         ));
        // exit();
        $res = $this->mistralClient->chat()->create(
            messages: [
                [
                    'role' => 'system',
                    'content' => "$context"
                ],
                [
                    'role' => 'user',
                    'content' => "$text"
                ]
            ]
        );
        $choices = $res->json();
        return $choices['choices'][0]['message']['content'];
    }
}
