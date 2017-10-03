<?php
namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


	// Controller pour gérer tous les includes des pages twig. comme la navbar par exemple..
class IncludeController
{
	public function menuAction(Application $app)
	{
		return $app['twig']->render('inc/menu.html.twig');
	}
}