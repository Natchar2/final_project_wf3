<?php
namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Application\Traits\Shortcut;
use Application\Model\Verifications;

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

	public function connexionAction(Application $app)
	{
		return $app['twig']->render('commerce/connexion.html.twig');
	}

	public function inscriptionAction(Application $app)
	{
		return $app['twig']->render('commerce/inscription.html.twig');
	}

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
}


?>