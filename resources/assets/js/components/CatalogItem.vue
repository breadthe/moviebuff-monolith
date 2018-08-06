<template>
<li
    class="list-group-item list-group-item-action"
    @mouseover="showControls = true"
    @mouseout="showControls = false"
>

    <span class="d-flex align-items-center" v-if="isEditing">
        <form class="form-inline" @submit="saveCatalogName($event)">
            <div class="btn-toolbar" role="toolbar" aria-label="Edit catalog name">
                <div class="input-group">
                    <input 
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Catalog name"
                        v-model="catalog.name"
                        @key.enter="saveCatalogName($event)"
                        v-focus
                    >

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="!catalog.name.length">Save</button>
                    </div>
                </div>
            </div>
        </form>

        <span class="ml-auto pl-1" v-if="isEditing">
            <button class="btn btn-link" @click="isEditing = false">
                Cancel
            </button>
        </span>
    </span>

    <span class="d-flex align-items-center" v-else-if="isDeleting">
        <span>Are you sure you want to delete <strong>{{ catalog.name }}</strong>?</span>

        <span class="ml-auto pl-1">
            <button class="btn btn-catalog-action btn-red" @click="deleteCatalog()">
                Delete
            </button>

            <button class="btn btn-link" @click="isDeleting = false">
                Cancel
            </button>
        </span>
    </span>

    <span class="d-flex align-items-center" v-else>
        <a :href="'/catalogs/' + catalog.id">{{ catalog.name }}</a>
        <small v-if="catalog.movies.length">&nbsp;({{ catalog.movies.length }})</small>

        <span class="ml-auto pl-1" v-if="showControls">
            <button class="btn btn-sm btn-catalog-action btn-blue" :title="'Edit ' + catalog.name" @click="editCatalog($event)">
                <i class="fa fa-pencil fa-sm" aria-hidden="true" :title="'Edit ' + catalog.name"></i>
                Edit
            </button>
            <button class="btn btn-sm btn-catalog-action btn-red ml-3" :title="'Delete ' + catalog.name" @click="confirmDelete($event)">
                <i class="fa fa-trash fa-sm" aria-hidden="true" :title="'Delete ' + catalog.name"></i>
                Delete
            </button>
        </span>
    </span>

</li>
</template>

<script>
    export default {
        name: 'catalog-item',
        props: {
            'catalog': {
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
            editCatalog(event) {
                event.preventDefault();
                event.stopPropagation();

                this.isEditing = true;
            },
            async saveCatalogName(event) {
                event.preventDefault();
                event.stopPropagation();

                await axios.post(`/api/catalog/${this.catalog.id}`, this.catalog)
                    .then(response => {
                        // A little fall-back in case the name change failed on the back-end for some reason
                        if (response.data.catalog_name) {
                            this.catalog.name = response.data.catalog_name;
                        }
                    }).catch (e => {
                        // TODO: handle errors somehow
                    });

                this.isEditing = false;
            },
            confirmDelete(event) {
                event.preventDefault();
                event.stopPropagation();

                this.isDeleting = true;
            },
            async deleteCatalog() {
                await axios.delete(`/api/catalog/${this.catalog.id}`)
                    .then(response => {
                        if (response.status === 200) {
                            this.isDeleting = false;
                            this.$emit('removeItem', this.catalog.id); // remove this item from the DOM
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

