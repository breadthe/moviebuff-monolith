<template>
<div>

    <div class="btn-toolbar level" role="toolbar" aria-label="Toolbar with button groups">

        <div class="level-item left" role="group" aria-label="First, Previous">
            <button type="button" class="btn btn-link text-body bg-light" v-if="page > 1" @click="$emit('goto', firstPage)">
                <i class="fa fa-step-backward"></i>
            </button>
            <button type="button" class="btn btn-link text-body bg-light" v-if="page > 1" @click="$emit('goto', previousPage)">
                <i class="fa fa-caret-left fa-lg"></i>
            </button>
        </div>

        <div class="level-item center">
            Showing&nbsp;<strong>{{ resultsRange.from }}</strong>-<strong>{{ resultsRange.to }}</strong>&nbsp;of&nbsp;<strong>{{ totalResults }}</strong>
        </div>

        <div class="level-item right" role="group" aria-label="Next, Last">
            <button type="button" class="btn btn-link text-body bg-light" v-if="page < numberOfPages" @click="$emit('goto', nextPage)">
                <i class="fa fa-caret-right fa-lg"></i>
            </button>
            <button type="button" class="btn btn-link text-body bg-light" v-if="page < numberOfPages" @click="$emit('goto', lastPage)">
                <i class="fa fa-step-forward"></i>
            </button>
        </div>

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
    firstPage: function () {
      return 1
    },
    lastPage: function () {
      return this.numberOfPages
    },
    previousPage: function () {
      let previousPage = this.page
      previousPage--
      if (previousPage < 1) {
        return this.firstPage
      }
      return previousPage
    },
    nextPage: function () {
      let nextPage = this.page
      nextPage++
      if (nextPage > this.numberOfPages) {
        return this.lastPage
      }
      return nextPage
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
