<template>
    <form action="" method="">
        <input class="form-control searchbar" type="text" placeholder="Find a movie" v-model="searchString" @keyup.enter="searchMovie">
    </form>
</template>

<script>
    export default {
        data () {
            return {
                searchString: ''
            }
        },
        methods: {
            searchMovie: function () {
                alert('searching movie');
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style scoped>
input.form-control {
  border-left: none;
  border-right: none;
  padding-left: 5vw;
  padding-right: 5vw;
  box-shadow: inset 0 1px 2px hsla(0,0%,4%,.1);
  border-radius: 0;
  font-size: 1.25rem;
  height: 2.25em;
  font-weight: 300;
  color: black;
}
input.form-control::placeholder {
  color: #dddddd;
}
</style>

