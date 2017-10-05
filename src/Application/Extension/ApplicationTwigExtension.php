<?php
namespace Application\Extension;

class ApplicationTwigExtension extends \Twig_Extension 
{
	public function getFilters()
	{
		return array(
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
			new \Twig_Filter('accroche', function($text){

				$strRetour=strip_tags($text);

				if (strlen($strRetour) > 170)
				{
					$strRetour=substr($strRetour,0, 170);
					$strRetour=substr($strRetour, 0, strrpos($strRetour, ' '));
					$strRetour.=" ...";
				}
				return $strRetour;
			}),#fin twig_filter accroche

		);
	}
}

?>