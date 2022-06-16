<?php
	if (isset($_GET['q'])) {
		$shortcut=htmlspecialchars($_GET['q']);
		$bdd=new PDO('mysql:host=localhost;dbname=url_project;charset=utf8','root','');
		$requete=$bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut=?');
		$requete->execute(array($shortcut));
		while ($donnes=$requete->fetch()) {
			if ($donnes['x'] !=1) {
				header('location:?error=true&message=url introuvable.');
				exit();
			}
		}
		$requete=$bdd->prepare('SELECT * FROM links WHERE shortcut=?');
		$requete->execute(array($shortcut));
		while ($donnes=$requete->fetch()) {
			header('location:'.$donnes['url']);
			exit();
		}
	}


	// si url à ete envoyée
	if (isset($_POST['url'])) {
		$url= $_POST['url'];

		//verification URL
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			//pas un lien
			header('location:?error=true&message=Url non valide');
			exit();
		}

		//raccourccis
		$shortcut= crypt($url,rand());

		//si url à dejà été raccourcie
		$bdd=new PDO('mysql:host=localhost;dbname=url_project;charset=utf8','root','');
		$requete=$bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url=?');
		$requete->execute(array($url));

		while ($donnes= $requete->fetch()) {
			if ($donnes['x']!=0) {
				header('location:?error=true&message=Adress url déjà raccourcie.');
				exit();
			}
		}

		//envoie de la requette
		$envoi=$bdd->prepare('INSERT INTO links(url,shortcut) VALUES(?,?)');
		$envoi->execute(array($url,$shortcut));
		header('location:?short='.$shortcut);
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>raccourcisseur url</title>
	<link rel="stylesheet" type="text/css" href="default/design.css">
	<link rel="icon" type="image/png" href="pictures/favico.png">
</head>

<body>
	
	<section id="hello">
		<div class="contener">

			<header >
				<img src="pictures/logo1.png" id="logo" alt="logo">
			</header>
			<h1>une url longue ? raccourcissez-là  ? </h1>
			<h2>largement meilleur et plus court que les autres</h2>
		</div>
	<form method="post">
		<table>
			<tr>
				<td><input type="url" name="url" placeholder="coller un lien à raccourcir"></td>
				<td><button type="submit">raccourcir</button></td>
			</tr>
		</table>
	</form>
	 	<?php 
	 	if (isset($_GET['error']) && isset($_GET['message'])) { ?>
	 		<div class="center">
	 			<div id="result">
	 				<b> <?=htmlspecialchars($_GET['message'])?></b>
	 			</div>
	 		</div>
	    <?php }elseif (isset($_GET['short'])) {?>
	    	<div class="center">
	 			<div id="result">
	 				<b><span>URL RACCOURCIE:http://localhost/test2/?q=</span><?=htmlspecialchars($_GET['short'])?></b>
	 			</div>
	 		</div>
	   <?php }
	 	?>
	</section>
	
	<section  class="container">
		<h4>ces marques nous font confiance.</h4>
		<div class="marques">
			<img src="pictures/1.png" id="marc">
			<img src="pictures/2.png" id="marc">
			<img src="pictures/3.png" id="marc">
			<img src="pictures/4.png" id="marc">
		</div>
	</section>
	<section class="footer">
		<p class="mail">Cliquez sur <a href="mailto:koutoubou@gmail.com"> koutoubou@gmail.com </a> pour me contacter.</p>
		<footer>&copy copyright drissa /<?php 
			$date=date('Y');
			echo $date;
		 ?></footer>
	</section>
</body>
</html>