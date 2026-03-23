<?php


$conn = oci_connect("SYSTEM", "daniel2026", "localhost/XE");
if (!$conn) {
    $e = oci_error();
    die("Erreur de connexion : " . $e['message']);
}

if (isset($_POST["enregistre"])) {

    if (!empty($_POST["filiere"]) && !empty($_POST["niveau"])) {

        $filiere = $_POST["filiere"];
        $niveau = $_POST["niveau"];
        $responsable = "-";
        $classe = "-";
        $nombre = 0;

        $filieresur = "SELECT COUNT(*) AS NBAF FROM filiere WHERE nomfiliere = :fil AND niveau = :nav";
        $xexee = oci_parse($conn,$filieresur);
        oci_bind_by_name($xexee,":fil",$filiere);
        oci_bind_by_name($xexee,":nav",$niveau);

        oci_execute($xexee);

        $nbresur = oci_fetch_assoc($xexee);

        if ($nbresur["NBAF"] == 0) {
            $fil = "INSERT INTO filiere (nomfiliere,niveau,responsable,nbre_etudiant,salle) 
            VALUES (:nomfil,:niv,:respo,:nbre,:sal)";

            $stmt = oci_parse($conn,$fil);

            oci_bind_by_name($stmt,":nomfil",$filiere);
            oci_bind_by_name($stmt,":niv",$niveau);
            oci_bind_by_name($stmt,":respo",$responsable);
            oci_bind_by_name($stmt,":nbre",$nombre);
            oci_bind_by_name($stmt,":sal",$classe);

            oci_execute($stmt);
        }
    }
}

if (isset($_POST["supp"])) {
    if (!empty($_POST["supprimer"])) {
        $suppfiliere = $_POST["supprimer"];
        $niveau = $_POST["niv"];
        $supp = "DELETE FROM filiere WHERE nomfiliere = :nomsupp AND niveau = :nivsupp";
        $sup = "DELETE FROM matiere WHERE filierematiere = :fi AND niveau = :niv";
        $sav = "DELETE FROM etudiant WHERE filiere = :fil AND niveau =:ni";
        $parse = oci_parse($conn,$sav);
        $suparse = oci_parse($conn,$sup);
        $supparse = oci_parse($conn,$supp);

        oci_bind_by_name($supparse,":nomsupp",$suppfiliere);
        oci_bind_by_name($supparse,":nivsupp",$niveau);

        oci_bind_by_name($suparse,":fi",$suppfiliere);
        oci_bind_by_name($suparse,":niv",$niveau);

        oci_bind_by_name($parse,":fil",$suppfiliere);
        oci_bind_by_name($parse,":ni",$niveau);

        oci_execute($supparse);
        oci_execute($suparse);
        oci_execute($parse);
    }
}

if (isset($_POST["suppreme"])) {
    if (!empty($_POST["supprime"])) {
        $suppfiliere = $_POST["supprime"];
        $salsup = "DELETE FROM salle WHERE nomsalle = :salname";
        $salparse = oci_parse($conn,$salsup);
        oci_bind_by_name($salparse,":salname",$suppfiliere);
        oci_execute($salparse);
    }
}

if (isset($_POST["show"])) {
    if (!empty($_POST["supprimee"]) && !empty($_POST["niv1"])) {
        $suppfiliere = $_POST["supprimee"];
        $niveau = $_POST["niv1"];
        $supp = "DELETE FROM surveillant WHERE nomsurveillant = :nomsupp AND prenomsurveillant = :nivsupp";
        $supparse = oci_parse($conn,$supp);

        oci_bind_by_name($supparse,":nomsupp",$suppfiliere);
        oci_bind_by_name($supparse,":nivsupp",$niveau);

        oci_execute($supparse);
    }
}

if (isset($_POST["show1"])) {
    if (!empty($_POST["mes"]) && !empty($_POST["niv2"])) {
        $suppfiliere = $_POST["mes"];
        $niveau = $_POST["niv2"];
        $supp = "DELETE FROM etudiant WHERE nometudiant = :nomsupp AND prenometudiant = :nivsupp";
        $supparse = oci_parse($conn,$supp);

        oci_bind_by_name($supparse,":nomsupp",$suppfiliere);
        oci_bind_by_name($supparse,":nivsupp",$niveau);

        oci_execute($supparse);
    }
}

if (isset($_POST["soumettre"])) {
    if (!empty($_POST["type"]) && !empty($_POST["batiment"]) && !empty($_POST["capacite"]) && !empty($_POST["sallenom"])) {
        
        $sallenom = $_POST["sallenom"];
        $capacite = $_POST["capacite"];
        $batiment = $_POST["batiment"];
        $type = $_POST["type"];
        $statuus = "Disponible";
        $respo = "-";

        $filiere = "SELECT IDFILIERE FROM filiere WHERE salle = :sal";
        $filparse = oci_parse($conn,$filiere);
        oci_bind_by_name($filparse,":sal",$sallenom);
        oci_execute($filparse);

        $result = oci_fetch_assoc($filparse);

       $idfiliere = $result ? $result["IDFILIERE"] : NULL;

        $salsur = "SELECT COUNT(*) AS NBAS FROM salle WHERE nomsalle = :salnom";
        $xexe = oci_parse($conn,$salsur);
        oci_bind_by_name($xexe,":salnom",$sallenom);
        

        oci_execute($xexe);

        $nbresal = oci_fetch_assoc($xexe);

        if ($nbresal["NBAS"] == 0) {
            $salinsert = "INSERT INTO salle (idfiliere,nomsalle,type_salle,responsable,capacite,statue,batiment) 
            VALUES (:id,:class,:typesalle,:respo,:cap,:sta,:bat)";

            $sallparse = oci_parse($conn,$salinsert);

            oci_bind_by_name($sallparse,":id",$idfiliere);
            oci_bind_by_name($sallparse,":class",$sallenom);
            oci_bind_by_name($sallparse,":bat",$batiment);
            oci_bind_by_name($sallparse,":typesalle",$type);
            oci_bind_by_name($sallparse,":cap",$capacite);
            oci_bind_by_name($sallparse,":sta",$statuus);
            oci_bind_by_name($sallparse,":respo",$respo);

            oci_execute($sallparse);
        }
    }
}

