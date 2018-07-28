<template>
<div class="container">
    <div class="movie-results">
        <div v-for="movie in movies" :key="movie.imdbID" class="movie-item">
            <div class="left-side">
                <img :src="movie.Poster" v-if="movie.Poster && movie.Poster != 'N/A'">
                <img src="http://via.placeholder.com/75x100?text=NO+IMAGE" v-else>
            </div>
            <div class="right-side">
                <div class="movie-year is-size-6">{{movie.Year}}</div>
                <div class="movie-title is-size-4 has-text-black">{{movie.Title}}</div>
                <div class="action-buttons">

                <!-- <button
                    class="button"
                    :class="{'is-success': isInSeenlist(movie.imdbID)}"
                    :disabled="isInSeenlist(movie.imdbID)"
                    @click="openSeenDetails(movie.imdbID)"
                >
                    <i class="fa"
                    :class="{'fa-circle-o-notch fa-spin': addingToSeenlist === movie.imdbID, 'fa-eye': addingToSeenlist !== movie.imdbID && isInSeenlist(movie.imdbID), 'fa-eye-slash': addingToSeenlist !== movie.imdbID && !isInSeenlist(movie.imdbID)}"
                    aria-hidden="true"
                    ></i>&nbsp;Seen
                </button> -->

                <!-- <button
                    v-if="!isInSeenlist(movie.imdbID) && !isInWishlist(movie.imdbID)"
                    class="button"
                    @click="addToWishlist(movie.imdbID)"
                >
                    <i class="fa"
                    :class="{'fa-circle-o-notch fa-spin': addingToWishlist === movie.imdbID, 'fa-heart-o': addingToWishlist !== movie.imdbID}"
                    aria-hidden="true"
                    ></i>&nbsp;Wishlist
                </button> -->

                <!-- <button
                    v-if="!isInSeenlist(movie.imdbID) && isInWishlist(movie.imdbID)"
                    class="button is-warning"
                    @click="removeFromWishlist(movie.imdbID)"
                >
                    <i class="fa"
                    :class="{'fa-circle-o-notch fa-spin': addingToWishlist === movie.imdbID, 'fa-heart': addingToWishlist !== movie.imdbID}"
                    aria-hidden="true"
                    ></i>&nbsp;Wishlist
                </button> -->

                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: {
            'movies': {
                required: true,
                type: Array
            }
        },
        data () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]'),
                // isSearching: true,
                // searchResults: null,
                // totalResults: 0,
                // resultsPerPage: 10,
                // numberOfPages: 0,
                // page: 1,
            }
        },
        methods: {
        },
        mounted () {
        }
    }
</script>

<style scoped>
</style>

