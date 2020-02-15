@extend('layouts/frontend', ['title' => 'Homepage'])


<style type="text/css">
    body {
        background: #0b0218;
        text-align: center;
        font-family: 'Tomorrow', sans-serif;
    }

    img {
        margin-top: 15%;
    }

    .title {
        text-align: center;
        color: #00fcff;
        text-shadow: -2px 2px 0px rgba(255, 0, 229, 0.8);
        font-size: 28px;
        margin: 0
    }

    .sub {
        margin: 0;
        font-size: 15px;
        color: #787878;
    }

    center {
        font-size: 13px;
        position: fixed;
        bottom: 5px;
        width: 100%;
        color: #666666;
    }
</style>

<img src="/assets/img/strife.png" width="150">
<p class="title">Strife Framework</p>
<p class="sub">A Fast and Lightweight PHP MVC Framework.</p>

<center>&copy; 2020 Strife Framework. All rights reserved.</center>

@stop()