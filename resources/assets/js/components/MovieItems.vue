<template>
<section>
    <div v-if="mutableMovies.length">
        <movie-item
            :catalog-id="catalogId"
            :catalog-name="catalogName"
            :movie="movie"
            :all-catalogs="allCatalogs"
            v-for="(movie, i) in mutableMovies"
            :key="i"
            @removeItem="removeItem($event)"
            @loadAllCatalogs="loadAllCatalogs()"
        ></movie-item>
    </div>

    <div v-else>
        There are no movies in this catalog.
    </div>
</section>
</template>

<script>
    export default {
        name: 'movie-items',
        props: {
            'catalogId': {
                required: true,
                type: Number
            },
            'catalogName': {
                required: true,
                type: String,
                default: ''
            },
            'movies': {
                required: true,
                type: Array
            }
        },
        data () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]'),
                allCatalogs: [],
                mutableMovies: this.movies
            }
        },
        methods: {
            loadAllCatalogs() {
                const $this = this;

                axios.get('/api/catalogs')
                    .then(response => {
                        $this.error = false;
                        $this.allCatalogs = response.data;
                    })
                    .catch (e => {
                        $this.error = true;
                        $this.allCatalogs = [];
                    });
            },
            removeItem(movieId) {
                this.mutableMovies = this.mutableMovies.filter((mov) => mov.id !== movieId);
            }
        },
        mounted() {
            // Restore internal headers headers for axios request
            window.axios.defaults.headers.common = {
                'X-CSRF-TOKEN': this.csrf.content,
                'X-Requested-With': 'XMLHttpRequest'
            };

            this.loadAllCatalogs()
        }
    }
</script>

<style scoped>
</style>

