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

		$controllers
		->get('/event/ajouter/{ID_event}/{token}','Application\Controller\InterfaceAgendaController::addEventAction')
		->assert('ID_event', '\d+')
		->value('ID_event', '0')
		->assert('token', '\w+')
		->value('token', '0')
		->bind('view_agenda_add');

		$controllers
		->post('/event/ajouter/{ID_event}','Application\Controller\InterfaceAgendaController::agendaAddEventPostAction')
		->assert('ID_event', '\d+')
		->value('ID_event', '0')
		->bind('agenda_add');

		$controllers
		->get('/event/liste', 'Application\Controller\InterfaceAgendaController::listEvents')
		->bind('list_events');

		$controllers
		->post('/event/liste', 'Application\Controller\InterfaceAgendaController::deleteEvent')
		->bind('deleteEvent');

		return $controllers;
	}
}
?>