<?php
namespace Application\Provider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class InterfaceForumControllerProvider implements ControllerProviderInterface
{

		public function connect(Application $app)
	{

		$controllers = $app['controllers_factory'];

		$controllers
		->get('/forum',  function() use($app){
			return $app->redirect('accueil_Forum');
		});

		$controllers
		->get('/accueil', 'Application\Controller\InterfaceForumController::accueilForumAction')
		->bind('accueil_Forum');

		$controllers
		->get('/topic/{slugTopic}_{ID_topic}.html','Application\Controller\InterfaceForumController::topicAction')
		->assert('slugTopic', '[\w\-\_\|]+')
		->assert('ID_topic', '\d+')
		->bind('forum_topic');

		$controllers
		->post('/topic/{slugTopic}_{ID_topic}.html', 'Application\Controller\InterfaceForumController::newPostTopicAction')
		->assert('slugTopic', '[\w\-\_\|]+')
		->assert('ID_topic', '\d+')
		->bind('forum_topic_post');

		return $controllers;
	}
}