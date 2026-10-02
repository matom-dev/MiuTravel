<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\Hotel;
use App\Models\Tour;
use Tests\TestCase;

class ChatbotControllerTest extends TestCase
{
    public function test_guest_can_send_message_without_legacy_hotel_price_column(): void
    {
        putenv('GEMINI_API_KEY=');
        $_ENV['GEMINI_API_KEY'] = '';
        $_SERVER['GEMINI_API_KEY'] = '';

        Tour::create([
            't_title' => 'Tour Phong Nha',
            't_journeys' => 'Dong Hoi - Phong Nha',
            't_starting_gate' => 'Dong Hoi',
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_description' => 'Kham pha hang dong Phong Nha.',
        ]);

        Hotel::create([
            'h_name' => 'Khach san Phong Nha',
            'h_address' => 'Phong Nha',
            'h_description' => 'Gan trung tam du lich.',
            'h_status' => Hotel::STATUS_VISIBLE,
        ]);

        $response = $this->postJson('/chat/send', [
            'message' => 'Tư vấn tour và khách sạn Phong Nha',
        ]);

        $response->assertOk()
            ->assertJsonPath('user.sender', 'user')
            ->assertJsonPath('bot.sender', 'bot');

        $this->assertSame(2, ChatMessage::count());
        $this->assertDatabaseHas('chat_messages', [
            'sender' => 'user',
            'message' => 'Tư vấn tour và khách sạn Phong Nha',
        ]);
    }
}
