<?php
	namespace Application\Controller;

	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response; 
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/*bloulouoi*/
	class GlobalController
	{
		public function sendMailAction(Application $app, Request $request)
	    {
			$transport = $app['swiftmailer.transport'];
			$mailer = $app['mailer'];

			$message = (new \Swift_Message())
				->setSubject($request->get('subject'))
				->setFrom(array($request->get('from')))
				->setTo(array($request->get('to')))
				->setBody($request->get('content'));

		    $app['swiftmailer.use_spool'] = false;
	    	$result = $mailer->send($message);

	    	return $app->redirect($request->get('redirectTo'));
	    }
	}
?>