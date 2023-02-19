<?php

    require_once(__DIR__ . "/db_inc.php");

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <title>Mon journal de bord</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <script>

if (localStorage.getItem("mode") == null) {
            localStorage.setItem("mode", "light");
        }
        if(localStorage.getItem("mode") == "dark") {
            localStorage.setItem("mode", "light");            
            setTimeout(() => {
                if(document.getElementById("cke_texte") != null) {
                    document.getElementById("cke_texte").style.setProperty('filter', 'invert(1)');
                }
            }, 925);
            changeColor();
        }

        function changeColor() {
            if (localStorage.getItem("mode") == null) {
                localStorage.setItem("mode", "light");
            }
            if (localStorage.getItem("mode") != "dark") {
                document.documentElement.style.setProperty('--dark', '#171717');
                document.documentElement.style.setProperty('--black', 'black');
                document.documentElement.style.setProperty('--light', '#f1f1f8');
                if(document.getElementById("cke_texte") != null) {
                    document.getElementById("cke_texte").style.setProperty('filter', 'invert(1)');
                }
                localStorage.setItem("mode", "dark");
            }
            else {
                document.documentElement.style.setProperty('--light', '#171717');
                document.documentElement.style.setProperty('--black', 'white');
                document.documentElement.style.setProperty('--dark', '#f1f1f8');
                if(document.getElementById("cke_texte") != null) {
                    document.getElementById("cke_texte").style.setProperty('filter', 'invert(0)');
                }
                localStorage.setItem("mode", "light");
            }
            document.getElementById("mode").blur();
        }

    </script>

    <h1>Mon journal de bord</h1>

    <button id="mode" onclick="changeColor()"><i class="fa fa-lightbulb" aria-hidden="true"></i></button>

    <?php
        $qry = $pdo->prepare("select * from journal order by timestamp_update, texte");
        $qry->execute();
        while (false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
    ?>

    <article>

        <h2><?php print($rec->titre); ?></h2>

        <p>Date : <?php print(date("d-m-Y", $rec->timestamp_update)); ?></p>

        <?php print($rec->texte); ?>

    </article>

    <?php
        }
    ?>

</body>
</html>