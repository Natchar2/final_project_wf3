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

		$articles = $app['idiorm.db']->for_table('products')->where('category_name', $category_name)->find_result_set();

		return $app['twig']->render('categorie.html.twig',[
			'products' => $products
		]);
	}
}


?>