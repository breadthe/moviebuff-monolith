<template>
<div class="container">

    <div class="movie-results">
        <div v-for="movie in movies" :key="movie.imdbID">
            <search-result
                :all-catalogs="allCatalogs"
                :movie="movie"
                @loadAllCatalogs="loadAllCatalogs()"
            ></search-result>
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
                allCatalogs: [],
                movie: {},
                error: false
            }
        },
        methods: {
            loadAllCatalogs() {
                const $this = this

                axios.get('/api/catalogs')
                    .then(response => {
                        $this.error = false
                        $this.allCatalogs = response.data
                    })
                    .catch (e => {
                        $this.error = true
                        $this.allCatalogs = []
                    })
            }
        },
        mounted () {
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

