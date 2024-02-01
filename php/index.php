<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=$, initial-scale=1.0">
    <title>AFCI</title>
    <style>
        .navbar ul{display: flex;
            height: 5vh;
            width: 100vw;
            text-decoration: none;
            background-color: purple;
            list-style: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            justify-content: flex-end;
            
        }

        li {
            float: left;
            margin-left: 4vh;
            color: white;
        }
       
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <ul>
           <a href="?page=roles"> <li>Rôles</li></a>
           <a href="?page=centres"> <li>Centres</li></a>
           <a href="?page=formations">  <li>Formations</li></a>
           <a href="?page=pedagogie">  <li>Pédagogie</li></a>
           <a href="?page=sessions">  <li>Sessions</li></a>
           <a href="?page=apprenants">  <li>Apprenants</li></a>
        </ul>
    </nav>
</header>

<?php
$host = "mysql"; // Nom du service du conteneur MySQL dans Docker

$port = "3306"; // Le port exposé par le conteneur MySQL dans Docker
$dbname = "afci"; // Remplacez par le nom de votre base de données
$user = "admin"; // Remplacez par votre nom d'utilisateur
$pass = "admin"; // Remplacez par votre mot de passe


    // Création d'une nouvelle instance de la classe PDO
    $bdd = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);

    // Configuration des options PDO
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // echo "Connexion réussie !";

    // Lire des données dans la BDD

    // $sql = "SELECT * FROM apprenants";
    // $requete = $bdd->query($sql);
    // $results = $requete->fetchAll(PDO::FETCH_ASSOC);
    

    // foreach( $results as $value ){
    //     foreach($value as $data){
    //         echo $data;
    //         echo "<br>";

    //     }
    //     echo "<br>";
    // }

    // foreach( $results as $value ){
    //     echo "<h2>" . $value["nom_apprenant"] . "</h2>";
    //     echo "<br>";
    // }


    // Insérer des données dans la BDD

    // PAGE ROLES


    if(isset($_GET["page"])&& $_GET["page"]=="roles"){
        $sqlrole = "SELECT * FROM role";
        $requeterole = $bdd->query($sqlrole);
        $resultatsrole = $requeterole->fetchAll(PDO::FETCH_ASSOC);

        ?>
        <br>
        <form method="POST">
        <label>Rôle</label>
        <input type="text" name="nomRole">
        <input type="submit" name="submitRole">
    
    </form>
    <br>
    <table border ="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom du Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultatsrole as $value) {
                            echo '<tr>';
                            echo '<td>' . $value['id_role'] . '</td>';
                            echo '<td>' . $value['nom_role'] . '</td>';
                            echo '<td><a href="?page=roles&action=edit&id=' . $value['id_role'] . '">Modifier</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <?php
if(isset($_GET["page"]) && $_GET["page"] == "roles") {
    if(isset($_GET["action"]) && $_GET["action"] == "edit") {
        // Récupérer l'ID du rôle à modifier
        $idRoleToEdit = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

        // Récupérer les informations du rôle à modifier depuis la base de données
        $sqlEditRole = "SELECT * FROM role WHERE id_role = :idRole";
        $stmtEditRole = $bdd->prepare($sqlEditRole);
        $stmtEditRole->bindParam(':idRole', $idRoleToEdit, PDO::PARAM_INT);
        $stmtEditRole->execute();
        $roleToEdit = $stmtEditRole->fetch(PDO::FETCH_ASSOC);

        // Afficher le formulaire de modification
        if ($roleToEdit) {
            ?>
            <form method="POST">
                <input type="hidden" name="idRoleToEdit" value="<?php echo $roleToEdit['id_role']; ?>">
                <label>Nouveau nom du rôle</label>
                <input type="text" name="newNomRole" value="<?php echo $roleToEdit['nom_role']; ?>">
                <input type="submit" name="submitEditRole" value="Enregistrer">
            </form>
            <?php
        }
    }
}
?>

    <?php 
    }
  
