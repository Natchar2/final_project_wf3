<?php
namespace Application\Provider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class InterfaceCommerceControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers
		->get('/',  function() use($app){
			return $app->redirect('accueil');
		});

		$controllers
		->get('/accueil', 'Application\Controller\InterfaceCommerceController::accueilAction')
		->bind('accueil');

		// -----------	Panier et paiement 	-------------------

		$controllers
		->get('/panier', 'Application\Controller\InterfaceCommerceController::panierAction')
		->bind('panier');


		
		$controllers
		->post('/addItem', 'Application\Controller\InterfaceCommerceController::addItemAction')
		->bind('addItem');


		$controllers
		->post('/removeOneItem', 'Application\Controller\InterfaceCommerceController::removeOneItemAction')
		->bind('removeOneItem');

		$controllers
		->post('/removeAllItem', 'Application\Controller\InterfaceCommerceController::removeAllItemAction')
		->bind('removeAllItem');



		
		$controllers
		->post('/create_customer', 'Application\Controller\InterfaceCommerceController::createCustomerAction')
		->bind('create_customer');

		$controllers
		->post('/confirmation_of_sale', 'Application\Controller\InterfaceCommerceController::confirmationSaleAction')
		->bind('confirmation_of_sale');



		$controllers
		->get('/suivi', 'Application\Controller\InterfaceCommerceController::suiviAction')
		->bind('suivi');

		$controllers
		->post('/suivi', 'Application\Controller\InterfaceCommerceController::suiviMajAction')
		->bind('suiviMaj');



		// ---------------		fin Panier et paiement		-----------------








		$controllers
		->get('/shop/{category_name}', 'Application\Controller\InterfaceCommerceController::categorieAction')
		->assert('category_name','[^/]+')
		->value('category_name','all')
		->bind('shop');


		$controllers
		->get('/shop/{category_name}/page{page}', 'Application\Controller\InterfaceCommerceController::categoriePageAction')
		->assert('category_name','[^/]+')
		->value('category_name','all')
		->assert('page','[0-9]+')	
		->value('page','1')		
		->bind('shop_page');

		$controllers
		->get('/{category_name}/{slugproduct}_{ID_product}.html','Application\Controller\InterfaceCommerceController::articleAction')
		->assert('category_name','[^/]+')
		->assert('ID_product','[0-9]+')
		->bind('article');

		$controllers
		->get('/faq', 'Application\Controller\InterfaceCommerceController::faqAction')
		->bind('faq');

		$controllers
		->get('/about', 'Application\Controller\InterfaceCommerceController::aboutAction')
		->bind('about');



	

		$controllers
		->get('/produit/ajouter/{ID_product}', 'Application\Controller\InterfaceCommerceController::newAdAction')
		->assert('ID_product', '\d+')
		->value('ID_product', '0')
		->bind('view_newAd');

		$controllers
		->post('/produit/ajouter/{ID_product}', 'Application\Controller\InterfaceCommerceController::newAdPostAction')
		->assert('ID_product', '\d+')
		->value('ID_product', '0')
		->bind('newAd');

		$controllers
		->get('/contact', 'Application\Controller\GlobalController::contactAction')
		->bind('contact_page');

		$controllers
		->post('/contact', 'Application\Controller\GlobalController::contactPostAction')
		->bind('contactPost');

		$controllers
		->get('/conditions_generales', 'Application\Controller\GlobalController::conditionsAction')
		->bind('conditions_page');

		$controllers
		->get('/produit/liste', 'Application\Controller\InterfaceCommerceController::listProducts')
		->bind('listProducts');

		$controllers
		->post('/produit/liste', 'Application\Controller\InterfaceCommerceController::deleteProduct')
		->bind('deleteProduct');

		$controllers
		->get('/shoppingCard', 'Application\Controller\InterfaceCommerceController::shoppingCardAction')
		->bind('shoppingCard');

		$controllers
		->get('/connexion', 'Application\Controller\InterfaceCommerceController::connexionAction')
		->bind('connexion');

		$controllers
		->match('/inscription/{url_error}', 'Application\Controller\InterfaceCommerceController::inscriptionPostAction')
		->assert('url_error', 'erreur')
		->value('url_error', '')
		->method('GET|POST')
		->bind('inscription_post');



		return $controllers;
	}
}
?>