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

	 public function accueilForumAction(Application $app)
    {

    	return $app['twig']->render('forum/forumIndex.html.twig');
   	}

	public function topicAction(Application $app, $ID_topic=2)
	{
		if($ID_topic>0)
  	  	{
            //appel de base pour afficher les données pour retrouver l'article a modifier
			$topics = $app['idiorm.db']->for_table('view_topics')
			->find_one($ID_topic);
			$posts = $app['idiorm.db']->for_table('post')
			->where('ID_topic', $ID_topic)
			->find_result_set();

			return $app['twig']->render('forum/topic.html.twig', [
		        'categories'  => $app['categories'],      
		        'error'       => [] ,
		        'errors'      => [],
		        'topics'=> $topics,
	  	  ]);
		}
		else
		{

			$app->redirect('forum/accueil');
		}

	}
	public function newPostTopicAction(Application $app, Request $request)
	{

		//utilisation de Vérification dans Model\Vérifications
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

			$post->save();

			$success = "Votre commentaire a bien été ajouté";

			return $app['twig']->render('forum/topic.html',[
				'success'     => $success,
    			'errors'      => [] ,
    			'categories'  => $app['categories'],
			]);
		}
		else
		{

			return $app['twig']->render('forum/topic.html.twig',[
			'errors'    => $errors,
			'categories'=> $app['categories']
			]);

		}



	}

}
 ?>