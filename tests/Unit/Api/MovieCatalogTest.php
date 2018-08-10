<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions as Transaction;
use App\Catalog;
use App\Movie;
use App\User;

/**
 * Tests the /catalogs endpoints which handle the movie-catalog relationship
 */
class MovieCatalogTest extends TestCase
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
     * POST /catalog
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

        $this->actingAs($this->user, 'api')->json('POST', '/api/movie/catalog', $body, ['Accept' => 'application/json'])
            ->assertOk();
    }

    /**
     * POST /catalog
     * Test I can add a movie to a new catalog
     */
    public function test_add_movie_to_new_catalog()
    {
        $new_catalog_name = 'New Catalog';

        $body = [
            'catalog_name' => $new_catalog_name,
            'movie' => $this->movies[1]
        ];

        $this->actingAs($this->user, 'api')->json('POST', '/api/movie/catalog', $body, ['Accept' => 'application/json'])
            ->assertOk();
    }

    /**
     * DELETE /catalog/{catalog}/movie/{movie}
     * Test I can remove a movie from a catalog (untag it)
     */
    public function test_remove_movie_from_catalog()
    {
        $catalog = $this->user->catalogs()->first();
        // $this->catalog_id = $catalog->id;

        $this->actingAs($this->user, 'api')->json('DELETE', "/api/movie/{$this->movie_id}/catalog/{$catalog->id}", [], ['Accept' => 'application/json'])
            ->assertOk();
    }

    /**
     * PUT /catalog/{catalog}/movie/{movie}
     * Test I can Move a movie to an existing Catalog
     */
    public function test_move_movie_to_existing_catalog()
    {
        // Find the user's catalogs and get the first 2
        $catalogs = $this->user->catalogs()->get();

        $from_catalog_id = $catalogs[0]->id;
        $to_catalog_id = $catalogs[1]->id;

        // Attach the movie to the source catalog
        $body = [
            'catalog_id' => $from_catalog_id,
            'movie' => $this->movies[0]
        ];
        $this->actingAs($this->user, 'api')->json('POST', '/api/movie/catalog', $body, ['Accept' => 'application/json']);

        // Move the movie to the destination catalog
        $body = [
            'action' => 'move',
            'source_catalog_id' => $from_catalog_id,
            'destination_catalog_id' => $to_catalog_id
        ];
        $this->actingAs($this->user, 'api')->json('PUT', "/api/movie/{$this->movie_id}/catalog", $body, ['Accept' => 'application/json'])
            ->assertOk();

        // Check if the movie exists in the destination catalog
        $this->assertEquals($this->movie_id, $this->user->catalogs()->with('movies')->find($to_catalog_id)->movies->first()->id);

        // And doesn't exist in the source catalog
        $this->assertCount(0, $this->user->catalogs()->with('movies')->find($from_catalog_id)->movies);
    }

    /**
     * PUT /catalog/{catalog}/movie/{movie}
     * Test I can Copy a movie to an existing Catalog
     */
    public function test_copy_movie_to_existing_catalog()
    {
        // Find the user's catalogs and get the first 2
        $catalogs = $this->user->catalogs()->get();

        $from_catalog_id = $catalogs[0]->id;
        $to_catalog_id = $catalogs[1]->id;

        // Attach the movie to the source catalog
        $body = [
            'catalog_id' => $from_catalog_id,
            'movie' => $this->movies[0]
        ];
        $this->actingAs($this->user, 'api')->json('POST', '/api/movie/catalog', $body, ['Accept' => 'application/json']);

        // Copy the movie to the destination catalog
        $body = [
            'action' => 'copy',
            'source_catalog_id' => $from_catalog_id,
            'destination_catalog_id' => $to_catalog_id
        ];
        $this->actingAs($this->user, 'api')->json('PUT', "/api/movie/{$this->movie_id}/catalog", $body, ['Accept' => 'application/json'])
            ->assertOk();

        // Check if the movie exists in the destination catalog
        $this->assertEquals($this->movie_id, $this->user->catalogs()->with('movies')->find($to_catalog_id)->movies->first()->id);

        // And also exists in the source catalog
        $this->assertEquals($this->movie_id, $this->user->catalogs()->with('movies')->find($from_catalog_id)->movies->first()->id);
    }

    /**
     * PUT /catalog
     * TODO: should be
     * PUT /movie/{movie}
     * or /movie/{movie}/move
     * with catalog_name, action in the payload
     * Test I can Move a movie to a new Catalog
     */
    public function test_move_movie_to_new_catalog()
    {
        // Find the user's first catalog
        $catalog = $this->user->catalogs()->first();

        $from_catalog_id = $catalog->id;

        $new_catalog_name = 'New Catalog';

        // Attach the movie to the source catalog
        $body = [
            'catalog_id' => $from_catalog_id,
            'movie' => $this->movies[0]
        ];
        $this->actingAs($this->user, 'api')->json('POST', '/api/movie/catalog', $body, ['Accept' => 'application/json']);

        // Move the movie to the destination catalog
        $body = [
            'action' => 'move',
            'source_catalog_id' => $from_catalog_id,
            'catalog_name' => $new_catalog_name
        ];
        $this->actingAs($this->user, 'api')->json('PUT', "/api/movie/{$this->movie_id}/catalog", $body, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJson(['catalog_name' => $new_catalog_name]);

        // Find the newly created catalog
        $new_catalog = $this->user->catalogs()->where('name', $new_catalog_name)->first();

        // Check if the movie exists in the destination catalog
        $this->assertEquals($this->movie_id, $this->user->catalogs()->with('movies')->find($new_catalog->id)->movies->first()->id);

        // And doesn't exist in the source catalog
        $this->assertCount(0, $this->user->catalogs()->with('movies')->find($from_catalog_id)->movies);
    }

    /**
     * PUT /catalog
     * TODO: should be
     * PUT /movie/{movie}
     * or /movie/{movie}/copy
     * with catalog_name, action in the payload
     * Test I can Copy a movie to a new Catalog
     */
    public function test_copy_movie_to_new_catalog()
    {
        // Find the user's first catalog
        $catalog = $this->user->catalogs()->first();

        $from_catalog_id = $catalog->id;

        $new_catalog_name = 'New Catalog';

        // Attach the movie to the source catalog
        $body = [
            'catalog_id' => $from_catalog_id,
            'movie' => $this->movies[0]
        ];
        $this->actingAs($this->user, 'api')->json('POST', '/api/movie/catalog', $body, ['Accept' => 'application/json']);

        // Copy the movie to the destination catalog
        $body = [
            'action' => 'copy',
            'source_catalog_id' => $from_catalog_id,
            'catalog_name' => $new_catalog_name
        ];
        $this->actingAs($this->user, 'api')->json('PUT', "/api/movie/{$this->movie_id}/catalog", $body, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJson(['catalog_name' => $new_catalog_name]);

        // Find the newly created catalog
        $new_catalog = $this->user->catalogs()->where('name', $new_catalog_name)->first();

        // Check if the movie exists in the destination catalog
        $this->assertEquals($this->movie_id, $this->user->catalogs()->with('movies')->find($new_catalog->id)->movies->first()->id);

        // And also exists in the source catalog
        $this->assertEquals($this->movie_id, $this->user->catalogs()->with('movies')->find($from_catalog_id)->movies->first()->id);
    }
}
