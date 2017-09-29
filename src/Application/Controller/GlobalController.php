<?php
	namespace Application\Controller;

	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response; 
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
	use Application\Model\Verifications;


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

	    public function contactAction(Application $app){

	    	return $app['twig']->render('contact_us.html.twig', [
		        'errors' => []
  			]);
	    }

	    public function contactPostAction(Application $app, Request $request){

	    	$Verifications = new Verifications;

	    	$verifs = $Verifications->VerificationContact($request, $app);


	    	$errors = $verifs['errors'];

	    	if(empty($errors)){

	    		return return $app['twig']->render('contact_us.html.twig',[
		                'success' => true,
		                'errors' => [] ,
            ]);

	    	}else{

	    		return $app['twig']->render('contact_us.html.twig', [

	    			'errors' => $errors
	    		]);
	    	}

	    }
	}
?>