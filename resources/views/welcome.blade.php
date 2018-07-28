@extends ('layouts.app')

@section ('content')

@if (Auth::check())
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">Hi {{ Auth::user()->name }}</h1>
            <p class="lead">Check your <a href="{{ route('dashboard') }}">dashboard</a> for a summary of your movies.</p>
        </div>
    </div>
@else
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to {{ config('app.name') }}</h1>
            <p class="lead">Like movies? This is the place for you.</p>
            <hr class="my-4">
            <p>Sign in or register to search for movies, and create lists of your favorites, mark and comment on ones you've seen, or save the ones you haven't.</p>
            <a class="btn btn-primary btn-lg" href="{{ route('login') }}" role="button">Login</a>
            &nbsp;OR&nbsp;
            <a class="btn btn-primary btn-lg" href="{{ route('register') }}" role="button">Register</a>
        </div>
    </div>
@endif
@endsection