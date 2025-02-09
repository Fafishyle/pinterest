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
            <span class="alert"> retour à l'accueil</span>
        </a>
        </div>

        <?php
            //recupere l'information sur le photo
            function recup_info()
            {
                $projet = new PDO("mysql:host=localhost; dbname=bdd; charset=utf8", "root", "");

                //récupère la valeur envoyé par l'URL
                $recup= $_GET['idphoto'];
                $req = $projet->query("SELECT * FROM categorie c NATURAL JOIN photo p  WHERE photoId='". $recup."' ");
                $resultat = $req->fetch();
                return $resultat;
            }

            if(isset($_GET['idphoto']))
            {
            $res= recup_info()['nomFich'];
            echo " <img src='data/". $res ."' importance='auto' alt=''>";
            }
            $act= "modifier.html?idphoto=".$_GET['idphoto']."&idcat=".$_GET['idcat']."";
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
                            <label for='description'>Decrire la photo en une phrase: </label><br />
                            <textarea name='description' rows='5' cols='30' minlength='1'></textarea>
                        </p>
                    </td>
                    
                </tr>";
                if(isset($_GET['idphoto']))
                {
                echo "<tr>
                    <td><B>Nom du fichier</B></td> ";
                   
                        $inf=recup_info()['nomFich'];
                        echo "<td><strong>$inf</strong>";
                        }
                        echo"<div>Modifier la photo: </div><br />";
                        echo "
                        <p>
                            <input type='file' name='nomFich' id='nomFich' /></div><br />
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
        function modif_info()
        {
            $projet = new PDO("mysql:host=localhost; dbname=bdd; charset=utf8", "root", "");
            //récupère la valeur envoyé par l'URL
            $req = $projet->query("
            UPDATE `photo` 
            SET `nomFich` = '".$_FILES['nomFich']['name']."', 
            `description` = '".$_POST['description']."',
            `catId`='".$_POST['categorie']."' 
            WHERE `photoId` = '".$_GET['idphoto']."';
            ");
            echo "ENVOIE!";
        }



        //pour modifier la photo

        if (isset($_POST['submit'])){
            
            $fileName = $_FILES['nomFich']['name'];
            $tempName = $_FILES['nomFich']['tmp_name'];
            

            if (isset($fileName)){

                if (!empty($fileName)){

                    $location = "data/";
                    if (move_uploaded_file($tempName, $location.$fileName)){

                        echo "<div class='alert'>Le nouveau fichier a été déplacé dans le répertoire Data<br>
                            ";
                            if (isset($_POST['description']) && isset($_POST['categorie']))
                            {
                                modif_info();
                                echo "<header><div class='titre'>LE FICHIER A ETE MODIFIE!</div></header>";
                            }
                                                               
                    }
                }
                else{
                    echo "vous n'avez pas insérer de photo";
                }
            }
            else{
                echo "vous n'avez pas insérer de photo";
            }
        }
        ?>


    </BODY> 
</html>