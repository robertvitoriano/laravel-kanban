<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Board;
use Illuminate\Support\Facades\Log;


class AddBoardMemberTest extends TestCase
{
    use RefreshDatabase;

    public function testBoardMembershipIsCreatedSuccessfully()
    {
        $user = User::factory()->create(['level' => 'admin']);
        $this->actingAs($user);
        $board = Board::factory()->create();

        Log::debug($board);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
        ->postJson('/api/boards/create-membership', [
            'user_id' => $user->id,
            'board_id' => $board->id,
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'board membership created']);
    }
}
