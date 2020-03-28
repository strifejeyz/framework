@extend('layouts/backend',['title' => 'Welcome'])

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <p class="navbar-text"><a href="/" class="navbar-link">Home</a></p>
        <p class="navbar-text navbar-right"><a href="/logout" class="navbar-link">Logout</a></p>
        <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link"><b>{{$user->firstname}} {{$user->lastname}}</b></a></p>
    </div>
</nav>

<div class="container" style="margin-top: 50px">
    <h1>Welcome {{$user->firstname}}!</h1>
</div>

@stop()