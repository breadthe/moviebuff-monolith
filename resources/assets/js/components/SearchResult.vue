<template>
<section class="movie-item">

    <div class="left-side">
        <img :src="movie.Poster" v-if="movie.Poster && movie.Poster != 'N/A'">
        <img src="http://via.placeholder.com/75x100?text=NO+IMAGE" v-else>
    </div>
    <div class="right-side">
        <section>
            <h6>{{movie.Year}}</h6>
            <h3>{{movie.Title}}</h3>
        </section>
        <section class="action-buttons">

            <tag-move-copy-dropdown
                :movie="movie"
                :all-catalogs="allCatalogs"
                @getMovieTags="getMovieTags()"
                @getAllTags="getAllTags()"
            ></tag-move-copy-dropdown>

            <!-- <span v-if="isLoadingCatalogs"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i></span> -->

            <!-- <span v-else> -->
            <span v-if="!isLoadingCatalogs">
                <a :href="'catalogs/' + catalog.id" class="badge badge-moviebuff mr-1" v-if="catalogs.length" v-for="catalog in catalogs" :key="catalog.name">{{ catalog.name }}</a>
            </span>

        </section>
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
                isLoadingCatalogs: false
            }
        },
        methods: {
            getAllTags() {
                // Tell the parent to reload all the catalogs
                this.$emit('getAllTags');
            },
            async getMovieTags() {
                const $this = this

                $this.isLoadingCatalogs = true

                await axios.get(`/api/movies/${$this.movie.imdbID}/catalogs`)
                    .then(response => {
                        $this.catalogs = response.data
                        $this.isLoadingCatalogs = false
                    }).catch (e => {
                        $this.catalogs = []
                        $this.isLoadingCatalogs = ''
                    })
            },
        },
        mounted () {
            // Restore internal headers headers for axios request
            window.axios.defaults.headers.common = {
                'X-CSRF-TOKEN': this.csrf.content,
                'X-Requested-With': 'XMLHttpRequest'
            };

            this.getMovieTags()
        }
    }
</script>

<style scoped>
</style>
