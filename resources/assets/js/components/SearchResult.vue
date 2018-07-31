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

            <p v-if="isLoadingCatalogs === movie.imdbID"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>&nbsp;loading catalogs...</p>

            <p v-else>
                <button
                    class="btn btn-link p-0"
                    :class="{'is-success': isInCatalog(movie.imdbID)}"
                    :disabled="isInCatalog(movie.imdbID)"
                    @click="$emit('openModal', movie)"
                    data-toggle="modal"
                    data-target="#addMovieToCatalog"
                >
                    Add to List
                </button>
            </p>

        </div>
    </div>

</section>
</template>

<script>
    export default {
        props: {
            'isLoadingCatalogs': {
                required: true,
                type: String,
                default: ''
            },
            'movie': {
                required: true,
                type: Object
            }
        },
        data () {
            return {
            }
        },
        methods: {
            isInCatalog: function (imdbID) {
                return false;
            }
        },
        mounted () {
        }
    }
</script>

<style scoped>
</style>

