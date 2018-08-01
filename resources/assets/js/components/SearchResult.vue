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

            <p v-if="isLoadingCatalogs"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></p>

            <p v-else>
                <a :href="'catalogs/' + catalog.id" class="badge badge-primary mr-1" v-if="catalogs.length" v-for="catalog in catalogs" :key="catalog.name">{{ catalog.name }}</a>
            </p>

            <p v-if="isOpeningModal === movie.imdbID"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>&nbsp;loading catalogs...</p>

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
            'isOpeningModal': {
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
                csrf: document.head.querySelector('meta[name="csrf-token"]'),
                catalogs: [],
                isLoadingCatalogs: false
            }
        },
        methods: {
            isInCatalog: function (imdbId) {
                return false;
            },
            getCatalogs: async function () {
                const $this = this

                // Restore internal headers headers for axios request
                window.axios.defaults.headers.common = {
                    'X-CSRF-TOKEN': this.csrf.content,
                    'X-Requested-With': 'XMLHttpRequest'
                };

                $this.isLoadingCatalogs = true

                await axios.get(`/api/movie/${$this.movie.imdbID}/catalogs`)
                    .then(response => {
                        $this.catalogs = response.data
                        $this.isLoadingCatalogs = false
                    }).catch (e => {
                        $this.catalogs = []
                        $this.isLoadingCatalogs = ''
                    })
            }
        },
        mounted () {
            this.getCatalogs()
        }
    }
</script>

<style scoped>
</style>

