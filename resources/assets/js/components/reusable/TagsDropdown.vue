<template>
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
            @click="tagMovie(catalog.id, $event)"
            @mouseover="showDelete = catalog.id"
            @mouseout="showDelete = false"
        >
            <i class="fa" :class="hasTag(catalog.id) ? 'fa-check text-primary' : 'fa-check text-white'"></i>&nbsp;
            {{ catalog.name }}
            <span v-if="catalog.movies.length"><small>&nbsp;({{ catalog.movies.length }})</small></span>
            <i class="fa fa-ban ml-auto pl-1 text-danger" v-if="showDelete === catalog.id && hasTag(catalog.id)"></i>
        </a>

        <div class="dropdown-divider"></div>

        <form class="form-inline px-2" @submit="tagMovie(null, $event)">
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" placeholder="New catalog" v-model="newCatalogName" @key.enter="tagMovie(null, $event)">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="!newCatalogName.length">Add</button>
                    </div>
                </div>
            </div>
        </form>

    </div><!-- .dropdown-menu -->
</div><!-- .btn-group -->
</template>

<script>
    export default {
        name: 'tags-dropdown',
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
                newCatalogName: '',
                showDelete: false,
                notifyDuration: 8000 // How long should notifications persist (ms)
            }
        },
        methods: {
            // Determines if a Movie has a specific Tag
            hasTag(catalogId) {
                const catalog = this.allCatalogs.filter(catalog => catalog.id === catalogId);
                const movies = catalog[0].movies; // HACKY - find a better way
                const hasTag = movies.filter(movie => movie.id === this.movie.imdbID);
                return hasTag.length || false;
            },
            tagMovie(catalogId, event) {
                event.preventDefault();
                event.stopPropagation();

                const addTag = async (data) => {
                    await axios.post(`/api/movie/catalog`, data)
                        .then(response => {
                            handleSuccess();
                        }).catch (e => {
                            handleFailure('An error occurred trying to tag <strong>' + this.movie.Title + '</strong>');
                        })
                }

                const removeTag = async (movieId, catalogId) => {
                    await axios.delete(`/api/movie/${movieId}/catalog/${catalogId}`)
                        .then(response => {
                            handleSuccess();
                        }).catch (e => {
                            handleFailure('An error occurred trying to untag <strong>' + this.movie.Title + '</strong>');
                        })
                }

                const handleSuccess = () => {
                    // Retrieve catalogs for the current movie again
                    this.$emit('getMovieTags');

                    // Tell the parent to reload all the catalogs
                    this.$emit('getAllTags');
                }

                const handleFailure = (text) => {
                    this.$notify({
                        group: 'error',
                        type: 'error',
                        duration: this.notifyDuration,
                        title: 'Error!',
                        text: text
                    });
                }

                // Existing catalog
                if (catalogId) {
                    // If the movie is already in a catalog, remove it
                    if (this.hasTag(catalogId)) {
                        removeTag(this.movie.imdbID, catalogId);
                    }
                    // Otherwise add it
                    else {
                        addTag({
                            'movie': this.movie,
                            'catalog_id': catalogId
                        });
                    }
                }
                // New catalog
                else {
                    addTag({
                        'movie': this.movie,
                        'catalog_name': this.newCatalogName
                    });

                    this.newCatalogName = '';
                }
            }
        },
        mounted () {
            // Restore internal headers headers for axios request
            window.axios.defaults.headers.common = {
                'X-CSRF-TOKEN': this.csrf.content,
                'X-Requested-With': 'XMLHttpRequest'
            };
        }
    }
</script>

<style scoped>
</style>

