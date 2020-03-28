@extend('layouts/auth', ['title' => 'Sign In'])

<br><br><br>

<div class="container">
    <div class="col-md-4 col-md-push-4">
        <div class="well">
            {!Form::open(route('auth.attempt'))!}
            <h3 class="help-block text-center"><b>LOGIN</b></h3>
            <hr>
            <div class="form-group{{!empty(form_error('username')) ? ' has-error':''}}">
                {!Form::text('username',form_values('username'),['class'=>'form-control','placeholder'=>'Username:'])!}
                <i class="text-danger">{{form_error('username')}}</i>
            </div>
            <div class="form-group{{!empty(form_error('password')) ? ' has-error':''}}">
                {!Form::password('password',null,['class'=>'form-control','placeholder'=>'Password:'])!}
                <i class="text-danger">{{form_error('password')}}</i>
            </div>
            <i class="has-error">{!Session::getFlash('flash')!}</i>
            {if flash_exists('message')}
                {!getflash('message')!}
            {else}
                <a href="/forgot-password">Having trouble logging in?</a><br><br>
            {endif}
            <button type="submit" class="btn btn-success" style="width:100%"><i class="glyphicon glyphicon-log-in"></i> Login</button>
            {!Form::close()!}
        </div>
        <p class="text-center underline"><a href="/">&larr; Home</a></p>
    </div>
</div>

@stop()