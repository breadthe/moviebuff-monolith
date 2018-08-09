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
class CatalogMovieTest extends TestCase
{
    use Transaction;

    protected $user;
    protected $movies;
    protected $catalog_id; // Used to test untagging
    protected $movie_id; // Used to test untagging

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        factory(Catalog::class, 5)->create([
            'user_id' => $this->user->id
        ]);

        factory(Movie::class, 5)->create();

        // Array of movies in the format returned by the OMDB API
        $this->movies = [
            [
                'Poster' => 'https://m.media-amazon.com/images/M/MV5BMmVmODY1MzEtYTMwZC00MzNhLWFkNDMtZjAwM2EwODUxZTA5XkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg',
                'Title' => 'Jaws',
                'Type' => 'movie',
                'Year' => '1975',
                'imdbID' => 'tt0073195'
            ],
            [
                'Poster' => 'https://m.media-amazon.com/images/M/MV5BOTE2OTk4MTQzNV5BMl5BanBnXkFtZTcwODUxOTM3OQ@@._V1_SX300.jpg',
                'Title' => 'Sharknado',
                'Type' => 'movie',
                'Year' => '2013',
                'imdbID' => 'tt2724064'
            ]
        ];

        $this->movie_id = $this->movies[0]['imdbID']; // The movie that is tagged (added to a catalog)
    }

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    /**
     * POST /catalogs
     * Test I can add a movie to an existing catalog
     */
    public function test_add_movie_to_existing_catalog()
    {
        $catalog = $this->user->catalogs()->first();
        $this->catalog_id = $catalog->id;

        $body = [
            'catalog_id' => $catalog->id,
            'movie' => $this->movies[0]
        ];

        $this->actingAs($this->user, 'api')->json('POST', '/api/catalog', $body, ['Accept' => 'application/json'])
            ->assertOk();
    }

    /**
     * POST /catalogs
     * Test I can add a movie to a new catalog
     */
    public function test_add_movie_to_new_catalog()
    {
        $catalog_name = 'New Catalog';

        $body = [
            'catalog_name' => $catalog_name,
            'movie' => $this->movies[1]
        ];

        $this->actingAs($this->user, 'api')->json('POST', '/api/catalog', $body, ['Accept' => 'application/json'])
            ->assertOk();
    }

    /**
     * DELETE /catalog/{catalog}/movie/{movie}
     * Test I can remove a movie from a catalog (untag it)
     */
    public function test_remove_movie_from_catalog()
    {
        $catalog = $this->user->catalogs()->first();
        $this->catalog_id = $catalog->id;

        $this->actingAs($this->user, 'api')->json('DELETE', "/api/catalog/{$catalog->id}/movie/{$this->movie_id}", [], ['Accept' => 'application/json'])
            ->assertOk();
    }

    /**
     * PUT /catalog/{catalog}/movie/{movie}
     * Test I can move/copy a movie to an existing Catalog
     */
    public function test_move_or_copy_movie_to_existing_catalog()
    {
    }

    /**
     * PUT /catalog
     * TODO: should be
     * PUT /catalog/{catalog}/movie/{movie}
     * Test I can move/copy a movie to a new Catalog
     */
    public function test_move_or_copy_movie_to_new_catalog()
    {
    }

    // Route::put('/catalog/{catalog}/movie/{movie}', 'Api\CatalogMovieController@update');

    // Route::put('/catalog', 'Api\CatalogMovieController@store');
}
