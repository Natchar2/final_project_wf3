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

class InterfaceCommerceController
{

	use Shortcut;

	public function accueilAction(Application $app)
	{
		$products=$app['idiorm.db']->for_table('view_products')->order_by_desc('creation_date')->limit(6)->find_result_set();
		$topics=$app['idiorm.db']->for_table('view_topics')->order_by_desc('creation_date')->limit(6)->find_result_set();
		$events=$app['idiorm.db']->for_table('view_events')->order_by_desc('creation_date')->limit(3)->find_result_set();

		// $t = 	$app['security.encoder.digest']
		// ->encodePassword('test', '');  

		// print_r($t);
		// die();

		return $app['twig']->render('commerce/accueil.html.twig',[
			'products' => $products,
			'topics' => $topics,       
			'events' => $events
		]);

	}

	public function categorieAction($category_name,Application $app,$page = 1,$nbPerPage = 2)
	{
		$offset=(($page-1)*$nbPerPage);

		if ($category_name == "all")
		{
			$totalProducts=$app['idiorm.db']->for_table('view_products')->find_result_set();
			$totalProducts=count($totalProducts);
			$products=$app['idiorm.db']->for_table('view_products')->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}
		else
		{
			$totalProducts=$app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->find_result_set();
			$totalProducts=count($totalProducts);
			$products=$app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}          


		return $app['twig']->render('commerce/shop.html.twig',[
			'totalProducts' => $totalProducts,       
			'products' => $products,
			'page' => $page,       
			'nbPerPage' => $nbPerPage
		]);
	}

	public function categoriePageAction($category_name,Application $app,$page = 1,$nbPerPage = 2)
	{
		$offset=(($page-1)*$nbPerPage);

		if ($category_name == "all")
		{
			$totalProducts=$app['idiorm.db']->for_table('view_products')->find_result_set();
			$totalProducts=count($totalProducts);
			$products=$app['idiorm.db']->for_table('view_products')->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}
		else
		{
			$totalProducts=$app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->find_result_set();
			$totalProducts=count($totalProducts);
			$products=$app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->order_by_desc('creation_date')->limit($nbPerPage)->offset($offset)->find_result_set();
		}          



		return $app['twig']->render('commerce/shop.html.twig',[
			'totalProducts' => $totalProducts,       
			'products' => $products,
			'page' => $page,       
			'nbPerPage' => $nbPerPage
		]);
	}

	public function articleAction($category_name,$slugproduct,$ID_product,Application $app)
	{
        #format index.php/business/une-formation-innovante-a-lyon_87943512.html
		$product = $app['idiorm.db']->for_table('view_products')->find_one($ID_product);
		$suggests = $app['idiorm.db']->for_table('view_products')->raw_query('SELECT * FROM view_products WHERE ID_category=' . $product->ID_category . ' AND ID_product<>' . $ID_product . ' ORDER BY RAND() LIMIT 3 ')->find_result_set();   

		$topic = $app['idiorm.db']->for_table('view_topics')->where('ID_product', $ID_product)->find_one();

		if (isset($topic) AND !empty($topic))
		{
			$posts = $app['idiorm.db']->for_table('view_topics')->where('ID_topic', $topic['ID_topic'])->find_result_set();
		}
		else
		{
			$posts = null;
		}


		return $app['twig']->render('commerce/item.html.twig',[
			'product' => $product,
			'suggests' => $suggests,
			'posts' => $posts
		]);

	}


    #génération du menu dans le layout
	public function menu($active, Application $app)
	{
		$categories = $app['idiorm.db']->for_table('category')->find_result_set();
		return $app['twig']->render('menu.html.twig',[
			'active' => $active,
		]);
	}


    #génération du menu dans le layout
	public function menuShop($active, Application $app)
	{
		$categories = $app['idiorm.db']->for_table('category')->find_result_set();
		return $app['twig']->render('menu-shop.html.twig',[
			'active' => $active,
			'categories' => $categories
		]);
	}










	public function panierAction(Application $app)
	{
		return $app['twig']->render('commerce/panier.html.twig');
	}

