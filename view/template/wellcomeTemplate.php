<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CoelhoWork</title>
        <meta name="description" content="">
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="" />
        <link rel='stylesheet' type='text/css' href='/plugins/bootstrap/css/bootstrap.min.css' media='all'>
        <style>
            html, body {
                background-color: purple;
                height: 100%;
                background: radial-gradient(rgb(128, 0, 128), rgb(190, 50, 190));
            }
            .container {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
            }
            .main-title {
                color: white;
            }
            .sub-title {
                color: white;
            }
        </style>
    </style>
</head>
<body>
    <script type='text/javascript' src='/plugins/jquery/jquery-3.1.1.min.js'></script>
    <script type='text/javascript' src='/plugins/bootstrap/js/bootstrap.min.js'></script>

    <div class="container">
        <?php echo $content; ?>
    </div>
</body>
</html>