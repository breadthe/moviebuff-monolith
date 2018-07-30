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

                <button
                    class="btn btn-link p-0"
                    :class="{'is-success': isInList(movie.imdbID)}"
                    :disabled="isInList(movie.imdbID)"
                    @click="addToList(movie.imdbID)"
                >
                    Add to List
                </button>


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
            }
        },
        methods: {
            isInList: function (imdbID) {
                return false;
            },
            addToList: async function (imdbID) {
                // console.log(imdbID);
                await axios.get('/api/catalogs').then((data) => {
                    console.log(data);
                });
                return false;
            },
        },
        mounted () {
        }
    }
</script>

<style scoped>
</style>

