@extend('layouts/frontend')

<div class="strife">
    <p class="logo">S</p>
    <p class="title">Strife Framework <sup>PHP 8.0</sup></p>
    <p class="sub-title">A Fast and Lightweight PHP MVC Framework.</p>
</div>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        font-weight: 300;
        text-align: center;
        background: #fae7e7;
        background: -webkit-linear-gradient(top right,#ffc2c2,#ffe3e3,#ffffff);
        height: 1024px;
        overflow: hidden;
        margin-top: 10%;
    }
    ul {
        list-style: none;
        padding: 0;
    }
    ul li {
        display: inline
    }
    ul li a {
        display: inline-table;
        padding: 5px;
        color: #8a8a8a;
        text-shadow: 0 2px 1px #e2e2e2;
        text-decoration: none;
    }
    ul li a:hover {
        color: #3a3a3a
    }
    .logo {
        font-size: 150px;
        margin: 0;
        padding: 0;
        font-weight: 100;
        color: #ffffff;
        position: relative;
        left: -20px;
        font-style: italic;
        text-shadow: 0px 0px 0px #eb452b,
        5px  5px 0px #efa032,
        10px 10px 0px #46b59b,
        15px 15px 0px #017e7f,
        20px 20px 0px #052939,
        25px 25px 0px #c11a2b,
        30px 30px 0px #c11a2b,
        35px 35px 0px #c11a2b,
        40px 40px 0px #c11a2b;
    }
    .title {
        font-size: 50px;
        color: #ffffff;
        text-shadow: -2px 1px 2px #b36464;
        margin-bottom: 0;
        position: relative;
    }
    .title sup {
        position: absolute;
        font-size: 16px;
        text-shadow: none;
        color: #ffffff;
        text-shadow: 0 1px 2px #9a6464;
        font-weight: 400;
        font-style: italic;
    }
    .sub-title {
        margin: 0;
        font-style: italic;
        font-size: 16px;
    }
</style>

@stop()