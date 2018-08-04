<template>
<div class="movie-item">
    <div class="left-side">
        <img :src="movie.poster ? movie.poster : 'http://via.placeholder.com/75x100?text=NO+IMAGE'">
    </div>
    <div class="right-side">
        <div class="movie-year is-size-6">{{ movie.year }}</div>
        <div class="movie-title is-size-4 has-text-black">{{ movie.title }}</div>
    </div>
</div>
</template>

<script>
    export default {
        name: 'movie-item',
        props: {
            'movie': {
                required: true,
                type: Object
            }
        },
        data () {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]'),
                showControls: false,
                isEditing: false,
                isDeleting: false
            }
        },
        methods: {
            confirmDelete(event) {
                event.preventDefault();
                event.stopPropagation();

                this.isDeleting = true;
            },
            async deleteMovie() {
                await axios.delete(`/api/movie/${this.movie.id}`)
                    .then(response => {
                        if (response.status === 200) {
                            this.isDeleting = false;
                            this.$emit('removeItem', this.movie.id);
                            // remove this item from the DOM
                        }
                    }).catch (e => {
                        // TODO: handle errors somehow
                    });
            },
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

