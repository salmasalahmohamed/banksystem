<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepositTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_deposit_in_bank_system(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user);


        $response2 = $this->postJson('/api/accountnumber', ['id'=>$user->id,'userdata'=>999999999]);
        $account_number=$user->account->id;
 $response3=$this->postJson('/api/deposit', ['account_number'=>$account_number,'amount'=>9966,'description'=>';;;;;;']);
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
    "message"=>"deposit successfully"
]
);


        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
        ]);

    }
}
