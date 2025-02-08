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
        <a href="index.php" > 
            <h5><span class="titre">◄ |M.P.|</span></h5>
            <span class="alert"> retour à l'accueil</span>
        </a>
        </div>

        <?php
            
            function recup_info()
            {
                $projet = new PDO("mysql:host=localhost; dbname=bdd; charset=utf8", "root", "");

                //récupère la valeur envoyé par l'URL
                $recup= $_GET['idphoto'];
                $req = $projet->query("SELECT * FROM categorie c NATURAL JOIN photo p  WHERE photoId='". $recup."' ");
                $resultat = $req->fetch();
                return $resultat;
            }
            $res= recup_info()['nomFich'];
            echo " <img src='data/". $res ."' importance='auto' alt=''>";
        ?>
        <table>
            <thead>
                <tr>
                    <th colspan="2">
                        <center>
                            <h3> Les détails sur cette <span class="titre">PHOTO </span></h3>
                        </center>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><B>Description</B> </td>
                    <?php
                        $inf=recup_info()['description'];
                        echo "<td>$inf</td>";
                    ?>
                    
                </tr>
                <tr>
                    <td><B>Nom du fichier</B></td>
                    <?php
                        $inf=recup_info()['nomFich'];
                        echo "<td>$inf</td>";
                    ?>                  
                </tr>
                <tr>
                    <td><B>Catégorie</B></td>
                        <?php
                            $inf=recup_info()['nomCat'];
                            echo "<td> <a href='index.php?n_cat=".$inf."'> $inf </a> </td>";
                        ?>
                </tr>
            </tbody>
            
        </table>    



    </BODY> 
</html>