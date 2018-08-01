<template>
<div class="container">

    <!-- Modal -->
    <div class="modal fade" id="addMovieToCatalog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add <strong>{{ movie.Title }}</strong> To Catalog</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div v-if="error">
                        <div class="alert alert-danger" role="alert">
                            There was an error trying to retrieve your catalogs. Please close this window and try again.
                        </div>
                    </div>

                    <div v-else>
                        <p>You can add this movie to one or more existing catalogs or to a new catalog.</p>

                        <p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" v-for="catalog in catalogs" :key="catalog.id">
                                    <label class="form-check-label ml-2">
                                        <input type="checkbox" class="form-check-input">{{ catalog.name }}
                                    </label>
                                    <span class="badge badge-primary badge-pill">{{ catalog.movies.length }}</span>
                                </li>
                            </ul>
                        </p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" v-if="!error" @click="addMovieToCatalog">Save changes</button>
                </div>

            </div>
        </div>
    </div>

</div>
</template>

<script>
    export default {
        props: {
            'catalogs': {
                required: true,
                type: Array
            },
            'movie': {
                required: true,
                type: Object
            },
            'error': {
                required: false,
                type: Boolean,
                default: false
            }
        },
        data () {
            return {
            }
        },
        methods: {
            addMovieToCatalog() {
                console.log(this.movie)
            }
        }
    }
</script>

<style scoped>
</style>

