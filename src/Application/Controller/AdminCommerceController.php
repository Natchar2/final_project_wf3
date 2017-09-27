<?php
	namespace Application\Controller;

	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response; 
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	class AdminCommerceController
	{
	    public function accueilAdminAction(Application $app)
	    {
	    	if($this->is_Admin())
	    	return $app['twig']->render('admin/accueilAdmin.html.twig');
	    }
	}
	

?>