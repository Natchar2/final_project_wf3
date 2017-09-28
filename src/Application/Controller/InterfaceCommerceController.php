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
    	return $app['twig']->render('commerce/accueil.html.twig');
    }

    public function panierAction(Application $app)
    {
    	return $app['twig']->render('commerce/panier.html.twig');
    }

    public function addItemAction(Application $app, Request $request)
    {
    	return new Response($request->get('id'));
    }

	public function categorieAction($category_name,Application $app)
	{

		$articles = $app['idiorm.db']->for_table('products')->where('category_name', $category_name)->find_result_set();

		return $app['twig']->render('categorie.html.twig',[
			'products' => $products
		]);
	}

public function newAdAction(Application $app){


  return $app['twig']->render('ajout_produit.html.twig', [
      'categories'=> $app['categories'],      
        'error' => [] ,
        'errors' => []


  ]);
}


public function newAdPostAction(Application $app, Request $request){

$Verifications = new Verifications;

$verifs =  $Verifications->VerificationNewAd($request, $app);

 

$errors = $verifs['errors'];
$error = $verifs['error'];
$finalFileName1 = $verifs['finalFileName1'];
$finalFileName2 = $verifs['finalFileName2'];
$finalFileName3 = $verifs['finalFileName3'];


if(empty($errors) && empty($error)){
  //Connexion à la bdd
		$product = $app['idiorm.db']->for_table('products')->create();



		//Affectation des valeurs
		$product->name 			   = $request->get('name');
		$product->brand    		 = $request->get('brand');
		$product->price 		   = $request->get('price');
		$product->description	 = $request->get('description');
		$product->image_1		   = $finalFileName1;
		$product->image_2		   = $finalFileName2;
		$product->image_3		   = $finalFileName3;
    $product->ID_category  = $request->get('category');
    $product->creation_date= strtotime('now');


    if((float)$request->get('shipping_charges') == 0.0){

      $product->shipping_charges = 0.0;

    }else{

		$product->shipping_charges  = $request->get('shipping_charges');
  }

		//ON persiste en bdd
		$product->save();

    return $app['twig']->render('ajout_produit.html.twig',[
              'success' => true,
              'errors' => [] ,
              'error' => [] ,
              'categories' => $app['categories']
            ]);

		}else{

			return $app['twig']->render('ajout_produit.html.twig',[
				'errors' => $errors,
				'error' => $error,
        'categories'=> $app['categories']
			]);

		}
	
	}


}


?>