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
		->get('/paiement', 'Application\Controller\InterfaceCommerceController::paiementAction')
		->bind('paiement');

		
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
		->get('/shop/{category_name}', 'Application\Controller\InterfaceCommerceController::shopAction')
		->assert('category_name','[^/]+')
		->value('category_name','all')
		->bind('shop');


		$controllers
		->get('/shop/{category_name}/page{page}', 'Application\Controller\InterfaceCommerceController::shopPageAction')
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
		->post('/addItem', 'Application\Controller\InterfaceCommerceController::addItemAction')
		->bind('addItem');

		$controllers
		->get('/faq', 'Application\Controller\InterfaceCommerceController::faqAction')
		->bind('faq');

		$controllers
		->get('/about', 'Application\Controller\InterfaceCommerceController::aboutAction')
		->bind('about');


		$controllers
		->get('/item', 'Application\Controller\InterfaceCommerceController::itemAction')
		->bind('item');

		/*$controllers
		->get('/produit/ajouter/{ID_product}', 'Application\Controller\InterfaceCommerceController::newAdAction')
		->assert('ID_product', '\d+')
		->value('ID_product', '0')
		->bind('view_newAd');
*/
		$controllers
		->get('/produit/ajouter/{ID_product}/{token}', 'Application\Controller\InterfaceCommerceController::newAdAction')
		->assert('ID_product', '\d+')
		->value('ID_product', '0')
		->assert('token', '\w+')
		->value('token', '0')
		->bind('view_newAd');

		$controllers
		->post('/produit/ajouter', 'Application\Controller\InterfaceCommerceController::newAdPostAction')
		//->assert('ID_product', '\d+')
		//->value('ID_product', '0')
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
		->assert('ID_product', '\d+')
		->bind('deleteProduct');

		$controllers
		->get('/shoppingCard', 'Application\Controller\InterfaceCommerceController::shoppingCardAction')
		->bind('shoppingCard');

		$controllers
		->get('/connexion/{success_inscription}', 'Application\Controller\InterfaceCommerceController::connexionAction')
		->assert('sucess_inscription', 'success_inscription')
		->value('success_inscription', '')
		->bind('connexion');

		// $controllers
		// ->get('/inscription', 'Application\Controller\InterfaceCommerceController::inscriptionAction')
		// ->bind('inscription');

		$controllers
		->match('/inscription/{url_error}', 'Application\Controller\InterfaceCommerceController::inscriptionPostAction')
		->assert('url_error', 'erreur')
		->value('url_error', '')
		->method('GET|POST')
		->bind('inscription_post');


		$controllers
		->get('/profil/{ID_user}/{success_modification}', 'Application\Controller\InterfaceCommerceController::ProfilAction')
		->assert('ID_user','\d+')
		->value('ID_user','0')
		->assert('success_modification','success_modification')
		->value('success_modification','0')
		->bind('profil');

		$controllers
		->post('/search', 'Application\Controller\InterfaceCommerceController::searchAction')
		->bind('search');

		$controllers
		->match('/profil/modification_profil-{token}', 'Application\Controller\InterfaceCommerceController::setProfilAction')
		->assert('token','\w+')
		->value('token','0')
		->method('POST|GET')
		->bind('set_profil');

		$controllers
		->get('/forgot_password-{token}', 'Application\Controller\GlobalController::forgot_passwordAction')
		->assert('token','\w+')
		->value('token','0')
		->bind('forgot_password');

		$controllers
		->post('/forgot_password-{token}', 'Application\Controller\GlobalController::forgot_passwordPostAction')
		->assert('token','\w+')
		->value('token','0')
		->bind('forgot_passwordPost');

		$controllers
		->get('/reset_password-{token}', 'Application\Controller\GlobalController::reset_passwordAction')
		->assert('token','\w+')
		->value('token','0')
		->bind('reset_password');

		$controllers
		->post('/reset_password-{token}', 'Application\Controller\GlobalController::reset_passwordPostAction')
		->assert('token','\w+')
		->value('token','0')
		->bind('reset_passwordPost');

		$controllers

		->get('profil/utilisateur/{ID_user}', 'Application\Controller\InterfaceCommerceController::ProfilUserAction')
		->assert('ID_user','\d+')
		->value('ID_user','0')
		->bind('profilUser');

		->match('/newsletter', 'Application\Controller\GlobalController::newsletterPostAction')
		->method('POST|GET')
		->bind('newsletter');



		return $controllers;
	}
}
?>