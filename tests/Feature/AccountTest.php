<?php

namespace Tests\Feature;

use App\Exceptions\AccountNumberExists;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_account_in_bank_system(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user);


        $response2 = $this->postJson('/api/accountnumber', ['id'=>$user->id,'userdata'=>999999999]);

        $response2
            ->assertStatus(200)
            ->assertJson([
                'message' => 'account number generate successfully.',
            ]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
        ]);

    }
    public function test_if_user_can_have_multi_account(){

        $user = User::factory()->create();

        $response = $this->actingAs($user);


         $this->postJson('/api/accountnumber', ['id'=>$user->id,'userdata'=>999999999]);

        $response2 = $this->postJson('/api/accountnumber', ['id'=>$user->id,'userdata'=>999999999]);

        $response2
            ->assertStatus(500)->withException(new AccountNumberExists());
    }
}