if(isset($_POST['submitEditRole'])) {
    $idRoleToEdit = $_POST['idRoleToEdit'];
    $newNomRole = $_POST['newNomRole'];

    // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

    $sqlUpdateRole = "UPDATE role SET nom_role = :newNomRole WHERE id_role = :idRoleToEdit";
    $stmtUpdateRole = $bdd->prepare($sqlUpdateRole);
    $stmtUpdateRole->bindParam(':idRoleToEdit', $idRoleToEdit, PDO::PARAM_INT);
    $stmtUpdateRole->bindParam(':newNomRole', $newNomRole, PDO::PARAM_STR);
    $stmtUpdateRole->execute();

    echo "Rôle mis à jour avec succès.";
}
?>


?>

<?php 
    if (isset($_POST['submitRole'])){
        $nomRole = $_POST['nomRole'];

        $sql = "INSERT INTO `role`(`nom_role`) VALUES ('$nomRole')";
        $bdd->query($sql);

        echo "data ajoutée dans la bdd";

    }

?>



<?php 
// PAGE CENTRES
    if(isset($_GET["page"])&& $_GET["page"]=="centres"){
        $sqlcentre = "SELECT * FROM centres";
        $requetecentre = $bdd->query($sqlcentre);
        $resultatscentre = $requetecentre->fetchAll(PDO::FETCH_ASSOC);
        ?> 

        <br>
        <form method="POST">
         <h1>Ajout Centre</h1>
        <label>Ville</label>
        <input type="text" name="villeCentre" >
        <label>Adresse</label>
        <input type="text" name="adresseCentre">
        <label>Code Postal</label>
        <input type="text" name="cpCentre">
        <input type="submit" name="submitCentre">
    </form>
    <br>
    <table border ="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ville</th>
                            <th>Adresse</th>
                            <th>Code Postal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultatscentre as $value) {
                            echo '<tr>';
                            echo '<td>' . $value['id_centre'] . '</td>';
                            echo '<td>' . $value['ville_centre'] . '</td>';
                            echo '<td>' . $value['adresse_centre'] . '</td>';
                            echo '<td>' . $value['code_postal_centre'] . '</td>';
                            echo '<td><a href="?page=centres&action=edit&id=' . $value['id_centre'] . '">Modifier</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

    <?php 
        }
    ?>
<?php 
    if(isset($_POST['submitCentre'])) {
        $villeCentre = $_POST['villeCentre'];
        $adresseCentre = $_POST['adresseCentre'];
        $cpCentre = $_POST['cpCentre'];

        // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

        $sqlcentre = "INSERT INTO `centres`(`ville_centre`, `adresse_centre`, `code_postal_centre`) VALUES ('$villeCentre','$adresseCentre','$cpCentre')";
        $bdd->query($sqlcentre);

        echo "Données ajoutées dans la base de données";
    }
    
?>
<?php
if(isset($_GET["page"]) && $_GET["page"] == "centres") {
    if(isset($_GET["action"]) && $_GET["action"] == "edit") {
        // Récupérer l'ID du rôle à modifier
        $idCentreToEdit = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

        // Récupérer les informations du rôle à modifier depuis la base de données
        $sqlEditCentre = "SELECT * FROM centres WHERE id_centre = :idCentre";
        $stmtEditCentre= $bdd->prepare($sqlEditCentre);
        $stmtEditCentre->bindParam(':idCentre', $idCentreToEdit, PDO::PARAM_INT);
        $stmtEditCentre->execute();
        $centreToEdit = $stmtEditCentre->fetch(PDO::FETCH_ASSOC);

        // Afficher le formulaire de modification
        if ($centreToEdit) {
            ?>
            <form method="POST">
                <input type="hidden" name="idCentreToEdit" value="<?php echo $centreToEdit['id_centre']; ?>">
                <label>Nouveau nom du Centre</label>
                <input type="text" name="newNomCentre" value="<?php echo $centreToEdit['ville_centre']; ?>">
                <input type="text" name="newAdresseCentre" value="<?php echo $centreToEdit['adresse_centre']; ?>">
                <input type="text" name="newCpCentre" value="<?php echo$centreToEdit['code_postal_centre']; ?>">
                <input type="submit" name="submitEditCentre" value="Enregistrer">
            </form>
            <?php
        }
    }
}
?>
<?php
if(isset($_POST['submitEditCentre'])) {
    $idCentreToEdit = $_POST['idCentreToEdit'];
    $newNomCentre = $_POST['newNomCentre'];
    $newAdresseCentre = $_POST['newAdresseCentre'];
    $newCpCentre = $_POST['newCpCentre'];

    // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

    $sqlUpdateCentre= "UPDATE centres SET ville_centre = :newNomCentre WHERE id_centre = :idCentreToEdit";
    $stmtUpdateCentre = $bdd->prepare($sqlUpdateRole);
    $stmtUpdateCentre->bindParam(':idCentreToEdit', $idCentreToEdit, PDO::PARAM_INT);
    $stmtUpdateCentre->bindParam(':newNomCentre', $newNomCentre, PDO::PARAM_STR);
    $stmtUpdateCentre->bindParam(':newAdresseCentre', $newAdresseCentre, PDO::PARAM_STR);
    $stmtUpdateCentre->bindParam(':newCpCentre',$newCpCentre, PDO::PARAM_STR);
    $stmtUpdateCentre->execute();

    echo "Centre mis à jour avec succès.";
}
?>



