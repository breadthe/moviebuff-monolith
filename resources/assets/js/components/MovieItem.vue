<template>
<div class="movie-item" @mouseover="showControls = true" @mouseout="showControls = false">
    <div class="left-side">
        <img :src="movie.poster ? movie.poster : 'http://via.placeholder.com/75x100?text=NO+IMAGE'">
    </div>

    <div class="right-side">
        <section class="confirmation" v-if="isMoving">
            <p>Move <strong>{{ movie.title }}</strong> to another catalog?</p>

            <all-catalogs-dropdown
                :catalog-id="catalogId"
                :movie="movie"
                :all-catalogs="allCatalogs"
                action="move"
                @removeItem="removeItem($event)"
                @isMoving="isMoving = $event"
                @loadAllCatalogs="$emit('loadAllCatalogs')"
            ></all-catalogs-dropdown>

            <button class="btn btn-link" @click="isMoving = false">
                Cancel
            </button>
        </section>

        <section class="confirmation" v-else-if="isCopying">
            <p>Copy <strong>{{ movie.title }}</strong> to another catalog?</p>

            <all-catalogs-dropdown
                :catalog-id="catalogId"
                :movie="movie"
                :all-catalogs="allCatalogs"
                action="copy"
                @isCopying="isCopying = $event"
                @loadAllCatalogs="$emit('loadAllCatalogs')"
            ></all-catalogs-dropdown>

            <button class="btn btn-link" @click="isCopying = false">
                Cancel
            </button>
        </section>

        <section class="confirmation d-flex align-items-center" v-else-if="isDeleting">
            <p>Are you sure you want to remove <strong>{{ movie.title }}</strong> from this catalog?</p>

            <button class="btn btn-danger btn-sm" @click="deleteMovie()">
                Delete
            </button>

            <button class="btn btn-link" @click="isDeleting = false">
                Cancel
            </button>
        </section>

        <section v-else>
            <div class="movie-year is-size-6">{{ movie.year }}</div>
            <div class="movie-title is-size-4 has-text-black">{{ movie.title }}</div>
        </section>
    </div>

    <div class="d-flex align-items-center" v-if="showControls && !(isMoving || isCopying || isDeleting)">
        <span class="ml-auto align-self-center">
            <a href="#" @click="confirmMove($event)">
                <i class="fa fa-share-square-o fa-lg text-primary m-2" aria-hidden="true" :title="'Move to Catalog'"></i>
            </a>
            <a href="#" @click="confirmCopy($event)">
                <i class="fa fa-clone fa-lg text-success m-2" aria-hidden="true" :title="'Copy to Catalog'"></i>
            </a>
            <a href="#" @click="confirmDelete($event)">
                <i class="fa fa-trash fa-lg text-danger m-2" aria-hidden="true" :title="'Delete ' + movie.name"></i>
            </a>
        </span>
    </div>
</div>
</template>

<script>
    export default {
        name: 'movie-item',
        props: {
            'catalogId': {
                required: true,
                type: Number
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
                showControls: false,
                isMoving: false,
                isCopying: false,
                isDeleting: false
            }
        },
        methods: {
            confirmMove(event) {
                event.preventDefault();
                event.stopPropagation();

                this.isMoving = true;
            },
            confirmCopy(event) {
                event.preventDefault();
                event.stopPropagation();

                this.isCopying = true;
            },
            confirmDelete(event) {
                event.preventDefault();
                event.stopPropagation();

                this.isDeleting = true;
            },
            async deleteMovie() {
                axios.delete(`/api/catalog/${this.catalogId}/movie/${this.movie.id}`)
                    .then(response => {
                        if (response.status === 200) {
                            this.isDeleting = false;
                            this.$emit('removeItem', this.movie.id); // remove this item from the DOM
                        }
                    }).catch (e => {
                        // TODO: handle errors somehow
                    })
            },
            removeItem($event) {
                this.$emit('removeItem', $event);
            }
        },
        mounted () {
            // Restore internal headers headers for axios request
            window.axios.defaults.headers.common = {
                'X-CSRF-TOKEN': this.csrf.content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }
    }
</script>

<style scoped>
</style>

