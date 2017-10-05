<?php
namespace Application\Provider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class InterfaceAgendaControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers
		->get('/',  function() use($app){
			return $app->redirect('all');
		});


		$controllers
		->get('/{category_name}', 'Application\Controller\InterfaceAgendaController::agendaAction')
		->assert('category_name','[^/]+')
		->value('category_name','all')
		->bind('agenda');


		$controllers
		->get('/{category_name}/page{page}', 'Application\Controller\InterfaceAgendaController::agendaPageAction')
		->assert('category_name','[^/]+')
		->value('category_name','all')
		->assert('page','[0-9]+')	
		->value('page','1')		
		->bind('agenda_page');

		$controllers
		->get('/{category_name}/{slugevent}_{ID_event}.html','Application\Controller\InterfaceAgendaController::agendaArticleAction')
		->assert('category_name','[^/]+')
		->assert('ID_event','[0-9]+')
		->bind('agenda_article');



		return $controllers;
	}
}
?>