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
                <div class="search-summary">
                    Found <strong>{{ totalResults }}</strong> results for <strong>"{{ searchString }}"</strong>
                </div>

                <search-pagination-controls
                    :search-string="searchString"
                    :number-of-pages="numberOfPages"
                    :page="page"
                    :results-range="resultsRange"
                    :total-results="totalResults"
                    @goto="goto($event)"
                />
                <hr>

                <search-results :movies="searchResults"></search-results>

                <hr>
                <search-pagination-controls
                    :search-string="searchString"
                    :number-of-pages="numberOfPages"
                    :page="page"
                    :results-range="resultsRange"
                    :total-results="totalResults"
                    @goto="goto($event)"
                />

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
            searchMovie: async function (page = null) {
                this.isSearching = true
                this.page = page || 1

                try {
                    // Override Axios headers, OMDB doesn't accept those
                    const omdbAxios = Object.assign({}, axios)
                    delete omdbAxios.defaults.headers.common['X-Requested-With']
                    delete omdbAxios.defaults.headers.common['X-CSRF-TOKEN']

                    const results = await omdbAxios.get(`${this.apiUrl}` + '/?apikey=' + `${this.apiKey}` + '&type=movie&s=' + `${this.searchString}` + '&page=' + `${this.page}`)
                    if (results.data) {
                        this.totalResults = parseInt(results.data.totalResults, 10)
                        this.numberOfPages = parseInt(Math.ceil(this.totalResults / this.resultsPerPage), 10)
                        this.searchResults = results.data.Search
                    }
                }
                finally {
                    this.isSearching = false
                }
            },
            goto: function (page) {
                this.searchMovie(page)
            }
        },
        computed: {
            resultsRange: function () {
                let from, to
                from = this.page === 1 ? 1 : Math.floor((this.page - 1) * this.resultsPerPage + 1)
                to = this.page === this.numberOfPages ? this.totalResults : this.page * this.resultsPerPage
                return {
                    from: from,
                    to: to
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

