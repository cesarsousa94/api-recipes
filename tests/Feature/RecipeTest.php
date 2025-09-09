<?php

namespace Tests\Feature;

use Tests\TestCase;

class RecipeTest extends TestCase
{
    public function test_creates_lists_updates_and_deletes_recipes(): void
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        // create
        $r = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/recipes', ['title'=>'Bolo', 'ingredients'=>['trigo','farinha']])
            ->assertCreated()->json('data');

        // list
        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/recipes?q=Bolo')
            ->assertOk()->assertJsonFragment(['title'=>'Bolo']);

        // update
        $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/recipes/{$r['uuid']}", ['title'=>'Bolo de Cenoura'])
            ->assertOk()->assertJsonFragment(['title'=>'Bolo de Cenoura']);

        // print
        $this->withHeader('Authorization', "Bearer $token")
            ->get("/api/recipes/{$r['uuid']}/print")
            ->assertOk();

        // delete
        $this->withHeader('Authorization', "Bearer $token")
            ->delete("/api/recipes/{$r['uuid']}")
            ->assertNoContent();
    }
}
