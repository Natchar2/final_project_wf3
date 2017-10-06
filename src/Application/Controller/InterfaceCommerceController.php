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
			'events' => $events,
		]);

	}

	public function categorieAction($category_name,Application $app,$page = 1,$nbPerPage = 6)
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

	public function categoriePageAction($category_name,Application $app,$page = 1,$nbPerPage = 6)
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




	public function suiviAction(Application $app, $errors = "")
	{
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
			if(($panier[$request->get('id')] - 1) == 0)
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
			unset($panier[$request->get('id')]);
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


public function createCustomerAction(Application $app, Request $request)
	{
		\Stripe\Stripe::setApiKey("sk_test_QmSww6Ib9W6e27EL24MysACJ");

		$customer = \Stripe\Customer::create(array(
			"email" => $request->get('email'),
			"source" => $request->get('token'),
		));

		if(empty($customer->failure_code))
		{
			$globalController = new GlobalController();

			$subject = "Street Connect - [" . $customer->id . "] Demande prise en compte";
			$from = "postmaster@localhost";
			$to = $request->get('customer_email');
			$content = "Bonjour " . $request->get('customer_name') . ", votre demande a bien été prise en compte. Nous attendons la confirmation du vendeur avant de vous de vous débiter. Cordialement.";
			
			$globalController->sendMailAction($app, $subject, $from, $to, $content);

			// -- Envoie de mail au vendeur
			$product_by_id = $app['session']->get('total_product_by_id');

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
//---------------------------------------------------------------ATTENTION, ecrire la session des que fonctionnelle ------------------------------
				$order->ID_buyer = 1;
				$order->ID_customer= $customer->id;
				$order->ID_product = $key;
				$order->name = $product->name;			
				$order->quantity = $value;
//---------------------------------------------------------------ATTENTION, ecrire la session des que fonctionnelle ------------------------------
				$order->total_price = 112;
				$order->seller_status = 0;
				$order->buyer_status = 0;
				$order->order_date = strtotime('now');
				$order->payment_token = $request->get('token');
				$order->save();
			}
			// ----------------------------

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

  public function connexionAction(Application $app, Request $request)
	{
		return $app['twig']->render('commerce/connexion.html.twig', array(
			'error' => $app['security.last_error']($request),
			'last_username' => $app['session']->get('_security.last_username'),
		));

	}

 
	
  public function newAdAction(Application $app, $ID_product){
    if($ID_product>0)// affichage des données pour un article a modifier dans le formulaire de ajout_produit.html
    {
            //appel de base pour afficher les données pour retrouver l'article a modifier
        $modification = $app['idiorm.db']->for_table('products')
        ->find_one($ID_product);

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
}






    public function newAdPostAction(Application $app, Request $request)
    {
    	//gestion du formulaire d'ajout de produit  sur ajout_produit.html

    	if($app['session']->get('token') == $request->get('token'))
    	{
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

	    				//ON persi
		            $product->save();
		            $last_insert_id = $product->id();

					$success = "Votre produit a bien été ajouté et un topic a été créer sur le sujet";

					//connexion a la bdd pour l'insertion automatique d'un topic en cas d'ajout de produit
					$topic = $app['idiorm.db']->for_table('topic')->create();

					$topic->title 	 	  = $request->get('name');
					$topic->ID_category   = $request->get('category');
					$topic->ID_product 	  = $last_insert_id;
					$topic->creation_date = strtotime('now');

					$topic->save();

	       }
	       
		       return $app['twig']->render('commerce/ajout_produit.html.twig',[
		        'success'     => $success,
		        'errors'      => [] ,
		        'error'       => [] ,
		        'categories'  => $app['categories'],
		        'modification'=> $modification,
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
   	 	 $app->get('/redirect', function() use ($app) {
	    return $app->redirect($app["url_generator"]->generate("inscription_post"));
			});
   	 }



    }

    public function listProducts(Application $app, $ID_user=2) //penser a passer l'ID_User ac la sessions
    {
    //gestion des produits en ventes par un utlisateur

        $products = $app['idiorm.db']->for_table('view_products')
        ->where('ID_user',2)  //penser a passer l'ID_User ac la sessions
        ->find_result_set();

        return $app['twig']->render('commerce/list_products.html.twig',[
            'products' => $products,

        ]);
    }

    public function deleteProduct(Application $app, Request $request)//ID USER !!!
    {//supprimer un produit


        $products = $app['idiorm.db']->for_table('view_products')
        ->where('ID_user',2)//penser a passer l'ID_User ac la sessions
        ->find_result_set();


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
			$suppression->delete();

			$topic = $app['idiorm.db']->for_table('topic')
    			->where('ID_product', $request->get('ID_product'))
    			->find_one()
    			->set(array(
					'ID_product' => 0,
				));

            $success = 'Le produit a été supprimé de la liste';


           
        }
        else
        {
           $success = 'Vous ne pouvez supprimé un élément sans être connecté';
           
        }

         return $app['twig']->render('commerce/list_products.html.twig',[
                'success'=>$success,
                'products'=>$products
            ]);

		
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