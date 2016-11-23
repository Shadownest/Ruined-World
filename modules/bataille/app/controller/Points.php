<?php
	namespace modules\bataille\app\controller;
	
	
	class Points {
		//-------------------------- BUILDER ----------------------------------------------------------------------------//
		//-------------------------- END BUILDER ----------------------------------------------------------------------------//
		
		
		//-------------------------- GETTER ----------------------------------------------------------------------------//
		/**
		 * @param $id_base
		 * @return int
		 * renvoi les points de la base
		 */
		public static function getPointsBase($id_base) {
			$dbc = App::getDb();
			
			//on récupère les points de la base en cours
			$query = $dbc->select("points")
				->from("_bataille_base")
				->where("ID_base", "=", $id_base)
				->where("ID_identite", "=", $_SESSION['id_login'].CLEF_SITE)
				->get();
			
			if ((is_array($query)) && (count($query) > 1)) {
				foreach ($query as $obj) {
					return $obj->points;
				}
			}
			
			return 0;
		}
		
		/**
		 * @return mixed
		 * fonction qui renvoi le nombre de points à ajouter à la base lorsqu'on update un batiment
		 */
		private static function getPointAjoutBatiment() {
			$dbc = Bataille::getDb();
			
			$query = $dbc->select("points_batiment")->from("configuration")->where("ID_configuration", "=", 1)->get();
			
			foreach ($query as $obj) return $obj->points_batiment;
		}
		//-------------------------- END GETTER ----------------------------------------------------------------------------//
		
		
		//-------------------------- SETTER ----------------------------------------------------------------------------//
		/**
		 * @param $id_base
		 * @param $type
		 * fonction qui ajoute des points à la base en fonction du type
		 * le type peut etre : batiment, attaque, defense, troupe
		 */
		public static function setAjouterPoints($id_base, $type) {
			$dbc = App::getDb();
			
			if ($type == "batiment") {
				$points = self::getPointsBase($id_base)+self::getPointAjoutBatiment();
				
			}
			
			$dbc->update("points", $points)
				->from("_bataille_base")
				->where("ID_base", $id_base)
				->where("ID_identite", $_SESSION['id_login'].CLEF_SITE)
				->set();
		}
		//-------------------------- END SETTER ----------------------------------------------------------------------------//
		
	}