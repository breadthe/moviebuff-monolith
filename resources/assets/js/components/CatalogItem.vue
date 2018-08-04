<template>
<li
    class="list-group-item list-group-item-action"
    @mouseover="showControls = true"
    @mouseout="showControls = false"
>

    <span class="d-flex align-items-center" v-if="! isEditing">
        <a :href="'/catalogs/' + catalog.id">{{ catalog.name }}</a>
        <small v-if="catalog.movies.length">&nbsp;({{ catalog.movies.length }})</small>

        <span class="ml-auto pl-1" v-if="showControls">
            <a href="#" @click="editCatalog($event)">
                <i class="fa fa-pencil fa-lg text-primary" aria-hidden="true" :title="'Edit ' + catalog.name"></i>
            </a>
            <a href="#" @click="deleteCatalog($event)">
                <i class="fa fa-trash fa-lg text-danger ml-3" aria-hidden="true" :title="'Delete ' + catalog.name"></i>
            </a>
        </span>
    </span>

    <span class="d-flex align-items-center" v-else>
        <form class="form-inline px-2" @submit="saveCatalogName($event)">
            <div class="btn-toolbar" role="toolbar" aria-label="Edit catalog name">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" placeholder="Catalog name" v-model="catalog.name" @key.enter="saveCatalogName($event)">
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
                isEditing: false
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
            deleteCatalog(event) {
                event.preventDefault();
                event.stopPropagation();
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

