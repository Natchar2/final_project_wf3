<?php
	namespace Application\Extension;

	class ApplicationTwigExtension extends \Twig_Extension
	{
		public function get_filter()
		{
			return array(
				new \Twig_Filter('name_filter', function($variable){
					
				}),
			);
		}
	}
	
?>