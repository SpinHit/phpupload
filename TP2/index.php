<?php
// on se login a la bdd
$localhost = "localhost"; 
$dbusername = "root"; 
$dbpassword = "";  
$dbname = "testexo2";  
try{

$dsn = "mysql:host=$localhost;dbname=$dbname"; 

// pagination 
$nbrElementParPage = 4;

// mettre par defaut l'index sur la page 1
if(isset($_GET['page']) && !empty($_GET['page'])){
     $page = (int) strip_tags($_GET['page']);
   }else{
     $page = 1;
   }

   $debut=($page-1)* $nbrElementParPage;


$sql2 = "SELECT id,chemin,file_name from images order by id DESC limit $debut,$nbrElementParPage";

$pdo = new PDO($dsn, $dbusername, $dbpassword);
// on execute la requete sql
$stmt = $pdo->query($sql2);

// on récupére les lignes
$rows = $stmt->fetchAll();

$var = $pdo->prepare('SELECT COUNT(*) from images') ;
$var->execute();
$nbrDelignes = $var->fetch(PDO::FETCH_NUM);
$nbrDePage = ceil($nbrDelignes[0]/ $nbrElementParPage);

if($stmt === false){
     die("Erreur");
  }
  
 }catch (PDOException $e){
     echo $e->getMessage();
 }

 function insereBddDossier($pdo,$pname,$psize,$dest){
    $sql = "INSERT into images (file_name, size , chemin) VALUES ('$pname','$psize','$dest')";
    
     // dossier ou l'on va insserer les images 
    
     
    if ($_FILES['mon_fichier']['error'] > 0) $erreur = "Erreur lors du transfert";
     // upload de l'image dans le dossier 
    $resultat = move_uploaded_file($_FILES['mon_fichier']['tmp_name'],$dest.$_FILES['mon_fichier']['name']);
    
    if ($resultat) $pdo->query($sql) ; echo "Transfert réussi"; header("Refresh:0");
    }

// nom de l'image
if(isset($_FILES["mon_fichier"])){
$pname = $_FILES['mon_fichier']['name'];
$psize = $_FILES['mon_fichier']['size'];
$dest = "images/";

// on insert la vleur nom de l'image dans nom_image(bdd)
insereBddDossier($pdo,$pname,$psize,$dest);
}



?>


<!-- Partie HTML-->
<center>
<h1>Gestion d'images</h1><br/>
 
 <form method="post" enctype="multipart/form-data">
      <label for="mon_fichier">Veuiller choisir une image a téléversser :</label>
      <input type="file" name="mon_fichier" id="mon_fichier" accept="images/png, images/jpeg, images/jpg, images/gif" />
      <input type="submit" name="submit" value="Envoyer" />
 </form>

<!-- on va afficher image par image et l'id avec-->
<div id="listePagination">
    <?php $i=0; foreach($rows as $row): ?>
        <img src="<?= $row['chemin'],$row['file_name'] ; ?>" alt="" />
        <a><?= $row['id'] ; ?></a>
        
        <?php $i++; if($i%2==0){ ?> <br> <?php } ?>
        
    <?php endforeach; ?>
    </div>


    <!-- création des boutons en fonction du nombre de page-->
    <div id="pagination">
    <?php
    // affichage des chiffres
    for($i=1;$i<=$nbrDePage;$i++){
        if($page!=$i)
            echo "<a href='?page=$i'>$i</a>&nbsp;";
        else
            echo "<a>$i</a>&nbsp;";
    }

    ?>
    </div>
</div>

<?php

//
set_time_limit (500);
$path= "docs";


?>
<center/>
<style>

center {
    font-family: "Google Sans",Roboto,Arial,sans-serif;
}



 div#pagination a {
      text-align:center;
      background-color: grey;
      border: 4px solid #2e2722;
      align-items: center;
      box-shadow: rgba(0, 0, 0, .2) 0 3px 5px -1px,rgba(0, 0, 0, .14) 0 6px 10px 0,rgba(0, 0, 0, .12) 0 1px 18px 0;

 }
 div#pagination a:hover {
    color:white;
 }

 div#pagination {
    padding:2%;
 }


 div#listePagination img {
     max-height:200px;
     max-width:200px;
    border: 8px solid #2e2722;
    border-radius: 5px;
    width:300;
    height:300;
    object-fit:cover;
    
 }

 div#listePagination img:hover {
  transform:scale(1.1);
  transition: transform .2s; 
 }

 br {
            display: block; /* makes it have a width */
            content: ""; /* clears default height */
            margin-top: 30; /* change this to whatever height you want it */
}
 div#listePagination {
    border: 8px solid #2e2722;
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
	background-size: 400% 400%;
    margin-left:20%;
    margin-right:20%;
    padding-top: 1%;
    padding-bottom: 1%;
    border-radius: 30px;

 }
 div#listePagination a{
     color: white;
     position:relative;
     top:20px;
     right:120px;
 }

</style>