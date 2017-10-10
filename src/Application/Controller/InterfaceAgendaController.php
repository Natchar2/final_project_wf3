<?php
namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Application\Traits\Shortcut;
use Application\Model\Verifications;

class InterfaceAgendaController
{

	use Shortcut;

	
	public function agendaAction($category_name,Application $app,$page = 1,$nbPerPage = 6)
	{
		$offset=(($page-1)*$nbPerPage);

		if ($category_name == "all")
		{
			$totalEvents=$app['idiorm.db']->for_table('view_events')->find_result_set();
			$totalEvents=count($totalEvents);
			$events=$app['idiorm.db']->for_table('view_events')->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}
		else
		{
			$totalEvents=$app['idiorm.db']->for_table('view_events')->where('category_name', $category_name)->find_result_set();
			$totalEvents=count($totalEvents);
			$events=$app['idiorm.db']->for_table('view_events')->where('category_name', $category_name)->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}   


		$top = $app['idiorm.db']->for_table('event')->where('ontop', 1)->find_one();       


		return $app['twig']->render('agenda/agenda.html.twig',[
			'totalEvents' => $totalEvents,       
			'events' => $events,
			'top' => $top,
			'page' => $page,       
			'nbPerPage' => $nbPerPage
		]);
	}

	public function agendaPageAction($category_name,Application $app,$page = 1,$nbPerPage = 6)
	{
		$offset=(($page-1)*$nbPerPage);

		if ($category_name == "all")
		{
			$totalEvents=$app['idiorm.db']->for_table('view_events')->find_result_set();
			$totalEvents=count($totalEvents);
			$events=$app['idiorm.db']->for_table('view_events')->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}
		else
		{
			$totalEvents=$app['idiorm.db']->for_table('view_events')->where('category_name', $category_name)->find_result_set();
			$totalEvents=count($totalEvents);
			$events=$app['idiorm.db']->for_table('view_events')->where('category_name', $category_name)->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}          



		return $app['twig']->render('agenda/agenda.html.twig',[
			'totalEvents' => $totalEvents,       
			'events' => $events,
			'page' => $page,       
			'nbPerPage' => $nbPerPage
		]);
	}

	public function agendaArticleAction($category_name,$slugevent,$ID_event,Application $app)
	{
        #format index.php/business/une-formation-innovante-a-lyon_87943512.html
		$event = $app['idiorm.db']->for_table('view_events')->find_one($ID_event);
		$suggests = $app['idiorm.db']->for_table('view_events')->raw_query('SELECT * FROM view_events WHERE ID_category=' . $event->ID_category . ' AND ID_event<>' . $ID_event . ' ORDER BY RAND() LIMIT 3 ')->find_result_set();   

		$topic = $app['idiorm.db']->for_table('view_topics')
		->where('ID_event', $ID_event)
		->find_one();
		
		if (isset($topic) AND !empty($topic))
		{
			$posts = $app['idiorm.db']->for_table('view_posts')
			->where('ID_topic', $topic['ID_topic'])
			->order_by_desc('post_date')
			->limit(5)
			->find_result_set();
		}
		else
		{
			$posts = null;
		}


		return $app['twig']->render('agenda/event.html.twig',[
			'event' => $event,
			'suggests' => $suggests,
			'posts' => $posts
		]);

	}

