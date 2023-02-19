<?php

require_once(__DIR__ . "/db_inc.php");

$op = (isset($_POST["op"]) ? trim($_POST["op"]) : "");

$erreurs = "";

if ("dspadd" == $op) {
    $op = "add";
} else if ("add" == $op) {
    $titre = (isset($_POST["titre"]) ? strip_tags(trim($_POST["titre"])) : "");
    if (empty($titre)) {
        $erreurs .= "Veuillez indiquer un titre.\n";
    }
    $texte = (isset($_POST["texte"]) ? trim($_POST["texte"]) : "");
    if (empty($texte)) {
        $erreurs .= "Veuillez mettre du texte.\n";
    }
    if (empty($erreurs)) {
        $qry = $pdo->prepare("insert into journal (titre, texte, timestamp_create, timestamp_update) values (:ti, :te, :ts1, :ts2)");
        $qry->execute(array(":ti" => $titre, ":te" => $texte, ":ts1" => time(), ":ts2" => time()));
        $op = "lst";
    }
} else if ("dspupd" == $op) {
    $id = (isset($_POST["id"]) ? intval($_POST["id"]) : -1);
    $qry = $pdo->prepare("select * from journal where id=:id");
    $qry->execute(array(":id" => $id));
    if (false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
        $titre = $rec->titre;
        $texte = $rec->texte;
        $op = "upd";
    } else {
        $erreurs .= "Affichage impossible. Entrée du journal inconnue.\n";
        $op = "lst";
    }
} else if ("upd" == $op) {
    $id = (isset($_POST["id"]) ? intval($_POST["id"]) : -1);
    $qry = $pdo->prepare("select * from journal where id=:id");
    $qry->execute(array(":id" => $id));
    $titlequery = $pdo->prepare("SELECT titre FROM journal WHERE id=:id");
    $titlequery->bindParam(':id', $id);
    $titlequery->execute();
    $oldtitre = $titlequery->fetchColumn();
    $textquery = $pdo->prepare("SELECT texte FROM journal WHERE id=:id");
    $textquery->bindParam(':id', $id);
    $textquery->execute();
    $oldtext = $textquery->fetchColumn();
    if (false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
        $titre = (isset($_POST["titre"]) ? strip_tags(trim($_POST["titre"])) : $oldtitre);
        if (empty($titre)) {
            $erreurs .= $oldtitre . "\n\n";
        }
        $texte = (isset($_POST["texte"]) ? trim($_POST["texte"]) : "");
        if (empty($texte)) {
            $erreurs .= $oldtext . "\n";
        }
        if(empty($erreurs)) {
            $qry = $pdo->prepare("update journal set titre=:ti, texte=:te, timestamp_update=:ts where id=:id");
            $qry->execute(array(":id" => $id, ":ti" => $titre, ":te" => $texte, ":ts" => time()));
            $op = "dsp";
        }
    } else {
        $erreurs .= "Mise à jour impossible. Entrée du journal inconnue.\n";
        $op = "lst";
    }
} else if ("dspdlt" == $op) {
    $id = (isset($_POST["id"]) ? intval($_POST["id"]) : -1);
    $qry = $pdo->prepare("select * from journal where id=:id");
    $qry->execute(array(":id" => $id));
    if (false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
        $titre = $rec->titre;
        $texte = $rec->texte;
        $op = "dlt";
    } else {
        $erreurs .= "Affichage impossible. Entrée du journal inconnue.\n";
        $op = "lst";
    }
} else if ("dlt" == $op) {
    $id = (isset($_POST["id"]) ? intval($_POST["id"]) : -1);
    $qry = $pdo->prepare("select * from journal where id=:id");
    $qry->execute(array(":id" => $id));
    if (false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
        $qry = $pdo->prepare("delete from journal where id=:id");
        $qry->execute(array(":id" => $id));
    } else {
        $erreurs .= "Suppression impossible. Entrée du journal inconnue.\n";
    }
    $op = "lst";
} else if ("dsp" == $op) {
    $id = (isset($_POST["id"]) ? intval($_POST["id"]) : -1);
    $qry = $pdo->prepare("select * from journal where id=:id");
    $qry->execute(array(":id" => $id));
    if (false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
        $titre = $rec->titre;
        $texte = $rec->texte;
        $op = "dsp";
    } else {
        $erreurs .= "Affichage impossible. Entrée du journal inconnue.\n";
        $op = "lst";
    }
} else {
    $op = "lst";
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <title>Gestion de mon journal de bord</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="ckeditor_4.20.1/ckeditor/ckeditor.js"></script>
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

    <h1>Gestion de mon journal de bord</h1>

    <?php
        if (!empty($erreurs)) {
            print("<div class='erreurs'>" . nl2br($erreurs) . "</div>");
        }
    ?>

    <button id="mode" onclick="changeColor()"><i class="fa fa-lightbulb" aria-hidden="true"></i></button>

    <form method="post" action="admin.php" id="frm">
        <input type="hidden" name="op" id="op" value="<?php print(isset($op) ? $op : ""); ?>">
        <input type="hidden" name="id" id="id" value="<?php print(isset($id) ? $id : -1); ?>">
        <?php
            if ("lst" == $op) {
        ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Titre</th>
                <th>ID</th>
                <th>
                    <button type="button" onclick="LancerOperation('dspadd');" class="green"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </th>
            </tr>
            <?php
                $qry = $pdo->prepare("select * from journal order by timestamp_update, titre");
                $qry->execute();
                while (false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
            ?>
            <tr>
                <td class="date"><?php print(date("d-m-Y", $rec->timestamp_update)); ?></td>
                <td>
                    <?php print(htmlentities($rec->titre)); ?>
                </td>
                <td>
                    <?php print($rec->id); ?>
                </td>
                <td>
                    <button type="button" onclick="LancerOperation('upd',<?php print($rec->id); ?>);" class="blue"><i class="fa fa-pen" aria-hidden="true"></i></button>
                    <button type="button" onclick="LancerOperation('dlt',<?php print($rec->id); ?>);" class="red"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    <button type="button" onclick="LancerOperation('dsp',<?php print($rec->id); ?>);" class="purple"><i class="fa fa-eye" aria-hidden="true"></i></button>
                </td>
            </tr>
            <?php
                }
            ?>
        </table>
        <?php
            } else if (("dspadd" == $op) || ("add" == $op)) {
        ?>
        <fieldset>
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" placeholder="Titre de votre entrée de journal" value="<?php print(isset($titre) ? htmlentities($titre) : ""); ?>">
        </fieldset>
        <fieldset>
            <label for="texte">Texte</label>
            <textarea name="texte" id="texte" placeholder="Texte de votre entrée de journal">
                <?php
                    print(isset($texte) ? htmlentities($texte) : "");
                ?>
            </textarea>
        </fieldset>
        <div>
            <button type="button" onclick="RetourListe();" class="red"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
            <button type="submit" class="green"><i class="fa fa-save" aria-hidden="true"></i></button>
        </div>
        <?php
            } else if (("dspupd" == $op) || ("upd" == $op)) {
        ?>
        <fieldset>
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" placeholder="Nouveau titre" value="<?php print(isset($titre) ? htmlentities($titre) : ""); ?>">
        </fieldset>
        <fieldset>
            <label for="texte">Texte</label>
            <textarea name="texte" id="texte" placeholder="Nouveau texte">
                <?php
                    print(isset($texte) ? htmlentities($texte) : "");
                ?>
            </textarea>
        </fieldset>
        <div>
            <button type="button" onclick="RetourListe();" class="red"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
            <button type="submit" onclick="LancerOperation('upd')" class="green"><i class="fa fa-save" aria-hidden="true"></i></button>
        </div>
        <?php
            } else if (("dspdlt" == $op) || ("dlt" == $op) || ("dsp" == $op)) {
        ?>
        <fieldset>
            <label for="titre">Titre</label>
            <?php
                print(isset($titre) ? htmlentities($titre) : "");
            ?>
        </fieldset>
        <fieldset>
            <label for="texte">Texte</label>
            <?php
                print(isset($texte) ? $texte : "");
            ?>
        </fieldset>
        <div>
            <?php
                if (("dspdlt" == $op) || ("dlt" == $op)) {
            ?>
            <button type="submit" class="green"><i class="fa fa-save" aria-hidden="true"></i></button>
            <?php
                }
            ?>
            <button type="button" onclick="RetourListe();" class="red"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
        </div>
        <?php
            }
        ?>
    </form>

    <script>
        if(document.getElementById('texte') != null) {
            CKEDITOR.replace('texte');
        }
        <?php
        if ("lst" == $op) {
        ?>function LancerOperation(op, id) {
            document.getElementById('op').value = op;
            if (id) {
                document.getElementById('id').value = id;
            }
            document.getElementById('frm').submit();
        }<?php
        } ?>
        function RetourListe() {
            document.getElementById('op').value = 'lst';
            document.getElementById('frm').submit();
        }
    </script>

</body>
</html>