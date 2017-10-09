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

		return $app['twig']->render('commerce/accueil.html.twig',[
			'products' => $products,
			'topics' => $topics,       
			'events' => $events,
		]);
	}

	public function shopAction($category_name,Application $app,$page = 1,$nbPerPage = 6)
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

	public function shopPageAction($category_name,Application $app,$page = 1,$nbPerPage = 6)
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

		$topic = $app['idiorm.db']->for_table('view_topics')
		->where('ID_product', $ID_product)
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

		$panierProducts[]=0;
		
		if (!empty($app['session']->get('panier')))
		{
			foreach ($app['session']->get('panier') as $key => $value)
			{
				if ($value>0)
				{
					$panierProducts[]=$key;
				}
			}
		}

		$products=$app['idiorm.db']->for_table('view_products')->where_id_in($panierProducts)->order_by_asc('name')->find_result_set();

		return $app['twig']->render('commerce/panier.html.twig',[
			'products' => $products

		]);
	}


	public function paiementAction(Application $app)
	{

		$panierProducts[]=0;
		
		if (!empty($app['session']->get('panier')))
		{
			foreach ($app['session']->get('panier') as $key => $value)
			{
				if ($value>0)
				{
					$panierProducts[]=$key;
				}
			}
		}

		$products=$app['idiorm.db']->for_table('view_products')->where_id_in($panierProducts)->order_by_asc('name')->find_result_set();

		return $app['twig']->render('commerce/paiement.html.twig',[
			'products' => $products

		]);
	}





	public function suiviAction(Application $app, $errors = "")
	{
		$token = $app['security.token_storage']->getToken();  
		
		//test d'authentification
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
		{
	  	 	//récupération de l'ID_user
			$user = $token->getUser();
			$idUser = $user->getID_user();
		}
		else
		{
			return $app->redirect('connexion');
		}

        #format index.php/business/une-formation-innovante-a-lyon_87943512.html
		$buyers = $app['idiorm.db']->for_table('orders')->raw_query('SELECT orders.*,users.pseudo FROM orders,users WHERE ID_buyer=' . $idUser . ' AND users.ID_user=ID_seller ORDER BY order_date DESC')->find_result_set();			
		$sellers = $app['idiorm.db']->for_table('orders')->raw_query('SELECT orders.*,users.pseudo FROM orders,users WHERE ID_seller=' . $idUser . ' AND users.ID_user=ID_buyer ORDER BY order_date DESC')->find_result_set();
		

		return $app['twig']->render('commerce/suivi.html.twig',[
			'buyers' => $buyers,
			'sellers' => $sellers,
			'errors' => $errors
			
		]);

	}

	public function suiviMajAction(Application $app, Request $request, $error = "")
	{

		$ID_order = $request->get('ID_order');

		$update=$app['idiorm.db']->for_table('orders')->find_one($ID_order);



		if ($request->get('seller_status'))
		{


			if ($request->get('seller_status') == 0)
			{
				$update->set(array(
					'seller_status' => 1
				));
				$update->save();
			}

			if ($request->get('seller_status') == 1 or $request->get('seller_status') == 2)
			{
				
				if(null!=($request->get('tracking_number')) && !empty($request->get('tracking_number')))
				{
					if (!preg_match('#^[a-z0-9\/ \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,50}$#i',$request->get('tracking_number')))
					{
						$errors[] = 'numéro de colis incorrect';
					}
				}
				else
				{
					$errors[] = "Veuillez indiquer un numéro de suivi";
				}

				if (!isset($errors))
				{
					$update->set(array(
						'seller_status' => 2,
						'tracking_number' => $request->get('tracking_number')
					));
					$update->save();
				}
			}
		}

		if ($request->get('buyer_status'))
		{
			if ($request->get('seller_status') == 0)
			{
				$update->set(array(
					'buyer_status' => 1
				));
				$update->save();
			}
		}


		if (!isset($errors))
		{
			$errors = "";
		}

		$idUser=1;

        #format index.php/business/une-formation-innovante-a-lyon_87943512.html
		$buyers = $app['idiorm.db']->for_table('orders')->raw_query('SELECT orders.*,users.pseudo FROM orders,users WHERE ID_buyer=' . $idUser . ' AND users.ID_user=ID_seller ORDER BY order_date DESC')->find_result_set();			
		$sellers = $app['idiorm.db']->for_table('orders')->raw_query('SELECT orders.*,users.pseudo FROM orders,users WHERE ID_seller=' . $idUser . ' AND users.ID_user=ID_buyer ORDER BY order_date DESC')->find_result_set();
		

		return $app['twig']->render('commerce/suivi.html.twig',[
			'buyers' => $buyers,
			'sellers' => $sellers,
			'errors' => $errors	
		]);



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
		$app['session']->set('total_price_by_id', $this->getTotalPriceById($app));
		$array = array(
			'total_price' => $app['session']->get('total_price'),
			'total_product' => $app['session']->get('total_product'),
			'total_product_by_id' => $app['session']->get('total_product_by_id'),
			'total_price_by_id' => $app['session']->get('total_price_by_id'),
		);

		return new Response(json_encode($array));
	}

	public function removeOneItemAction(Application $app, Request $request)
	{

		$panier = $app['session']->get('panier');
		$num_product = 0;

		if(isset($panier[$request->get('id')]))
		{
			if(($panier[$request->get('id')] - 1) == 0)
			{
				$num_product = $panier[$request->get('id')];
				unset($panier[$request->get('id')]);
			}
			else
			{
				$panier[$request->get('id')] -= 1;
			}
			$app['session']->set('panier', $panier);
		}

		$app['session']->set('total_price', $this->get_total_price($app, $request->get('id'), 'decrementation', $num_product));
		$app['session']->set('total_product', $this->getTotalProduct($app));
		$app['session']->set('total_product_by_id', $this->getTotalProductById($app));
		$app['session']->set('total_price_by_id', $this->getTotalPriceById($app));
		$array = array(
			'total_price' => $app['session']->get('total_price'),
			'total_product' => $app['session']->get('total_product'),
			'total_product_by_id' => $app['session']->get('total_product_by_id'),
			'total_price_by_id' => $app['session']->get('total_price_by_id'),			
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
			unset($panier[$request->get('id')]);
		}

		$app['session']->set('panier', $panier);

		$app['session']->set('total_price', $this->get_total_price($app, $request->get('id'), 'decrementationAll', $num_product));
		$app['session']->set('total_product', $this->getTotalProduct($app));
		$app['session']->set('total_product_by_id', $this->getTotalProductById($app));
		$app['session']->set('total_price_by_id', $this->getTotalPriceById($app));
		$array = array(
			'total_price' => $app['session']->get('total_price'),
			'total_product' => $app['session']->get('total_product'),
			'total_product_by_id' => $app['session']->get('total_product_by_id'),
			'total_price_by_id' => $app['session']->get('total_price_by_id'),	
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

	public function getTotalPriceById(Application $app)
	{
		$total_price_by_id = array();

		$panier = $app['session']->get('panier');

		if(!empty($panier))
		{
			foreach ($panier as $key => $data)
			{
				if(isset($panier[$key]))
				{
					$product = $app['idiorm.db']->for_table('products')->where('ID_product', $key)->find_result_set();
					$price = $product[0]->price;
					$shipping_charges = $product[0]->shipping_charges;	

					$total_price_by_id[$key] = round(($price + $shipping_charges) * $data);			
				}
			}  
		}

		return $total_price_by_id;
	}







	public function createCustomerAction(Application $app, Request $request)
	{
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			\Stripe\Stripe::setApiKey("sk_test_QmSww6Ib9W6e27EL24MysACJ");

			$customer = \Stripe\Customer::create(array(
				"email" => $request->get('email'),
				"source" => $request->get('token'),
			));

			if(empty($customer->failure_code))
			{
				$globalController = new GlobalController();
				$token = $app['security.token_storage']->getToken();

				$user = $token->getUser();    		
				$ID_user = $user->getID_user();

				$subject = "Street Connect - [" . $customer->id . "] Demande prise en compte";
				$from = "postmaster@localhost";
				$to = $request->get('customer_email');
				$content = "Bonjour " . $request->get('customer_name') . ", votre demande a bien été prise en compte. Nous attendons la confirmation du vendeur avant de vous de vous débiter. Cordialement.";
				
				$globalController->sendMailAction($app, $subject, $from, $to, $content);

				// -- Envoie de mail au vendeur
				$product_by_id = $app['session']->get('total_product_by_id');
				$price_by_id = $app['session']->get('total_price_by_id');

				foreach ($product_by_id as $key => $value)
				{
					$product = $app['idiorm.db']->for_table('products')->where('ID_product', $key)->find_result_set()[0];
					$seller = $app['idiorm.db']->for_table('users')->where('ID_user', $product->ID_user)->find_result_set()[0];


					$globalController = new GlobalController();

					$subject = "Street Connect - Un client demande vos articles";
					$from = "postmaster@localhost";
					$to = $seller->mail;

					$strTemp = ($value > 1) ? "s" : "";
					$content = "Bonjour " . $seller->name . ", un client vous demande " . $value . " article" . $strTemp . ", cliquez sur le lien suivant pour accepter la vente: <br/><a href='" . PUBLIC_ROOT . "suivi'>Accepter la vente</a><br/><b>Cordialement.</b>";
					
					$globalController->sendMailAction($app, $subject, $from, $to, $content);

					$order = $app['idiorm.db']->for_table('orders')->create();
					$order->ID_seller = $seller->ID_user;
					$order->ID_buyer = $ID_user;
					$order->ID_customer= $customer->id;
					$order->ID_product = $key;
					$order->name = $product->name;			
					$order->quantity = $value;
					$order->total_price = $price_by_id[$key];
					$order->seller_status = 0;
					$order->buyer_status = 0;
					$order->order_date = strtotime('now');
					$order->payment_token = $request->get('token');
					$order->save();
				}

				return 'Votre demande a bien été prise en compte';
			}
			else
			{
				$order = $app['idiorm.db']->for_table('orders')->create();
				$order->error_code = $charge->failure_code;
				$order->save();
			}

			return 'Une erreur c\'est produite, veuillez réessayer';
		}

		return $app->redirect('connexion');

	}

	public function confirmationSaleAction(Application $app, Request $request)
	{
		if($request->get('token') != undefined)
		{
			if($request->get('token') === $app['session']->get('token'))
			{
				$order_payment = $app['idiorm.db']->for_table('order')->where('ID_order', $request->get('ID_order'))->find_result_set()[0];
				$seller = $app['idiorm.db']->for_table('users')->where('ID_user', $order_payment->ID_seller)->find_result_set()[0];
				$buyer = $app['idiorm.db']->for_table('users')->where('ID_user', $order_payment->ID_buyer)->find_result_set()[0];

				$charge = \Stripe\Charge::create(array(
					"amount" => $order_payment->total_price,
					"currency" => "EUR",
					"description" => $order_payment->name,
					"statement_descriptor" => "Passé le: " . date("d/m/Y H:i:s", $order_payment->order_date),
					"customer" => $order_payment->ID_customer,
				));

				if(empty($charge->failure_code))
				{
					$globalController = new GlobalController();

					$subject = "Street Connect - [" . $order_payment->ID_order . "] Paiement effectué";
					$from = "postmaster@localhost";
					$to = $seller->mail;
					$content = "Bonjour " . $seller->name . ", le versement d'une somme de " . $order_payment->total_price . " EUR, a bien été effectué le " . date("d/m/Y H:i:s", $order_payment->order_date) .  ". Cordialement.";
					
					$globalController->sendMailAction($app, $subject, $from, $to, $content);

					$subject = "Street Connect - [" . $order_payment->ID_order . "] Paiement effectué";
					$from = "postmaster@localhost";
					$to = $seller->mail;
					$content = "Bonjour " . $buyer->name . ", le paiement d'une somme de " . $order_payment->total_price . " EUR, a bien été effectué " . date("d/m/Y H:i:s", $order_payment->order_date) .  ". Cordialement.";
					
					$globalController->sendMailAction($app, $subject, $from, $to, $content);

					return 'Le paiement a bien été effectué';
				}
				else
				{
					$order_error = $app['idiorm.db']->for_table('orders')->create();
					$order_error->error_code = $charge->failure_code;
					$order_error->save();

					return 'Une erreur c\'est produite lors du paiement';
				}
			}
		}
		
		return $app->redirect('acceuil');

	}

	public function faqAction(Application $app)
	{
		return $app['twig']->render('commerce/FAQ.html.twig');
	}


	public function aboutAction(Application $app)
	{
		return $app['twig']->render('commerce/about.html.twig');
	}


	public function shoppingCardAction(Application $app)
	{
		return $app['twig']->render('commerce/shoppingCard.html.twig');
	}

	public function connexionAction(Application $app, Request $request, $success_inscription)
	{
		$success = "";
		if($success_inscription === 'success_inscription') $success = "Vous avez été inscrit avec succès, veuillez à present vous connecter";

		return $app['twig']->render('commerce/connexion.html.twig', array(
			'error' => $app['security.last_error']($request),
			'last_username' => $app['session']->get('_security.last_username'),
			'success_inscription' => $success
		));

	}

	//affichage de la page formulaire ajout de produit ac les eventuelles données deu produit en cas de modification	

	public function newAdAction(Application $app, Request $request, $ID_product,$token){



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
				   }
				   
				   return $app['twig']->render('commerce/ajout_produit.html.twig', [
				   	'categories'  => $app['categories'],      
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

				if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {

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

					if(empty($errors) && empty($error)){



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
				    $modification = $app['idiorm.db']->for_table('products')
				    ->find_one($ID_product);

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
//gestion des produits en ventes par un utlisateur
    public function listProducts(Application $app) //penser a passer l'ID_User ac la sessions
    {

    	$token = $app['security.token_storage']->getToken();

    	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {

    		$user = $token->getUser();    		
    		$ID_user = $user->getID_user();

    		$products = $app['idiorm.db']->for_table('view_products')
	        ->where('ID_user',$ID_user)  //penser a passer l'ID_User ac la sessions
	        ->find_result_set();

	        return $app['twig']->render('commerce/list_products.html.twig',[
	        	'products' => $products,

	        ]);
	    }
	    else
	    {
	    	return $app->redirect('../inscription/erreur');
	    }
	}

	public function deleteProduct(Application $app, Request $request)
    {//supprimer un produit

    	$token1 = $app['security.token_storage']->getToken();

    	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {

    		$user = $token1->getUser();    		
    		$ID_user = $user->getID_user();

    		


	        if($request->get('token') == $app['session']->get('token'))
	        {

	        	$suppression = $app['idiorm.db']->for_table('products')
	        	->where('ID_product', $request->get('ID_product'))
	        	->find_one();


	        	if(!empty($suppression->get('image_1')))
	        	{
	        		unlink(PUBLIC_ROOT.'assets/images/'.$suppression->get('image_1'));
	        	}


	        	if(!empty($suppression->get('image_2')))
	        	{
	        		unlink(PUBLIC_ROOT.'assets/images/'.$suppression->get('image_2'));
	        	}

	        	if(!empty($suppression->get('image_3')))
	        	{
	        		unlink(PUBLIC_ROOT.'assets/images/'.$suppression->get('image_3'));
	        	}
	        	
	        	$topic = $app['idiorm.db']->for_table('topic')
	        	->where('ID_product', $request->get('ID_product'))
	        	->find_one()
	        	->set(array(
	        		'ID_product' => 0,
	        	));

	        	$topic->save();
	        	
				$suppression->delete();

	        	$success = 'Le produit a été supprimé de la liste';
	        	
	        	$products = $app['idiorm.db']->for_table('view_products')
		        ->where('ID_user',$ID_user)//penser a passer l'ID_User ac la sessions
		        ->find_result_set();

		        return $app['twig']->render('commerce/list_products.html.twig',[
		        	'success'=>$success,
		        	'products'=>$products
		        ]);
		    }
		    else
		    {
		    	return $app->redirect('../inscription/erreur');
		    }

	    }
	    else
	    {
	    	return $app->redirect('../inscription/erreur');
	    }

	}  



	public function inscriptionPostAction(Application $app, Request $request, $url_error) {

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

				$users = $app['idiorm.db']->for_table('users')
				->where('mail', $inscription['email'])
				->find_result_set();


				if(count($users) == 0)
				{
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

						$newsletter = $app['idiorm.db']->for_table('newsletter')->where('mail', $inscription['email'])->find_result_set();

						if(count($newsletter) > 0)
						{
							$inscriptionDb->newsletter = true;
						}
						
		            # Insertion en BDD
						$inscriptionDb->save();
						
		            # Redirection
						return $app->redirect("connexion/success_inscription");
				}
				else
				{

					$error = "Cette adresse mail est déjà utilisée. Veuillez en choisir une autre";
					return $app['twig']->render('commerce/inscription.html.twig', [
						'form' => $form->createView(),
						'error'=> $error
					]);
				}	
			
			
		}
			
		endif;

		if($url_error == 'erreur')
		{
			
			$url_error =  " Vous devez être connecté pour pouvoir accéder à cette page " ; 

			$error = $url_error;
        	 # Affichage du Formulaire dans la Vue
			return $app['twig']->render('commerce/inscription.html.twig', [
				'form' => $form->createView(),
				'error'=> $error
			]);
		}else
		{	
			$url_error =  "" ; 

			$error = $url_error;

        	 # Affichage du Formulaire dans la Vue
			return $app['twig']->render('commerce/inscription.html.twig', [
				'form' => $form->createView(),
				'error'=> $error
			]);
		}
	}

	public function setProfilAction(Application $app, Request $request, $token)
	{
		$token_silex = $app['security.token_storage']->getToken();
		if($token != $app['session']->get('token'))
		{
			$app->redirect($this->getRacineSite() . 'error/403');
		}

		if($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			
			$user = $token_silex->getUser();    		
			$ID_user = $user->getID_user();
		}
		else
		{
			$app->redirect('connexion');
		}

		$user = $app['idiorm.db']->for_table('users')->where('ID_user', $ID_user)->find_result_set();

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
				'value' => $user[0]->name,
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
				'value' => $user[0]->surname,
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
				'value' => $user[0]->pseudo,
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
				'value' => $user[0]->street,
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
				'value' => $user[0]->zip_code,
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
				'value' => $user[0]->city,
			)                
		])

		->add('email', TextType::class, [
			'label' => false,
			'constraints' => new Assert\Email(),
			'attr' => array('class' => 'form-control', 'placeholder' => 'Your@email.com', 'value' => $user[0]->mail)
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
				'value' => $user[0]->phone,
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
				'value' => $user[0]->society_name,
			)                
		])

		->add('avatar', FileType::class, [
			'required'      => false,
			'label'         => false,
			'attr'          => [
				'class' => 'dropify',
			]
		])

		->add('password', PasswordType::class, [
			'required'      => true,
			'label'         => false,
			'constraints'   => array(new NotBlank(
				array('message'=>'Vous devez saisir votre mot de passe')
			)
		),
			'attr'          => array(
				'class'         => 'form-control',
			)                
		])
		

		->add('submit', SubmitType::class, ['label' => 'Modifier'])
		
		->getForm();

         # Traitement des données POST
		$form->handleRequest($request);
		
        # Vérifier si le Formulaire est valid
		if($form->isValid())
		{   
            # Récupération des données du Formulaire
			$infosProfil = $form->getData();

			$infosProfil['password'] = $app['security.encoder.digest']->encodePassword($infosProfil['password'], '');

			if($infosProfil['password'] != $token_silex->getUser()->getPassword())
			{
				$error = "Mot de passe incorrecte";
			}
			else
			{
				# Récupération de l'image
				if(!empty($infosProfil['avatar']))
				{
					$image = $infosProfil['avatar'];
					$newname = $this->createFileName(10);

					$chemin = PUBLIC_ROOT.'/assets/images/avatar/';
					$image->move($chemin, $newname.'.jpg');
				}

            	# Insertion en BDD
				$infosProfilDb = $app['idiorm.db']->for_table('users')->find_one($user[0]->ID_user)->set(array(
					"name" 	   		=> $infosProfil['name'],
					"surname"  		=> $infosProfil['surname'],
					"pseudo"   		=> $infosProfil['pseudo'],
					"street"   		=> $infosProfil['street'],
					"zip_code" 		=> $infosProfil['zip_code'],
					"city"     		=> $infosProfil['city'],
					"mail"    		=> $infosProfil['email'],
					"phone"    		=> $infosProfil['phone'],
					"society_name"  => $infosProfil['society_name'],
				));


				if(!empty($infosProfil['avatar'])) $infosProfilDb = $app['idiorm.db']->for_table('users')->find_one($user[0]->ID_user)->set(array(
					"avatar" => $newname.'.jpg'
				));

				$infosProfilDb->save();
            # Modification en BDD
				
            # Redirection
				return $app->redirect($user[0]->ID_user . '/success_modification');
			}

			# Affichage du Formulaire dans la Vue
			return $app['twig']->render('commerce/profil_modification.html.twig', [
				'form' => $form->createView(),
				'error' => $error,
			]);

		}
	 	# Affichage du Formulaire dans la Vue
		return $app['twig']->render('commerce/profil_modification.html.twig', [
			'form' => $form->createView()
		]);
	}

	// ----------------------- recherche -------------------------------------

	public function searchAction(Application $app, Request $request)
	{
		$array = array(
			'for_table' => array(
				'products' => array(
					'name',
					'brand',
					'description',
				),
				'event' => array(
					'event_title',
					'event_description',
				),			
				'topic' => array(
					'title',
				),
				'post' => array(
					'content',
				),
			),
			'search_text' => $request->get('searchString'),
		);

		if($array['search_text'])
		{
			$result = array();

			$search_text = preg_replace("#[,\./\\;]#", " ", $array['search_text']);

			$search_text_array = explode(' ', $search_text);

			if($array['for_table'])
			{
				if(gettype($array['for_table']) == 'array')
				{
					foreach ($array['for_table'] as $table_key => $table_value)
					{
						if(gettype($array['for_table'][$table_key]) == 'array')
						{
							foreach ($array['for_table'][$table_key] as $field_key => $field_value)
							{
								$tmp_result = $app['idiorm.db']->for_table($table_key)->where_like($field_value, '%' . $array['search_text'] . '%')->find_result_set();
								if(count($tmp_result) > 0)
								{
									foreach ($tmp_result as $key => $result_tmp)
									{
										$result[] = $tmp_result[$key]->$field_value . $this->optionsResult($app, $table_key, $tmp_result[$key]);
									}
								}

								$text = '';
								foreach ($search_text_array as $text_value)
								{
									if($text_value != ' ')
									{
										if(mb_strlen($text_value) <= 3 || strpos($text_value, "'"))
										{
											if(mb_strlen($text) > 0)
											{
												$text .= ' ' . $text_value;
											}
											else
											{
												$text .= $text_value;
											}
										}
										elseif(mb_strlen($text_value) > 3)
										{
											$tmp_result = $app['idiorm.db']->for_table($table_key)->where_like($field_value, '%' . $text_value . '%')->find_result_set();
											if(count($tmp_result) > 0)
											{
												foreach ($tmp_result as $key => $result_tmp)
												{
													$result[] = $tmp_result[$key]->$field_value . $this->optionsResult($app, $table_key, $tmp_result[$key]);
												}
											}
											if(mb_strlen($text) > 0)
											{
												$tmp_result = $app['idiorm.db']->for_table($table_key)->where_like($field_value, '%' . $text . ' ' . $text_value . '%')->find_result_set();
												if(count($tmp_result) > 0)
												{
													foreach ($tmp_result as $key => $result_tmp)
													{
														$result[] = $tmp_result[$key]->$field_value . $this->optionsResult($app, $table_key, $tmp_result[$key]);
													}
												}
											}
										}
									}
								}
							}
						}
						else
						{
							$tmp_result = $app['idiorm.db']->for_table($table_key)->where_like($field_value, '%' . $array['search_text'] . '%')->find_result_set();
							if(count($tmp_result) > 0)
							{
								foreach ($tmp_result as $key => $result_tmp)
								{
									$result[] = $tmp_result[$key]->$field_value . $this->optionsResult($app, $table_key, $tmp_result[$key]);
								}
							}

							$text = '';

							foreach ($search_text_array as $text_value)
							{
								if($text_value != ' ')
								{
									if(mb_strlen($text_value) <= 3 || strpos($text_value, "'"))
									{
										if(mb_strlen($text) > 0)
										{
											$text .= ' ' . $text_value;
										}
										else
										{
											$text .= $text_value;
										}
									}
									elseif(mb_strlen($text_value) > 3)
									{
										$tmp_result = $app['idiorm.db']->for_table($table_key)->where_like($field_value, '%' . $text_value . '%')->find_result_set();
										if(count($tmp_result) > 0)
										{
											foreach ($tmp_result as $key => $result_tmp)
											{
												$result[] = $tmp_result[$key]->$field_value . $this->optionsResult($app, $table_key, $tmp_result[$key]);
											}
										}

										if(mb_strlen($text) > 0)
										{
											$tmp_result = $app['idiorm.db']->for_table($table_key)->where_like($field_value, '%' . $text . ' ' . $text_value . '%')->find_result_set();
											if(count($tmp_result) > 0)
											{
												foreach ($tmp_result as $key => $result_tmp)
												{
													$result[] = $tmp_result[$key]->$field_value . $this->optionsResult($app, $table_key, $tmp_result[$key]);
												}
											}
										}
									}
								}
							}
						}
					}
				}
				else
				{
					$result[] = 'le champs < for_table > n\'a pas été renseigné sous forme tableau';
				}
			}
			else
			{
				$result[] = 'le tableau < for_table > n\'a pas été renseigné dans les données de recherche';
			}
		}
		else
		{
			$result[] = 'le champs < search_text > n\'a pas été renseigné dans les données de recherche';
		}

		if(count($result) == 0)
		{
			$resultset[] = "";
			$nbResultats = 0;
		}
		else
		{
			$resultset = $this->uniqueArray($result);
			$nbResultats = count($resultset);
		}

		return $app['twig']->render('public/searchresults.html.twig',[
			'results' => $resultset,
			'nbResultats' => $nbResultats,
			'searchText' => $array['search_text'],
		]);
	}

	public function optionsResult(Application $app, $table, $args)
	{
		$separate_string = "(&é'(-è_çà)=$^*ù:!;,)";
		$url = $separate_string;
		// CATEGORY NAME
		switch ($table)
		{
			case 'event':
			$url .= "Evénement";
			break;
			case 'post':
			$url .= "Post";
			break;
			case 'topic':
			$url .= "Topic";
			break;
			case 'products':
			$category = $app['idiorm.db']->for_table('category')->where('ID_category', $args->ID_category)->find_result_set();
			$url .= 'Shop - ' . $category[0]->category_name;
			break;
			default:
			'titre non trouvé';
			break;
		}
		// CONSTRUCTION DU LIEN
		$url .= $separate_string;
		$url .= '<a href="';
		switch($table)
		{
			case 'event':
			$url .= $this->getRacineSite() . "agenda/all/" . $this->generateSlug($args->event_title) . "_" . $args->ID_event . ".html";
			break;
			case 'post':
			$topic = $app['idiorm.db']->for_table('topic')->where('ID_topic', $args->ID_topic)->find_result_set();
			if(isset($topic->ID_topic))
			{
				$url .= $this->getRacineSite() . "forum/topic/" . $this->generateSlug($topic->title) . "_" . $topic->ID_topic . ".html";
			}
			break;
			case 'topic':
			$url .= $this->getRacineSite() . "forum/topic/" . $this->generateSlug($args->title) . "_" . $args->ID_topic . ".html";
			break;
			case 'products':
			$url .= $this->getRacineSite() . "all/" . $this->generateSlug($args->name) . "_" . $args->ID_product . ".html";
			break;
			default:
			'lien non crée';
			break;
		}
		$url .= '">Voir ce résultat</a>';

		return $url;
	}

	public function uniqueArray($array, $aleatory_string = '', $lev = 0)
	{
		if($lev == 0)
		{
			$string = "*ù%£$!:;./§?,&é'(-è_çà)='";
			for ($i = 0; $i < 5; $i++)
			{
				$aleatory_string .= $string[mt_rand(0,mb_strlen($string) - 1)];
			}
		}


		$array_result = array();
		$tmp_text = '';
		if(is_Array($array))
		{
			if(count($array) > 0)
			{
				foreach ($array as $key => $value)
				{
					if(is_Array($value))
					{
						$tmp_text .= $this->uniqueArray($value, $aleatory_string, $lev + 1);
					}
					else
					{
						$tmp_text .= $value . $aleatory_string;
					}
				}
			}
		}

		if($lev == 0)
		{
			$array_result = explode($aleatory_string, $tmp_text);
			unset($array_result[count($array_result) - 1]);
			$array_result = array_unique($array_result);
			return $array_result;
		}
		return $tmp_text;
	}

	public function profilAction(Application $app, $ID_user, $success_modification)
	{
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			$userProfil = $app['idiorm.db']->for_table('users')
			->find_one($ID_user);

			$topic = $app['idiorm.db']->for_table('topic')
			->where('ID_user',$ID_user)
			->order_by_desc('creation_date')
			->limit(1)
			->find_one();

			$event = $app['idiorm.db']->for_table('event')
			->where('ID_user',$ID_user)
			->order_by_desc('creation_date')
			->limit(1)
			->find_one();

			return $app['twig']->render('commerce/profil.html.twig',[
				'userProfil'			=>$userProfil,
				'success_modification'	=>$success_modification,
				'topic'					=>$topic,
				'event'					=>$event
			]);			
		}
		else
		{
			return $app->redirect('connexion');
		}
	}
}
?>