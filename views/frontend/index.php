@extend('layouts/frontend', ['title' => 'Homepage'])

<style type="text/css">
    body {
        background: #f9f9f9;
        text-align: center;
    }

    img {
        margin-top: 15%;
    }

    .title {
        text-align: center;
        color: rgba(48, 48, 48, 0.84);
        font-size: 24px;
        margin: 0
    }

    .sub {
        margin: 0;
        font-size: 13px;
        color: #787878;
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

<center>&copy; 2019 Strife Framework. All rights reserved.</center>

@stop()