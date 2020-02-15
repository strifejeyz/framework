@extend('layouts/auth', ['title' => 'Password Recovery'])

<div class="container">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <br>
        <br>
        <div class="well">
            <h3 class="help-block text-center"><b>Password Reset Notice</b><br>
                <small>Your password reset link has expired.</small>
            </h3>
            <hr>
            <a href="/forgot-password" style="width:100%" class="btn btn-primary">Ok</a>
        </div><!--well-->
        <br>
        <p class="text-center underline"><a href="/">&larr; Home</a></p>
    </div>
    <div class="col-md-4"></div>
</div>

@stop()