<?php
	namespace Application\Controller;

	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response; 
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	class InterfaceController
	{
	    public function accueilAction(Application $app)
	    {
	    	return $app['twig']->render('accueil.html.twig');
	    }

	    public function feedbackAction(Application $app, Request $request)
	    {
			$transport = $app['swiftmailer.transport'];
			$mailer = $app['mailer'];

			$message = (new \Swift_Message())
				->setSubject('Subject Mail')
				->setFrom(array('postmaster@localhost'))
				->setTo(array('root@localhost'))
				->setBody("This is an email");

		    $app['swiftmailer.use_spool'] = false;
	    	$result = $mailer->send($message);

	    	return $app['twig']->render('feedback.html.twig');
	    }
	}
	

?>