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
		->get('/accueil/{category_name}', 'Application\Controller\InterfaceForumController::accueilForumAction')
		->assert('category_name','[^/]+')
		->value('category_name','all')
		->bind('accueil_forum');

		$controllers
		->get('/accueil/{category_name}/page{page}', 'Application\Controller\InterfaceForumController::accueilForumAction')
		->assert('category_name','[^/]+')
		->value('category_name','all')
		->assert('page','[0-9]+')	
		->value('page','1')	
		->bind('accueil_forum_page');

		$controllers
		->get('/topic/{slugTopic}_{ID_topic}.html','Application\Controller\InterfaceForumController::topicAction')
		->assert('slugTopic', '[\w\-\_\|]+')
		->assert('ID_topic', '\d+')
		->bind('forum_topic');

		$controllers
		->post('/topic/{slugTopic}_{ID_topic}.html', 'Application\Controller\InterfaceForumController::newPostAction')
		->assert('slugTopic', '[\w\-\_\|]+')
		->assert('ID_topic', '\d+')
		->bind('forum_topic_post');

		$controllers
		->post('/topic_add_post', 'Application\Controller\InterfaceForumController::addPostTopicAction')
		->bind('topic_add_post');

		$controllers
		->get('/topic/ajouter', 'Application\Controller\InterfaceForumController::newTopicAction')
		->bind('new_topic');

		$controllers
		->post('/topic/ajouter', 'Application\Controller\InterfaceForumController::newTopicPostAction')
		->bind('new_topic_post');

		$controllers
		->get('/topic/liste', 'Application\Controller\InterfaceForumController::listTopics')
		->bind('list_topics');

		$controllers
		->post('/topic/liste', 'Application\Controller\InterfaceForumController::deleteTopic')
		->bind('deleteTopic');

		return $controllers;
	}
}