	public function faqAction(Application $app)
	{
		return $app['twig']->render('commerce/FAQ.html.twig');
	}

	public function addItemAction(Application $app, Request $request)
	{

		$panier = $app['session']->get('panier');
		if($panier)
		{
			if(isset($panier[$request->get('id')]))
			{
				$panier[$request->get('id')] += 1;
			}
			else
			{
				$panier[$request->get('id')] = 1;  
			}
			$app['session']->set('panier',$panier);
		}
		else
		{
			$app['session']->set('panier', array($request->get('id') => 1));
			$panier = $app['session']->get('panier');
		}

		$app['session']->set('total_price', $this->get_total_price($app, $request->get('id'), 'incrementation'));
		$app['session']->set('total_product', $this->getTotalProduct($app));
		$app['session']->set('total_product_by_id', $this->getTotalProductById($app));

		$array = array(
			'total_price' => $app['session']->get('total_price'),
			'total_product' => $app['session']->get('total_product'),
			'total_product_by_id' => $app['session']->get('total_product_by_id'),
		);

		return new Response(json_encode($array));
	}

	public function removeOneItemAction(Application $app, Request $request)
	{

		$panier = $app['session']->get('panier');

		if(isset($panier[$request->get('id')]))
		{
			if($panier[$request->get('id')] == 0)
			{
				unset($panier[$request->get('id')]);
			}
			else
			{
				$panier[$request->get('id')] -= 1;
			}

			$app['session']->set('panier', $panier);
		}

		$app['session']->set('total_price', $this->get_total_price($app, $request->get('id'), 'decrementation'));
		$app['session']->set('total_product', $this->getTotalProduct($app));
		$app['session']->set('total_product_by_id', $this->getTotalProductById($app));

		$array = array(
			'total_price' => $app['session']->get('total_price'),
			'total_product' => $app['session']->get('total_product'),
			'total_product_by_id' => $app['session']->get('total_product_by_id'),
		);

		return new Response(json_encode($array)); 
	}

	public function removeAllItemAction(Application $app, Request $request)
	{
		
		$panier = $app['session']->get('panier');
		$num_product = 0;

		if(isset($panier[$request->get('id')]))
		{
			$num_product = $panier[$request->get('id')];

			if($panier[$request->get('id')] == 0)
			{
				unset($panier[$request->get('id')]);
			}
			else
			{
				$panier[$request->get('id')] = 0;  
			}
		}

		$app['session']->set('panier', $panier);

		$app['session']->set('total_price', $this->get_total_price($app, $request->get('id'), 'decrementationAll', $num_product));
		$app['session']->set('total_product', $this->getTotalProduct($app));
		$app['session']->set('total_product_by_id', $this->getTotalProductById($app));

		$array = array(
			'total_price' => $app['session']->get('total_price'),
			'total_product' => $app['session']->get('total_product'),
			'total_product_by_id' => $app['session']->get('total_product_by_id'),
		);

		return new Response(json_encode($array));
	}

	public function getTotalProduct(Application $app)
	{
		$total_product = 0;

		$panier = $app['session']->get('panier');

		if(!empty($panier))
		{
			foreach ($panier as $data)
			{
				$total_product += $data;
			}
		}

		return $total_product;
	}

	public function getTotalProductById(Application $app)
	{
		$total_product_by_id = array();

		$panier = $app['session']->get('panier');

		if(!empty($panier))
		{
			$n = 0;
			foreach ($panier as $key => $data)
			{
				if(isset($panier[$key]))
				{
					$total_product_by_id[$key] = $data;
					$n++;
				}
			}  
		}

		return $total_product_by_id;
	}

