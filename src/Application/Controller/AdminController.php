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