    #génération du menu dans le layout
	public function menuAgenda($active, Application $app)
	{

			$categories = $app['idiorm.db']->for_table('category')->find_result_set();
			return $app['twig']->render('menu-agenda.html.twig',[
				'active' => $active,
				'categories' => $categories
			]);
		
		
		
	}
//affichage du formulaire ac eventuellement les données en base pour une modif
	public function addEventAction(Application $app, $ID_event,$token)
	{
	

			//recupération du token de session
	  	$token1 = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	 {

	  	 	
	    			  	 	//récupération de l'ID_user
		    	$user = $token1->getUser();
		    	$ID_user = $user->getID_user();

				if($ID_event>0)// affichage des données pour un article a modifier dans le formulaire de ajout_produit.html
					{
					     if($app['session']->get('token') == $token)
						{    
						//appel de base pour afficher les données pour retrouver l'article a modifier
						    $modification = $app['idiorm.db']->for_table('event')
						    ->find_one($ID_event);
					   	}else
						{
							return $app->redirect($this->getRacineSite().'inscription/erreur');
						}

					}
					else
					{
						$ID_event = 0;
					    $modification ='';
					}

					return $app['twig']->render('agenda/add_event.html.twig', [
					    
					    'categories'  => $app['categories'],      
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

//ajout d'evenement  et modification ac la création automatique d'un evenement
	public function agendaAddEventPostAction(Application $app, Request $request)
    {
    	
    		//recupération du token de session
	  	$token1 = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	{
	  	 	//récupération de l'ID_user
	    	$user = $token1->getUser();
	    	$ID_user = $user->getID_user();

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
				              ->find_one($request->get('ID_event'));

								if(!empty($request->get('end_date'))){
									$date = implode("-",  array_reverse(explode("/", $request->get('end_date'))));

                            		$end_date = strtotime($date);
								}
								else
								{
									$end_date = null;
								}

				              $modification->set(array(

				                  'event_title'   		=> $request->get('event_title'),
				                  'start_date'    		=> strtotime(implode("-",  array_reverse(explode("/", $request->get('end_date'))))),
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
				                  'end_date'	  		=> $end_date,
				                 
				              ));


				              	$ID_event = $request->get('ID_event');

					            $modification->save();

								$success = "Votre événement a bien été ajouté ";

								//connexion a la bdd pour l'insertion automatique d'un topic en cas d'ajout de produit
								$topic = $app['idiorm.db']->for_table('topic')->where('ID_event', (int)$request->get('ID_event'))->find_result_set();
								if (count($topic)>0)
					        	{
					        		$topic->set(array(
									'title' 	 => $request->get('event_title'),
									'ID_category'=> $request->get('category'),
									));

									$topic->save();
								}
								
									
				              $success = "Votre événement a bien été modifié et le topic sur le sujet également";

				                $events = $app['idiorm.db']->for_table('view_events')
						        ->where('ID_user', $ID_user)  //penser a passer l'ID_User ac la sessions
						        ->find_result_set();

						            return $app['twig']->render('agenda/list_events.html.twig',[
							        'success'     => $success,
							        'categories'  => $app['categories'],
							        'events'	  => $events
							   		]);
				          }
				          else
				          {

			    				//Connexion à la bdd
				            $event = $app['idiorm.db']->for_table('event')->create();


				    				//Affectation des valeurs
								$event->event_title   		= $request->get('event_title');
								$event->start_date    		= strtotime(implode("-",  array_reverse(explode("/", $request->get('start_date')))));
								$event->creation_date 		= strtotime('now');
								$event->event_description   = $request->get('event_description');
								$event->image        		= $finalFileName1;
								$event->start_date    		= strtotime($request->get('start_date'));
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
								$event->status 		        = 1;
								if(!empty($request->get('end_date'))){
									$date = implode("-",  array_reverse(explode("/", $request->get('end_date'))));

                            		$end_date = strtotime($date);
								}
								else
								{
									$event->end_date     	= null;
								}
			    			

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
					        'ID_event'	  => $ID_event
					   		]);

				    }
				    else
				    {
				    	if($ID_event>0)// affichage des données pour un evenement a modifier dans le formulaire de add_event.html
					    {//meme en cas d'erreur
					            //appel de base pour afficher les données pour retrouver l'evenement a modifier
					        $modification = $app['idiorm.db']->for_table('event')
					        ->find_one($ID_event);

					    }
					    else
					    {
					    	$ID_event = 0;
					        $modification ='';
					    }
					     return $app['twig']->render('agenda/add_event.html.twig',[
					        'errors'      => $errors,
					        'error'       => $error,
					        'categories'  => $app['categories'],
					        'modification'=> $modification,
					       'ID_event'	  => $ID_event				        

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

    public function listEvents(Application $app)
    {

    		//recupération du token de session
	  	$token = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	 {
	  	 	//récupération de l'ID_user
	    	$user = $token->getUser();
	    	$ID_user = $user->getID_user();

			//gestion des événement d'un utlisateur

	        $events = $app['idiorm.db']->for_table('view_events')
	        ->where('ID_user', $ID_user)  //penser a passer l'ID_User ac la sessions
	        ->find_result_set();

	        return $app['twig']->render('agenda/list_events.html.twig',[
	            'events' => $events,

	        ]);


		}
	   	 else
		{
		return $app->redirect($this->getRacineSite().'inscription/erreur');

		} 	
    
    }

    public function deleteEvent(Application $app, Request $request)
    {//supprimer un produit


	    		//recupération du token de session
		  	$token1 = $app['security.token_storage']->getToken();  
			
			//test d'authentification
		  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
		  	 {
		  	 	//récupération de l'ID_user
		    	$user = $token1->getUser();
		    	$ID_user = $user->getID_user();

				

		        if($request->get('token') == $app['session']->get('token'))
		        {

		        	$suppression = $app['idiorm.db']->for_table('event')
		    			->where('ID_event', $request->get('ID_event'))
		    			->find_one();


					if(!empty($suppression->get('image')))
					{
						unlink(PUBLIC_ROOT.'assets/images/'.$suppression->get('image'));
					}

					

					$topic = $app['idiorm.db']->for_table('topic')->where('ID_event', (int)$request->get('ID_event'))->find_result_set();
					if (count($topic)>0)
		        	{
		        		$topic->set(array(
		        		'ID_event' => null,
		        	));

						$topic->save();
					}
		    			
					$suppression->delete();

		            $success = 'L\'événement a été supprimé de la liste';

		           $events = $app['idiorm.db']->for_table('view_events')
		           ->where('ID_user',$ID_user)
			       ->find_result_set();


		         return $app['twig']->render('agenda/list_events.html.twig',[
		                'success'=>$success,
		                'events'=>$events
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


}


?>