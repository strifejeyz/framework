@extend('layouts/auth', ['title' => 'Password Recovery'])

<div class="container">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <br>
        <br>
        <div class="well">
            <h3 class="help-block text-center"><b>Password change</b><br>
                <small>Please use a strong password that is hard to guess.</small>
            </h3>
            <hr>
            {!Form::open('/password-reset/update')!}
                <div class="form-group{{!empty(form_error('new_password'))?' has-error':''}}">
                    <input type="password" placeholder="Enter new password:" class="form-control" name="new_password"/>
                    <i class="text-error">{{form_error('new_password')}}</i>
                </div>

                <div class="form-group{{!empty(form_error('confirm_password'))?' has-error':''}}">
                    <input type="password" placeholder="Confirm new password:" class="form-control" name="confirm_password"/>
                    <i class="text-error">{{form_error('confirm_password')}}</i>
                </div>

                <input type="hidden" name="user_id" value="{{$authToken->user_id}}">
                <input type="hidden" name="reset_token" value="{{$authToken->reset_token}}">

                <button class="btn btn-success btn-lg" style="width:100%">Submit</button>
            {!Form::close()!}
        </div><!--well-->
        <br>
        <p class="text-center underline"><a href="/">&larr; Home</a></p>
    </div>
    <div class="col-md-4"></div>
</div>

@stop()