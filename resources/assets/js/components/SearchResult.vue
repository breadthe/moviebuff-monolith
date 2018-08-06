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
                    <a
                        class="dropdown-item d-flex align-items-center"
                        href="#"
                        v-for="catalog in allCatalogs"
                        :key="catalog.id"
                        @click="addMovieToCatalog(catalog.id, $event)"
                        @mouseover="showDelete = catalog.id"
                        @mouseout="showDelete = false"
                    >
                        <i class="fa" :class="isInCatalog(catalog.id) ? 'fa-star text-primary' : 'fa-star-o text-white'"></i>&nbsp;
                        {{ catalog.name }}
                        <span v-if="catalog.movies.length"><small>&nbsp;({{ catalog.movies.length }})</small></span>
                        <i class="fa fa-ban ml-auto pl-1 text-danger" v-if="showDelete === catalog.id && isInCatalog(catalog.id)"></i>
                    </a>

                    <div class="dropdown-divider"></div>

                    <form class="form-inline px-2" @submit="addMovieToNewCatalog($event)">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" placeholder="New catalog" v-model="newCatalogName" @key.enter="addMovieToNewCatalog($event)">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary btn-sm" :disabled="!newCatalogName.length">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div><!-- .dropdown-menu -->
            </div><!-- .btn-group -->

            <span v-if="isLoadingCatalogs"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></span>

            <span v-else>
                <a :href="'catalogs/' + catalog.id" class="badge badge-moviebuff mr-1" v-if="catalogs.length" v-for="catalog in catalogs" :key="catalog.name">{{ catalog.name }}</a>
            </span>

        </div>
    </div>

</section>
</template>

<script>
    export default {
        props: {
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
                isLoadingCatalogs: false,
                newCatalogName: '',
                showDelete: false
            }
        },
        methods: {
            // Determines if a movie belongs to a catalog
            isInCatalog(catalogId) {
                const catalog = this.allCatalogs.filter(catalog => catalog.id === catalogId)
                const movies = catalog[0].movies // HACKY - find a better way
                const isInCatalog = movies.filter(movie => movie.id === this.movie.imdbID)
                return isInCatalog.length || false
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
            addMovieToCatalog(catalogId, event) {
                event.preventDefault()
                event.stopPropagation()

                const data = {
                    'movie': this.movie,
                    'catalog_id': catalogId
                }

                if (this.isInCatalog(catalogId)) { // If the movie is already in a catalog, remove it
                    axios.delete(`/api/catalog/${catalogId}/movie/${this.movie.imdbID}`, data)
                        .then(response => {
                            // Retrieve catalogs for the current movie again
                            this.getCatalogs()

                            // Tell the parent to reload all the catalogs
                            this.$emit('loadAllCatalogs')
                        }).catch (e => {
                            // TODO: handle errors somehow
                        })
                } else { // Otherwise add it
                    axios.post(`/api/catalog`, data)
                        .then(response => {
                            // Retrieve catalogs for the current movie again
                            this.getCatalogs()

                            // Tell the parent to reload all the catalogs
                            this.$emit('loadAllCatalogs')
                        }).catch (e => {
                            // TODO: handle errors somehow
                        })
                }
            },
            addMovieToNewCatalog(event) {
                event.preventDefault()
                event.stopPropagation()

                const data = {
                    'movie': this.movie,
                    'catalog_id': null,
                    'catalog_name': this.newCatalogName
                }

                axios.post(`/api/catalog`, data)
                    .then(response => {
                        // Retrieve catalogs for the current movie again
                        this.getCatalogs()

                        // Tell the parent to reload all the catalogs
                        this.$emit('loadAllCatalogs')

                        this.newCatalogName = ''
                    }).catch (e => {
                        // TODO: handle errors somehow
                    })
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