	public function get_total_price(Application $app, $id, $mode, $num_product = 0)
	{
		$total_price = $app['session']->get('total_price');

		if(!$total_price)
		{
			$total_price = 0;
		}

		if(isset($app['session']->get('panier')[$id]) && $app['session']->get('panier')[$id] >= 0)
		{
			$product = $app['idiorm.db']->for_table('products')->where('ID_product', $id)->find_result_set();
			$price = $product[0]->price;
			$shipping_charges = $product[0]->shipping_charges;

			if($mode == 'incrementation')
			{
				$total_price += round($price + $shipping_charges);
			}
			elseif($mode == 'decrementation')
			{
				$total_price -= round($price + $shipping_charges);
				if($total_price < 0)
				{
					$total_price = 0;
				}
			}
			else
			{
				for ($i = $app['session']->get('panier')[$id]; $i > 0; $i--)
				{
					$total_price -= round($price + $shipping_charges);
					if($total_price <= 0)
					{
						$total_price = 0;
					}
				}
			}
		}

		if($num_product > 0)
		{
			$product = $app['idiorm.db']->for_table('products')->where('ID_product', $id)->find_result_set();
			$price = $product[0]->price;
			$shipping_charges = $product[0]->shipping_charges;

			for ($i = $num_product; $i > 0; $i--)
			{
				$total_price -= round($price + $shipping_charges);
				if($total_price <= 0)
				{
					$total_price = 0;
				}
			}
		}

		if($total_price < 0)
		{
			$total_price = 0;
		}

		return round($total_price);
	}

	public function aboutAction(Application $app)
	{
		return $app['twig']->render('commerce/about.html.twig');
	}

	public function forumAjoutPostAction(Application $app)
	{
		return $app['twig']->render('commerce/forumAjoutPost.html.twig');
	}

	public function forumIndexAction(Application $app)
	{
		return $app['twig']->render('commerce/forumIndex.html.twig');
	}

	public function forumPostDetailAction(Application $app)
	{
		return $app['twig']->render('commerce/forumPostDetail.html.twig');
	}

	public function itemAction(Application $app)
	{
		return $app['twig']->render('commerce/item.html.twig');
	}

	public function shopAction(Application $app)
	{
		return $app['twig']->render('commerce/shop.html.twig');
	}

	public function shoppingCardAction(Application $app)
	{
		return $app['twig']->render('commerce/shoppingCard.html.twig');
	}

	public function connexionAction(Application $app, Request $request)
	{
		return $app['twig']->render('commerce/connexion.html.twig', array(
			'error' => $app['security.last_error']($request),
			'last_username' => $app['session']->get('_security.last_username'),
		));

	}




	// public function connexionPostAction(Application $app, Request $request){

	// 	if($request->get('email') !=null && !empty($request->get('email'))){

	// 		if (!filter_var(htmlspecialchars($request->get('email')),FILTER_VALIDATE_EMAIL)) {

	// 			$error[] = 'Identification invalide';

	// 		}

	// 	}else{

	// 		$error[] = 'Veuillez remplir le champ email';

	// 	}



	// 	if($request->get('password') !=null && !empty($request->get('password'))){

	// 		if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,50}$#i',$request->get('password'))) {

	// 			$errors[] = 'Identification invalide';
	// 		}
	// 	}else{

	// 		$errors[] = "Veuillez remplir le champ mot de passe";

	// 	}

	// 	if(!isset($errors)){
	// 		$user = $app['idiorm.db']->for_table('users')->where('mail', $request['email'])->find_one();
	// 		if(!empty($user))
	// 		{
	// 			if(!password_verify($request->get('password'), $user->get('password'))){
	// 				$request
	// 			};
	// 		}
	// 	}else
	// 	{
	// 		$errors[] = 'erreur d\'authentification';
	// 	}


	// 	return array(
	// 		'errors'  => $errors,
	// 	);

	// }




	// public function inscriptionAction(Application $app)
	// {
	// 	return $app['twig']->render('commerce/inscription.html.twig');
	// }

	// public function inscriptionPostAction(Application $app, Request $request)
	// {
	// 	//utilisation de la fonction de vérification dans Model\Vérifications
	// 	$Verifications = new Verifications;

	// 	$verifs =  $Verifications->VerificationInscription($request, $app);


	// 	//Retour des variables de VerificationNewAd
	// 	$errors         = $verifs['errors'];
	// 	$error          = $verifs['error'];
	// 	$finalFileName1 = $verifs['finalFileName1'];

