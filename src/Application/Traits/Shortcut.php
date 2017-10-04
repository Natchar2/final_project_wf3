<?php 
namespace Application\Traits;

trait Shortcut{


	//Génération de nom de fichier pour la mise en bdd des images

	public function createFileName($nameLength){


		if(!is_int($nameLength) || $nameLength <=0){

			trigger_error('entrez un entier positif', E_USER_ERROR);

		}

		for($name = NULL; strlen($name)<=$nameLength;){

			$a= rand(0,1);

			if($a == 0){

				$name = $name. rand(0,9);

			}else{

				$name = $name.chr(rand(97,122));
			}

		}
		return $name;
	}


	public static function staticSlug($text)
	{

		//seuls lettre et chiffre accepter, utf8 , pas d'accent et minuscule et remplace les espaces par des tirets


		/*pour retirer tt les accents: 
		$strRetour = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $strRetour)
		*/


		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, '-');

		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}

//créer un slug a partir du titre d'un article
	public function generateSlug($text){

		return self::staticSlug($text);

	}



}

?>

