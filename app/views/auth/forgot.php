@extend('layouts/auth', ['title' => 'Password Recovery'])

<div class="container">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <br>
        <br>
        <div class="well">
            <h3 class="help-block text-center"><b>Forgot Password</b><br>
                <small>Please enter the email associated to your account</small>
            </h3>
            <hr>

            {if flash_exists('message')}
                <h4>{!getflash('message')!}</h4>
            <a href="/login" class="btn btn-info" style="width: 100%">Ok</a>
            {else}
                {!Form::open('/forgot-password/send-email', ['id'=>'reset-form'])!}
                <div class="form-group{{!empty(form_error('email'))?' has-error':''}}">
                    <input type="email" name="email" class="form-control" required placeholder="Enter your email:"/>
                    <i class="text-error">{{form_error('email')}}</i>
                </div>
                <button class="btn btn-info" style="width: 100%"><i class="glyphicon glyphicon-envelope"></i> Submit</button>
                {!Form::close()!}
            {endif}

        </div><!--well-->
        <br>
        <p class="text-center underline"><a href="/">&larr; Home</a></p>
    </div>
    <div class="col-md-4"></div>
</div>

@stop()