<?php

namespace App\Services;

use HelgeSverre\Mistral\Enums\Model;
use HelgeSverre\Mistral\Mistral;

class MistralService
{
    private Mistral $mistralClient;

    public function __construct(Mistral $mistralClient)
    {
        $this->mistralClient = $mistralClient;
    }

    public function query(string $context, string $text, mixed $model = Model::large->value)
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
            ], model: $model
        );
        $choices = $res->json();
        return $choices['choices'][0]['message']['content'];
    }
}
