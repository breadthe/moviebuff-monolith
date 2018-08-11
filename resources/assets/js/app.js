
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Notifications from 'vue-notification';

/**
 * Custom directive to focus an input when inserting it into the DOM
 */
Vue.directive('focus', {
    inserted: function (el) {
        el.focus()
    }
});

// Register vue-notification
Vue.use(Notifications);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('tags-dropdown', require('./components/reusable/TagsDropdown.vue'));
Vue.component('all-catalogs-dropdown', require('./components/reusable/AllCatalogsDropdown.vue'));

Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

Vue.component('catalog-items', require('./components/CatalogItems.vue'));
Vue.component('catalog-item', require('./components/CatalogItem.vue'));

Vue.component('movie-items', require('./components/MovieItems.vue'));
Vue.component('movie-item', require('./components/MovieItem.vue'));

Vue.component('search-page', require('./components/SearchPage.vue'));
Vue.component('search-results', require('./components/SearchResults.vue'));
Vue.component('search-result', require('./components/SearchResult.vue'));
Vue.component('search-pagination-controls', require('./components/SearchPaginationControls.vue'));

const app = new Vue({
    el: '#app'
});
