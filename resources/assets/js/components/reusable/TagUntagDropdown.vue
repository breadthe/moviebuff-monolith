<template>
<div class="btn-group">
    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Move to Catalog">
        -- {{ action }} --
    </button>

    <div class="dropdown-menu">
        <a
            class="dropdown-item d-flex align-items-center"
            :class="{'disabled_catalog': hasTag(catalog.id)}"
            :title="hasTag(catalog.id) ? 'The movie is already in this catalog' : ''"
            href="#"
            v-for="catalog in allCatalogs"
            :key="catalog.id"
            @click="transformTag(catalog.id, $event)"
            @mouseover="showDelete = catalog.id"
            @mouseout="showDelete = false"
        >
            <!-- <i class="fa" :class="hasTag(catalog.id) ? 'fa-star text-primary' : 'fa-star-o text-white'"></i>&nbsp; -->
            {{ catalog.name }}
            <span v-if="catalog.movies.length"><small>&nbsp;({{ catalog.movies.length }})</small></span>
            <!-- <i class="fa fa-ban ml-auto pl-1 text-danger" v-if="showDelete === catalog.id && hasTag(catalog.id)"></i> -->
        </a>

        <div class="dropdown-divider"></div>

        <form class="form-inline px-2" @submit="transformTag(null, $event)">
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" placeholder="New catalog" v-model="newCatalogName" @key.enter="transformTag(null, $event)">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="!newCatalogName.length">{{ action }}</button>
                    </div>
                </div>
            </div>
        </form>

    </div><!-- .dropdown-menu -->
</div><!-- .btn-group -->
</template>

<script>
    export default {
        name: 'tag-untag-dropdown',
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
            'movie': {
                required: true,
                type: Object
            },
            'action': {
                required: true,
                type: String
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
                showDelete: false,
                notifyDuration: 8000 // How long should notifications persist (ms)
            }
        },
        methods: {
            // Determines if a movie belongs to a catalog
            hasTag(catalogId) {
                const catalog = this.allCatalogs.filter(catalog => catalog.id === catalogId)
                const movies = catalog[0].movies // HACKY - find a better way
                const hasTag = movies.filter(movie => movie.id === this.movie.id)
                return hasTag.length || false
            },

            // Move/Copy Movie to an existing or new Tag
            transformTag(destinationCatalogId, event) {
                event.preventDefault();
                event.stopPropagation();

                const moveOrCopyToTag = async (data) => {
                    await axios.put(`/api/movie/${this.movie.id}/catalog/${this.catalogId}/${this.action}`, data)
                        .then(response => {
                            handleSuccess(response);
                        }).catch (e => {
                            handleFailure();
                        })
                }

                const handleSuccess = (response) => {
                    if (this.action === 'move') {
                        this.$emit('isMoving', false);
                        this.$emit('removeItem', this.movie.id);
                    } else {
                        this.$emit('isCopying', false);
                    }

                    this.newCatalogName = '';
        
                    this.$emit('getAllTags');

                    this.$notify({
                        group: 'success',
                        type: 'success',
                        duration: this.notifyDuration,
                        title: 'Success!',
                        text: '<strong>' + this.movie.title + '</strong> ' + (this.action === 'move' ? 'moved' : 'copied') + ' to <strong>' + response.data.catalog_name + '</strong>'
                    });
                }

                const handleFailure = () => {
                    this.$notify({
                        group: 'error',
                        type: 'error',
                        duration: this.notifyDuration,
                        title: 'Error!',
                        text: 'An error occurred trying to ' + this.action + ' <strong>' + this.movie.title + '</strong>'
                    });
                }

                // Existing catalog
                if (destinationCatalogId) {
                    // Allow Move/Copy only if movie is not already in the catalog
                    if (!this.hasTag(destinationCatalogId)) {
                        moveOrCopyToTag({
                            'destination_catalog_id': destinationCatalogId
                        });
                    }
                }
                // New catalog
                else {
                    moveOrCopyToTag({
                        'catalog_name': this.newCatalogName
                    });
                }

            },
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
