<?php
namespace Application\Extension;

class ApplicationTwigExtension extends \Twig_Extension 
{
	public function getFilters()
	{
				return array(

			//https://twig.symfony.com/doc/2.x/advanced.html#filters
			new \Twig_Filter('accroche', function($text){

				$string = strip_tags($text);

		/*SI ma chaine de caractère est sup à 170, je poursuis 
		Sinon c'est inutile*/
				if(strlen($string)>170){
					//je coupe ma chaine si sup à 170
					$stringCut = substr($string, 0, 170);

				//je m'assure que je ne coupe pas un mot
				$string = substr($stringCut,0, strrpos($stringCut,' ' ));
					}

				return $string;

				}),//fin de twig_Filter Accroche
			
			new \Twig_Filter('slug', function($text){
				$strRetour=strip_tags($text);

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

			}), #fin twig_filter slug
		);
	}
}

?>