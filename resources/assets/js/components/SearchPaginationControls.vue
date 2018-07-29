<template>
<!-- <div class="level is-mobile"> -->
<div>

    <div class="btn-toolbar level" role="toolbar" aria-label="Toolbar with button groups">

        <div class="btn-group level-item left" role="group" aria-label="First, Previous">
            <button type="button" class="btn btn-secondary">
                <i class="fa fa-step-backward"></i>
            </button>
            <button type="button" class="btn btn-secondary">
                <i class="fa fa-caret-left fa-lg"></i>
            </button>
        </div>
        <!-- <div class="level-item">
            <router-link class="button is-light" :class="{'is-static': page === 1}" :disabled="page === 1" :to="firstPageUrl" append>
            <i class="fa fa-step-backward"></i>
            </router-link>
            &nbsp;
            <router-link class="button is-light" :class="{'is-static': page === 1}" :disabled="page === 1" :to="previousPageUrl" append>
            <i class="fa fa-caret-left fa-lg"></i>
            </router-link>
        </div> -->

        <div class="level-item center">
            Showing&nbsp;<strong>{{ resultsRange.from }}</strong>-<strong>{{ resultsRange.to }}</strong>&nbsp;of&nbsp;<strong>{{ totalResults }}</strong>
        </div>

        <div class="btn-group level-item right" role="group" aria-label="Next, Last">
            <button type="button" class="btn btn-secondary">
                <i class="fa fa-caret-right fa-lg"></i>
            </button>
            <button type="button" class="btn btn-secondary">
                <i class="fa fa-step-forward"></i>
            </button>
        </div>
        <!-- <div class="level-item">
            <router-link class="button is-light" :class="{'is-static': page === numberOfPages}" :disabled="page === numberOfPages" :to="nextPageUrl" append>
            <i class="fa fa-caret-right fa-lg"></i>
            </router-link>
            &nbsp;
            <router-link class="button is-light" :class="{'is-static': page === numberOfPages}" :disabled="page === numberOfPages" :to="lastPageUrl" append>
            <i class="fa fa-step-forward"></i>
            </router-link>
        </div> -->
    </div>

</div>
</template>

<script>
export default {
  name: 'SearchPaginationControls',
  props: {
    'search-string': {
      type: String,
      required: true,
      default: ''
    },
    'number-of-pages': {
      type: Number,
      required: true
    },
    'page': {
      type: Number,
      required: true,
      default: 1
    },
    'results-range': {
      type: Object,
      required: true
    },
    'total-results': {
      type: Number,
      required: true
    }
  },
  computed: {
    firstPageUrl: function () {
      return '/search?q=' + `${this.searchString}` + '&page=1'
    },
    lastPageUrl: function () {
      return '/search?q=' + `${this.searchString}` + '&page=' + this.numberOfPages
    },
    previousPageUrl: function () {
      let previousPage = this.page
      previousPage--
      if (previousPage < 1) {
        return this.firstPageUrl
      }
      return '/search?q=' + `${this.searchString}` + '&page=' + previousPage
    },
    nextPageUrl: function () {
      let nextPage = this.page
      nextPage++
      if (nextPage > this.numberOfPages) {
        return this.lastPageUrl
      }
      return '/search?q=' + `${this.searchString}` + '&page=' + nextPage
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
