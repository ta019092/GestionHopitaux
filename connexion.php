<?php
require_once("../BD/connexionBD.php");
$table = "tbmembres";
$num=$_POST['num'];
$pass=$_POST['pass'];
$pass=md5($pass);
$query = "SELECT * FROM $table WHERE '$num'=numMembre AND '$pass'=passMembre";
$result = mysql_query($query) or die("Echec de lister");
$lignes = mysql_num_rows($result);
if ($lignes==0){
	echo "<b>Membre Invalide<br>";
	echo "<a href=\"../connexion.html\">Retour au formulaire</a>";
}
else 
	 header('Location: ../formulairesMembres/membres.html'); 
?>