	// 	if(empty($errors) && empty($error))
	// 	{
	// 		$user = $app['idiorm.db']->for_table('users')->create();

 //            $user->name = $request->get('name');
 //            $user->surname = $request->get('surname');
 //            $user->password = $app['security.encoder.digest']->encodePassword($request->get('password'), '');
 //            $user->pseudo = $request->get('pseudo');
 //            $user->street = $request->get('street');
 //            $user->zip_code = $request->get('zip_code');
 //            $user->city = $request->get('city');
 //            $user->mail = $request->get('mail');
 //            $user->phone = $request->get('phone');
 //            $user->society_name = $request->get('society_name');
 //            $user->avatar = $finalFileName1;

 //            $user->save();

 //            return $app['twig']->render('commerce/connexion.html.twig',[
	// 			'success'    => 'Vous êtes bien inscrit',
	// 		]);
	// 	}
	// 	else
	// 	{
	// 		return $app['twig']->render('commerce/inscription.html.twig',[
	// 			'errors'    => $errors,
	// 			'error'     => $error,
	// 		]);
	// 	}
	// }

	public function agendaAction(Application $app)
	{
		return $app['twig']->render('commerce/agenda.html.twig');
	}

	public function newAdAction(Application $app, $ID_product){


		if($ID_product>0)
		{
			//appel de base pour afficher les données pour retrouver l'article a modifier
			$modification = $app['idiorm.db']->for_table('products')
			->find_one($ID_product);

		}
		else
		{

			$modification ='';
		}

		return $app['twig']->render('commerce/ajout_produit.html.twig', [
			'categories'      => $app['categories'],      
			'error'       => [] ,
			'errors'      => [],
			'modification'=> $modification,
		]);
	}


	public function newAdPostAction(Application $app, Request $request){


		//utilisation de la fonction de vérification dans Model\Vérifications
		$Verifications = new Verifications;

		$verifs =  $Verifications->VerificationNewAd($request, $app);


		//Retour des variables de VerificationNewAd
		$errors         = $verifs['errors'];
		$error          = $verifs['error'];
		$finalFileName1 = $verifs['finalFileName1'];
		$finalFileName2 = $verifs['finalFileName2'];
		$finalFileName3 = $verifs['finalFileName3'];


		if(empty($errors) && empty($error))
		{
			//SI c'est une modification d'article :
			if($request->get('ID_product')!=null)
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
					'shipping_charges' => $request->get('shipping_charges')
				));

				$modification->save();

				$success = "Votre produit a bien été modifié";
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

				//Affectation d'une valeur par défaut à zéro si il n'y en a pas eu dans le formulaire
				if((float)$request->get('shipping_charges') == 0.0)
				{

					$product->shipping_charges = 0.0;

				}
				else
				{

					$product->shipping_charges  = $request->get('shipping_charges');
				}

				//ON persiste en bdd
				$product->save();

