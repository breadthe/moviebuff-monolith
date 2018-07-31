<template>
<div class="container">

    <div class="movie-results">
        <div v-for="movie in movies" :key="movie.imdbID" class="movie-item">
            <search-result :movie="movie" :is-loading-catalogs="isLoadingCatalogs" @openModal="openModal($event)"></search-result>
        </div>
    </div>

    <add-movie-to-catalog :catalogs="catalogs" :error="error" :movie="movie"></add-movie-to-catalog>

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
                catalogs: [],
                movie: {},
                isLoadingCatalogs: '',
                error: false
            }
        },
        methods: {
            openModal: function (movie) {
                this.movie = movie
                this.isLoadingCatalogs = movie.imdbID
                this.loadCatalogs()
            },
            loadCatalogs: function () {
                const $this = this

                // Restore internal headers headers for axios request
                window.axios.defaults.headers.common = {
                    'X-CSRF-TOKEN': this.csrf.content,
                    'X-Requested-With': 'XMLHttpRequest'
                };

                axios.get('/api/catalogs')
                    .then((response) => {
                        $this.error = false
                        $this.catalogs = response.data
                        $this.isLoadingCatalogs = ''
                    })
                    .catch ((e) => {
                        $this.error = true
                        $this.catalogs = []
                        $this.isLoadingCatalogs = ''
                    })
            }
        },
        mounted () {
        }
    }
</script>

<style scoped>
</style>

