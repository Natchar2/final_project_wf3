<?php
	namespace Application\Controller;

	use Application\Model\Verifications;
	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response; 
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



	class GlobalController
	{
		public function sendMailAction(Application $app, $subject, $from, $to, $content) 
	    { 
			$transport = $app['swiftmailer.transport'];
			$mailer = $app['mailer'];

			$message = (new \Swift_Message())
				->setSubject($subject)
				->setFrom(array($from))
				->setTo(array($to))
				->setBody($content, 'text/html');

		    $app['swiftmailer.use_spool'] = false;
	    	$result = $mailer->send($message);

	    	return new Response('Votre message a bien été envoyé');
	    }

	    public function contactAction(Application $app){

	    	return $app['twig']->render('public/contact_us.html.twig', [
		        'errors' => []
  			]);
	    }

	    public function contactPostAction(Application $app, Request $request){

	    	$Verifications = new Verifications;

	    	$verifs = $Verifications->VerificationContact($request, $app);


	    	$errors = $verifs['errors'];

	    	if(empty($errors)){

	    		$content 	= htmlspecialchars($request->get('message')).$request->get('name').$request->get('surname');
	    		$subject 	= $request->get('subject');
	    		$from 		= $request->get('mail');
	    		$to 	 	= 'root@localhost';
	    		$redirectTo = '/accueil';

	    		$success = $this->sendMailAction( $app, $subject,$from,$to,$content,$redirectTo);


	    			return $app['twig']->render('public/contact_us.html.twig', [

	    				'errors' => $errors,
	    				'success'=> $success
	    			]);
	    		
	    		
	    	}else{

	    		return $app['twig']->render('public/contact_us.html.twig', [

	    			'errors' => $errors
	    		]);
	    	}

	    }

		public function ConditionsAction(Application $app)
			{
				return $app['twig']->render('public/conditions.html.twig');
			}

	}
?>