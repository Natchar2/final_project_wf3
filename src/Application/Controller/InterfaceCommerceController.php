<?php
namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InterfaceCommerceController
{
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

        $products = $app['idiorm.db']->for_table('view_products')->where('category_name', $category_name)->find_result_set();

        return $app['twig']->render('commerce/categorie.html.twig',[
         'products' => $products
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


}


?>