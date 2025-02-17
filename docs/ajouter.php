<!DOCTYPE html>
<?php session_start ();?>
<html lang="en-US">
	<HEAD>
        <style>
            .titre {
			font: 200% sans-serif; 
			text-shadow:#ff006e 2.5px 2.5px 0, #8338ec 5px 5px 0, #3a86ff 7.5px 7.5px 0,#333 10px 10px 10px;color:#ffcc00;
 			background-color:#D8BFD8;
				}
                .alert {
			padding: 20px;
			background-color: #D8BFD8; 
			color: #8A2BE2;
			margin-bottom: 15px;
				}

                .center {
             margin: auto;                                                                                      
             width: 50%;
             border: 3px solid green;
            padding: 10px;
                }

        </style>
		<meta charset="utf-8" />
        <TITLE  >Ajouter une photo</TITLE>
    </HEAD>
    <BODY>
        <center><h2 class="titre">MINI-PINTEREST</h2></center>
        <br>
        <?php 
        if (isset($_SESSION['logged']))
        {
            //echo"Connecté: " . $_SESSION['logged'] ."<br>";
        }
        if(isset($_SESSION['debut']))
        {	echo "Votre temps de connexion: ". time() - $_SESSION['debut']." sec.
            <br>";
        }
    ?>
        
    <div>
        <a href="index.php" > 
            <h5><span class="titre">◄ |Accueil|</span></h5>
        </a>
        </div>

        <BODY>
        


            <form id="formAdd" action="ajouter.php" method="POST" name="formulaire" enctype="multipart/form-data">
    
                <br/>
              
                <div class="titre"><h2> Ajouter une nouvelle photo </h2></div>
                Sélectionner une photo de moins de 100 Ko * :<br/>
                
                <input type="file" name="nomfich" id="nomfich" /><br />
    
                <div style="text-align: left;">
                    <p>
                        <label for="description">Décrire la photo en une phrase * : </label><br />
                        <textarea name="description" id="description" rows="5" cols="30"></textarea>
                    </p>
                </div>
    
                <div style="text-align: left;">
                    <label for="categorie">Choisissez une catégorie * : </label><br />
    
                    <select name="categorie" id="categorie">
                        <option value="">--Veuillez choisir une catégorie--</option>
                        <option value="1" >Animaux</option>
                        <option value="2">Vetements</option>
                        <option value="3">Voitures</option>
                        <option value="4">Nourriture</option>
        
                    </select>
                </div>
                    <br/>
    
                <div style="text-align: left;">
                    <input type="submit" name="submit" value="Ajouter">
                </div>
                </form>
                <br/>


                <?php

                error_reporting(E_ALL);
                ini_set('display_errors', 1);
    
                // Lire les données JSON envoyées dans la requête
                $data = json_decode(file_get_contents("php://input"), true);
                error_log(print_r($data, true));
                function recup_id()
            {
                header("Access-Control-Allow-Origin: *");
				header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
				header("Access-Control-Allow-Headers: Content-Type");
				if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
					http_response_code(200);
					exit;
				}
				// Récupérer l'URL de la base de données depuis les variables d'environnement Heroku
				$DATABASE_URL = getenv('DATABASE_URL');

				if (!$DATABASE_URL) {
					die(json_encode(["error" => "DATABASE_URL non définie."]));
				}

				// Décomposer l'URL en ses parties
				$parts = parse_url($DATABASE_URL);
				$host = $parts["host"];
				$user = $parts["user"];
				$pass = $parts["pass"];
				$port = $parts["port"];
				$dbname = ltrim($parts["path"], "/");
				try {
                    http_response_code(200);
                    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					]);

					//ICI, il recupere le nombre de fichiers en tout selon la catégorie
					$stmt = $pdo->prepare('SELECT photoid AS lastphotoid FROM photo ORDER BY photoid DESC LIMIT 1');
					$stmt->execute([]);
					$resultat = $stmt->fetch(PDO::FETCH_ASSOC);
                    //retourne le nouvel id
                    return $resultat['lastphotoid']+1;

                }catch (PDOException $e) {
					http_response_code(500);
                    echo "error";
					echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
                    return null;
                }  	
                
            }
            
            // Verification du formulaire
            
                if (isset($_POST['submit'])){
                    //$maxsize = 100000;
                    $validExt = array('.jpg', '.jpeg', '.gif', '.png');
                
                    // Gestion erreur du fichier
            
                    if ($_FILES['nomfich']['error'] > 0) {
                        echo "<div class='alert'> ";
                        switch ($_FILES['nomfich']['error']) {
                            case UPLOAD_ERR_INI_SIZE:
                                echo "Le fichier dépasse la taille autorisée par le serveur.";
                                break;
                            case UPLOAD_ERR_FORM_SIZE:
                                echo "Le fichier dépasse la taille autorisée par le formulaire.";
                                break;
                            case UPLOAD_ERR_PARTIAL:
                                echo "Le fichier n'a été que partiellement téléchargé.";
                                break;
                            case UPLOAD_ERR_NO_FILE:
                                echo "Aucun fichier n'a été téléchargé.";
                                break;
                            case UPLOAD_ERR_NO_TMP_DIR:
                                echo "Dossier temporaire manquant.";
                                break;
                            case UPLOAD_ERR_CANT_WRITE:
                                echo "Échec de l'écriture du fichier sur le disque.";
                                break;
                            case UPLOAD_ERR_EXTENSION:
                                echo "Une extension PHP a arrêté l'upload du fichier.";
                                break;
                            default:
                                echo "Erreur inconnue.";
                                break;
                        }
                        echo "<br></div> ";
                        die; // Arrête le script en cas d'erreur
                    }
            
                    //  Erreur du format de la photo
            
                    $fileName = $_FILES['nomfich']['name'];
                    $fileExt = "." . strtolower(substr(strrchr($fileName, '.'), 1));
                    
                    if(!in_array($fileExt, $validExt)){
                        echo "<div class='alert'> Le fichier n'est pas une image, vérifiez l'extension!<br>";
                        die;
                    }
            
                    // Erreur description vide
            
                    $recupDescription = $_POST['description'];
                    
                    if($recupDescription == ""){ 
            
                        echo "<div class='alert'> La description doit contenir au moins une lettre. <br></div>";
                        die;
                    }
            
                    // Erreur categorie vide
            
                    $recupCategorie = $_POST['categorie'];
            
                    if($recupCategorie == ""){ 
            
                        echo " <div class='alert'> Vous devez obligatoirement choisir une catégorie!<br></div>";
                        die;
                    }
            
                    $newPhotoId = recup_id();
                    $fileName = "DSC_".$newPhotoId ;
                    $tempName = $_FILES['nomfich']['tmp_name'];
                    if (isset($fileName) && !empty($fileName)){
        
                            $location = "data/";
                            $destination = $location . $fileName . $fileExt;
                            if (move_uploaded_file($tempName, $destination)) {
                                header("Access-Control-Allow-Origin: *");
                                header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
                                header("Access-Control-Allow-Headers: Content-Type");
                                if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                                    http_response_code(200);
                                    exit;
                                }
                                // Récupérer l'URL de la base de données depuis les variables d'environnement Heroku
                                $DATABASE_URL = getenv('DATABASE_URL');

                                if (!$DATABASE_URL) {
                                    die(json_encode(["error" => "DATABASE_URL non définie."]));
                                }

                                // Décomposer l'URL en ses parties
                                $parts = parse_url($DATABASE_URL);
                                $host = $parts["host"];
                                $user = $parts["user"];
                                $pass = $parts["pass"];
                                $port = $parts["port"];
                                $dbname = ltrim($parts["path"], "/");
                                try {
                                    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                                    ]);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $stmt = $pdo->prepare('INSERT INTO photo (photoid,nomfich, description, catid) VALUES (?, ?, ?, ?)');
                                    $stmt->execute(array($newPhotoId,$fileName.$fileExt, $_POST['description'], $_POST['categorie']));    
                                }catch (PDOException $e) {
                                http_response_code(500);
                                echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
                                return null;
                                } 
        
                                echo "<div class='alert'>Le fichier a été ajouté.<br></div>";
                                    $redirec= "detail.php?idphoto=".$newPhotoId."&idcat=".$_POST['categorie']." "; 
                                    echo "<script>
                                        setTimeout(() => {
                                            window.location.href = '$redirec';
                                        }, 2000);
                                    </script>";                                   
                                } else {
                                    echo "Erreur lors du déplacement du fichier.";
                                }
                    }                 
                }
            ?>

    </BODY>  
    </html>