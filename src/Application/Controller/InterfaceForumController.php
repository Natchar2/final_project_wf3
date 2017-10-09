<?php 

namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Application\Traits\Shortcut;
use Application\Model\Verifications;

class InterfaceForumController
{

	use Shortcut;


//Afichage de la page d'accueil du forum + pagination
	 public function accueilForumAction($category_name,Application $app,$page = 1,$nbPerPage = 5)
    {

    	$offset=(($page-1)*$nbPerPage);

		if ($category_name == "all")
		{
			$totalTopics=$app['idiorm.db']->for_table('view_accueil_forum')->find_result_set();
			$totalTopics=count($totalTopics);
			$topics=$app['idiorm.db']->for_table('view_accueil_forum')->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}
		else
		{
			$totalTopics=$app['idiorm.db']->for_table('view_accueil_forum')->where('category_name', $category_name)->find_result_set();
			$totalTopics=count($totalTopics);
			$topics=$app['idiorm.db']->for_table('view_accueil_forum')->where('category_name', $category_name)->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}          


		return $app['twig']->render('forum/forumIndex.html.twig',[
			'totalTopics' => $totalTopics,       
			'topics' 	  => $topics,
			'page'		  => $page,       
			'nbPerPage'   => $nbPerPage
		]);

   	}

//Affichage de la page accueil du forum + pagination en fonction des catégories
   	 public function accueilForumPageAction($category_name,Application $app,$page = 1,$nbPerPage = 5)
    {
    	$nbPerPage = 5;

    	$offset=(($page-1)*$nbPerPage);

		if ($category_name == "all")
		{
			$totalTopics=$app['idiorm.db']->for_table('view_accueil_forum')->find_result_set();
			$totalTopics=count($totalTopics);
			$topics=$app['idiorm.db']->for_table('view_accueil_forum')->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}
		else
		{
			$totalTopics=$app['idiorm.db']->for_table('view_accueil_forum')->where('category_name', $category_name)->find_result_set();
			$totalTopics=count($totalTopics);
			$topics=$app['idiorm.db']->for_table('view_accueil_forum')->where('category_name', $category_name)->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}          


		return $app['twig']->render('forum/forumIndex.html.twig',[
			'totalTopics' => $totalTopics,       
			'topics' 	  => $topics,
			'page'		  => $page,       
			'nbPerPage'   => $nbPerPage
		]);

   	}

