<style>
    .box {
        border: 1px solid #333;
        margin: 5% auto;
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 150px;
    }
</style>


<!DOCTYPE html>
<html>

    <body>
        <div class="box">
            <head>
                <title>Error</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
            </head>
            <?php echo $exception->getMessage() . " (" . $exception->getCode() . ")"; ?>
        </div>
    </body>
</html>
