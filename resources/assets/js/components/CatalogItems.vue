<template>
<ul class="list-group list-group-flush">
    <catalog-item
        :catalog="catalog"
        v-for="(catalog, i) in mutableCatalogs"
        :key="i"
        @removeItem="removeItem($event)"
    ></catalog-item>
</ul>
</template>

<script>
    export default {
        name: 'catalog-items',
        props: {
            'catalogs': {
                required: true,
                type: Array
            }
        },
        data () {
            return {
                mutableCatalogs: this.catalogs
            }
        },
        methods: {
            removeItem(catalogId) {
                this.mutableCatalogs = this.mutableCatalogs.filter((cat) => cat.id !== catalogId);
            }
        }
    }
</script>

<style scoped>
</style>