<?php 
// PAGE FORMATIONS
    if(isset($_GET["page"])&& $_GET["page"]=="formations"){
        $sqlformation= "SELECT * FROM formations";
        $requeteformation = $bdd->query($sqlformation);
        $resultatsformation = $requeteformation->fetchAll(PDO::FETCH_ASSOC);
        ?> 


        <form method="POST">
        <h1>Ajout Formation</h1>
        <label>Nom</label>
        <input type="text" name="nomFormation">
        <label>Durée</label>
        <input type="text" name="dureeFormation">
        <label>Niveau sortie Formation</label>
        <input type="text" name="niveauFormation">
        <label>Description</label>
        <input type="text" name="descriptionFormation">
        <input type="submit" name="submitFormation">
    
    </form>
    <br>
    <table border ="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Durée</th>
                            <th>Niveau Sortie formation</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultatsformation as $value) {
                            echo '<tr>';
                            echo '<td>' . $value['id_formation'] . '</td>';
                            echo '<td>' . $value['nom_formation'] . '</td>';
                            echo '<td>' . $value['duree_formation'] . '</td>';
                            echo '<td>' . $value['niveau_sortie_formation'] . '</td>';
                            echo '<td>' . $value['description'] . '</td>';
                            echo '<td><a href="?page=formations&action=edit&id=' . $value['id_formation'] . '">Modifier</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
    <?php 
    }
    if(isset($_POST['submitFormation'])) {
        $nomFormation = $_POST['nomFormation'];
        $dureeFormation = $_POST['dureeFormation'];
        $niveauFormation = $_POST['niveauFormation'];
        $descriptionFormation = $_POST['descriptionFormation'];

        // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

        $sql = "INSERT INTO `formations`(`nom_formation`, `duree_formation`, `niveau_sortie_formation`, `description`) VALUES ('$nomFormation','$dureeFormation','$niveauFormation','$descriptionFormation')";
        $bdd->query($sql);

        echo "Données ajoutées dans la base de données";
    }
    
    
?>
                <?php
