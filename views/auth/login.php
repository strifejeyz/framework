<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login &mdash; {{ APP_NAME }} </title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <br>
        <br>
        <div class="well">
            {!Form::open(route('auth.attempt'))!}
            <h3 class="help-block text-center"><b>LOGIN</b></h3>
            <hr>
            <div class="form-group{{!empty(errors('username')) ? ' has-error':''}}">
                {!Form::text('username',fields('username'),['class'=>'form-control','placeholder'=>'Username:'])!}
                <i class="text-danger">{{errors('username')}}</i>
            </div>
            <div class="form-group{{!empty(errors('password')) ? ' has-error':''}}">
                {!Form::password('password',null,['class'=>'form-control','placeholder'=>'Password:'])!}
                <i class="text-danger">{{errors('password')}}</i>
            </div>
            <i class="has-error">{!Session::getFlash('flash')!}</i>
            <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-log-in"></i> Login</button>
            {!Form::close()!}
        </div>
        <br>
        <p class="text-center underline"><a href="/">&larr; home</a></p>
    </div>
    <div class="col-md-4"></div>
</div>

</body>
</html>