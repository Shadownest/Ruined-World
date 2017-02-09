<?php
	namespace modules\bataille\app\controller;
	
	
	use core\App;
	use core\HTML\flashmessage\FlashMessage;
	
	class GroupeUnite extends Unite {
		
		
		//-------------------------- BUILDER ----------------------------------------------------------------------------//
		//-------------------------- END BUILDER ----------------------------------------------------------------------------//
		
		
		//-------------------------- GETTER ----------------------------------------------------------------------------//
		/**
		 * fonction qui récupère tous les groupes de la bases
		 */
		public function getAllGroupeUnite() {
			$dbc = App::getDb();
			
			$query = $dbc->select()->from("_bataille_groupe_unite")->get();
			
			if ((is_array($query)) && (count($query))) {
				$groupe = [];
				
				foreach ($query as $obj) {
					$groupe[] = [
						"id_groupe" => $obj->ID_groupe_unite,
						"nom_groue" => $obj->nom_groupe,
						"unites" => $this->getAllUnites(Bataille::getIdBase(), $obj->ID_groupe_unite)
					];
				}
				
				Bataille::setValues(["groupe_unites" => $groupe]);
			}
		}
		
		/**
		 * @param $nom_groupe
		 * @return bool
		 * fonction qui test si un groupe existe déjà ou pas dans cette base
		 */
		private function getTestGroupExist($nom_groupe) {
			$dbc = App::getDb();
			
			$query = $dbc->select("ID_groupe_unite")
				->from("_bataille_groupe_unite")
				->where("nom_groupe", "=", $nom_groupe, "AND")
				->where("ID_base", "=", Bataille::getIdBase())
				->get();
			
			if (count($query) > 0) {
				return false;
			}
			
			return true;
		}
		//-------------------------- END GETTER ----------------------------------------------------------------------------//
		
		
		//-------------------------- SETTER ----------------------------------------------------------------------------//
		/**
		 * @param $nombre_unite
		 * @param $nom_unite
		 * @param $type_unite
		 * @param $nom_groupe
		 * @return bool
		 * fonction qui permet de créer un groupe
		 */
		public function setCreerGroupe($nombre_unite, $nom_unite, $type_unite, $nom_groupe) {
			$dbc = App::getDb();
			
			if ($this->getTestGroupExist($nom_groupe) == false) {
				FlashMessage::setFlash("Un groupe portant ce nom existe déjà, merci d'en choisir un autre !");
				return false;
			}
			
			$dbc->insert("nom_groupe", $nom_groupe)
				->insert("niveau_groupe", 1)
				->insert("ID_base", Bataille::getIdBase())
				->into("_bataille_groupe_unite")
				->set();
			
			$id_groupe = $dbc->lastInsertId();
			
			$count = count($nombre_unite);
			
			for ($i=0 ; $i<$count ; $i++) {
				$this->setAjouterUniteGroupe($nombre_unite[$i], $nom_unite[$i], $type_unite[$i], $id_groupe);
			}
		}
		
		/**
		 * @param $nombre_unite
		 * @param $nom_unite
		 * @param $type_unite
		 * @param $id_groupe
		 * @return bool
		 * fonction qui permet d'ajouter des unités a un groupe
		 */
		private function setAjouterUniteGroupe($nombre_unite, $nom_unite, $type_unite, $id_groupe) {
			$dbc = App::getDb();
			
			$nombre_unite_base = $this->getNombreUniteNom($type_unite, $nom_unite);
			
			if ($nombre_unite > $nombre_unite_base) {
				FlashMessage::setFlash("Pas assez d'unités ".$nom_unite." disponibles dans les ajouter à ce groupe");
				return false;
			}
			
			$dbc->update("ID_groupe", $id_groupe)
				->from("_bataille_unite")
				->where("type", "=", $type_unite, "AND")
				->where("nom", "=", $nom_unite, "AND")
				->where("ID_base", "=", Bataille::getIdBase(), "AND")
				->where("(ID_groupe IS NULL OR ID_groupe = 0)", "", "", "", true)
				->limit($nombre_unite, "no")
				->set();
			
			return true;
		}
		//-------------------------- END SETTER ----------------------------------------------------------------------------//    
	}