   	#génération du menu dans le layout
	public function menuForum($active, Application $app)
	{
		$categories = $app['idiorm.db']->for_table('category')
		->find_result_set();

		return $app['twig']->render('menu-forum.html.twig',[
			'active' => $active,
			'categories' => $categories
		]);
	}

//page topic avec un sujet les 5 dernier posts
	public function topicAction(Application $app, $slugTopic,$ID_topic)
	{
		if($ID_topic>0)
  	  	{
            //appel de base pour afficher les données pour retrouver le topic a modifier
			$topics = $app['idiorm.db']->for_table('view_topics')
			->where('ID_topic', $ID_topic)
			->find_one();



			//appel de la page pour tranche de 5 posts
			$posts = $app['idiorm.db']->for_table('view_posts')
			->where('ID_topic',$ID_topic)
			->order_by_desc('post_date')
	 		->limit(5)
			->find_result_set();

			return $app['twig']->render('forum/topic.html.twig', [
		        'categories'  => $app['categories'],      
		        'errors'      => [],
		        'topics'	  => $topics,
		        'posts'		  => $posts,
	  	  ]);
		}
		else
		{
			$success = "Il n'y a pas encore de topic sur ce sujet";

			return $app['twig']->render('forum/topic.html.twig', [
		        'categories'  => $app['categories'],      
		        'errors'      => [],
		        'topics'	  => $topics,
		        'posts'		  => $posts,
		        'success'	  => $success, 
	  	  ]);
		}

	}

//affichage de 5  poste en plus sur la page topic.html
	public function addPostTopicAction(Application $app, Request $request)
	{
		$number_post = $request->get('number_post');
		$ID_topic = $request->get('ID_topic');

		$posts = $app['idiorm.db']->for_table('view_posts')
		->where('ID_topic', $ID_topic)
		->order_by_desc('post_date')
		->limit(5)
		->offset($number_post)
		->find_array();

		return json_encode($posts);
	}

//ajout de commentaire sur la page topic.html
	public function newPostAction(Application $app, Request $request, $slugTopic ,$ID_topic)
	{

		//gestion du formulaire d'ajout de produit  sur add_event.html

    	$token1 = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	 {

	  	 	//récupération de l'ID_user
	    	$user = $token1->getUser();
	    	$ID_user = $user->getID_user();

    		if($app['session']->get('token') == $request->get('token'))
			{//utilisation de Vérification dans Model\Vérifications
				$Verifications = new Verifications;
	
				$verifs = $Verifications->VerificationNewPost($request, $app);
	
				//retour des variables de VerificationNewPost
	
				$errors = $verifs['errors'];
	
				if(empty($errors))
				{
					//Connexion  a la bdd
					$post = $app['idiorm.db']->for_table('post')->create();
	
					//Afectation des valeurs
					$post->content   = $request->get('content');
					$post->ID_topic  = filter_var($request->get('ID_topic'), FILTER_VALIDATE_INT);
					$post->ID_user   = $request->get('ID_user');
					$post->post_date = strtotime('now');
					$post->ID_user   = $ID_user;
	
					$post->save();
	
					if($ID_topic>0)
					  	  	{
					            //appel de base pour afficher les données pour retrouver l'article a modifier
								$topics = $app['idiorm.db']->for_table('view_topics')
								->where('ID_topic',$ID_topic)
								->find_one();
	
	
	
								//appel de la page pour tranche de 5 posts
								$posts = $app['idiorm.db']->for_table('view_posts')
								->where('ID_topic',$ID_topic)
								->order_by_desc('post_date')
						 		->limit(5)
								->find_result_set();
	
								
							}
							
					$success = "Votre commentaire a bien été ajouté";
	
					return $app['twig']->render('forum/topic.html.twig',[
						'success'     => $success,
		    			'errors'      => [] ,
		    			'categories'  => $app['categories'],
		    			'topics'	  => $topics,
				        'posts'		  => $posts,
					]);
				}
				else
				{
	
					return $app['twig']->render('forum/topic.html.twig',[
					'errors'    => $errors,
					'categories'=> $app['categories'],
					'topics'	=> $topics,
				    'posts'		=> $posts,
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
 

 //affichage de la page pour l'ajout de topic : new_topic.html.twig
	public function newTopicAction(Application $app)
	{
		//gestion du formulaire d'ajout de produit  sur add_event.html

    	$token = $app['security.token_storage']->getToken();  
		
		//test d'authentification
	  	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
	  	 {

	  	 	//récupération de l'ID_user
	    	$user = $token->getUser();
	    	$ID_user = $user->getID_user();

    		return $app['twig']->render('forum/new_topic.html.twig',[
			'categories'=> $app['categories']
			]);
		}
		else
		{
			return $app->redirect($this->getRacineSite().'inscription/erreur');
			
		}
		
	}

//Gestion du formulaire de création du topic
	public function newTopicPostAction(Application $app, Request $request)
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

	//gestion des topics mis en ligne par un utlisateur
    public function listTopics(Application $app) //penser a passer l'ID_User ac la sessions
    {

    	$token = $app['security.token_storage']->getToken();

  		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
    	
    		$user = $token->getUser();    		
    		$ID_user = $user->getID_user();

	    	 $topics = $app['idiorm.db']->for_table('view_topics')
	        ->where('ID_user',$ID_user)  
	        ->find_result_set();

	        return $app['twig']->render('forum/list_topic.html.twig',[
	            'topics' => $topics,

	        ]);
		}
		else
	   	{
	   	 	  return $app->redirect($this->getRacineSite().'inscription/erreur');
	   	}
    

       
    }

    public function deleteTopic(Application $app, Request $request)
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

			        
			         return $app['twig']->render('forum/list_topic.html.twig',[
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
}
 ?>