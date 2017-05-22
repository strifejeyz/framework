@extend('layouts/frontend', ['title' => 'Homepage'])

<style type="text/css">
    body {
        font-family: "Segoe UI Light", "Segoe UI";
        background: #ffffff;
        text-align: center;
    }

    img {
        margin-top: 15%;
    }

    .title {
        text-align: center;
        color: #ff2469;
        font-size: 28px;
        margin: 0
    }

    .sub {
        margin: 0;
        font-size: 15px;
        color: #333333;
        font-style: italic;
    }

    center {
        font-size: 12px;
        position: fixed;
        bottom: 5px;
        width: 100%;
        color: #666666;
    }
</style>

<img src="img/strife.png" width="120">
<p class="title">Strife Framework</p>
<p class="sub">A Fast and Lightweight PHP MVC Framework.</p>

<center>&copy; 2017 Strife Framework. All rights reserved.</center>

@stop()