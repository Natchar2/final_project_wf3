<?php
namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Application\Traits\Shortcut;
use Application\Model\Verifications;

class InterfaceAgendaController
{

	use Shortcut;

	
	public function agendaAction($category_name,Application $app,$page = 1,$nbPerPage = 6)
	{
		$offset=(($page-1)*$nbPerPage);

		if ($category_name == "all")
		{
			$totalEvents=$app['idiorm.db']->for_table('view_events')->find_result_set();
			$totalEvents=count($totalEvents);
			$events=$app['idiorm.db']->for_table('view_events')->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}
		else
		{
			$totalEvents=$app['idiorm.db']->for_table('view_events')->where('category_name', $category_name)->find_result_set();
			$totalEvents=count($totalEvents);
			$events=$app['idiorm.db']->for_table('view_events')->where('category_name', $category_name)->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}   


		$top = $app['idiorm.db']->for_table('event')->where('ontop', 1)->find_one();       


		return $app['twig']->render('agenda/agenda.html.twig',[
			'totalEvents' => $totalEvents,       
			'events' => $events,
			'top' => $top,
			'page' => $page,       
			'nbPerPage' => $nbPerPage
		]);
	}

	public function agendaPageAction($category_name,Application $app,$page = 1,$nbPerPage = 6)
	{
		$offset=(($page-1)*$nbPerPage);

		if ($category_name == "all")
		{
			$totalEvents=$app['idiorm.db']->for_table('view_events')->find_result_set();
			$totalEvents=count($totalEvents);
			$events=$app['idiorm.db']->for_table('view_events')->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}
		else
		{
			$totalEvents=$app['idiorm.db']->for_table('view_events')->where('category_name', $category_name)->find_result_set();
			$totalEvents=count($totalEvents);
			$events=$app['idiorm.db']->for_table('view_events')->where('category_name', $category_name)->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}          



		return $app['twig']->render('agenda/agenda.html.twig',[
			'totalEvents' => $totalEvents,       
			'events' => $events,
			'page' => $page,       
			'nbPerPage' => $nbPerPage
		]);
	}

	public function agendaArticleAction($category_name,$slugevent,$ID_event,Application $app)
	{
        #format index.php/business/une-formation-innovante-a-lyon_87943512.html
		$event = $app['idiorm.db']->for_table('view_events')->find_one($ID_event);
		$suggests = $app['idiorm.db']->for_table('view_events')->raw_query('SELECT * FROM view_events WHERE ID_category=' . $event->ID_category . ' AND ID_event<>' . $ID_event . ' ORDER BY RAND() LIMIT 3 ')->find_result_set();   

		$topic = $app['idiorm.db']->for_table('view_topics')->where('ID_event', $ID_event)->find_one();

		if (isset($topic) AND !empty($topic))
		{
			$posts = $app['idiorm.db']->for_table('view_topics')->where('ID_topic', $topic['ID_topic'])->find_result_set();
		}
		else
		{
			$posts = null;
		}


		return $app['twig']->render('agenda/event.html.twig',[
			'event' => $event,
			'suggests' => $suggests,
			'posts' => $posts
		]);

	}

    #génération du menu dans le layout
	public function menuAgenda($active, Application $app)
	{
		$categories = $app['idiorm.db']->for_table('category')->find_result_set();
		return $app['twig']->render('menu-agenda.html.twig',[
			'active' => $active,
			'categories' => $categories
		]);
	}


}


?>