if(isset($_GET["page"]) && $_GET["page"] == "formations") {
    if(isset($_GET["action"]) && $_GET["action"] == "edit") {
        // Récupérer l'ID du rôle à modifier
        $idFormationToEdit = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

        // Récupérer les informations du rôle à modifier depuis la base de données
        $sqlEditFormation = "SELECT * FROM formations WHERE id_Formation = :idFormation";
        $sqlEditFormation= $bdd->prepare($sqlEditFormation);
        $sqlEditFormation->bindParam(':idFormation', $idFormationToEdit, PDO::PARAM_INT);
        $sqlEditFormation->execute();
        $formationToEdit = $sqlEditFormation->fetch(PDO::FETCH_ASSOC);

        // Afficher le formulaire de modification
        if ($formationToEdit) {
            ?>
            <form method="POST">
                <input type="hidden" name="idFormationToEdit" value="<?php echo $formationToEdit['id_formation']; ?>">
                <label>Nouveau nom de la Formation</label>
                <input type="text" name="newNomFormation" value="<?php echo $formationToEdit['nom_formation']; ?>">
                <input type="text" name="newDureeFormation" value="<?php echo $formationToEdit['duree_formation']; ?>">
                <input type="text" name="newNiveauFormation" value="<?php echo $formationToEdit['niveau_sortie_formation']; ?>">
                <input type="text" name="newDescriptionFormation" value="<?php echo $formationToEdit['description']; ?>">
                <input type="submit" name="submitEditFormation" value="Enregistrer">
            </form>
            <?php
        }
    }
}
?>

    <?php 
  
  
if(isset($_POST['submitEditFormation'])) {
    $idFormationToEdit = $_POST['idFormationToEdit'];
    $newNomFormation = $_POST['newNomFormation'];
    $newDureeFormation = $_POST['newDureeFormation'];
    $newNiveauFormation = $_POST['newNiveauFormation'];
    $newDescriptionFormation = $_POST['newDescriptionFormation'];

    // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

    $sqlUpdateFormation= "UPDATE formations SET nom_formation = :newNomFormation WHERE id_formation = :idFormationToEdit";
    $sqlUpdateFormation = $bdd->prepare($sqlUpdateFormation);
    $sqlUpdateFormation->bindParam(':idCentreToEdit', $idFormationToEdit, PDO::PARAM_INT);
    $sqlUpdateFormation->bindParam(':newNomFormation', $newNomFormation, PDO::PARAM_STR);
    $sqlUpdateFormation->bindParam(':newDureeFormation', $newDureeFormation, PDO::PARAM_STR);
    $sqlUpdateFormation->bindParam(':newNiveauFormation', $newNiveauFormation, PDO::PARAM_STR);
    $sqlUpdateFormation->bindParam(':newDescriptionFormation', $newDescriptionFormation, PDO::PARAM_STR);
    $sqlUpdateFormation->execute();

    echo "Formation mis à jour avec succès.";
}
?>




<?php 
// PAGE PEDAGOGIE
    if(isset($_GET["page"])&& $_GET["page"]=="pedagogie"){
        $sqlpedagogie = "SELECT * FROM pedagogie";
        $requetepedagogie = $bdd->query($sqlpedagogie);
        $resultspedagogie = $requetepedagogie->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT * FROM role";
        $requete = $bdd->query($sql);
        $results = $requete->fetchAll(PDO::FETCH_ASSOC);

        ?> <form method="POST">
        <h1>Ajout Pédagogie</h1>
        <label>Nom</label>
        <input type="text" name="nomPedagogie">
        <label>Prénom</label>
        <input type="text" name="prenomPedagogie">
        <label>Mail</label>
        <input type="text" name="mailPedagogie">
        <label>Numéro</label>
        <input type="text" name="numPedagogie">
        <label>Rôle</label>
        <select name="idPedagogie" id="">
                <!-- <option value="idrole">id - nom role</option> -->
                <?php 
                
                foreach( $results as $value ){             
                        echo '<option value="' . $value['id_role'] .  '">' . $value['id_role'] . ' - ' . $value['nom_role'] . '</option>';   
                }
                ?>
            </select>
        <input type="submit" name="submitPeda">
    
    </form>
    <br>
    <table border ="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Mail</th>
                            <th>Numéro</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultspedagogie as $value) {
                            echo '<tr>';
                            echo '<td>' . $value['id_pedagogie'] . '</td>';
                            echo '<td>' . $value['nom_pedagogie'] . '</td>';
                            echo '<td>' . $value['prenom_pedagogie'] . '</td>';
                            echo '<td>' . $value['mail_pedagogie'] . '</td>';
                            echo '<td>' . $value['num_pedagogie'] . '</td>';
                            echo '<td>' . $value['id_role'] . '</td>';
                            echo '<td><a href="?page=pedagogie&action=edit&id=' . $value['id_pedagogie'] . '">Modifier</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
    <?php 
    }
