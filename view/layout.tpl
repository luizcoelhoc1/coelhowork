<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>[@titulo]</title>
        <meta name="description" content="">
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="" />
        <link rel='stylesheet' type='text/css' href='/plugins/bootstrap/css/bootstrap.min.css' media='all'>
        <style>

            .navbar {
                background-color: #8A2BE2;
                border-color: #6317A8;
            }
            .navbar .navbar-brand {
                color: #fff;
            }
            .navbar .navbar-brand:hover, .navbar .navbar-brand:focus {
                color: #fff;
            }
            .navbar .navbar-nav > li > a {
                color: #fff;
            }
            .navbar .navbar-nav > li > a:hover, .navbar .navbar-nav > li > a:focus {
                color: #fff;
            }
            .navbar .navbar-nav .active > a, .navbar .navbar-nav .active > a:hover, .navbar .navbar-nav .active > a:focus {
                color: #fff;
                background-color: #6317A8;
            }
            .navbar .navbar-nav .open > a, .navbar .navbar-nav .open > a:hover, .navbar .navbar-nav .open > a:focus {
                color: #fff;
                background-color: #6317A8;
            }
            .navbar .navbar-nav .open > a .caret, .navbar .navbar-nav .open > a:hover .caret, .navbar .navbar-nav .open > a:focus .caret {
                border-top-color: #fff;
                border-bottom-color: #fff;
            }
            .navbar .navbar-nav > .dropdown > a .caret {
                border-top-color: #fff;
                border-bottom-color: #fff;
            }
            .navbar .navbar-nav > .dropdown > a:hover .caret, .navbar .navbar-nav > .dropdown > a:focus .caret {
                border-top-color: #fff;
                border-bottom-color: #fff;
            }
            .navbar .navbar-toggle {
                border-color: #6317A8;
            }
            .navbar .navbar-toggle:hover, .navbar .navbar-toggle:focus {
                background-color: #6317A8;
            }
            .navbar .navbar-toggle .icon-bar {
                background-color: #fff;
            }

            @media (max-width: 767px) {
                .navbar .navbar-nav .open .dropdown-menu > li > a {
                    color: #fff;
                }
                .navbar .navbar-nav .open .dropdown-menu > li > a:hover, .navbar .navbar-nav .open .dropdown-menu > li > a:focus {
                    color: #fff;
                    background-color: #6317A8;
                }
            }

            a.solink {
                position: fixed;
                top: 0;
                width: 100%;
                text-align: center;
                background: #f3f5f6;
                color: #cfd6d9;
                border: 1px solid #cfd6d9;
                line-height: 30px;
                text-decoration: none;
                transition: all .3s;
                z-index: 999;
            }

            .navbar a:hover {
                color: #428bca;
            }

            .btn-blueviolet { 
                color: #ffffff; 
                background-color: #8A2BE2; 
                border-color: #6317A8; 
            } 

            .btn-blueviolet:hover, 
            .btn-blueviolet:focus, 
            .btn-blueviolet:active, 
            .btn-blueviolet.active, 
            .open .dropdown-toggle.btn-blueviolet { 
                color: #ffffff; 
                background-color: #49247A; 
                border-color: #6317A8; 
            } 

            .btn-blueviolet:active, 
            .btn-blueviolet.active, 
            .open .dropdown-toggle.btn-blueviolet { 
                background-image: none; 
            } 

            .btn-blueviolet.disabled, 
            .btn-blueviolet[disabled], 
            fieldset[disabled] .btn-blueviolet, 
            .btn-blueviolet.disabled:hover, 
            .btn-blueviolet[disabled]:hover, 
            fieldset[disabled] .btn-blueviolet:hover, 
            .btn-blueviolet.disabled:focus, 
            .btn-blueviolet[disabled]:focus, 
            fieldset[disabled] .btn-blueviolet:focus, 
            .btn-blueviolet.disabled:active, 
            .btn-blueviolet[disabled]:active, 
            fieldset[disabled] .btn-blueviolet:active, 
            .btn-blueviolet.disabled.active, 
            .btn-blueviolet[disabled].active, 
            fieldset[disabled] .btn-blueviolet.active { 
                background-color: #8A2BE2; 
                border-color: #6317A8; 
            } 

            .btn-blueviolet .badge { 
                color: #8A2BE2; 
                background-color: #ffffff; 
            }
        </style>


    </style>
</head>
<body>
    <script type='text/javascript' src='/plugins/jquery/jquery-3.1.1.min.js'></script>
    <script type='text/javascript' src='/plugins/bootstrap/js/bootstrap.min.js'></script>
    <br />
    <br />
    <br />
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://luizcoelhoc1.hostei.com/coelhowork/">CoelhoWORK</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">{@o}</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div class="container">
        [@conteudo]
    </div><!-- /.container -->
</body>
</html>