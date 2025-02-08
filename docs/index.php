<!DOCTYPE html>
<?php session_start ();?>
<html>
	<HEAD>
        <style>

		.alert {
			padding: 20px;
			background-color: #D8BFD8; 
			color: #8A2BE2;
			margin-bottom: 15px;
				}

		.titre {
			font: 200% sans-serif; 
			text-shadow:#ff006e 2.5px 2.5px 0, #8338ec 5px 5px 0, #3a86ff 7.5px 7.5px 0,#333 10px 10px 10px;color:#ffcc00;
 			background-color:#D8BFD8;
				}

		img {
			width:25%;
			height:50%;
			object-position: 10px center; ;	  
			margin-right: 2em;
			border: 50 px;
			border-radius: 20px;
			}
		
			.button_red {
    		padding: 15px 30px;
    		min-width: 100px;
    		text-align: center;
			border-radius: 9px;
			float: right;
			margin-right: 2em;
			color: #ffffff;
			font-family:verdana, sans-serif;
			font-size:75%;
			text-decoration:none;
			}

		</style>
		<meta charset="utf-8" />
        <TITLE  >Mini-Pinterest</TITLE>
    </HEAD>
	
    <BODY>
		<div class=pull-right>
			<?php

			//Si quelqu'un s'est connecté, si une session démarre
			//Si $_SESSION a une valeur
			$co= "Connexion";
			$ref1="href= 'connexion.html' ";
			$affichage1 = "Inscription";
			$ref="href = 'inscription.html' ";
			$targ="'_blank'"; //pour afficher dans un nouvel onglet
			//Si la session est connectée
			if (isset($_SESSION['logged']))
			{
				$temp=time();

				$adm= "";
				//si c'est un compte administrateur
				if($_SESSION['admin']== 1)
				{
					$adm="COMPTE ADMINISTRATEUR: ";
					echo "
				<a class='button_red' style=' background-color: #FFA07A;' href='statistic.html' >
				<strong>Statistique </strong>
			</a>";

				}
				//l'email de la session en cours
				$affichage1 = $adm.$_SESSION['logged'];
				$affichage1 = find_pseudo($affichage1);

				$ref="";
				$co="Déconnexion";
				$ref1="href = 'deconnexion.php' ";
				$targ= "''";

				//Boutont ajouter une photo
				echo "
				<a class='button_red' style=' background-color: #FFA07A;' href='ajouter.html' >
				<strong>Ajouter </strong> <i>une photo</i> 
			</a>";
				
			}
			echo "
				<a class='button_red' style=' background-color: #FFA07A;' $ref1 target=$targ >
				<strong>$co </strong> 
			</a>";
				echo " 			
			<a class='button_red ' style=' background-color:#F08080;' $ref target='_blank' >
				<strong><i>$affichage1</i></strong>
			</a>";
		
		?>
		</div>
		<center><h2 class="titre">MINI-PINTEREST</h2></center>
		<?php
		if(isset($_SESSION['debut']))
			{	echo "Votre temps de connexion: ". time() - $_SESSION['debut']." sec.
				<br>";
			}
		?>
        Quelles photos souhaitez vous afficher?         
        <FORM action="" method="post">
            <SELECT name="Cat" size="1">			
				<option value="Toutes les photos">Toutes les photos</option>
				<option value="Animaux">animaux</option>
				<option value="Vetements">vetements</option>
				<option value="Voitures">voitures</option>
				<option value="Nourriture">nourriture</option>
            </SELECT>
			<input type="submit" name="submit" value="Valider">
        </FORM>
        
        <div id=image>
        <?php	
			function find_pseudo($email){
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
						// Connexion à PostgreSQL
						$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
							PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
						]);
						$stmt = $pdo->prepare("SELECT pseudo FROM users WHERE email = :email");
						$stmt->execute(['email' => $email]);
						$result = $stmt->fetch(PDO::FETCH_ASSOC);
						if ($result) {
							return $result['pseudo']; // Stocke le pseudo récupéré
						} else {
							return "Pseudo introuvable"; // Gère le cas où l'email n'existe pas
						}
					} catch (PDOException $e) {
						http_response_code(500);
						echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
					}
			}

			function affic_cat($cate)
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
					// Connexion à PostgreSQL
					$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					]);
					$stmt = $pdo->prepare('SELECT count(*) AS total FROM categorie c NATURAL JOIN photo p WHERE nomCat = :cate ');
					$stmt->execute(['cate' => $cate]);
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					var_dump($result); // Vérifie ce que contient réellement le tableau
					//ICI, il recupere le nombre de fichiers en tout selon la catégorie
					if ($result) {
						$c = $result['total']; // Stocke le pseudo récupéré
						echo "<div class='alert'>
							Nous vous avons selectionnés | $c | photos. <br>
						</div>";
					} else {
						echo "<div class='alert'>
							Aucune photo trouvée <br>
						</div>";
					}
					
					//ICI, il recupere le nom des fichiers selon la catégorie
					$stmt = $pdo->prepare("SELECT p.nomFich,p.photoId,p.catId FROM categorie c NATURAL JOIN photo p  WHERE nomCat = :cate");
					$stmt->execute(['cate' => $cate]);
						//resultat de la requête avec fetch va chercher le premier res[attribut].
					while ($resultat = $stmt->fetch(PDO::FETCH_ASSOC) )
					{
						//echo $resultat['nomFich'];
						$res= $resultat['nomFich'];

						//on va envoyer le photoId et le catID
						$info1=$resultat['photoId'];
						$info2=$resultat['catId'];
						if(isset($_SESSION['admin']) && $_SESSION['admin']== 1)
						{
							echo"<div>"; //pour l'affichage du bouton sur la même ligne
						}
						echo "
						<a href='detail.html?idphoto=". $info1 ."&idcat=". $info2 ."'>
							<img src='data/". $res ."' importance='auto' alt=''>
						</a>";
						if(isset($_SESSION['admin']) && $_SESSION['admin']== 1)
						{ echo "
						<a class='button_red' style=' background-color: #FFA07A;' 
						href='modifier.html?idphoto=". $info1 ."&idcat=". $info2 ."' >
							<strong>Modifier </strong> <i>la photo</i> 
						</a>
						</div>
						
						";
						}
					}  
					} catch (PDOException $e) {
						http_response_code(500);
						echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
					}  			
			}

			//affichage par défaut: l'accueil_toutes les photos
			function affic_def()
			{
				$bdd = new PDO("mysql:host=localhost; dbname=bdd", "root", "");
				//ICI, il recupere le nombre de fichiers en tout selon la catégorie
				$req_compt = $bdd->query("SELECT count(*) FROM categorie c NATURAL JOIN photo p ");
				$compt = $req_compt->fetch();
				$c= $compt['count(*)'];
				echo "<h1>Toutes les photos</h1>";
				echo "<div class='alert'>
				Nous vous avons selectionnés | $c | photos. <br>
				</div>";
				$req = $bdd->query("SELECT p.nomFich,p.photoId,p.catId FROM categorie c NATURAL JOIN photo p");
				while ($resultat = $req->fetch() )
				{
					//echo $resultat['nomFich'];
					$res= $resultat['nomFich'];

					//on va envoyer le photoId et le catID
					$info1=$resultat['photoId'];
					$info2=$resultat['catId'];
					if(isset($_SESSION['admin']) && $_SESSION['admin']== 1)
					{
						echo"<div>"; //pour l'affichage du bouton sur la même ligne
					}
					echo "
					<a href='detail.html?idphoto=". $info1 ."&idcat=". $info2 ."'>
						<img src='data/". $res ."' importance='auto' alt=''>
					</a>
					
					";
					if(isset($_SESSION['admin']) && $_SESSION['admin']== 1)
					{ echo "
					<a class='button_red' style=' background-color: #FFA07A;' 
					href='modifier.html?idphoto=". $info1 ."&idcat=". $info2 ."'  >
						<strong>Modifier </strong> <i>la photo</i> 
					</a>
					</div>
					";
					}
				} 
			}

			if(!isset($_POST['submit']) && !isset($_POST['Cat'] ) && !isset($_GET['n_cat']) )
			{
				affic_def();
			}
			
			if( isset($_POST['Cat']) && $_POST['Cat']=="Toutes les photos")
			{
				affic_def();
			}

			if( isset($_POST['Cat']) && $_POST['Cat']!=="Toutes les photos")
			{
				$selected = $_POST['Cat'];
				echo '<br><h4> => Voici les photos correspendants à la catégoriee <span class="titre">' . $selected . '</span></h4>';
				echo '<br>';
				affic_cat($selected);
				
				
			}

			if(isset($_GET['n_cat']) && !isset($_POST['Cat']) && !isset($_POST['submit']))
			{
				//echo "XXXX ".$_GET['n_cat']." XXXX ";
				$_POST['Cat']=$_GET['n_cat'];
				//echo "XXXX ".$_POST['Cat']." XXXX ";
				$selected = $_POST['Cat'];
				echo '<br><h4> => Voici les photos correspendants à la catégorie <span class="titre">' . $selected . '</span></h4>';
				echo '<br>';
				if ($_POST['Cat']!=="Toutes les photos")
				{
					affic_cat($selected);
				}
				
			}				
		?>
        </div>
    </BODY>  
</html>