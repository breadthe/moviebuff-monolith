<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions as Transaction;
use App\Catalog;
use App\Movie;
use App\User;

/**
 * Tests the /catalogs endpoints which handle the catalog-movie relationship
 */
class MoviesTest extends TestCase
{
    use Transaction;

    protected $user;
    protected $movies;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        factory(Catalog::class, 5)->create([
            'user_id' => $this->user->id
        ]);

        // Movie in the format returned by the OMDB API
        $this->movie = [
            'Poster' => 'https://m.media-amazon.com/images/M/MV5BMmVmODY1MzEtYTMwZC00MzNhLWFkNDMtZjAwM2EwODUxZTA5XkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg',
            'Title' => 'Jaws',
            'Type' => 'movie',
            'Year' => '1975',
            'imdbID' => 'tt0073195'
        ];

        $this->movie_id = $this->movie['imdbID']; // The movie that is tagged (added to a catalog)
    }

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    /**
     * GET /movie/{movie}/catalogs
     * Test I can see all the catalogs a movie was tagged with
     */
    public function test_retrieve_all_catalogs_a_movie_belongs_to()
    {
        // Get the first 3 catalogs belonging to the user
        $catalogs = $this->user->catalogs()->take(3)->get();

        // Tag $this->movie with the 3 catalogs
        $catalogs->map(function ($catalog) {
            $body = [
                'catalog_id' => $catalog->id,
                'movie' => $this->movie
            ];

            $this->actingAs($this->user, 'api')->json('POST', '/api/catalog', $body, ['Accept' => 'application/json']);
        });

        // Retrieve the catalogs and assert that all 3 are present
        $this->actingAs($this->user, 'api')->json('GET', "/api/movies/{$this->movie_id}/catalogs", [], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonCount(3);
    }
}