if (isset($_POST["submit"])) {
    if (!empty($_POST["nomsurveillant"]) && !empty($_POST["prenomsurveillant"]) && !empty($_POST["matriculesurveillant"]) && !empty($_POST["contactsurveillant"])) {
        
        $nomsurveillant = $_POST["nomsurveillant"];
        $prenomsurveillant = $_POST["prenomsurveillant"];
        $matriculesurveillant = $_POST["matriculesurveillant"];
        $contactsurveillant = $_POST["contactsurveillant"];
        $statue = "Disponible";
        $filiere = "-";
        $salle = "-";

        $surveillant = "SELECT COUNT(*) AS NB FROM surveillant WHERE nomsurveillant = :nomsur AND prenomsurveillant = :presur";
        $exe = oci_parse($conn,$surveillant);
        oci_bind_by_name($exe,":nomsur",$nomsurveillant);
        oci_bind_by_name($exe,":presur",$prenomsurveillant);

        oci_execute($exe);

        $stmt = oci_fetch_assoc($exe);

        if ($stmt["NB"] == 0) {
            $insert = "INSERT INTO surveillant (nomsurveillant,prenomsurveillant,matriculesurveillant,contactsurveillant,statue,filiere,salle)
            VALUES (:nom,:prenom,:matri,:cont,:sta,:fil,:sal)";

            $xexe = oci_parse($conn,$insert);

            oci_bind_by_name($xexe,":nom",$nomsurveillant);
            oci_bind_by_name($xexe,":prenom",$prenomsurveillant);
            oci_bind_by_name($xexe,":matri",$matriculesurveillant);
            oci_bind_by_name($xexe,":cont",$contactsurveillant);
            oci_bind_by_name($xexe,":sta",$statue);
            oci_bind_by_name($xexe,":fil",$filiere);
            oci_bind_by_name($xexe,":sal",$salle);

            oci_execute($xexe);
        }

    }
}

if (isset($_POST["envoie"])) {
    if (!empty($_POST["selectfiliere"]) && !empty($_POST["selectstatus"]) && !empty($_POST["selectniveau"]) 
        && !empty($_POST["names"]) && !empty($_POST["surname"]) && !empty($_POST["mat"]) && !empty($_POST["cont"])) {
        
        $selectfiliere = $_POST["selectfiliere"];
        $selectstatus = $_POST["selectstatus"];
        $selectniveau = $_POST["selectniveau"];
        $names = $_POST["names"];
        $surname = $_POST["surname"];
        $mat = $_POST["mat"];
        $cont = $_POST["cont"];

        $surveillant = "SELECT COUNT(*) AS NB FROM etudiant WHERE nometudiant = :nomsur AND prenometudiant = :presur";
        $exe = oci_parse($conn,$surveillant);
        oci_bind_by_name($exe,":nomsur",$names);
        oci_bind_by_name($exe,":presur",$surname);

        oci_execute($exe);

        $stmt = oci_fetch_assoc($exe);

        $filiere = "SELECT COUNT(*) AS NBA FROM filiere WHERE nomfiliere = :tas AND niveau = :nv";
        $fill = oci_parse($conn,$filiere);
        oci_bind_by_name($fill,":tas",$selectfiliere);
        oci_bind_by_name($fill,":nv",$selectniveau);
        oci_execute($fill);

        $sul = oci_fetch_assoc($fill);

        if ($stmt["NB"] == 0) {
            if ($sul["NBA"] == 1) {
                $insert = "INSERT INTO etudiant (nometudiant,prenometudiant,matricule,filiere,niveau,contact,statue)
                VALUES (:nom,:prenom,:matri,:cont,:sta,:fil,:sal)";

                $xexe = oci_parse($conn,$insert);

                oci_bind_by_name($xexe,":nom",$names);
                oci_bind_by_name($xexe,":prenom",$surname);
                oci_bind_by_name($xexe,":matri",$mat);
                oci_bind_by_name($xexe,":cont",$selectfiliere);
                oci_bind_by_name($xexe,":sta",$selectniveau);
                oci_bind_by_name($xexe,":fil",$cont);
                oci_bind_by_name($xexe,":sal",$selectstatus);

                oci_execute($xexe);

                $ln = "SELECT COUNT(*) AS TOTAL FROM etudiant WHERE filiere = :f AND niveau = :n";
                $lnparse = oci_parse($conn,$ln);
                oci_bind_by_name($lnparse,":f",$selectfiliere);
                oci_bind_by_name($lnparse,":n",$selectniveau);

                oci_execute($lnparse);

                $rp = oci_fetch_assoc($lnparse);

                $lne = "UPDATE filiere SET nbre_etudiant = :et WHERE nomfiliere = :nf AND niveau = :nm";
                $lneparse = oci_parse($conn,$lne);
                oci_bind_by_name($lneparse,":et",$rp["TOTAL"]);
                oci_bind_by_name($lneparse,":nf",$selectfiliere);
                oci_bind_by_name($lneparse,":nm",$selectniveau);

                oci_execute($lneparse);
            }
        }

    }
}

