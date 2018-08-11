# MovieBuff-monolith API Reference

The internal MovieBuff API uses Laravel's built-in cookie authentication.

## Movies

### Get all the Catalogs (Tags) for a given Movie

Used in displaying the Tag list for each search result.

`GET /api/movies/{movie}/catalogs`

**Examples**

> Request
```shell
GET /api/movies/tt0080684/catalogs
```

> Response Body (Tags found)
```json
[
    {"id":1,"name":"Watchlist","created_at":"2018-08-06 22:14:59"},
    {"id":3,"name":"SciFi","created_at":"2018-08-06 22:15:38"}
]
```

> TODO: Response Body (no Tags)

This should be the correct behavior, not the 404
```json
[]
```

> Response Codes

Code | Status | Description
---- | ------ | -----------
200 | OK | Request succeeded
404 | Not found | The movie doesn't have any tags


## Catalogs (Tags)

### Get all the (logged in) User's Catalogs (Tags)

Used in displaying all the Catalogs (Tags) in the TagUntag and TagMoveCopy dropdowns.

`GET /api/catalogs`

**Examples**

> Request
```shell
GET /api/catalogs
```

> Response Body (Tags found)
```json
[
    {"id":1,"name":"Watchlist","created_at":"2018-08-06 22:14:59",
        "movies":[
            {"id":"tt0080684","title":"Star Wars: Episode V - The Empire Strikes Back","poster":"https:\/\/m.media-amazon.com\/images\/M\/MV5BYmU1NDRjNDgtMzhiMi00NjZmLTg5NGItZDNiZjU5NTU4OTE0XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SX300.jpg","type":"movie","year":1980,"created_at":"2018-08-06 22:15:33","updated_at":"2018-08-06 22:15:33"},
            {"id":"tt0086190","title":"Star Wars: Episode VI - Return of the Jedi","poster":"https:\/\/m.media-amazon.com\/images\/M\/MV5BOWZlMjFiYzgtMTUzNC00Y2IzLTk1NTMtZmNhMTczNTk0ODk1XkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg","type":"movie","year":1983,"created_at":"2018-08-06 22:15:58","updated_at":"2018-08-06 22:15:58"}
        ]
    },
    {"id":3,"name":"SciFi","created_at":"2018-08-06 22:15:38",
        "movies":[
            {"id":"tt0076759","title":"Star Wars: Episode IV - A New Hope","poster":"https:\/\/m.media-amazon.com\/images\/M\/MV5BNzVlY2MwMjktM2E4OS00Y2Y3LWE3ZjctYzhkZGM3YzA1ZWM2XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SX300.jpg","type":"movie","year":1977,"created_at":"2018-08-06 22:15:29","updated_at":"2018-08-06 22:15:29"},
            {"id":"tt0848228","title":"The Avengers","poster":"https:\/\/m.media-amazon.com\/images\/M\/MV5BNDYxNjQyMjAtNTdiOS00NGYwLWFmNTAtNThmYjU5ZGI2YTI1XkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg","type":"movie","year":2012,"created_at":"2018-08-11 15:50:01","updated_at":"2018-08-11 15:50:01"}
        ]
    }
]
```

> Response Body (no Tags)
```json
[]
```

> Response Codes

Code | Status | Description
---- | ------ | -----------
200 | OK | Request succeeded


## Catalog (Tag)

### Edit the Tag (Catalog) name

`PUT /api/catalog/{id}`

**Examples**

> Request
```shell
PUT /api/catalog/45
```

> Request Body

```json
{"catalog_name":"Wishlist"}
```

> Response Body
```json
{"catalog_name":"Wishlist"}
```

> Response Codes

Code | Status | Description
---- | ------ | -----------
200 | OK | Request succeeded

### Delete the Tag (Catalog) name

`DELETE /api/catalog/{id}`

**Examples**

> Request
```shell
DELETE /api/catalog/45
```
> Response Body

`empty`

> Response Codes

Code | Status | Description
---- | ------ | -----------
200 | OK | Request succeeded

## Movie-Catalog

### Tag a Movie with an existing or new Tag (Catalog)

`POST /api/movie/catalog`

**Examples**

> Request
```shell
POST /api/movie/catalog
```

> Request Body (Existing Tag)

```json
{
    "catalog_id":46,
    "movie":{
        "Title":"Star Wars: Episode IV - A New Hope","Year":"1977","imdbID":"tt0076759","Type":"movie","Poster":"https://m.media-amazon.com/images/M/MV5BNzVlY2MwMjktM2E4OS00Y2Y3LWE3ZjctYzhkZGM3YzA1ZWM2XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SX300.jpg"
    }
}
```

> Request Body (New Tag)

```json
{
    "catalog_name":"Tag",
    "movie":{
        "Title":"Star Wars: Episode IV - A New Hope","Year":"1977","imdbID":"tt0076759","Type":"movie","Poster":"https://m.media-amazon.com/images/M/MV5BNzVlY2MwMjktM2E4OS00Y2Y3LWE3ZjctYzhkZGM3YzA1ZWM2XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SX300.jpg"
    }
}
```

> Response Body

`empty`

> Response Codes

Code | Status | Description
---- | ------ | -----------
200 | OK | Request succeeded

### Untag a Movie

`DELETE /api/movie/{movie}/catalog/{catalog}`

**Examples**

> Request
```shell
DELETE /api/movie/tt0076759/catalog/46
```

> Response Body

`empty`

> Response Codes

Code | Status | Description
---- | ------ | -----------
200 | OK | Request succeeded

### Move/Copy Movie to an existing or new Tag (Catalog)

`PUT /movie/{movie}/catalog/{catalog}/{action}`

**Examples**

> Request (Move)
```shell
PUT /movie/tt0076759/catalog/46/move
```

> Request (Copy)
```shell
PUT /movie/tt0076759/catalog/46/copy
```

> Request Body (Move/Copy - Existing Tag)

```json
{
    "destination_catalog_id":47
}
```

> Request Body (Move/Copy - New Tag)

```json
{
    "catalog_name":"SciFi"
}
```

> Response Body (Existing or New Tag)

```json
{"catalog_name":"SciFi"}
```

> Response Codes

Code | Status | Description
---- | ------ | -----------
200 | OK | Request succeeded


