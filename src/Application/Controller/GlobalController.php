<?php
	namespace Application\Controller;

	use Application\Traits\Shortcut;
	use Application\Model\Verifications;
	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response; 
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



	class GlobalController
	{
		use Shortcut;

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

		public function forgot_passwordAction(Application $app, Request $request, $token)
		{
			if($token != $app['session']->get('token'))
			{
				$app->redirect($this->getRacineSite() . 'error/403');
			}

			return $app['twig']->render('public/forgot_password.html.twig');
		}

		public function forgot_passwordPostAction(Application $app, Request $request, $token)
		{
			if($token != $app['session']->get('token'))
			{
				return $app->redirect($this->getRacineSite() . 'error/403');
			}

			$is_exist = $app['idiorm.db']->for_table('users')->where('mail', $request->get('mail'))->find_result_set()[0];

			if(count($is_exist) == 0)
			{
				return $app['twig']->render('public/forgot_password.html.twig', array(
					"not_exist_user" => "<b>" . $request->get('mail') . "</b> n'existe pas <b>",
				));
			}

			$token_generate = $this->generateToken(40);

			$subject = "Réinitialisation de mot de passe";
			$from = "postmaster@localhost";
			$to = $request->get('mail');

			$content = "<p>Le lien de réinitialisation n'est valide que 24h.</p> <p>Pour réinitialiser votre mot de passe cliquez sur le lien suivant:<br/><a href='" . $this->getRacineSite() . "reset_password-" . $token_generate . "'>réinitialiser son mot de passe.</a></p><p><b>Cordialement</b></p>";


			$modif_reset_date = $app['idiorm.db']->for_table('users')->where('mail', $to)->find_result_set()->set(array(
				'reset_password_date' => date('now'),
				'reset_password_token' => $token_generate,
			));

			$modif_reset_date->save();

			$this->sendMailAction($app, $subject, $from, $to, $content);

			return $app['twig']->render('public/forgot_password.html.twig', array(
				"success_send" => "Un email de réinitialisation à bien été envoyé à <b>" . $request->get('mail') . "</b>",
			));
		}

		public function reset_passwordAction(Application $app, $token)
		{
			$user = $app['idiorm.db']->for_table('users')->where('reset_password_token', $token)->find_result_set();

			if(count($user) == 0)
			{
				return $app['twig']->render('public/forgot_password.html.twig', array(
					"token_invalid" => "Ce token est invalide",
					"token" => $token,
				));
			}

			$user = $user[0];

			if($user->reset_password_date + (3600*24) < date('now'))
			{
				return $app['twig']->render('public/forgot_password.html.twig', array(
					"token_expired" => "Ce token n'est plus valide",
					"token" => $token,
				));
			}

			if($token != $user->reset_password_token)
			{
				return $app['twig']->render('public/forgot_password.html.twig', array(
					"token_invalid" => "Ce token est invalide",
					"token" => $token,
				));
			}

			return $app['twig']->render('public/reset_password.html.twig', array("token" => $token));
		}

		public function reset_passwordPostAction(Application $app, Request $request, $token)
		{
			if($token != $app['session']->get('token'))
			{
				$app->redirect($this->getRacineSite() . 'error/403');
			}

			$mail = $request->get('mail');
			$zip_code = $request->get('zip_code');
			$password = $request->get('password');
			$confirm_password = $request->get('confirm_password');
			$token_reset = $request->get('token');

			$user = $app['idiorm.db']->for_table('users')->where('reset_password_token', $token_reset)->find_result_set()[0];

			if(count($user) > 0)
			{
				if($user->reset_password_token !== $request->get('token'))
				{
					$app->redirect($this->getRacineSite() . 'error/403');
				}

				if($mail !== $user->mail || $zip_code !== $user->zip_code)
				{
					return $app['twig']->render('public/reset_password.html.twig', array(
						"invalid_field" => "Votre code postal ou votre email est incorrect",
						'token' => $token_reset,
					));
				}

				if($password !== $confirm_password)
				{
					return $app['twig']->render('public/reset_password.html.twig', array(
						"password_not_identic" => "Les mots de passe ne correspondent pas",
						'token' => $token_reset,
					));
				}

				$modif_password = $app['idiorm.db']->for_table('users')->where('mail', $mail)->find_result_set()->set(array(
					'reset_password_date' => 0,
					'reset_password_token' => '',
					'password' => $app['security.encoder.digest']->encodePassword($password, ''),
				));

				$modif_password->save();

				return $app['twig']->render('commerce/connexion.html.twig', array(
					"success_mofidification_password" => "Votre mot de passe à été modifié avec success, vous pouvez à présent vous connecter.",
					"last_username" => $mail,
				));
			}

			return $app->redirect($this->getRacineSite() . 'reset_password-' . $token_reset);
		}

		public function newsletterPostAction(Application $app, Request $request)
		{

	    	if($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	    	{
				$token = $app['security.token_storage']->getToken();
				$user = $token->getUser();

	    		if($user->getNewsletter())
	    		{
	    			$app->redirect('accueil#newsletter');
	    		}

	    		$newsletter_exist = $app['idiorm.db']->for_table('newsletter')->where('mail', $user->getMail())->find_result_set();
	    		$products=$app['idiorm.db']->for_table('view_products')->order_by_desc('creation_date')->limit(6)->find_result_set();
				$topics=$app['idiorm.db']->for_table('view_topics')->order_by_desc('creation_date')->limit(6)->find_result_set();
				$events=$app['idiorm.db']->for_table('view_events')->order_by_desc('creation_date')->limit(3)->find_result_set();

				if(count($newsletter_exist) > 0)
				{
	    			$saveInscriptionNews = $app['idiorm.db']->for_table('users')->where('mail', $user->getMail())->find_result_set()->set(array(
	    				'newsletter' => true,
	    			));
	    			$saveInscriptionNews->save();

	    			$newsletter = $app['idiorm.db']->for_table('newsletter')->create();
	    			$newsletter->mail = $user->getMail();
	    			$newsletter->save();

	    			return $app->redirect('accueil#newsletter');
	    		}

	    		return $app['twig']->render('commerce/accueil.html.twig', array(
    				'mail_exist' => 'Impossible de vous inscrire à la newsletter, cette email existe déjà',
    				'products' => $products,
					'topics' => $topics,       
					'events' => $events,
    			));
			}
			else
			{
				$products=$app['idiorm.db']->for_table('view_products')->order_by_desc('creation_date')->limit(6)->find_result_set();
				$topics=$app['idiorm.db']->for_table('view_topics')->order_by_desc('creation_date')->limit(6)->find_result_set();
				$events=$app['idiorm.db']->for_table('view_events')->order_by_desc('creation_date')->limit(3)->find_result_set();

				if(null === $request->get('mail'))
				{
					return $app->redirect('accueil#newsletter');
				}

				$mail = $request->get('mail');

				$user = $app['idiorm.db']->for_table('users')->where('mail', $mail)->find_result_set();

				if(count($user) > 0)
				{
					return $app['twig']->render('commerce/accueil.html.twig', array(
	    				'mail_exist' => 'Impossible de vous inscrire à la newsletter, cette email existe déjà',
	    				'products' => $products,
						'topics' => $topics,       
						'events' => $events,
	    			));
				}

				$newsletter_exist = $app['idiorm.db']->for_table('newsletter')->where('mail', $mail)->find_result_set();

				if(count($newsletter_exist) > 0)
				{
					return $app['twig']->render('commerce/accueil.html.twig', array(
	    				'mail_exist' => 'Impossible de vous inscrire à la newsletter, cette email existe déjà',
	    				'products' => $products,
						'topics' => $topics,       
						'events' => $events,
	    			));
				}

				$newsletter = $app['idiorm.db']->for_table('newsletter')->create();
    			$newsletter->mail = $mail;
    			$newsletter->save();

    			$app['session']->set('user_add_newsletter', true);

    			return $app['twig']->render('commerce/accueil.html.twig', array(
    				'success_inscription_newsletter' => 'Vous avez été inscrit avec success à la newsletter',
    				'products' => $products,
					'topics' => $topics,       
					'events' => $events,
    			));
			}
		}
	}
?>