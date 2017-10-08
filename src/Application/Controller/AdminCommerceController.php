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

	    	//gestion des topics mis en ligne par un utlisateur
	    public function listTopics(Application $app) //penser a passer l'ID_User ac la sessions
	    {

	    	$token = $app['security.token_storage']->getToken();

	  		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
	    	
	    		$user = $token->getUser();    		
	    		$ID_user = $user->getID_user();
	    		$role = $user->getRoles();

	    		if($role == 'ROLE_ADMIN')
	    		{

			    	 $topics = $app['idiorm.db']->for_table('view_topics')
			        ->find_result_set();

			        return $app['twig']->render('adminforum/list_topic.html.twig',[
			            'topics' => $topics,

			        ]);
		   		 }
		   		 else
		  	 	{
		   	 		return $app->redirect('/localhost/final_project_wf3/web/inscription/erreur');
		  	 	}
			}
			else
		   	{
		   	 	  return $app->redirect('/localhost/final_project_wf3/web/inscription/erreur');
		   	}
    

       
   		 }

   		  public function deleteTopic(Application $app, Request $request)
    {//supprimer un produit


	    	$token1 = $app['security.token_storage']->getToken();

	  		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
	    	
	    		$user = $token1->getUser();    		
	    		$ID_user = $user->getID_user();
	    		$role = $user->getRoles();

	    		if($role == 'ROLE_ADMIN')
	    		{

	    		 $topic = $app['idiorm.db']->for_table('view_topics')
		        ->where('ID_user',$ID_user)//penser a passer l'ID_User ac la sessions
		        ->find_result_set();


			        if($request->get('token') == $app['session']->get('token'))
			        {

			        	$suppression = $app['idiorm.db']->for_table('topic')
			    			->where('ID_topic', $request->get('ID_topic'))
			    			->find_one();

						$suppression->delete();

						
			            $success = 'Le topic a été supprimé de la liste';

			             $topics = $app['idiorm.db']->for_table('view_topics')
				        ->where('ID_user',$ID_user)  
				        ->find_result_set();

				    }
				     else
			        {
			           $success = 'Vous ne pouvez supprimé un élément sans être connecté';
			           
			        }
		           
		        }
		        else
		        {
		           $success = 'Vous ne pouvez supprimé un élément sans être connecté';
		           
		        }

		         return $app['twig']->render('forum/list_topic.html.twig',[
		                'success'=>$success,
		                'topic'=>$topic,
		                'topics'=>$topics
		            ]);

			
			}
			else
		   	{
		   	 	
			    return $app->redirect('/localhost/final_project_wf3/web/inscription/erreur');

		   	}
            

	}

	}
	

?>