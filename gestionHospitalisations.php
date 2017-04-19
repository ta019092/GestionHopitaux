<script language="javascript" src="js/monJS.js"></script>
<link rel="stylesheet" href="css/monCSS.css">
<?php
function listeHospitalisationsText(){
	$fichier=fopen("hospitalisations.txt","r");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
	//echo "Taille du fichier = ".filesize("hospitalisations.txt");
	//other comment
	$entete=array();
	$ligne=fgets($fichier);
	$entete=explode(":",$ligne);
	$etat=1;
	while(!feof($fichier)){
		if ($etat==2){
			echo "<br><b>".$entete[0]."=</b>".strtok($ligne,";");
			$taille=count($entete);
			for($i=1;$i<$taille;$i++)
				echo "<br><b>".$entete[$i]."=</b>".strtok(";");
			echo "<br>***************************************";
		}
		else{
		   $etat=2;
		}
		$ligne=fgets($fichier);
	}
	fclose($fichier);
}
function listeHospitalisationsHTML(){
	$fichier=fopen("hospitalisations.txt","r");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
	//echo "Taille du fichier = ".filesize("hospitalisations.txt");
	$entete=array();
	$ligne=fgets($fichier);
	$entete=explode(";",$ligne);
	echo "<table border=1>";
	echo "<caption>Liste des Hospitalisations</caption>";
	echo "<thead><tr>";
	$taille=count($entete);
	for($i=0;$i<$taille;$i++)
		echo "<th>".$entete[$i]."</th>";
	echo "</tr></thead>";
	$etat=1;
	while(!feof($fichier)){
		if ($etat==2){
		    echo "<tr>";
			$elem=strtok($ligne,";");
			while($elem!==false){
				echo "<td>".$elem."</td>";
				$elem=strtok(";");
			}
			echo "</tr>";
		}
		else{
		   $etat=2;
		}
		$ligne=fgets($fichier);
	}
	echo "</table>";
	fclose($fichier);
}
function listeHospitalisationsEtab($codeE){
	$fichier=fopen("hospitalisations.txt","r");
	if ($fichier==null){
		echo "<br>Fichier introuvable";
		exit;
	}
	//echo "Taille du fichier = ".filesize("hospitalisations.txt");
	$entete=array();
	$ligne=fgets($fichier);
	$entete=explode(";",$ligne);
	echo "<table border=1>";
	echo "<caption>Liste des Hospitalisations</caption>";
	echo "<thead><tr>";
	$taille=count($entete);
	for($i=0;$i<$taille;$i++)
		echo "<th>".$entete[$i]."</th>";
	echo "</tr></thead>";
	$etat=1;
	while(!feof($fichier)){
		if ($etat==2){   
			$elem=strtok($ligne,";");
			if ($elem==$codeE){
			echo "<tr>";
			while($elem!==false){
				echo "<td>".$elem."</td>";
				$elem=strtok(";");
			}
			echo "</tr>";
		}
		}
		else{
		   $etat=2;
		}
		$ligne=fgets($fichier);
	}
	echo "</table>";
	fclose($fichier);
}
 
function enregistrer(){
	$codeA=$_POST['codeA'];
	$dossier=$_POST['dossier'];
	$dateA=$_POST['dateA'];
	$dateS=$_POST['dateS'];
	$spec=$_POST['spec'];
	$chambre=$_POST['chambre'];
	$ligne=$codeA.";".$dossier.";".$dateA.";".$dateS.";".$spec.";".$chambre."\n";
	$sortie=fopen("hospitalisations.txt","a");
	fputs($sortie,$ligne);
	fclose($sortie);
	
}