if (isset($_POST["dif"])) {

    $nom = $_POST["nom"];
    $date = $_POST["date"];
    $heuredebut = $_POST["heuredebut"];
    $heurefin = $_POST["heurefin"];
    $fil = $_POST["fil"];
    $suv = $_POST["suv"];
    $sa = $_POST["sa"];
    $ni = $_POST["ni"];
    $dis = "Occupee";

    $erreur = false;

    for ($i = 0; $i < count($heuredebut); $i++) {
        for ($j = $i + 1; $j < count($heuredebut); $j++) {

            // On compare uniquement si les dates sont les mêmes
            if ($date[$i] == $date[$j]) {

                // Convertir les heures en timestamp pour comparer facilement
                $debut1 = strtotime($heuredebut[$i]);
                $fin1   = strtotime($heurefin[$i]);
                $debut2 = strtotime($heuredebut[$j]);
                $fin2   = strtotime($heurefin[$j]);

                // Vérifier le chevauchement
                if (($debut2 < $fin1 && $debut2 >= $debut1) ||   // début de j dans l'intervalle i
                    ($fin2 > $debut1 && $fin2 <= $fin1) ||       // fin de j dans l'intervalle i
                    ($debut1 >= $debut2 && $fin1 <= $fin2)) {    // intervalle i complètement inclus dans j
                    $erreur = true;
                    break 2; // on sort des deux boucles
                }
            }
        }
    }

    if ($erreur) {

        // supprimer toutes les matières de cette filière
        $delete = "DELETE FROM matiere WHERE filierematiere = :fil AND niveau = :nv";
        $delparse = oci_parse($conn, $delete);
        oci_bind_by_name($delparse, ":fil", $fil);
        oci_bind_by_name($delparse, ":nv", $ni);
        oci_execute($delparse);

        echo "Conflit d'heure detecté ❌. Tout a été annulé.";
    }


    else {

        $mat = "SELECT COUNT(*) AS NB FROM filiere WHERE nomfiliere = :tas AND niveau = :ts";
            $exe = oci_parse($conn,$mat);
            oci_bind_by_name($exe,":tas",$fil);
            oci_bind_by_name($exe,":ts",$ni);
            oci_execute($exe);
            $result = oci_fetch_assoc($exe);

            if ($result["NB"] == 1) {
                $ma = "SELECT COUNT(*) AS NBA FROM matiere WHERE filierematiere = :mat AND niveau = :mit";
                $ex = oci_parse($conn,$ma);
                oci_bind_by_name($ex,":mat",$fil);
                oci_bind_by_name($ex,":mit",$ni);
                oci_execute($ex);
                $resul = oci_fetch_assoc($ex);

                if ($resul["NBA"] == 0) {

                    $update = "UPDATE filiere
                            SET salle = :nom, 
                                responsable = :hdeb
                            WHERE nomfiliere = :js AND niveau = :jv";

                    $parse = oci_parse($conn, $update);

                    $updat = "UPDATE salle
                            SET responsable = :nom, 
                                statue = :hdeb
                            WHERE nomsalle = :jv";

                    $pars = oci_parse($conn, $updat);

                    oci_bind_by_name($pars, ":nom", $suv);
                    oci_bind_by_name($pars, ":hdeb", $dis);
                    oci_bind_by_name($pars, ":jv", $sa);

                    $upda = "UPDATE surveillant
                            SET filiere = :nom, 
                                salle = :hdeb,
                                statue = :js
                            WHERE nomsurveillant = :jv";

                    $par = oci_parse($conn, $upda);

                    oci_bind_by_name($par, ":nom", $fil);
                    oci_bind_by_name($par, ":hdeb", $sa);
                    oci_bind_by_name($par, ":js", $dis);
                    oci_bind_by_name($par, ":jv", $suv);

                    oci_bind_by_name($parse, ":nom", $sa);
                    oci_bind_by_name($parse, ":hdeb", $suv);
                    oci_bind_by_name($parse, ":js", $fil);
                    oci_bind_by_name($parse, ":jv", $ni);

                    oci_execute($parse);
                    oci_execute($pars);
                    oci_execute($par);
                        for ($i = 0; $i < count($nom); $i++) {
                            $insert = "INSERT INTO matiere (nommatiere, heuredebut, heurefi, datematiere, filierematiere, niveau)
                            VALUES (:nom, TO_DATE(:hdeb,'HH24:MI'), TO_DATE(:hfin,'HH24:MI'), TO_DATE(:datex,'YYYY-MM-DD'), :fil, :nvs)";

                            $stmt = oci_parse($conn, $insert);

                            oci_bind_by_name($stmt, ":nom", $nom[$i]);
                            oci_bind_by_name($stmt, ":datex", $date[$i]);
                            oci_bind_by_name($stmt, ":hdeb", $heuredebut[$i]);
                            oci_bind_by_name($stmt, ":hfin", $heurefin[$i]);
                            oci_bind_by_name($stmt, ":fil", $fil);
                            oci_bind_by_name($stmt, ":nvs", $ni);

                            oci_execute($stmt);
                        }       
                }
            }
    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des examens zone admistrateur</title>
    <link rel="stylesheet" href="interface.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>

    <header>
        <p id="logo"><i class="fa-solid fa-calendar-days"></i> FiliExam</p><hr>
        <nav>
            <ul class="header">
                <li><a href="#dasboard"><i class="fa-solid fa-layer-group"></i> Dashboard</a></li>
                <li><a href="#filiere"><i class="fa-solid fa-book"></i> Filieres</a></li>
                <li><a href="#salle"><i class="fa-solid fa-building-columns"></i> Salles</a></li>
                <li><a href="#surveilant"><i class="fa-solid fa-address-book"></i> Surveillants</a></li>
                <li><a href="#etudiant"><i class="fa-solid fa-graduation-cap"></i> Etudiants</a></li>
                <li><a href="#generer"><i class="fa-brands fa-hubspot"></i> Generer</a></li>
                <li><a href="#afficher"><i class="fa-solid fa-shapes"></i> Afficher</a></li>
            </ul>
        </nav>
    </header>

    <div class="search">
        <div id="search">
            <input type="search" placeholder="Rechercher par un nom etc...">
            <span><i class="fa-solid fa-magnifying-glass"></i></span>
        </div>
        <div class="logo">
            <img src="logo.png" alt="logo">
            <p>Institut universitaire du golfe de guinnee</p>
            <a href="#">www.univ-iug.com</a>
        </div>
    </div>

    <div id="dasboard">
        <p id="classdasboard"><span><i class="fa-solid fa-layer-group"></i></span> Dashboard</p>
        <div class="statistique">
            <div class="filiere" id="fil">
                <span><i class="fa-solid fa-book"></i></span>
                <p class="p1">Filieres</p>
                <p class="p2">
                    <?php
                        $nbre1 = "SELECT COUNT(*) AS NB FROM filiere";
                        $nbre1parse = oci_parse($conn,$nbre1);
                        oci_execute($nbre1parse);

                        $nbre = oci_fetch_assoc($nbre1parse);

                        echo $nbre["NB"];
                     ?>
                </p>
            </div>
            <div class="filiere" id="sall">
                <span><i class="fa-solid fa-building-columns"></i></span>
                <p class="p1">Salles</p>
                <p class="p2">
                    <?php
                        $sal1 = "SELECT COUNT(*) AS NBA FROM salle";
                        $sal1parse = oci_parse($conn,$sal1);
                        oci_execute($sal1parse);

                        $nbresal = oci_fetch_assoc($sal1parse);

                        echo $nbresal["NBA"];
                     ?>
                </p>
            </div>
             <div class="filiere" id="sur">
                <span><i class="fa-solid fa-address-book"></i></span>
                <p class="p1">Surveillants</p>
                <p class="p2">
                    <?php
                        $sur1 = "SELECT COUNT(*) AS NBA FROM surveillant";
                        $sur1parse = oci_parse($conn,$sur1);
                        oci_execute($sur1parse);

                        $nbresur = oci_fetch_assoc($sur1parse);

                        echo $nbresur["NBA"];
                     ?>
                </p>
            </div>
             <div class="filiere" id="etud">
                <span><i class="fa-solid fa-graduation-cap"></i> </span>
                <p class="p1">Etudiants</p>
                <p class="p2"><?php
                        $sal1 = "SELECT COUNT(*) AS NBA FROM etudiant";
                        $sal1parse = oci_parse($conn,$sal1);
                        oci_execute($sal1parse);

                        $nbresal = oci_fetch_assoc($sal1parse);

                        echo $nbresal["NBA"];
                     ?></p>
            </div>
        </div>
        <p id="classdasboard"><span><i class="fa-solid fa-location-dot"></i></span> Localisation</p>
        <iframe
            src="https://www.google.com/maps?q=Institut%20Universitaire%20du%20Golfe%20de%20Guinée&output=embed"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
    <div id="filiere">
        <p id="classfiliere"><span><i class="fa-solid fa-book"></i></span> Filiere</p>
        <button type="button" id="ajouterfiliere"><i class="fa-solid fa-plus"></i> Ajouter une filiere</button>
        <div class="divtable1">
            <?php 
                $tableau = "SELECT COUNT(*) AS NB FROM filiere";
                $tablexecute = oci_parse($conn,$tableau);
                oci_execute($tablexecute);

                $nbrefiliere = oci_fetch_assoc($tablexecute);

                if ($nbrefiliere["NB"] == 0) {
                    echo '<div class="ras">
                                <i class="fa-solid fa-book"></i>
                                <p>Aucune filiere a ete ajouter veuiilez le faire ulterieurement.</p>
                            </div>';
                }else {
                    $sql = "SELECT * FROM filiere";
                    $stmt = oci_parse($conn, $sql);
                    oci_execute($stmt);
                    echo '
                            <table id="table1">
                                <tr>
                                    <th>Filiere</th>
                                    <th>Niveau</th>
                                    <th>Responsable</th>
                                    <th> Nbre Etudiant</th>
                                    <th>Salle</th>
                                    <th>Action</th>
                                </tr>
                    ';
                    while ($ligne = oci_fetch_assoc($stmt)) {
                        echo '
                            <tr class="information">
                                <td>'. $ligne["NOMFILIERE"] .'</td>
                                <td>'. $ligne["NIVEAU"] .'</td>
                                <td>'. $ligne["RESPONSABLE"] .'</td>
                                <td>'. $ligne["NBRE_ETUDIANT"] .'</td>
                                <td>'. $ligne["SALLE"] .'</td>
                                <td> 
                                    <button id="info"><i class="fa-solid fa-info"></i></button>
                                    <button id="edite"><i class="fa-solid fa-pen"></i></button>
                                    <button id="supprime" class="bnt" data-filiere="'.$ligne["NOMFILIERE"].'" data-niveau = "'.$ligne["NIVEAU"].'"><i class="fa-solid fa-trash"></i></button>
                                </td>';
                    };

                    echo '</table>';
                }
            ?>
        </div>
    </div>

    <div id="salle">
        <p id="classfiliere"><span><i class="fa-solid fa-building-columns"></i></span> Salles</p>
        <button type="button" id="ajoutersalle"><i class="fa-solid fa-plus"></i> Ajouter une salle</button>
        <div class="divtable1">
           <?php 
                $tableau = "SELECT COUNT(*) AS NA FROM salle";
                $tablexecute = oci_parse($conn,$tableau);
                oci_execute($tablexecute);

                $nbrefiliere = oci_fetch_assoc($tablexecute);

                if ($nbrefiliere["NA"] == 0) {
                    echo '<div class="ras">
                                <i class="fa-solid fa-building-columns"></i> 
                                <p>Aucune salle a ete ajouter veuiilez le faire ulterieurement.</p>
                            </div>';
                }else {
                    $sql = "SELECT * FROM salle";
                    $stmt = oci_parse($conn, $sql);
                    oci_execute($stmt);
                    echo '
                            <table id="table1">
                                <tr>
                                    <th>Nom de la salle</th>
                                    <th>Capacite</th>
                                    <th>Batiment</th>
                                    <th>Type de salle</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                    ';
                    while ($ligne = oci_fetch_assoc($stmt)) {
                        echo '
                            <tr class="information">
                                <td>'. $ligne["NOMSALLE"] .'</td>
                                <td>'. $ligne["CAPACITE"] .'</td>
                                <td>'. $ligne["BATIMENT"] .'</td>
                                <td>'. $ligne["TYPE_SALLE"] .'</td>
                                <td>'. $ligne["STATUE"] .'</td>
                                <td> 
                                    <button id="info"><i class="fa-solid fa-info"></i></button>
                                    <button id="edite"><i class="fa-solid fa-pen"></i></button>
                                    <button id="supprime" class="bnt1" data-salle="'.$ligne["NOMSALLE"].'"><i class="fa-solid fa-trash"></i></button>
                                </td>';
                    };

                    echo '</table>';
                }
            ?>
        </div>
    </div>

    <div id="surveilant">
        <p id="classfiliere"><span><i class="fa-solid fa-address-book"></i></span> Surveillants</p>
        <button type="button" id="ajoutersurveillant"><i class="fa-solid fa-plus"></i> Ajouter une surveillant</button>
        <div class="divtable1">
            <?php 
                $tableau = "SELECT COUNT(*) AS NA FROM surveillant";
                $tablexecute = oci_parse($conn,$tableau);
                oci_execute($tablexecute);

                $nbrefiliere = oci_fetch_assoc($tablexecute);

                if ($nbrefiliere["NA"] == 0) {
                    echo '<div class="ras">
                                <i class="fa-solid fa-address-book"></i>  
                                <p>Aucune surveillant a ete ajouter veuiilez le faire ulterieurement.</p>
                            </div>';
                }else {
                    $sql = "SELECT * FROM surveillant";
                    $stmt = oci_parse($conn, $sql);
                    oci_execute($stmt);
                    echo '
                            <table id="table1">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prenom</th>
                                    <th>Matricule</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                    ';
                    while ($ligne = oci_fetch_assoc($stmt)) {
                        echo '
                            <tr class="information">
                                <td>'. $ligne["NOMSURVEILLANT"] .'</td>
                                <td>'. $ligne["PRENOMSURVEILLANT"] .'</td>
                                <td>'. $ligne["MATRICULESURVEILLANT"] .'</td>
                                <td>'. $ligne["CONTACTSURVEILLANT"] .'</td>
                                <td>'. $ligne["STATUE"] .'</td>
                                <td> 
                                    <button id="info"><i class="fa-solid fa-info"></i></button>
                                    <button id="edite"><i class="fa-solid fa-pen"></i></button>
                                    <button id="supprime" class="bnt2" data-nom="'.$ligne["NOMSURVEILLANT"].'" data-prenom="'.$ligne["PRENOMSURVEILLANT"].'"><i class="fa-solid fa-trash"></i></button>
                                </td>';
                    };

                    echo '</table>';
                }
            ?>
        </div>
    </div>

    <div id="etudiant">
        <p id="classfiliere"><span><i class="fa-solid fa-graduation-cap"></i></span> Etudiants</p>
        <button type="button" id="ajouterfiliere" class="ajouteretudiant"><i class="fa-solid fa-plus"></i> Ajouter une etudiant</button>
        <div class="divtable1">
             <?php 
                $tableau = "SELECT COUNT(*) AS NA FROM etudiant";
                $tablexecute = oci_parse($conn,$tableau);
                oci_execute($tablexecute);

                $nbrefiliere = oci_fetch_assoc($tablexecute);

                if ($nbrefiliere["NA"] == 0) {
                    echo '<div class="ras">
                                <i class="fa-solid fa-graduation-cap"></i>  
                                <p>Aucun etudiant a ete ajouter veuiilez le faire ulterieurement.</p>
                            </div>';
                }else {
                    $sql = "SELECT * FROM etudiant";
                    $stmt = oci_parse($conn, $sql);
                    oci_execute($stmt);
                    echo '
                            <table id="table1">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prenom</th>
                                    <th>Matricule</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                    ';
                    while ($ligne = oci_fetch_assoc($stmt)) {
                        echo '
                            <tr class="information">
                                <td>'. $ligne["NOMETUDIANT"] .'</td>
                                <td>'. $ligne["PRENOMETUDIANT"] .'</td>
                                <td>'. $ligne["MATRICULE"] .'</td>
                                <td>'. $ligne["CONTACT"] .'</td>
                                <td>'. $ligne["STATUE"] .'</td>
                                <td> 
                                    <button id="info"><i class="fa-solid fa-info"></i></button>
                                    <button id="edite"><i class="fa-solid fa-pen"></i></button>
                                    <button id="supprime" class="bnt3" data-nom="'.$ligne["NOMETUDIANT"].'" data-prenom="'.$ligne["PRENOMETUDIANT"].'"><i class="fa-solid fa-trash"></i></button>
                                </td>';
                    };

                    echo '</table>';
                }
            ?>
        </div>
    </div>


    <div id="generer">
        <p id="classfiliere"><span><i class="fa-brands fa-hubspot"></i></span> Generer</p>
        <form action="interface.php" method="post" id="genererform1" >
            <label for="">
                <select name="fil" id="fil" placeholder="" required>
                    <?php 
                        $filiere = "SELECT COUNT(*) AS NB FROM filiere";
                        $exe = oci_parse($conn,$filiere);
                        oci_execute($exe);
                        $result = oci_fetch_assoc($exe);

                        if ($result["NB"] == 0) {
                            echo '<option disabled selected>Aucune filiere enregistrer</option>';
                        }else{

                            $fil = "SELECT nomfiliere FROM filiere";
                            $ex = oci_parse($conn,$fil);
                            oci_execute($ex);
                            echo '<option value=""> Choisir la filiere </option>';
                            while ( $res = oci_fetch_assoc($ex)) {
                                echo '<option value = "'.$res["NOMFILIERE"].'">'.$res["NOMFILIERE"].'</option>'; 
                            }
                        }
                    ?>
                </select>
            </label>
            <label for="">
                <select name="ni" id="ni" required>
                    <option value="">Choisir le niveau concernee</option>
                    <option value="Licence 1">Licence 1</option>
                    <option value="Licence 2">Licence 2</option>
                    <option value="Licence 3">Licence 3</option>
                    <option value="Master 1">Master 1</option>
                    <option value="Master 2">Master 2</option>
                    <option value="Classe preparatoire">Classe preparatoire</option>
                </select>
            </label>
            <label for="">
                <select name="sa" id="sa" required>
                    <?php 
                        $filiere = "SELECT COUNT(*) AS NB FROM salle";
                        $exe = oci_parse($conn,$filiere);
                        oci_execute($exe);
                        $result = oci_fetch_assoc($exe);

                        if ($result["NB"] == 0) {
                            echo '<option disabled selected>Aucune salle enregistrer</option>';
                        }else{

                            $fil = "SELECT nomsalle FROM salle";
                            $ex = oci_parse($conn,$fil);
                            oci_execute($ex);
                            echo '<option value=""> Choisir la salle </option>';
                            while ( $res = oci_fetch_assoc($ex)) {

                                $tige = "SELECT COUNT(*) TOL FROM salle WHERE statue = 'Disponible' AND nomsalle = :ts";
                                $tiparse = oci_parse($conn,$tige);
                                oci_bind_by_name($tiparse,":ts",$res["NOMSALLE"]);
                                oci_execute($tiparse);
                                $rad = oci_fetch_assoc($tiparse);
                                if ($rad["TOL"] == 0) {                                                                     
                                    echo '<option value = "'.$res["NOMSALLE"].'" disabled selected>'.$res["NOMSALLE"].'</option>'; 
                                }else {
                                    echo '<option value = "'.$res["NOMSALLE"].'">'.$res["NOMSALLE"].'</option>'; 
                                }
                            }
                        }
                    ?>
                </select>
            </label>
            <label for="">
                <select name="suv" id="suv" required>
                    <?php 
                        $filiere = "SELECT COUNT(*) AS NB FROM surveillant";
                        $exe = oci_parse($conn,$filiere);
                        oci_execute($exe);
                        $result = oci_fetch_assoc($exe);

                        if ($result["NB"] == 0) {
                            echo '<option disabled selected>Aucun surveillant enregistrer</option>';
                        }else{
                            $fil = "SELECT nomsurveillant FROM surveillant";
                            $ex = oci_parse($conn,$fil);
                            oci_execute($ex);
                            echo '<option value=""> Choisir le surveillant </option>';
                            while ( $res = oci_fetch_assoc($ex)) {

                                $tige = "SELECT COUNT(*) TOL FROM surveillant WHERE statue = 'Disponible' AND nomsurveillant = :ts";
                                $tiparse = oci_parse($conn,$tige);
                                oci_bind_by_name($tiparse,":ts",$res["NOMSURVEILLANT"]);
                                oci_execute($tiparse);
                                $rad = oci_fetch_assoc($tiparse);

                                if ($rad["TOL"] == 0) {
                                    echo '<option value = "'.$res["NOMSURVEILLANT"].'" disabled selected>'.$res["NOMSURVEILLANT"].'</option>';
                                }else {
                                    echo '<option value = "'.$res["NOMSURVEILLANT"].'">'.$res["NOMSURVEILLANT"].'</option>';
                                }
                            }
                        }
                    ?>
                </select>
            </label>
             <label for="">
                <input type="number" placeholder="Entrer le nombre de matiere" id="nbre" required>
            </label>
            <button type="button" id="suivan">Suivant</button>

            <div class="div" style="display: none;">
                <div class="matiere">Matiere</div>
                <div class="date">Date</div>
                <div class="heuredebut">Heure debut</div>
                <div class="heurefin">Heure fin</div>
            </div>

            <div class="stricte" style="display: none;">

            </div>
            <button type="submit" class="dif" name="dif" style="display: none;">Enregistrer</button>
        </form>
    </div>

    <div class="formulairefiliere" style="display: none;">
        <form action="interface.php" method="POST" id="formfiliere">
            <p>Filiere</p>
            <div class="ensemble">
                <label for="" class="label">
                    <input type="text" name="filiere" placeholder="Entrer le nom de la filiere " class="input1" required>
                </label>
                <label for="" class="niveau">
                    <select name="niveau" id="" class="input2" required>
                        <option value="">Choisir le niveau de la filiere</option>
                        <option value="Licence 1">Licence 1</option>
                        <option value="Licence 2">Licence 2</option>
                        <option value="Licence 3">Licence 3</option>
                        <option value="Master 1">Master 1</option>
                        <option value="Master 2">Master 2</option>
                        <option value="Classe preparatoire">Classe preparatoire</option>
                    </select>
                </label>
                <!--
                <label for="" class="nombre">
                    <input type="number" name="nombre" placeholder="Entrer le nombre d'etudiant de cette classe" class="input3">
                </label>
                -->
            </div>
            <button id="enregistre" type="submit" name="enregistre">Enregistrer</button>
            <button id="annuler" type="button">Annuler</button>
        </form>
    </div>
    <div class="sup" style="display: none;">
        <form action="interface.php" method="post">
            <p>Voulez vous vraiment supprimer la filiere</p>
            <input type="text" value="" id="messsupprimer" name="supprimer">
            <input type="text" value="" id="niv" name="niv">
            <button id="supp" name="supp">Supprimer</button>
            <button id="annul">Annuler</button>
        </form>
    </div>
    <div class="sup" id="sup" style="display: none;">
        <form action="interface.php" method="post">
            <p>Voulez vous vraiment supprimer la salle</p>
            <input type="text" value="" id="messsupprimer"  class="messsupprime" name="supprime">
            <button id="supp" name="suppreme">Supprimer</button>
            <button id="annul">Annuler</button>
        </form>
    </div>
    <div class="sup" id="sup1" style="display: none;">
        <form action="interface.php" method="post">
            <p>Voulez vous vraiment supprimer le surveillant</p>
            <input type="text" value="" id="messsupprimer"  class="messsupprimer" name="supprimee">
            <input type="text" value="" id="niv1" name="niv1">
            <button id="supp" name="show">Supprimer</button>
            <button id="annul">Annuler</button>
        </form>
    </div>
    <div class="sup" id="sup2" style="display: none;">
        <form action="interface.php" method="post">
            <p>Voulez vous vraiment supprimer l'etudiant</p>
            <input type="text" value="" id="messsupprimer"  class="mes" name="mes">
            <input type="text" value="" id="niv2" name="niv2">
            <button id="supp" name="show1">Supprimer</button>
            <button id="annul">Annuler</button>
        </form>
    </div>
    <section id="afficher" style="display: block;">
        <p id="classfiliere"><span><i class="fa-solid fa-shapes"></i></span> Afficher</p>
        <div class="generique">
            <?php
            
            $sqlFil = "SELECT * FROM filiere";
            $stmtFil = oci_parse($conn, $sqlFil);
            oci_execute($stmtFil);

            while ($f = oci_fetch_assoc($stmtFil)) {
                $filiere = $f["NOMFILIERE"];
                $niveau = $f["NIVEAU"];
                $salle = $f["SALLE"];
                $surveillant = $f["RESPONSABLE"];

                
                $sqlMat = "SELECT nommatiere,
                                TO_CHAR(datematiere,'YYYY-MM-DD') AS d,
                                TO_CHAR(heuredebut,'HH24:MI') AS hd,
                                TO_CHAR(heurefi,'HH24:MI') AS hf
                        FROM matiere
                        WHERE filierematiere = :fil AND niveau = :niv
                        ORDER BY datematiere, heuredebut";
                $stmtMat = oci_parse($conn, $sqlMat);
                oci_bind_by_name($stmtMat, ":fil", $filiere);
                oci_bind_by_name($stmtMat, ":niv", $niveau);
                oci_execute($stmtMat);

                $planning = [];
                while ($m = oci_fetch_assoc($stmtMat)) {
                    $planning[$m["D"]][] = $m;
                }

                
                if (count($planning) === 0) continue;

                
                $sqlEtud = "SELECT NOMETUDIANT, PRENOMETUDIANT, MATRICULE 
                            FROM etudiant
                            WHERE filiere = :fil AND niveau = :niv AND statue = 'Reglementaire'
                            ORDER BY NOMETUDIANT, PRENOMETUDIANT";
                $stmtEtud = oci_parse($conn, $sqlEtud);
                oci_bind_by_name($stmtEtud, ":fil", $filiere);
                oci_bind_by_name($stmtEtud, ":niv", $niveau);
                oci_execute($stmtEtud);
                $etudiants = [];
                while ($e = oci_fetch_assoc($stmtEtud)) {
                    $etudiants[] = $e;
                }

                
                ?>
                <div class="divemploiexeman">
                    <div class="headexamen">
                        <img src="logo.png" alt="logo">
                        <div class="infoexeman">
                            <p>INSTITUT UNIVERSITAIRE DU GOLFE DE GUINNEE</p>
                            <p>PLANNING DES EXAMENS DE LA FIN DU SEMESTRE</p>
                        </div>
                        <img src="logo.png" alt="logo">
                    </div>

                    <div class="corps">
                        <p>Filiere : <b><?= $filiere ?></b></p>
                        <p>Niveau : <b><?= $niveau ?></b></p>
                        <p>Salle : <b><?= $salle ?></b></p>
                        <p>Surveillant : <b><?= $surveillant ?></b></p>
                    </div>

                    <table class="matieres">
                        <tr>
                            <th>Date</th>
                            <th>Matiere</th>
                            <th>Heure</th>
                            <th>Duree</th>
                        </tr>
                        <?php
                        foreach ($planning as $date => $matieres) {
                            $rowspan = count($matieres);
                            $first = true;
                            foreach ($matieres as $mat) {
                                $debut = strtotime($mat["HD"]);
                                $fin = strtotime($mat["HF"]);
                                $duree = ($fin - $debut)/3600;
                                echo "<tr>";
                                if ($first) {
                                    echo "<td rowspan='$rowspan'>" . date("d/m/Y", strtotime($date)) . "</td>";
                                    $first = false;
                                }
                                echo "<td>{$mat['NOMMATIERE']}</td>";
                                echo "<td>{$mat['HD']} - {$mat['HF']}</td>";
                                echo "<td>{$duree}h</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>

                    <div class="etudiantadmis">
                        <p>Listes des étudiants éligibles pour les examens</p>
                        <table>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Matricule</th>
                            </tr>
                            <?php
                            foreach ($etudiants as $etud) {
                                echo "<tr>";
                                echo "<td>{$etud['NOMETUDIANT']}</td>";
                                echo "<td>{$etud['PRENOMETUDIANT']}</td>";
                                echo "<td>{$etud['MATRICULE']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>

                    <button id="signature">🪶</button>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
    
    <div class="formulsalle" style="display: none;">
        <form action="interface.php" method="POST" id="formsalle">
            <p>Salle</p>
            <div class="ensemble">
                <label for="">
                    <input type="text" placeholder="Entrer le nom de la salle" name="sallenom" required>
                </label>
                <label for="">
                    <input type="number" placeholder="Entrer la capacite de la classe" name="capacite" required>
                </label>
                <label for="">
                    <input type="text" placeholder="Entrer le batiment de la salle" name="batiment">
                </label>
                <label for="">
                    <input type="text" placeholder="Entrer le type de salle" name="type" required>
                </label>
            </div>
            <button id="soumettre" type="submit" name="soumettre">Enregistrer</button>
            <button id="skill" type="button">Annuler</button>
        </form>
    </div>

    <div id="formulairesurveillant" style="display: none;">
        <form action="interface.php" method="post" id="formsurveillant">
            <p>Surveillant</p>
            <div class="ensemble">
                <label for="">
                    <input type="text" placeholder="Entrer le nom du surveillant" name="nomsurveillant" required>
                </label>
                <label for="">
                    <input type="text" placeholder="Entrer le prenom du surveillant" name="prenomsurveillant" required>
                </label>
                <label for="">
                    <input type="text" placeholder="Entrer le matricule du surveillant" name="matriculesurveillant" rerquired>
                </label>
                <label for="">
                    <input type="text" placeholder="Entrer le contact du surveillant" name="contactsurveillant" required>
                </label>
            </div>
            <button id="submit" type="submit" name="submit">Enregistrer</button>
            <button id="fermer" type="button">Annuler</button>
        </form>
    </div>

    <div id="formulaireetudiant" style="display: none;">
        <form action="interface.php" method="post" id="formetudiant">
            <p>Etudiant</p>
            <div class="ensemble">
                <label for="">
                    <input type="text" placeholder="Entrer le nom de l'etudiant" name="names" required>
                </label>
                <label for="">
                    <input type="text" placeholder="Entrer le prenom de l'etudiant" name="surname" required>
                </label>
                <label for="">
                    <input type="text" placeholder="Entrer le matricule de l'etudiant" name="mat" required>
                </label>
                <label for="">
                    <input type="number" placeholder="Entrer le contact de l'etudiant" name="cont" required>
                </label>
                <label for="">
                    <select name="selectfiliere" id="">
                        <?php 
                            $select = "SELECT COUNT(*) AS NB FROM filiere";
                            $exe = oci_parse($conn,$select);
                            oci_execute($exe);
                            $result = oci_fetch_assoc($exe);

                            if ($result["NB"] == 0) {
                                echo '<option disabled selected> Aucune filiere disponible </option>';
                            }else{
                                $sel = "SELECT nomfiliere FROM filiere";
                                $xe = oci_parse($conn,$sel);
                                oci_execute($xe);

                                echo '<option value=""> Choisir la filiere...';
                                while ($res = oci_fetch_assoc($xe)) {
                                    echo '<option value="'.$res["NOMFILIERE"].'">'.$res["NOMFILIERE"].'</option>';
                                }
                            }
                        ?>
                    </select>
                </label>
                <label for="">
                    <select name="selectniveau" id="">
                        <option value="">Choisir le niveau de la filiere</option>
                        <option value="Licence 1">Licence 1</option>
                        <option value="Licence 2">Licence 2</option>
                        <option value="Licence 3">Licence 3</option>
                        <option value="Master 1">Master 1</option>
                        <option value="Master 2">Master 2</option>
                        <option value="Classe preparatoire">Classe preparatoire</option>
                    </select>
                </label>
                <label for="">
                    <select name="selectstatus" id="">
                        <option value="">Choir le status de l'etudiant</option>
                        <option value="Reglementaire">Reglementaire</option>
                        <option value="Non reglementaire">Non reglementaire</option>
                    </select>
                </label>
            </div>
            <button id="envoie" type="envoie" name="envoie">Enregistrer</button>
            <button id="close" type="button">Annuler</button>
        </form>
    </div>
</body>
<script src="interface.js"></script>
</html>