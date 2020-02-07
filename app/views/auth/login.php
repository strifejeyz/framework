@extend('layouts/frontend', ['title' => 'Sign In'])

<div class="container">
    <div class="col-md-4 col-md-push-4">
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