<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransferTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_transfer_money_from_account_to_another_in_bank_system(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user);


        $response2 = $this->postJson('/api/accountnumber', ['id'=>$user->id,'userdata'=>999999999]);
        $receiver_user = User::factory()->has(Account::factory())->create();
        $receiver_account = $receiver_user->account;
        $response3=$this->postJson('/api/transfer', ['receiver_account_number'=>$receiver_account->id,'amount'=>9966,'description'=>'description','pin'=>'password']);
        $response2
            ->assertStatus(200)
            ->assertJson([
                'message' => 'account number generate successfully.',
            ]);
        $response3
            ->assertStatus(200)
            ->assertJson([
                    "success"=> true,
                    "data"=> [],
                    "message"=>"transfer successfully."
                ]
            );


        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
        ]);

    }
}
