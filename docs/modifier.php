<!DOCTYPE html>
<?php session_start ();
 ?>
<html>
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
            img {
			width:25%;
			height:50%;
			object-position: 10px center; ;	  
			margin-right: 2em;
            margin-top: 2em;
			border: 50 px;
			border-radius: 20px;
            float:left;
			}

            table {
            border-collapse:collapse;
            width:45%;
            margin-top: 2em;
            }
            
            th, td {
            border:1px solid black;
            width:20%;
            }

            td {
            text-align:center;
            }


        </style>
		<meta charset="utf-8" />
        <TITLE  >Detail de photo</TITLE>
    </HEAD>
    <BODY>
        <center><h2 class="titre">MINI-PINTEREST</h2></center>
        <br>
        <?php 
        if (isset($_SESSION['logged']))
        {
            echo"Connecté: " . $_SESSION['logged'] ."<br>";
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

        <?php
            
            //recupere l'information sur le photo
            function recup_info()
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
                    //récupère la valeur de l'id envoyé par l'URL
                    $recup= (int) $_GET['idphoto'];
                    // Connexion à PostgreSQL
					$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					]);
                    $req = $pdo->prepare('SELECT * FROM categorie c NATURAL JOIN photo p  WHERE photoId = :recup ');
                    $req->execute(['recup' => $recup]);
                    $resultat = $req->fetch(PDO::FETCH_ASSOC);
                    return $resultat;
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
                    return null;
                }  
            }
            if(isset($_GET['idphoto']))
            {
                $res= recup_info()['nomfich'];
                echo " <img src='data/". $res ."' importance='auto' alt=''>";
            }

            $act= "modifier.php?idphoto=".$_GET['idphoto']."&idcat=".$_GET['idcat']."";
        echo " <form action= $act method='POST' name='formulaire' enctype='multipart/form-data'> ";
            ?>
        <table>
            <thead>
                <tr>
                    <th colspan="2">
                        <center>
                            <?php
                            //la condition au cas où nous n'avons pas le l'id de la photo dans le lien
                            if(isset($_GET['idphoto']))
                            {
                                echo"
                            <h3> MODIFIER LA <span class='titre'>PHOTO </span></h3>";
                            }
                            ?>
                        </center>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($_GET['idphoto']))
                    {
                echo "<tr>
                    <td><B>Description</B> </td>";
                    
                        $inf=recup_info()['description'];
                        echo "<td><strong>$inf</strong><br>";
                        }
                        echo "
                        <p>
                            <label for='description'>Decrire la photo en une phrase : </label><br />
                            <textarea name='description' rows='5' cols='30' minlength='1'></textarea>
                        </p>
                    </td>
                    
                </tr>";
                if(isset($_GET['idphoto']))
                {
                echo "<tr>
                    <td><B>Nom du fichier</B></td> ";
                   
                        $inf=recup_info()['nomfich'];
                        echo "<td><strong>$inf</strong>";
                        }
                        echo"<div>Modifier la photo: </div><br />";
                        echo "
                        <p>
                            <input type='file' name='nomfich' id='nomfich' /></div><br />
                        </p>
                    </td>             
                </tr>";
                if(isset($_GET['idphoto']))
                    {
                echo"<tr>
                    <td><B>Catégorie</B></td>";
                    
                            $inf=recup_info()['nomCat'];
                            echo "<td> <a href='index.html?n_cat=".$inf."'> <strong>$inf </strong></a> ";
                    }
                            echo"
                            <div>
                                <label for='categorie'>Changer la catégorie: </label><br /> 
                                <select name='categorie' id='categorie'>
                                    <option value='1'>Animaux</option>
                                    <option value='2'>Vetements</option>
                                    <option value='3'>Voitures</option>
                                    <option value='4'>Nourriture</option>
                                </select>
                            </div>
                        </td>
                </tr>
            </tbody>
                      
        </table>   
        <br>
        <center><a class='alert'><input type='submit'  name='submit' value='MODIFIER'></center></a>
    </form>
        <left><div>";
           
         if(isset($_GET['idphoto']))
            {
         echo "<div >
            <a href='supprimer.php?idphoto=".$_GET['idphoto']."' > 
                <span class='alert'> SUPPRIMER (irréversible)</span>
            </a>
            </div></left>";
        }
        
        //modifie l'information sur le photo
        // Fonction pour mettre à jour les informations de la photo
function modif_info($fileName)
{
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

        if (!isset($_GET['idphoto'])) {
            die(json_encode(["error" => "ID photo manquant."]));
        }

        $idphoto = filter_var($_GET['idphoto'], FILTER_VALIDATE_INT);
        if (!$idphoto) {
            die(json_encode(["error" => "ID photo invalide."]));
        }

        $updates = [];
        $params = ["idphoto" => $idphoto];
        $messages = [];

        if (!empty($_POST['description'])) {
            $updates[] = "description = :description";
            $params['description'] = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $messages[] = "La description a été modifiée avec succès.";
        }

        if (!empty($_POST['categorie'])) {
            $categorie = filter_var($_POST['categorie'], FILTER_VALIDATE_INT);
            if ($categorie) {
                $updates[] = "catId = :categorie";
                $params['categorie'] = $categorie;
                $messages[] = "La catégorie a été modifiée avec succès.";
            }
        }

        if ($fileName !== null) {
            $updates[] = "nomfich = :nomfich";
            $params['nomfich'] = $fileName;
            $messages[] = "L'image a été modifiée avec succès.";
        }

        if (!empty($updates)) {
            $query = "UPDATE photo SET " . implode(", ", $updates) . " WHERE photoid = :idphoto";
            $req = $pdo->prepare($query);
            $success = $req->execute($params);

            if ($success) {
                echo json_encode(["success" => true, "messages" => $messages]);
            } else {
                echo json_encode(["error" => "Échec de la mise à jour."]);
            }
        } else {
            echo json_encode(["message" => "Aucune modification effectuée."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
    }
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    $fileName = null;

    if (isset($_FILES['nomfich']) && $_FILES['nomfich']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['nomfich']['name'];
        $tempName = $_FILES['nomfich']['tmp_name'];

        if (!empty($fileName) && file_exists($tempName)) {
            $location = "data/";

            if (move_uploaded_file($tempName, $location . $fileName)) {
                echo json_encode(["message" => "Le fichier a été déplacé dans le répertoire data."]);
            } else {
                die(json_encode(["error" => "Échec du déplacement du fichier."]));
            }
        } else {
            die(json_encode(["error" => "Aucun fichier inséré."]));
        }
    }

    modif_info($fileName);
}
?>


    </BODY> 
</html>