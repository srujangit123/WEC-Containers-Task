<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Apparel Prices</title>
</head>
<body>

    <h1>Apparel Prices:</h1><br>

    <ul>
        <?php
            foreach(json_decode(file_get_contents('http://prices')) as $item){
                echo "<li>$item->name: $$item->price</li>";
            }
        ?>
    </ul>

    <h2>Core Apparel:</h2><br>

    <ul>
        <?php
            foreach(json_decode(file_get_contents('http://apparel')) as $item){
                echo "<li>$item->name</li>";
            }
        ?>
    </ul>
</body>
</html>