?>
<?php 
    if(isset($_POST['submitPeda'])) {
        $nomPedagogie = $_POST['nomPedagogie'];
        $prenomPedagogie = $_POST['prenomPedagogie'];
        $mailPedagogie = $_POST['mailPedagogie'];
        $numPedagogie = $_POST['numPedagogie'];
        $idPedagogie = $_POST['idPedagogie'];

        // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

        $sql = "INSERT INTO `pedagogie`( `nom_pedagogie`, `prenom_pedagogie`, `mail_pedagogie`, `num_pedagogie`, `id_role`) VALUES ('$nomPedagogie','$prenomPedagogie','$mailPedagogie','$numPedagogie','$idPedagogie')";
        $bdd->query($sql);

        echo "Données ajoutées dans la base de données";
    }

?>
 <?php
if(isset($_GET["page"]) && $_GET["page"] == "pedagogie") {
    if(isset($_GET["action"]) && $_GET["action"] == "edit") {
        // Récupérer l'ID du rôle à modifier
        $idPedagogieToEdit = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

        // Récupérer les informations du rôle à modifier depuis la base de données
        $sqlEditPedagogie = "SELECT * FROM pedagogie WHERE id_Pedagogie = :idPedagogie";
        $sqlEditPedagogie= $bdd->prepare($sqlEditPedagogie);
        $sqlEditPedagogie->bindParam(':idPedagogie', $idPedagogieToEdit, PDO::PARAM_INT);
        $sqlEditPedagogie->execute();
        $pedagogieToEdit = $sqlEditPedagogie->fetch(PDO::FETCH_ASSOC);

        // Afficher le formulaire de modification
        if ($pedagogieToEdit) {
            ?>
            <form method="POST">
                <input type="hidden" name="idPedagogieToEdit" value="<?php echo $formationToEdit['id_formation']; ?>">
                <label>Nouveau</label>
                <input type="text" name="newNomPedagogie" value="<?php echo $pedagogieToEdit['nom_pedagogie']; ?>">
                <input type="text" name="newPrenomPedagogie" value="<?php echo $pedagogieToEdit['prenom_pedagogie']; ?>">
                <input type="text" name="newMailPedagogie" value="<?php echo $pedagogieToEdit['mail_pedagogie']; ?>">
                <input type="text" name="newNumPedagogie" value="<?php echo $pedagogieToEdit['num_pedagogie']; ?>">
                <input type="submit" name="submitEditPedagogie" value="Enregistrer">
            </form>
            <?php
        }
    }
}
?>

    <?php 

  
if(isset($_POST['submitEditPedagogie'])) {
    $idFormationToEdit = $_POST['idPedagogienToEdit'];
    $newNomPedagogie = $_POST['newNomPedagogie'];
    $newPrenomPedagogie = $_POST['newPrenomPedagogie'];
    $newMailPedagogie = $_POST['newMailPedagogie'];
    $newNumPedagogie = $_POST['newNumPedagogie'];

    // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

    $sqlUpdatePedagogie= "UPDATE pedagogie SET nom_pedagogie = :newNomPedagogie WHERE id_pedagogie = :idPedagogieToEdit";
    $sqlUpdatPedagogie = $bdd->prepare($sqlUpdatePedagogie);
    $sqlUpdatePedagogie->bindParam(':idPedagogieToEdit', $idPedagogieToEdit, PDO::PARAM_INT);
    $sqlUpdatePedagogie->bindParam(':newNomPedagogie', $newNomPedagogie, PDO::PARAM_STR);
    $sqlUpdatePedagogie->bindParam(':newPrenomPedagogie', $newPrenomPedagogie, PDO::PARAM_STR);
    $sqlUpdatePedagogie->bindParam(':newMailPedagogie', $newMailPedagogie, PDO::PARAM_STR);
    $sqlUpdatePedagogie->bindParam(':newNumPedagogie', $newNumPedagogie, PDO::PARAM_STR);
    $sqlUpdatePedagogie->execute();

    echo "Pédagogie mis à jour avec succès.";
}
?>