				$success = "Votre produit a bien été ajouté";
			}

			return $app['twig']->render('commerce/ajout_produit.html.twig',[
				'success'    => $success,
				'errors'     => [] ,
				'error'      => [] ,
				'categories' => $app['categories'],
				'modification'=> $modification,
			]);

		}
		else
		{

			return $app['twig']->render('commerce/ajout_produit.html.twig',[
				'errors'    => $errors,
				'error'     => $error,
				'categories'=> $app['categories']
			]);

		}

	}


	public function listProducts(Application $app, $ID_user)
	{

		$products = $app['idiorm.db']->for_table('view_products')
		->where('ID_USER')
		->find_result_set();

		return $app['twig']->render('commerce/list_products.html.twig',[
			'products' => $products
		]);

	}

	public function deleteProduct(Application $app, $ID_product, $token)
	{

		if($token == $app['session']->get('token')[0])
		{

			$success =  'Uiiiiiiiiiiiiiiiiiii';
			$app->redirect('/listProducts');

		}
		else
		{
			$success = 'nooooooooooooon';
			$app->redirect('/listProducts');
		}

		/* $suppression = $app['idiorm.db']->for_table('products')
		->where('ID_product', $ID_product)
		->find_one();

		$suppression->delete();

		return $app['twig']->render('commerce/list_products.html.twig',[
		'products' => $products,
		'delete'   => $delete
	]);*/
}  

 public function inscriptionPostAction(Application $app, Request $request) {
        # Création du Formulaire permettant l'ajout d'un User
        # use Symfony\Component\Form\Extension\Core\Type\FormType;
        $form = $app['form.factory']->createBuilder(FormType::class)
        
        ->add('name', TextType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir un nom')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('surname', TextType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir un prénom')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('pseudo', TextType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir un pseudo')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('street', TextType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir une adresse')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('zip_code', TextType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir un code postal')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('city', TextType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir une ville')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('email', TextType::class, [
            'constraints' => new Assert\Email(),
            'attr' => array('class' => 'form-control', 'placeholder' => 'Your@email.com')
        ])
        
        ->add('phone', TextType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir un numéro de téléphone')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('society_name', TextType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir un nom de société')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('avatar', FileType::class, [
            
            'required'      => false,
            'label'         => false,
            'attr'          => [
                'class' => 'dropify'
            ]                
        ])

        ->add('password', PasswordType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir un mot de passe')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])

        ->add('passwordVerif', PasswordType::class, [
            'required'      => true,
            'label'         => false,
            'constraints'   => array(new NotBlank(
                    array('message'=>'Vous devez saisir un mot de passe')
                )
            ),
            'attr'          => array(
                'class'         => 'form-control',
            )                
        ])
        

        ->add('submit', SubmitType::class, ['label' => 'Publier'])
        
        ->getForm();
        
        # Traitement des données POST
        $form->handleRequest($request);
        
        # Vérifier si le Formulaire est valid
        if($form->isValid()) :
            
            # Récupération des données du Formulaire
            $inscription = $form->getData();

			if($inscription['password'] != $inscription['passwordVerif'])
			{
				$error = "Les mots de passe ne sont pas identiques";
				return $app['twig']->render('commerce/inscription.html.twig', [
            'form' => $form->createView(),
            'error'=> $error
        		]);
			}
			else{
				# Récupération de l'image
            if(!empty($inscription['avatar'])){

            $image = $inscription['avatar'];
            $newname = $this->createFileName(10);

            $chemin = PUBLIC_ROOT.'/assets/images/avatar/';
            $image->move($chemin, $newname.'.jpg');

        	}

            # Insertion en BDD
            $inscriptionDb = $app['idiorm.db']->for_table('users')->create();
            
            #On associe les colonnes de notre BDD avec les valeurs du Formulaire
            #Colonne mySQL                         #Valeurs du Formulaire
            $inscriptionDb->name            =   $inscription['name'];
            $inscriptionDb->surname         =   $inscription['surname'];
            $inscriptionDb->pseudo          =   $inscription['pseudo'];
            $inscriptionDb->street          =   $inscription['street'];
            $inscriptionDb->zip_code        =   $inscription['zip_code'];
            $inscriptionDb->city            =   $inscription['city'];
            $inscriptionDb->mail            =   $inscription['email'];
            $inscriptionDb->phone           =   $inscription['phone'];
            $inscriptionDb->society_name    =   $inscription['society_name'];
            if(!empty($inscription['avatar']))
            $inscriptionDb->avatar          =	$newname.'.jpg';   
            $inscriptionDb->password 		= 	$app['security.encoder.digest']->encodePassword($inscription['password'], '');
            $inscriptionDb->creation_date	=	strtotime("now");
            
            # Insertion en BDD
            $inscriptionDb->save();
            
            # Redirection
            return $app->redirect( $app['url_generator'] ->generate('connexion', [ 

            ]));
			}
		
		
            
        
        endif;
        
        # Affichage du Formulaire dans la Vue
        return $app['twig']->render('commerce/inscription.html.twig', [
            'form' => $form->createView()
        ]);
}
}

?>