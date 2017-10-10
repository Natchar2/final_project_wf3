<?php
	namespace Application\Controller;

	use Silex\Application;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Validator\Constraints\NotBlank;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\FileType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response; 
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
	use Application\Traits\Shortcut;
	use Application\Model\Verifications;
	use Symfony\Component\Validator\Constraints as Assert;

	class AdminController
	{
	    public function accueilAdminAction(Application $app)
	    {
	    	//if($this->is_Admin())
	    	return $app['twig']->render('admin/accueilAdmin.html.twig');
	    }



//--------------------------- Produits --------------------------------------


    public function listProductsAdminAction(Application $app)
    //penser a passer l'ID_User ac la sessions
    {

    	$token = $app['security.token_storage']->getToken();

    	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {

    		//$user = $token->getUser();    		
    		//$ID_user = $user->getID_user();

    		$products = $app['idiorm.db']->for_table('view_products')
	        ->find_result_set();

	        return $app['twig']->render('admin/list_products_admin.html.twig',[
	        	'products' => $products,

	        ]);
	    }
	    else
	    {
	    	return "mes couilles à ski";
	    	//return $app->redirect('../inscription/erreur');
	    }
	}


	public function addProductAdminAction(Application $app, Request $request, $ID_product,$token){

		//recupération du token de session
		$token1 = $app['security.token_storage']->getToken();  

			//test d'authentification
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
		{
	  	 	//récupération de l'ID_user
			$user = $token1->getUser();
			$ID_user = $user->getID_user();


		    if($ID_product>0)// affichage des données pour un article a modifier dans le formulaire de ajout_produit.html
		    { 
			   	if($app['session']->get('token') == $request->get('token'))
			   	{
			        //appel de base pour afficher les données pour retrouver l'article a modifier
			   		$modification = $app['idiorm.db']->for_table('products')
			   		->find_one($ID_product);

					$userCreator = $app['idiorm.db']->for_table('users')
				    ->find_one($modification->ID_user); 
			   	}
			   	else
			   	{
			   		return $app->redirect('../inscription/erreur');
			   	}	
		    }
		    else
		    {
			   	$ID_product = 0;
			   	$modification ='';
			   	$userCreator = '';
		    }
		   
		   return $app['twig']->render('admin/add_product.html.twig', [
		   	'categories'  => $app['categories'],      
			'userCreator' => $userCreator,	  
		   	'error'       => [] ,
		   	'errors'      => [],
		   	'modification'=> $modification,
		   	'ID_product'  => $ID_product,
		   ]);

		}else
		{
			return $app->redirect('../inscription/erreur');
		}
	}




	public function newAdPostAction(Application $app, Request $request)
	{
    	//gestion du formulaire d'ajout de produit  sur ajout_produit.html

			$token1 = $app['security.token_storage']->getToken();

			if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
			{
				$user = $token1->getUser();    		
				$ID_user = $user->getID_user();

    		    //utilisation de la fonction de vérification dans Model\Vérifications
				$Verifications = new Verifications;

				$verifs =  $Verifications->VerificationNewAd($request, $app);
	         
	         	//Retour des variables de VerificationNewAd
				$errors         = $verifs['errors'];
				$error          = $verifs['error'];
				$finalFileName1 = $verifs['finalFileName1'];
				$finalFileName2 = $verifs['finalFileName2'];
				$finalFileName3 = $verifs['finalFileName3'];

				$ID_product = $request->get('ID_product');

				if(empty($errors) && empty($error))
				{

	                //SI c'est une modification d'article :
					if($request->get('ID_product')>0)
					{
						if($app['session']->get('token') == $request->get('token'))
						{
							$modification = $app['idiorm.db']->for_table('products')
							->find_one($request->get('ID_product'))
							->set(array(
								'name'             => $request->get('name'),
								'brand'            => $request->get('brand'),
								'price'            => $request->get('price'),
								'description'      => $request->get('description'),
								'image_1'          => $finalFileName1,
								'image_2'          => $finalFileName2,
								'image_3'          => $finalFileName3,
								'ID_category'      => $request->get('category'),
								'shipping_charges' => $request->get('shipping_charges'),
							));

							$modification->save();

							$success = "Votre produit a bien été ajouté ";

							//connexion a la bdd pour l'insertion automatique d'un topic en cas d'ajout de produit
							$topic = $app['idiorm.db']->for_table('topic')
							->where($request->get('ID_product'))
							->find_one()
							->set(array(
								'title' 	 => $request->get('name'),
								'ID_category'=> $request->get('category'),
							));

							$topic->save();
							
							$success = "Votre produit a bien été modifié et le topic sur le sujet également";
						}
						else
						{
							return $app->redirect('../inscription/erreur');
						}
					}
					else
					{
	    				//Connexion à la bdd
						$product = $app['idiorm.db']->for_table('products')->create();

	    				//Affectation des valeurs
						$product->name             = $request->get('name');
						$product->brand            = $request->get('brand');
						$product->price            = $request->get('price');
						$product->description      = $request->get('description');
						$product->image_1          = $finalFileName1;
						$product->image_2          = $finalFileName2;
						$product->image_3          = $finalFileName3;
						$product->ID_category      = $request->get('category');
						$product->creation_date    = strtotime('now');
						$product->ID_user		   = $ID_user;


	    				//Affectation d'une valeur par défaut à zéro si il n'y en a pas eu dans le formulaire
						if((float)$request->get('shipping_charges') == 0.0)
						{
							$product->shipping_charges = 0.0;
						}
						else
						{
							$product->shipping_charges  = $request->get('shipping_charges');
						}

	    				//ON persiste
						$product->save();
						$last_insert_id = $product->id();

						$success = "Votre produit a bien été ajouté et un topic a été créer sur le sujet";

						//connexion a la bdd pour l'insertion automatique d'un topic en cas d'ajout de produit
						$topic = $app['idiorm.db']->for_table('topic')->create();

						$topic->title 	 	  = $request->get('name');
						$topic->ID_category   = $request->get('category');
						$topic->ID_product 	  = $last_insert_id;
						$topic->creation_date = strtotime('now');
						$topic->ID_user		  = $ID_user;	

						$topic->save();
					}

					return $app['twig']->render('commerce/ajout_produit.html.twig',[
						'success'     => $success,
						'errors'      => [] ,
						'error'       => [] ,
						'categories'  => $app['categories'],
						'modification'=> '',
					]);

				}
				else
				{
		    		if($ID_product>0)// affichage des données pour un article a modifier dans le formulaire de ajout_produit.html
			    	{//meme en cas d'erreur
			            //appel de base pour afficher les données pour retrouver l'article a modifier
			    		$modification = $app['idiorm.db']->for_table('products')->find_one($ID_product);
					}
			else
			{
				$ID_product = 0;
				$modification ='';
			}
			return $app['twig']->render('commerce/ajout_produit.html.twig',[
				'errors'      => $errors,
				'error'       => $error,
				'categories'  => $app['categories'],
				'modification'=> $modification,
				'ID_product'  => $request->get('ID_product'),

			]);

		}


		}
		else
		{
			return $app->redirect('../inscription/erreur');
		}

	}








//--------------------------- Agenda --------------------------------------

   public function listEventsAdminAction(Application $app)
    {

    	//recupération du token de session
	  	$token = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	{
	  	 	//récupération de l'ID_user
	    	//$user = $token->getUser();
	    	//$ID_user = $user->getID_user();

			//gestion des événement d'un utlisateur

	        $events = $app['idiorm.db']->for_table('view_events')
	        ->find_result_set();

	        return $app['twig']->render('admin/list_events_admin.html.twig',[
	            'events' => $events,
	        ]);

		}
	   	 else
		{
			return "mes couilles à ski";
			//return $app->redirect('../inscription/erreur');
		} 	
	}

	//affichage du formulaire ac eventuellement les données en base pour une modif
	public function addEventAdminAction(Application $app, $ID_event,$token)
	{
		//recupération du token de session
	  	$token1 = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	    {
    		
	    	$user = $token1->getUser();
	    	//$ID_user = $user->getID_user();

			if($ID_event>0)// affichage des données pour un article a modifier dans le formulaire de ajout_produit.html
			{
			    if($app['session']->get('token') == $token)
				{    
				//appel de base pour afficher les données pour retrouver l'article a modifier
				    $modification = $app['idiorm.db']->for_table('event')
				    ->find_one($ID_event);

					$userCreator = $app['idiorm.db']->for_table('users')
				    ->find_one($modification->ID_user); 
			   	}
			   	else
				{
					return $app->redirect($this->getRacineSite().'inscription/erreur');
				}
			}
			else
			{
				$ID_event = 0;
			    $modification ='';
			    $userCreator = '';
			}

			return $app['twig']->render('admin/add_event.html.twig', [
			    'categories'  => $app['categories'],      
			    'userCreator' => $userCreator, 
			    'error'       => [] ,
			    'errors'      => [],
			    'modification'=> $modification,
			    'ID_event'    => $ID_event,
			]);

		}
		else
		{
		 	  return $app->redirect($this->getRacineSite().'inscription/erreur');
		}
	}
	

	public function addEventPostAdminAction(Application $app, Request $request)
    {
    	
    		//recupération du token de session
	  	$token1 = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	{
	  	 	//récupération de l'ID_user
	    	$user = $token1->getUser();
	    	//$ID_user = $user->getID_user();

				//gestion du formulaire d'ajout de produit  sur add_event.html

		    	if($app['session']->get('token') == $request->get('token'))
		    	{
		    		//utilisation de la fonction de vérification dans Model\Vérifications
			        $Verifications = new Verifications;

			        $verifs =  $Verifications->VerificationNewEvent($request, $app);

       
			         //Retour des variables de VerificationNewAd
			        $errors         = $verifs['errors'];
			        $error          = $verifs['error'];
			        $finalFileName1 = $verifs['finalFileName1'];
			       
			        $ID_event = $request->get('ID_event');

			        if(empty($errors) && empty($error))
			        {
			          
			            //SI c'est une modification d'article :
			            if($request->get('ID_event')>0)
			            {
				            $modification = $app['idiorm.db']->for_table('event')
				            ->find_one($request->get('ID_event'))
				            ->set(array(

				                'event_title'   		=> $request->get('event_title'),
				                'start_date'    		=> strtotime($request->get('start_date')),
								'end_date'      	=> strtotime($request->get('end_date')),
				                'event_description'   => $request->get('event_description'),
				                'image'         		=> $finalFileName1,
								'ID_category'   		=> $request->get('category'),
				                'street_name'   		=> $request->get('street_name'),
				                'zip_code' 	  		=> $request->get('zip_code'),
				                'city' 		  		=> $request->get('city'),
				                'url_1' 		  		=> $request->get('url_1'),
				                'url_2' 		  		=> $request->get('url_2'),
				                'url_3' 		  		=> $request->get('url_3'),
				                'mail' 		  		=> $request->get('mail'),
				                'phone' 		  		=> $request->get('phone'),
				                'latitude'	  		=> $request->get('latitude'),
				                'longitude'	  		=> $request->get('longitude'),
				            ));

				            $modification->save();

							$success = "Votre événement a bien été ajouté ";

							//connexion a la bdd pour l'insertion automatique d'un topic en cas d'ajout de produit
							$topic = $app['idiorm.db']->for_table('topic')
							->where($request->get('ID_event'))
							->find_one()
							->set(array(

								'title' 	 => $request->get('event_title'),
								'ID_category'=> $request->get('category'),
							));

							$topic->save();
									
				            $success = "Votre événement a bien été modifié et le topic sur le sujet également";
				        }
				        else
				        {

			    			//Connexion à la bdd
				           	$event = $app['idiorm.db']->for_table('event')->create();


			    			//Affectation des valeurs
							$event->event_title   		= $request->get('event_title');
							$event->start_date    		= strtotime($request->get('start_date'));
							if(!empty($request->get('end_date'))){
								$event->end_date      		= strtotime($request->get('end_date'));
							}

							$event->creation_date 		= strtotime('now');
							$event->event_description   = $request->get('event_description');
							$event->image        		= $finalFileName1;
							$event->ID_category   		= $request->get('category');
							$event->street_name   		= $request->get('street_name');
							$event->zip_code 	  		= $request->get('zip_code');
							$event->city		  		= $request->get('city');
							$event->url_1 		  		= $request->get('url_1');
							$event->url_2 		  		= $request->get('url_2');
							$event->url_3 		  		= $request->get('url_3');
							$event->mail 		  		= $request->get('mail');
							$event->phone 		  		= $request->get('phone');
							$event->latitude	  		= $request->get('latitude');
							$event->longitude	  		= $request->get('longitude');
							$event->ID_user	  			= $ID_user;

			    			

			    				//ON persiste
				            $event->save();
				            $last_insert_id = $event->id();

							$success = "Votre événement a bien été ajouté et un topic a été créer sur le sujet";

							//connexion a la bdd pour l'insertion automatique d'un topic en cas d'ajout de produit
							$topic = $app['idiorm.db']->for_table('topic')->create();

							$topic->title 	 	  = $request->get('event_title');
							$topic->ID_category   = $request->get('category');
							$topic->ID_event 	  = $last_insert_id;
							$topic->creation_date = strtotime('now');
							$topic->ID_user 	  = $ID_user;

							$topic->save();

			       		}
			       
					    return $app['twig']->render('agenda/add_event.html.twig',[
					        'success'     => $success,
					        'errors'      => [] ,
					        'error'       => [] ,
					        'categories'  => $app['categories'],
					        'modification'=> '',
					   	]);
				    }
				    else
				    {
				    	if($ID_event>0)// affichage des données pour un evenement a modifier dans le formulaire de add_event.html
					    {//meme en cas d'erreur
					            //appel de base pour afficher les données pour retrouver l'evenement a modifier
					        $modification = $app['idiorm.db']->for_table('event')
					        ->find_one($ID_event);
					        $userCreator = $app['idiorm.db']->for_table('users')
						    ->find_one($modification->ID_user); 
					    }
					    else
					    {
					    	$ID_event = 0;
					        $modification ='';
					        $userCreator = '';
					    }
					    
					    return $app['twig']->render('agenda/add_event.html.twig',[
					        'errors'      => $errors,
					        'error'       => $error,
					        'categories'  => $app['categories'],
					        'userCreator' => $userCreator,
					        'modification'=> $modification,
					        'ID_event'    => $request->get('ID_event'),
					    ]);
					}
				}
				else
				{
					return $app->redirect($this->getRacineSite().'inscription/erreur');
				}
		}
		else
		{
			return $app->redirect($this->getRacineSite().'inscription/erreur');
			
		} 

    }





//--------------------------- Forum --------------------------------------



   	//gestion des topics mis en ligne par un utlisateur
    public function listTopicsAdminAction(Application $app) //penser a passer l'ID_User ac la sessions
    {

		//test d'authentification
	   	$token = $app['security.token_storage']->getToken();

  		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
    	
    		//$user = $token->getUser();    		
    		//$ID_user = $user->getID_user();

	    	 $topics = $app['idiorm.db']->for_table('view_topics')
	        ->find_result_set();

	        return $app['twig']->render('admin/list_topics_admin.html.twig',[
	            'topics' => $topics,
	        ]);
	    }
	   	else
		{
			return "mes couilles à ski";
			//return $app->redirect('../inscription/erreur');
		} 	

  
	}


    public function deleteTopicAdminAction(Application $app, Request $request)
    {//supprimer un produit
    	$token1 = $app['security.token_storage']->getToken();

  		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
    	
    		$user = $token1->getUser();    		
    		$ID_user = $user->getID_user();


	        if($request->get('token') == $app['session']->get('token'))
	        {

	        	$suppression = $app['idiorm.db']->for_table('topic')
	    			->where('ID_topic', $request->get('ID_topic'))
	    			->find_one();

	    		$posts_suppression = $app['idiorm.db']->for_table('post')
	    		->where('ID_topic', $request->get('ID_topic'))
	    		->find_result_set();

	    		$posts_suppression->delete();

				$suppression->delete();

				
	            $success = 'Le topic a été supprimé de la liste ainsi que tt les posts correspondants';

	             $topics = $app['idiorm.db']->for_table('view_topics')
		        ->where('ID_user',$ID_user)  
		        ->find_result_set();

		        
		         return $app['twig']->render('admin/list_topic_admin.html.twig',[
		                'success'=>$success,			                
		                'topics'=>$topics
		            ]);

			}
			else
		   	{
		   	 	
			    return $app->redirect($this->getRacineSite().'inscription/erreur');

		   	}
		}
		else
	   	{
	   	 	
		    return $app->redirect($this->getRacineSite().'inscription/erreur');

	   	}           

	}



	//affichage de la page pour l'ajout de topic : new_topic.html.twig
	public function addTopicAdminAction(Application $app)
	{
		//gestion du formulaire d'ajout de produit  sur add_event.html

    	$token = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	 {

	  	 	//récupération de l'ID_user
	    	$user = $token->getUser();
	    	$ID_user = $user->getID_user();

    		return $app['twig']->render('admin/add_topic.html.twig',[
    			'categories'=> $app['categories']
			]);
		}
		else
		{
			return $app->redirect($this->getRacineSite().'inscription/erreur');
			
		}
		
	}

	//Gestion du formulaire de création du topic
	public function addTopicPostAdminAction(Application $app, Request $request)
	{
		
    	$token = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	 {	
	  	 	$errors = [] ;

	  	 	//récupération de l'ID_user
	    	$user = $token->getUser();
	    	$ID_user = $user->getID_user();

		//gestion du formulaire d'ajout de produit  sur add_event.html

    		if($app['session']->get('token') == $request->get('token'))
	        {//utilisation de la fonction de vérification dans Model\Vérifications
		        $Verifications = new Verifications;

		        $verifs =  $Verifications->VerificationNewTopic($request, $app);

		        $errors = $verifs['errors'];

		        		//Connexion à la bdd
		            $topic = $app['idiorm.db']->for_table('topic')->create();


		    				//Affectation des valeurs
		            $topic->title            = $request->get('title');
		            $topic->ID_category      = $request->get('category');
		 			$topic->creation_date    = strtotime('now');
		 			$topic->ID_user			 = $ID_user;
		 			
		 			$topic->save();

		           $success = "Votre topic a bien été ajouté";
			

				return $app['twig']->render('forum/new_topic.html.twig',[
			        'errors'    => $errors,
			        'categories'=> $app['categories'],
			        'success'   => $success
	        		   ]);
	        
	   		}
	   		else
	   		{
	   			return $app->redirect($this->getRacineSite().'inscription/erreur');
   			 
	   		}
		}
		else
		{
			return $app->redirect($this->getRacineSite().'inscription/erreur');
			
		}

			
	}
















//--------------------------- Users --------------------------------------



   	//gestion des topics mis en ligne par un utlisateur
    public function listUsersAdminAction(Application $app) //penser a passer l'ID_User ac la sessions
    {

		//test d'authentification
	   	$token = $app['security.token_storage']->getToken();

  		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
    	
    		//$user = $token->getUser();    		
    		//$ID_user = $user->getID_user();

	    	 $users = $app['idiorm.db']->for_table('users')
	        ->find_result_set();

	        return $app['twig']->render('admin/list_users_admin.html.twig',[
	            'users' => $users,

	        ]);
	    }
	   	else
		{
			return "mes couilles à ski";
			//return $app->redirect('../inscription/erreur');
		} 	

  
	}











}


?>