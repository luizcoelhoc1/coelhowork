<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>[@titulo]</title>
        <meta name="description" content="">
        <meta charset="utf-8" />
        <link rel='stylesheet' type='text/css' href='/plugins/bootstrap/css/bootstrap.min.css' media='all'>
        <style>
            .caixa-login {
                border:1px solid #BBB;
                padding:  5% 2%;
                width: 330px;
                margin: 5em auto 0;
                border-top: blueviolet solid 7px;
            }
            .caixa-login button {
                outline: 0;
                cursor: pointer;
                position: relative;
                display: inline-block;
                background: 0;
                width: 100%;
                border: 2px solid #e3e3e3;
                padding: 10px 0;
                font-size: 15px;
                font-weight: 600;
                line-height: 1;
                text-transform: uppercase;
                overflow: hidden;
                -webkit-transition: .3s ease;
                transition: .3s ease;
            }
            .caixa-login button span {
                position: relative;
                z-index: 1;
                color: #ddd;
                -webkit-transition: .3s ease;
                transition: .3s ease;
            }
            .caixa-login button:before {
                content: '';
                position: absolute;
                top: 49%;
                left: 49%;
                display: block;
                background: blueviolet;
                width: 30px;
                height: 30px;
                border-radius: 100%;
                border-color: blueviolet;
                opacity: 0;
                -webkit-transition: .4s ease;
                transition: .4s ease;
            }

            .caixa-login button span, .caixa-login button:active span, .caixa-login button:hover span {
                color: #999;
            }
            .caixa-login button:active span, .caixa-login button:hover span {
                color: #FFF;
            }
            .caixa-login button:active:before, .caixa-login button:hover:before {
                opacity: 1;
                -webkit-transform: scale(11);
                transform: scale(11);
            }
            .caixa-login label {
                color: blueviolet;
                opacity: 0.3;
            }

            .topo{
                background-color:blueviolet;
                height:20px;
                width:100%;	
            }
        </style>
        <script type='text/javascript' src='/plugins/jquery/jquery-3.1.1.min.js'></script>
        <script type='text/javascript' src='/plugins/bootstrap/js/bootstrap.min.js'></script>
    </head>
    <body>
        <div class="topo"></div>
        <div class="container">

            <div class="caixa-login">
                <a href="[@urlFacebook]"><img style="height: 50px; width: 50px; margin: -20px 0 20px 0;" src="/view/login/fblogo.png"/></a>
                <hr size="20"/>
                <form method="POST">
                    <div class="form-group">
                        <label for="user">Usuário</label>
                        <input type="text" class="form-control" id="user" placeholder="Usuário" name="user"  value=""/>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" placeholder="Senha" name="password">
                    </div>
                    <div class="checkbox" style="display: none;">
                        <label>
                            <input type="checkbox" name="oi"> Check me out
                        </label>
                    </div>
                    <span style="color:red;">[@msgErro]</span>
                    <button type="submit" class="btn"><span>Entrar</span></button>
                </form>
            </div>




            <script>
                $(".caixa-login input").on("focus", function (e) {
                    $(".caixa-login label[for=" + $(this).attr("id") + "]").css("opacity", "0.8");
                });
                $(".caixa-login input").on("focusout", function (e) {
                    if ($(this).val().length <= 0) {
                        $(".caixa-login label[for=" + $(this).attr("id") + "]").css("opacity", "0.3");
                    }
                });
            </script>
        </div>
    </body>
</html>