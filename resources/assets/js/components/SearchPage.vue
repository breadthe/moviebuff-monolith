<template>
<div class="container">
    <div v-if="searchString">
        <div class="alert alert-info" role="alert" v-if="isSearching">
            Searching for <strong>{{ searchString }}</strong>
            <span class="close" aria-label="Searching">
                <span><i class="fa fa-spinner fa-pulse" aria-hidden="true"></i></span>
            </span>
        </div>
        <div v-else>
            <div v-if="searchResults">
                <div class="">
                    Found <strong>{{ totalResults }}</strong> results for <strong>"{{ searchString }}"</strong>
                </div>

                <!-- <pagination-controls
                :search-string="searchString"
                :number-of-pages="numberOfPages"
                :page="page"
                :results-range="resultsRange"
                :total-results="totalResults"
                /> -->
                <hr>

                <search-results :movies="searchResults"></search-results>

            </div>

            <div v-else>
                No results for <strong>"{{searchString}}"</strong>
            </div>
        </div>
    </div>

    <div v-else>
        <div class="alert alert-warning" role="alert" v-if="isSearching">
            Oops! Try searching for something other than nothing.
            <span class="close" aria-label="Oops">
                <span><i class="fa fa-smile-o" aria-hidden="true"></i></span>
            </span>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: {
            'searchString': {
                type: String
            },
            'apiKey': {
                required: true,
                type: String
            },
            'apiUrl': {
                required: true,
                type: String
            }
        },
        data () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]'),
                isSearching: true,
                searchResults: null,
                totalResults: 0,
                resultsPerPage: 10,
                numberOfPages: 0,
                page: 1,
            }
        },
        methods: {
            searchMovie: async function () {
                this.isSearching = true

                try {
                    // Override Axios headers, OMDB doesn't accept these
                    const omdbAxios = Object.assign({}, axios)
                    delete omdbAxios.defaults.headers.common['X-Requested-With']
                    delete omdbAxios.defaults.headers.common['X-CSRF-TOKEN']

                    const results = await omdbAxios.get(`${this.apiUrl}` + '/?apikey=' + `${this.apiKey}` + '&type=movie&s=' + `${this.searchString}` + '&page=' + `${this.page}`)
                    // await store.dispatch('toggleSearching', false)
                    if (results.data) {
                        this.totalResults = parseInt(results.data.totalResults, 10)
                        this.numberOfPages = parseInt(Math.ceil(this.totalResults / this.resultsPerPage), 10)
                        this.searchResults = results.data.Search
                    }
                }
                finally {
                    this.isSearching = false
                }
            }
        },
        mounted () {
            if (this.searchString) {
                this.searchMovie()
            }
        }
    }
</script>

<style scoped>

</style>

