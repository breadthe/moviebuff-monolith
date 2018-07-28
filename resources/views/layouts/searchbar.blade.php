<form action="/search" method="POST">
    @csrf
    <input
        type="text"
        name="search_string"
        class="form-control searchbar"
        placeholder="Find a movie"
    >
</form>