<?php 
// PAGE SESSIONS
    if(isset($_GET["page"])&& $_GET["page"]=="sessions"){
        $sqlsession = "SELECT * FROM session";
        $requetesession = $bdd->query($sqlsession);
        $resultssession = $requetesession->fetchAll(PDO::FETCH_ASSOC);

        $sqlpedagogie = "SELECT * FROM pedagogie";
        $requetepegagogie = $bdd->query($sqlpedagogie); 
        $resultspedagogie = $requetepegagogie->fetchAll(PDO::FETCH_ASSOC); 
       
        $sqlformation = "SELECT * FROM formations";
        $requeteformation = $bdd->query($sqlformation);
        $resultsformation = $requeteformation->fetchAll(PDO::FETCH_ASSOC);

        $sqlcentre = "SELECT * FROM centres";
        $requetecentre = $bdd->query($sqlcentre);
        $resultscentre = $requetecentre->fetchAll(PDO::FETCH_ASSOC);

        ?> <form method="POST">
        <h1> Ajout sessions</h1>
        <label>Nom Session</label>
        <input type="text" name="nomSession" >
        <label>Date Début</label>
        <input type="text" name="dateDebut" >
        <label>Pédagogie</label>
        
        <select name="idPedagogie" id="">
                <!-- <option value="idrole">id - nom role</option> -->
                <?php 
                
                foreach( $resultspedagogie as $value ){             
                        echo '<option value="' . $value['id_pedagogie'] .  '">' . $value['id_pedagogie'] . ' - ' . $value['nom_pedagogie'] .  $value['prenom_pedagogie']. '</option>';   
                }
                ?>
            </select>
        <label>Formation</label>
   
        <select name="idFormation" id="">
                <!-- <option value="idrole">id - nom role</option> -->
                <?php 
                
                foreach( $resultsformation as $value ){             
                        echo '<option value="' . $value['id_formation'] .  '">' . $value['id_formation'] . ' - ' . $value['nom_formation'] . '</option>';   
                }
                ?>
            </select>

            <label>Centre</label>
   
        <select name="idCentre" id="">
                <!-- <option value="idrole">id - nom role</option> -->
                <?php 
                
                foreach( $resultscentre as $value ){             
                        echo '<option value="' . $value['id_centre'] .  '">' . $value['id_centre'] . ' - ' . $value['nom_centre'] . '</option>';   
                }
                ?>
            </select>
        <input type="submit" name="submitSession">
    
    </form>

    <br>
    <table border ="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Date Début</th>
                            <th>Pédagogie</th>
                            <th>Formation</th>
                            <th>Centre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultssession as $value) {
                            echo '<tr>';
                            echo '<td>' . $value['id_session'] . '</td>';
                            echo '<td>' . $value['nom_session'] . '</td>';
                            echo '<td>' . $value['date_debut'] . '</td>';
                            echo '<td>' . $value['id_pedagogie'] . '</td>';
                            echo '<td>' . $value['id_formation'] . '</td>';
                            echo '<td>' . $value['id_centre'] . '</td>';
                            echo '<td><a href="?page=session&action=edit&id=' . $value['id_role'] . '">Modifier</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

    <?php 
    }
?>

<?php 
    if (isset($_POST['submitSession'])){
        $dateDebut = $_POST['dateDebut'];
        $nomSession = $_POST['nomSession'];
        $idPedagogie = $_POST['idPedagogie'];
        $idFormation = $_POST['idFormation'];
        $idCentre = $_POST['idCentre'];

        $sql= "INSERT INTO `session`( `nom_session`, `date_debut`, `id_pedagogie`, `id_formation`, `id_centre`) VALUES ('$nomSession','$dateDebut','$idPedagogie','$idFormation','$idCentre')";
        $bdd->query($sql);

        echo "data ajoutée dans la bdd";

    }

?>

