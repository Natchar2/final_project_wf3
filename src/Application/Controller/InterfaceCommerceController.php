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

    public function panierAction(Application $app)
    {
        return $app['twig']->render('commerce/panier.html.twig');
    }

    public function addItemAction(Application $app, Request $request)
    {
       return new Response($request->get('id'));
    }

   public function categorieAction($category_name,Application $app,$page = 1,$nbPerPage = 2)
   {
    $offset=(($page-1)*$nbPerPage);
    $totalProducts=$app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->find_result_set();
    $totalProducts=count($totalProducts);
    $products=$app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->limit($nbPerPage)->offset($offset)->find_result_set();


    return $app['twig']->render('commerce/categorie.html.twig',[
        'totalProducts' => $totalProducts,       
        'products' => $products,
        'page' => $page,       
        'nbPerPage' => $nbPerPage
    ]);
}


public function categoriePageAction($category_name,Application $app,$page = 1,$nbPerPage = 2)
{
    $offset=(($page-1)*$nbPerPage);
    $totalProducts=$app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->find_result_set();
    $totalProducts=count($totalProducts);
    $products=$app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->limit($nbPerPage)->offset( $offset)->find_result_set();


    return $app['twig']->render('commerce/categorie.html.twig',[
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

    return $app['twig']->render('commerce/article.html.twig',[
        'product' => $product,
        'suggests' => $suggests
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
        $product->name               = $request->get('name');
        $product->brand            = $request->get('brand');
        $product->price          = $request->get('price');
        $product->description  = $request->get('description');
        $product->image_1        = $finalFileName1;
        $product->image_2        = $finalFileName2;
        $product->image_3        = $finalFileName3;
        $product->ID_category  = $request->get('category');
        $product->creation_date= strtotime('now');

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

        $total_price = $this->get_total_price($app, $request->get('id'), 'incrementation');

        return new Response($total_price); 
    }

    public function removeOneItemAction(Application $app, Request $request)
    {
        $total_price = $this->get_total_price($app, $request->get('id'), 'decrementation');

        $panier = $app['session']->get('panier');
        if(isset($panier[$request->get('id')]))
        {
            if($panier[$request->get('id')] == 1)
            {
                unset($panier[$request->get('id')]);
            }
            else
            {
                $panier[$request->get('id')] -= 1;
            }

            $app['session']->set('panier',$panier);
        }


        return new Response($total_price); 
    }

    public function removeAllItemAction(Application $app, Request $request)
    {
        $total_price = $this->get_total_price($app, $request->get('id'), 'decrementationAll');

        $panier = $app['session']->get('panier');

        unset($panier[$request->get('id')]);

        $app['session']->set('panier',$panier);

        return new Response($total_price); 
    }

    public function get_total_price(Application $app, $id, $mode)
    {
        $total_price = $app['session']->get('total_price');

        if(!$total_price)
        {
            $total_price = $app['session']->set('total_price', 0);
        }

        if(isset($app['session']->get('panier')[$id]))
        {
            $product = $app['idiorm.db']->for_table('products')->where('ID_product', $id)->find_result_set();
            $price = $product[0]->price;
            $shipping_charges = $product[0]->shipping_charges;

            if($mode == 'incrementation')
            {
                $total_price += $price + $shipping_charges;
            }
            elseif($mode == 'decrementation')
            {
                $total_price -= $price + $shipping_charges;
                if($total_price < 0)
                {
                    $total_price = 0;
                }
            }
            else
            {
                for ($i = $app['session']->get('panier')[$id]; $i > 0; $i--)
                {
                    $total_price -= $price + $shipping_charges;
                    if($total_price < 0)
                    {
                        $total_price = 0;
                    }    
                }
            }

            $app['session']->set('total_price', $total_price);  
        }

        return $total_price;
    }

	public function categorieAction($category_name,Application $app)
	{


    return $app['twig']->render('commerce/ajout_produit.html.twig',[
        'success'    => true,
        'errors'     => [] ,
        'error'      => [] ,
        'categories' => $app['categories']
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
}


?>