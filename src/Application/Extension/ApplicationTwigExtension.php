<?php
namespace Application\Extension;

use Silex\Application;

class ApplicationTwigExtension extends \Twig_Extension 
{
	public function getFilters()
	{
		return array(
					
			new \Twig_Filter('search_construct', function($text)
			{
				$text = explode("(&é'(-è_çà)=$^*ù:!;,)", $text);

				$result['content'] = strip_tags($text[0]);
				$result['category'] = $text[1];
				$result['url'] = $text[2];

				return $result;
			}),

			new \Twig_Filter('search_construct', function($text)
			{
				$array = explode("(&é'(-è_çà)=$^*ù:!;,)", $text);

				$result['content'] = strip_tags($array[0]);

				if(count($array) > 1)
				{
					$result['category'] = $array[1];
					$result['url'] = $array[2];
				}
				else
				{
					$result['category'] = "";
					$result['url'] = "";
				}

				return $result;
			}),
			//https://twig.symfony.com/doc/2.x/advanced.html#filters
			new \Twig_Filter('accroche', function($text)
			{

				$string = strip_tags($text);

		/*SI ma chaine de caractère est sup à 150, je poursuis 
		Sinon c'est inutile*/

				if(strlen($string)>150){
					//je coupe ma chaine si sup à 150
					$stringCut = substr($string, 0, 150);

				//je m'assure que je ne coupe pas un mot
				$string = substr($stringCut,0, strrpos($stringCut,' ' ))  . '...';

					}

				return $string;

				}),//fin de twig_Filter Accroche
			
			new \Twig_Filter('slug', function($text)
			{
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

			new \Twig_Filter('balise', function($text)
			{
				$text = str_replace('%', 'pourcent', $text);

				printf($text);
				return;
			}), #fin twig_filter balise

			new \Twig_Filter('number', function($text)
			{
				return floatval($text);
			}),
		);
	}
}

?>