<?php
if(isset($_GET["page"]) && $_GET["page"] == "session") {
    if(isset($_GET["action"]) && $_GET["action"] == "edit") {
        // Récupérer l'ID du rôle à modifier
        $idSessionToEdit = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

        // Récupérer les informations du rôle à modifier depuis la base de données
        $sqlEditSession = "SELECT * FROM session WHERE id_Session = :idSession";
        $sqlEditSession= $bdd->prepare($sqlEditSession);
        $sqlEditSession->bindParam(':idPedagogie', $idSessionToEdit, PDO::PARAM_INT);
        $sqlEditSession->execute();
        $sessionToEdit = $stmtEditSession->fetch(PDO::FETCH_ASSOC);

        // Afficher le formulaire de modification
        if ($sessionToEdit) {
            ?>
            <form method="POST">
                <input type="hidden" name="idPedagogieToEdit" value="<?php echo $formationToEdit['id_session']; ?>">
                <label>Nouveau</label>
                <input type="text" name="newNomSession" value="<?php echo $pedagogieToEdit['nom_session']; ?>">
                <input type="text" name="newDatDebut" value="<?php echo $pedagogieToEdit['date_debut']; ?>">
               
                <input type="submit" name="submitEditSession" value="Enregistrer">
            </form>
            <?php
        }
    }
}
?>

    <?php 

  
if(isset($_POST['submitEditSession'])) {
    $idSessionToEdit = $_POST['idSessionToEdit'];
    $newNomPSession = $_POST['newNomSession'];
    $newDateDebut = $_POST['newDateDebut'];
  

    // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

    $sqlUpdateSession= "UPDATE session SET nom_session = :newNomSession WHERE id_session = :idSessionToEdit";
    $sqlUpdatSession = $bdd->prepare($sqlUpdateSession);
    $sqlUpdateSession->bindParam(':idSessionToEdit', $idSessionToEdit, PDO::PARAM_INT);
    $sqlUpdateSession->bindParam(':newNomSession', $newNomSession, PDO::PARAM_STR);
    $sqlUpdateSession->bindParam(':newDateDebut', $newDateDebut, PDO::PARAM_STR);
    $sqlUpdateSession->execute();

    echo "Pédagogie mis à jour avec succès.";
}
?>

