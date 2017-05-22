@extend('layouts/frontend',['title' => 'Welcome'])

<div class="container">
    <h1 class="jumbotron">
        Welcome {{$name}}!, <a href='/logout'>Logout</a>
    </h1>
</div>

@stop()