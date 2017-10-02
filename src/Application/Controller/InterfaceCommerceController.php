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

 public function faqAction(Application $app)
 {
  return $app['twig']->render('commerce/FAQ.html.twig');
}

public function aboutAction(Application $app)
{
  return $app['twig']->render('commerce/about.html.twig');
}

public function ajoutArticleAction(Application $app)
{
  return $app['twig']->render('commerce/ajoutArticle.html.twig');
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

public function categorieAction($category_name,Application $app)
{

  $articles = $app['idiorm.db']->for_table('products')->where('category_name', $category_name)->find_result_set();

  return $app['twig']->render('categorie.html.twig',[
   'products' => $products
 ]);
}

public function newAdAction(Application $app){


  return $app['twig']->render('commerce/ajout_produit.html.twig', [
    'categories' => $app['categories'],      
    'error'  => [] ,
    'errors' => []
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

          //Affectation d'une valeur par défaut à zéro si il n'y en a pas eu dans le formulaire
    if((float)$request->get('shipping_charges') == 0.0){

      $product->shipping_charges = 0.0;

    }else{

      $product->shipping_charges  = $request->get('shipping_charges');
    }

    		//ON persiste en bdd
    $product->save();

    return $app['twig']->render('commerce/ajout_produit.html.twig',[
      'success'    => true,
      'errors'     => [] ,
      'error'      => [] ,
      'categories' => $app['categories']
    ]);

  }else{

   return $app['twig']->render('commerce/ajout_produit.html.twig',[
    'errors'    => $errors,
    'error'     => $error,
    'categories'=> $app['categories']
  ]);

 }

}


}


?>