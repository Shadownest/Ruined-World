<?php
	$pages_bataille = [
		"index",
		"popup/unbatiment",
		"popup/listebatiments",
		"aide",
		"aide-detail",
		"map",
		"popup/map",
		"popup/marche/index",
		"popup/marche/offre-et-demande",
		"popup/marche/envoyer-ressources",
		"popup/ambassade/index",
		"popup/caserne/index",
		"popup/caserne/recruter-troupe",
		"popup/centre_recherche/index",
		"popup/centre_recherche/liste-recherche",
		"login"
	];
	
	if (\core\modules\GestionModule::getModuleActiver("bataille")) {
		if (!in_array($this->page, $pages_bataille)) {
			\core\HTML\flashmessage\FlashMessage::setFlash("Cette page n'existe pas ou plus");
			header("location:".WEBROOT."bataille");
		}

		//pour l'index -> on récupere les derniers articles
		if ($this->page == "index") {
			$this->controller = "bataille/app/controller/initialise/index.php";
		}


		//------------------------------- POUR L'AIDE ----------------------------------//
		if ($this->page == "aide-detail") {
			\modules\bataille\app\controller\Aide::$parametre_router = $this->parametre;
			$this->controller = "bataille/app/controller/initialise/aide.php";
		}



		//------------------------------- POUR LA MAP ----------------------------------//
		if ($this->page == "map") {
			$this->controller = "bataille/app/controller/initialise/map.php";
		}
		if ($this->page == "popup/map") {
			$this->controller = "bataille/app/controller/initialise/popup/map.php";
		}



		//------------------------------- POUR LA POPUP POUR CONSTRUIRE BATIMENT ----------------------------------//
		if ($this->page == "popup/unbatiment") {
			$this->controller = "bataille/app/controller/initialise/popup/unbatiment.php";
		}
		if ($this->page == "popup/listebatiments") {
			$this->controller = "bataille/app/controller/initialise/popup/batiments_construire.php";
		}



		//------------------------------- POUR LA POPUP POUR LE MARCHE ----------------------------------//
		if ($this->page == "popup/marche/index") {
			$this->controller = "bataille/app/controller/initialise/popup/unbatiment.php";
		}



		//------------------------------- POUR LA POPUP POUR L'AMBASSADE ----------------------------------//
		if ($this->page == "popup/ambassade/index") {
			$this->controller = "bataille/app/controller/initialise/popup/unbatiment.php";
		}

		//------------------------------- POUR LA POPUP POUR LA CASERNE ----------------------------------//
		if ($this->page == "popup/caserne/index") {
			$this->controller = "bataille/app/controller/initialise/popup/unbatiment.php";
		}
		if ($this->page == "popup/caserne/recruter-troupe") {
			$this->controller = "bataille/app/controller/caserne/get_recruter_troupe.php";
		}

		//------------------------------- POUR LA POPUP POUR LE CENTRE DE RECHERCHE ----------------------------------//
		if ($this->page == "popup/centre_recherche/index") {
			$this->controller = "bataille/app/controller/initialise/popup/unbatiment.php";
		}
		if ($this->page == "popup/centre_recherche/liste-recherche") {
			$this->controller = "bataille/app/controller/centre_recherche/get_recherche.php";
		}
	}
	else {
		\core\HTML\flashmessage\FlashMessage::setFlash("L'accès à ce module n'est pas configurer ou ne fais pas partie de votre offre, contactez votre administrateur pour résoudre ce problème", "info");
		header("location:".WEBROOT);
	}