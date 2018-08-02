<template>
<section>

    <div class="left-side">
        <img :src="movie.Poster" v-if="movie.Poster && movie.Poster != 'N/A'">
        <img src="http://via.placeholder.com/75x100?text=NO+IMAGE" v-else>
    </div>
    <div class="right-side">
        <div class="movie-year is-size-6">{{movie.Year}}</div>
        <div class="movie-title is-size-4 has-text-black">{{movie.Title}}</div>
        <div class="action-buttons">

            <div class="btn-group">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Add to Catalog">
                    <i class="fa fa-plus fa-lg text-secondary"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" v-for="catalog in allCatalogs" :key="catalog.id" @click="addMovieToCatalog(movie.imdbID, catalog.id, $event)">{{ catalog.name }}</a>
                    <a class="dropdown-item active" href="#">Static catalog</a>
                    <a class="dropdown-item" href="#">Another static catalog</a>
                    <div class="dropdown-divider"></div>
                    <form class="form-inline px-2 py-1">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" placeholder="New catalog">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <span v-if="isLoadingCatalogs"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></span>

            <span v-else>
                <a :href="'catalogs/' + catalog.id" class="badge badge-primary mr-1" v-if="catalogs.length" v-for="catalog in catalogs" :key="catalog.name">{{ catalog.name }}</a>
            </span>

        </div>
    </div>

</section>
</template>

<script>
    export default {
        props: {
            'isOpeningModal': {
                required: true,
                type: String,
                default: ''
            },
            'movie': {
                required: true,
                type: Object
            },
            'allCatalogs': {
                required: false,
                type: Array,
                default: () => []
            }
        },
        data () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]'),
                catalogs: [],
                isLoadingCatalogs: false
            }
        },
        methods: {
            isInCatalog(imdbId) {
                return false;
            },
            async getCatalogs() {
                const $this = this

                $this.isLoadingCatalogs = true

                await axios.get(`/api/movie/${$this.movie.imdbID}/catalogs`)
                    .then(response => {
                        $this.catalogs = response.data
                        $this.isLoadingCatalogs = false
                    }).catch (e => {
                        $this.catalogs = []
                        $this.isLoadingCatalogs = ''
                    })
            },
            addMovieToCatalog(movieId, catalogId, event) {
                event.preventDefault()
                event.stopPropagation()
                console.log(movieId, catalogId)

                /* await axios.post(`/api/movie/${$this.movie.imdbID}/catalogs`)
                    .then(response => {
                        $this.catalogs = response.data
                        $this.isLoadingCatalogs = false
                    }).catch (e => {
                        $this.catalogs = []
                        $this.isLoadingCatalogs = ''
                    }) */
            }
        },
        mounted () {
            // Restore internal headers headers for axios request
            window.axios.defaults.headers.common = {
                'X-CSRF-TOKEN': this.csrf.content,
                'X-Requested-With': 'XMLHttpRequest'
            };

            this.getCatalogs()
        }
    }
</script>

<style scoped>
</style>