function supprimer($code, $dossier){
	$tmp=fopen("tmp.txt","a");
	$fic=fopen("hospitalisations.txt","r");
	$tab=array();
	$ligne=fgets($fic);//c'est l'entête
	fputs($tmp,$ligne."\n");
	$ligne=fgets($fic);//la première ligne
	while(!feof($fic)){
		$tab=explode(";",$ligne);
		if($tab[0]!==$code || $tab[1]!==$dossier)
		     fputs($tmp,$ligne);
		$ligne=fgets($fic);
	}//fin while
	fclose($fic);
	fclose($tmp);
	unlink("hospitalisations.txt");
	rename("tmp.txt","hospitalisations.txt");
}
function rechercher(){
	$codeM=trim($_POST['codeM']);
	$dossier=trim($_POST['dossier']);
	$tab=array();
	$trouve=false;
	$fic=fopen("hospitalisations.txt","r");
	$ligne=fgets($fic);//l'entête
	$ligne=fgets($fic);
	while(!feof($fic) && !$trouve){
		$tab=explode(";",$ligne);
		if ($tab[0]===$codeM && $tab[1]===$dossier)
		     $trouve=true;
		else
			$ligne=fgets($fic);
	}
	fclose($fic);
	if ($trouve)
	    return $tab;
	else
		return null;
}
function envoyerDossier(){
	$patient=rechercher();
	if ($patient==null)
	    echo "<br><br>Dossier du patient introuvable<br><br>";
	else {
		echo "<form name=\"formAdmission\" action=\"gestionHospitalisations.php\" method=\"post\">\n"; 
		echo "   Code établissement : <input type=\"text\" id=\"codeA\" name=\"codeA\" value=\"".$patient[0]."\" readonly></br>\n"; 
		echo "   Dossier : <input type=\"text\" id=\"dossier\" name=\"dossier\" value=\"".$patient[1]."\" readonly></br>\n"; 
		echo "   Date admission : <input type=\"text\" id=\"dateA\" name=\"dateA\" value=\"".$patient[2]."\"></br>\n"; 
		echo "   Date sortie : <input type=\"text\" id=\"dateS\" name=\"dateS\" value=\"".$patient[3]."\"></br>\n"; 
		echo "   Spécialité : <input type=\"text\" id=\"spec\" name=\"spec\" value=\"".$patient[4]."\"></br>\n"; 
		echo "   Type chambre : <input type=\"text\" id=\"chambre\" name=\"chambre\" value=\"".$patient[5]."\"></br>\n"; 
		echo "   \n"; 
		echo "   <input type=\"hidden\" name=\"monAction\" value=\"modifier\">\n";
		echo "   <input type=\"hidden\" name=\"etat\" value=\"maj\">\n"; 	
		echo "   <input type=\"button\" value=\"Envoyer\" onClick=\"envoyerFormulaire(formAdmission);\">\n"; 
		echo "   <img src=\"images/fermer.png\" onClick=\"divInvisible('divAdmission')\">\n"; 
		echo "</form>\n";
	}
}
//Le controleur
$action=$_POST['monAction'];
switch($action){
	case "obtenirListe" :
		listeHospitalisationsHTML();
	break;
	case "obtenirListeEtab" :
	     $codeE=$_POST['codeE'];
		listeHospitalisationsEtab($codeE);
	break;
	case "admission" :
		enregistrer();
		echo "<br><br>Patient ".$dossier." bien enregistré<br><br>";
	break;
	case "supprimer" :
		$codeS=trim($_POST['codeS']);
		$dossier=trim($_POST['dossier']);
		supprimer($codeS,$dossier);
		echo "<br><br>Dossier du patient ".$dossier." bien supprimé<br><br>";
	break;
	case "modifier" :
		$etat=$_POST['etat'];
		if ($etat=="rechercher")
			envoyerDossier();
		else {//$etat=="maj"
		    $codeA=$_POST['codeA'];
			$dossier=$_POST['dossier'];
			supprimer($codeA, $dossier);
		    enregistrer();
		   echo "<br><br>Patient ".$dossier." bien modifié<br><br>";
		}
		   ;
}
echo "<br><br><a href=\"accueilHopital.html\">Retour a la page accueil</a>";
?>