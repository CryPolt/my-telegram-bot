<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $json = '{
            "update_id": 608379926,
            "message": {
                "message_id": 5,
                "from": {
                    "id": 726823229,
                    "is_bot": false,
                    "first_name": "Владислав",
                    "username": "crypolt",
                    "language_code": "en"
                },
                "chat": {
                    "id": 726823229,
                    "first_name": "Владислав",
                    "username": "crypolt",
                    "type": "private"
                },
                "date": 1705668102,
                "text": "/start",
                "entities": [
                    {
                        "offset": 0,
                        "length": 6,
                        "type": "bot_command"
                    }
                ]
            }
        }';
        $response = $this->post('/webhook', json_decode($json,true));
        $response->assertStatus(200);
    }
}
