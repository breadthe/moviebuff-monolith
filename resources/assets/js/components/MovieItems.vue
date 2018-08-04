<template>
<section>
    <movie-item
        :movie="movie"
        v-for="(movie, i) in mutableMovies"
        :key="i"
        @removeItem="removeItem($event)"
    ></movie-item>
</section>
</template>

<script>
    export default {
        name: 'movie-items',
        props: {
            'movies': {
                required: true,
                type: Array
            }
        },
        data () {
            return {
                mutableMovies: this.movies
            }
        },
        methods: {
            removeItem(movieId) {
                this.mutableMovies = this.mutableMovies.filter((mov) => mov.id !== movieId);
            }
        }
    }
</script>

<style scoped>
</style>