<!-- PAGE APPRENANTS -->
<?php 
if(isset($_GET["page"])&& $_GET["page"]=="apprenants"){

    $sqlapprenants = "SELECT * FROM apprenants";
    $requeteapprenants = $bdd->query($sqlapprenants);
    $resultsapprenants = $requeteapprenants->fetchAll(PDO::FETCH_ASSOC);


    $sqlrole = "SELECT * FROM role";
    $requeterole = $bdd->query($sqlrole);
    $resultsrole = $requeterole->fetchAll(PDO::FETCH_ASSOC);

    $sqlsession = "SELECT * FROM session";
    $requetesession = $bdd->query($sqlsession);
    $resultssession = $requetesession->fetchAll(PDO::FETCH_ASSOC);
    ?><form method="POST">
        <h1>Ajout Apprenant</h1>
        <label>Nom</label>
        <input type="text" name="nomApprenant">
        <label>Prénom</label>
        <input type="text" name="prenomApprenant">
        <label>Mail</label>
        <input type="text" name="mailApprenant">
        <label>Adresse</label>
        <input type="text" name="adresseApprenant">
        <label>Ville</label>
        <input type="text" name="villeApprenant">
        <label>Code Postal</label>
        <input type="text" name="cpApprenant">
        <label>Numéro</label>
        <input type="text" name="numApprenant">
        <label>Date de Naissance</label>
        <input type="text" name="naissanceApprenant" placeholder="YYYY-MM-DD">
        <label>Niveau</label>
        <input type="text" name="niveauApprenant">
        <label>Pôle emploie</label>
        <input type="text" name="peApprenant">
        <label>Sécu</label>
        <input type="text" name="secuApprenant">
        <label>RIB</label>
        <input type="text" name="ribApprenant">
        <label>Rôle</label>
        <select name="idRole" id="">
                <!-- <option value="idrole">id - nom role</option> -->
                <?php 
                
                foreach( $resultsrole as $value ){             
                        echo '<option value="' . $value['id_role'] .  '">' . $value['id_role'] . ' - ' . $value['nom_role'] . '</option>';   
                }
                ?>
            </select>
        <label>Session</label>
      
        <select name="idSession" id="">
                <!-- <option value="idrole">id - nom role</option> -->
                <?php 
                
                foreach( $resultssession as $value ){             
                        echo '<option value="' . $value['id_session'] .  '">' . $value['id_session'] . ' - ' . $value['nom_session'] . '</option>';   
                }
                ?>
            </select>
        <input type="submit" name="submitApprenant">
    
    </form>
    <br>
    <table border ="1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Mail</th>
                            <th>Adresse</th>
                            <th>Ville</th>
                            <th>Code Postal</th>
                            <th>Numéro</th>
                            <th>Date de naissance</th>
                            <th>Niveau</th>
                            <th>Pôle Emploie</th>
                            <th>Sécu</th>
                            <th>RIB</th>
                            <th>Rôle</th>
                            <th>Session</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($resultsapprenants as $value) {
                            echo '<tr>';
                            echo '<td>' . $value['id_apprenant'] . '</td>';
                            echo '<td>' . $value['nom_apprenant'] . '</td>';
                            echo '<td>' . $value['prenom_apprenant'] . '</td>';
                            echo '<td>' . $value['mail_apprenant'] . '</td>';
                            echo '<td>' . $value['adresse_apprenant'] . '</td>';
                            echo '<td>' . $value['ville_apprenant'] . '</td>';
                            echo '<td>' . $value['code_postal_apprenant'] . '</td>';
                            echo '<td>' . $value['tel_apprenant'] . '</td>';
                            echo '<td>' . $value['date_naissance_apprenant'] . '</td>';
                            echo '<td>' . $value['niveau_apprenant'] . '</td>';
                            echo '<td>' . $value['num_PE_apprenant'] . '</td>';
                            echo '<td>' . $value['num_secu_apprenant'] . '</td>';
                            echo '<td>' . $value['rib_apprenant'] . '</td>';
                            echo '<td>' . $value['id_role'] . '</td>';
                            echo '<td>' . $value['id_session'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
    <?php 
    }
?>
<?php 
    if(isset($_POST['submitApprenant'])) {
        $nomApprenant = $_POST['nomApprenant'];
        $prenomApprenant = $_POST['prenomApprenant'];
        $mailApprenant = $_POST['mailApprenant'];
        $adresseApprenant = $_POST['adresseApprenant'];
        $villeApprenant = $_POST['villeApprenant'];
        $cpApprenant = $_POST['cpApprenant'];
        $numApprenant = $_POST['numApprenant'];
        $naissanceApprenant = $_POST['naissanceApprenant'];
        $niveauApprenant = $_POST['niveauApprenant'];
        $peApprenant = $_POST['peApprenant'];
        $secuApprenant= $_POST['secuApprenant'];
        $ribApprenant = $_POST['ribApprenant'];
        $idRole = $_POST['idRole'];
        $idSession =$_POST['idSession'];

        // Assurez-vous de sécuriser votre code contre les attaques par injection SQL

        $sql = "INSERT INTO `apprenants`(`nom_apprenant`, `prenom_apprenant`,
         `mail_apprenant`, `adresse_apprenant`, `ville_apprenant`, `code_postal_apprenant`,
          `tel_apprenant`, `date_naissance_apprenant`, `niveau_apprenant`, 
          `num_PE_apprenant`, `num_secu_apprenant`, `rib_apprenant`, `id_role`, `id_session`)
           VALUES ('$nomApprenant','$prenomApprenant','$mailApprenant','$adresseApprenant','$villeApprenant','$cpApprenant','$numApprenant','$naissanceApprenant','$niveauApprenant','$peApprenant','$secuApprenant','$ribApprenant','$idRole','$idSession')";
         

        $bdd->query($sql);

        echo "Données ajoutées dans la base de données";  
    }
   
?>

</body>
</html>



