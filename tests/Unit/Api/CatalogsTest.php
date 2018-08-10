<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions as Transaction;
use App\Catalog;
use App\Movie;
use App\User;

/**
 * Tests /catalogs endpoints
 */
class CatalogsTest extends TestCase
{
    use Transaction;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        factory(Catalog::class, 5)->create([
            'user_id' => $this->user->id
        ]);

        factory(Movie::class, 5)->create();
    }

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    /**
     * Test 200 response
     */
    public function test_200_response()
    {
        $this->actingAs($this->user, 'api')->get('/')->assertStatus(200);
    }

    /**
     * GET /catalogs
     * Test I can see all the user's catalogs
     */
    public function test_get_catalogs()
    {
        $this->actingAs($this->user, 'api')->json('GET', '/api/catalogs', [], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonCount(5)
            ->assertJsonStructure(
                [['id', 'name', 'movies', 'created_at']]
            );
    }

    /**
     * PUT /catalog/{id}
     * Test I can edit the catalog name
     */
    public function test_edit_catalog_name()
    {
        $new_catalog_name = 'New Catalog Name';

        $catalog = $this->user->catalogs()->first();

        $body = ['catalog_name' => $new_catalog_name];

        $this->actingAs($this->user, 'api')->json('PUT', "/api/catalog/{$catalog->id}", $body, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJson(['catalog_name' => $new_catalog_name]);
    }

    /**
     * DELETE /catalog/{id}
     * Test I can delete a catalog
     */
    public function test_delete_catalog()
    {
        $catalog = $this->user->catalogs()->first();
        $catalog_id = $catalog->id;

        // Delete the first catalog belonging to this user
        $this->actingAs($this->user, 'api')->json('DELETE', "/api/catalog/{$catalog->id}", [], ['Accept' => 'application/json'])
            ->assertOk();

        // Then check if there are 4 catalogs remaining (out of the 5 created initially)
        $this->actingAs($this->user, 'api')->json('GET', '/api/catalogs', [], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonCount(4);
    }
}
