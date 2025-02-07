<!DOCTYPE html>
<?php session_start ();?>
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


        </style>
		<meta charset="utf-8" />
        <TITLE  >Detail de Nom_fichier.jpg</TITLE>
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
        <a href="index.html" > 
            <h5><span class="titre">◄ |M.P.|</span></h5>
            
        </a>
        </div>
        <br>
        <?php
        
        //retourne le nombre d'image de la categorie $cate
        function n_cat($cate)
			{
				//PHP Data Object on va se connecter avec un BD
				//dbname est le nom de la base de donnée, ici bdd
				//root est le nom de l'utilisateur par défaut de xampp
				//il n'y a pas de mot de passe donc ""
				$bdd = new PDO("mysql:host=localhost; dbname=bdd", "root", "");
				
				//une requête qui va preparer la requete SQL dans la base 
				//ICI, il recupere le nombre de fichiers en tout selon la catégorie
				$req_compt = $bdd->query("SELECT count(*) FROM categorie c NATURAL JOIN photo p WHERE nomCat='". $cate ."' ");
				$compt = $req_compt->fetch();
				return $compt['count(*)']; 
               
            }

        $bdd = new PDO("mysql:host=localhost; dbname=bdd", "root", "");
        $req = $bdd->query("SELECT count(*) FROM validation");
        $n = $req->fetch();
        if($_SESSION['admin'] == 1)
        {
            echo "
                    <div class='alert'> STATISTIQUES SUR LE SITE </div>
                    Le nombre d'utilisateur: ".$n['count(*)']." .<br>
            
            Nombre d'image de la catégorie vetements: ".n_cat("vetements")."
            <br>
            Nombre d'image de la catégorie voitures: ".n_cat("voitures")."
            <br>
            Nombre d'image de la catégorie nourriture: ".n_cat("nourriture")."
            <br>
            Nombre d'image de la catégorie animaux: ".n_cat("animaux")."
            <br>
        ";

        }
        ?>



    </BODY> 
</html>