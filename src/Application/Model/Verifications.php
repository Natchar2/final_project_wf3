<?php 

namespace Application\Model;

use Application\Traits\Shortcut;


class Verifications
{

	use Shortcut;

	public function VerificationNewAd($request, $app)
	{


		/*-----------Verification du formulaire----------*/
		$error = [] ;
		$errors = [];

		//verification du champ name
		if(null!=($request->get('name')) && !empty($request->get('name')))
		{

			if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,50}$#i',$request->get('name')))
			{
				$errors[] = 'Nom ou modèle invalide';
			}
		}
		else
		{
			$errors[] = "Veuillez remplir le champ nom ou modèle";
		}


		//verification du champ marque
		if(null!=($request->get('brand')) && !empty($request->get('brand')))
		{



			if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,30}$#i',$request->get('brand')))
			{
				$errors[] = 'Marque invalide';
			}
		}
		else
		{
			$errors[] = "Veuillez remplir le champ marque";
		}

		//verification du champ category
		if(null!=($request->get('category')) && !empty($request->get('category')))
		{
			if(!filter_var($request->get('category'), FILTER_VALIDATE_INT))
			{
				$errors[] = 'Catégorie invalide';
			}
		}
		else
		{
			$errors[] = "Veuillez remplir le champ catégorie";
		}

		//verification du champ sous-catégorie
		if(null!=($request->get('sub_category'))&& !empty($request->get('sub_category')))
		{
			if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,30}$#i',$request->get('sub_category')))
			{
				$errors[] = 'Sous-catégorie invalide';
			}
		}


		//verification du champ prix
		if(null!=($request->get('price')) && !empty($request->get('price')))
		{
			if (!filter_var($request->get('price'), FILTER_VALIDATE_FLOAT))
			{
				$errors[] = 'Prix invalide';
			}
		}
		else
		{
			$errors[] = "Veuillez remplir le champ prix";
		}

		//verification du champ frais de livraison
		if(null!=($request->get('shipping_charges')) && !empty($request->get('shipping_charges')))
		{
			if (!preg_match('#^[\d.]*$#',$request->get('shipping_charges')))
			{
				$errors[] = 'Frais de livraisons invalides';
			}
		}

		//verification du champ description
		if(null!=($request->get('description')) && !empty($request->get('description')))
		{
			if (preg_match('#(<script.)#i',$request->get('description')))
			{
				$errors[] = 'Description invalide';
			}
		}
		else
		{
			$errors[] = "Veuillez remplir le champ description";
		}

		//verification du champ image_1
		if($_FILES['image_1']['name']>"")
		{
			switch ($_FILES['image_1']['error'])
			{

				case 1:
				$error[]='Image 1 : La taille de fichier est supérieur à celle acceptée';
				break;

				case 2:
				$error[]='Image 1 : La taille de fichier est supérieur à celle acceptée';
				break;

				case 3:
				$error[]='Le téléchargement est incomplet. Veuillez réessayer';
				break;

				case 4:
				$error[]='Image 1 : Veuillez selectionner un fichier';
				break; 

				case 6:
				$error[]='Image 1 : Erreur serveur code 90001 : Le téléchargement n\'a pus ce faire. Veuillez réessayer plus tard';
				break;
				//90001 doit etre inscrit chez nous afin de pouvoir identifier l'erreur facilement 

				case 7:
				$error[]='Image 1 : Le téléchargement n\'a pu ce faire. Veuillez réessayer plus tard';
				break;

				case 8:
				$error[]='Image 1 : Le téléchargement était interrompu';
				break;

                case !0://comme on a sauté des erreurs il faut verifier qu'il n'y en ai pas d'autres
                $error[]= 'Erreur inconnue.';

                default://si aucune erreur a été envoyer
               	$success[] = 'le téléchargement s\'est bien effectué';

                $extension = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['image_1']['tmp_name']);

                if(($_FILES['image_1']['size'])<=512000)
                {

                	$success[] = 'La taille est acceptable';

                	if($extension=='image/jpeg' || $extension=='image/png'|| $extension=='image/bmp' || $extension=='image/gif')
                	{


                		//recupération, deplacement et chgt du nom du fichier
                		if(null!==($error))
                		{
                			$newFileName = $this->createFileName(10);

                			if($extension == 'image/jpeg')
                			{
                				$newFileExt = '.jpg';
                			}

                			elseif ($extension == 'image/png')

                			{
                				$newFileExt = '.png';
                			}

                			$finalFileName1 = $newFileName .$newFileExt;
                		}


                		move_uploaded_file($_FILES['image_1']['tmp_name'],ROOT.'web/assets/images/'. $finalFileName1);

                		$success[]= 'image sauvegardée';


                	}
                	else
                	{
                		$error[] = 'Le format de l\'image 1 n\'est pas valide';
                	}
                }
                else
                {
                	$error[] = 'Veuillez choisir un fichier inférieur à 500Ko pour l\'image 1!';
                }

                break;
            }

        }
        else
        {
        	if(!empty($request->get('image_1-hidden')))
        	{
        		if(preg_match('#^[a-z0-9A-Z \.]{3,}$#i',$request->get('image_1-hidden')))
        		{
        			$finalFileName1 = ($request->get('image_1-hidden'));
        		}
        		else
        		{
        			$finalFileName1 = null;
        		}

        	}
        	else
        	{
        		$finalFileName1 = null;
        	}

        }

        //verification du champ image_2
        if(($_FILES['image_2']['name'])>"")
        {
        	switch ($_FILES['image_2']['error'])
        	{
        		case 1:
        		$error[]='Image 2 : La taille de fichier est supérieur à celle acceptée';
        		break;

        		case 2:
        		$error[]='Image 2 : La taille de fichier est supérieur à celle acceptée';
        		break;

        		case 3:
        		$error[]='Image 2 : Le téléchargement est incomplet. Veuillez réessayer';
        		break;

        		case 4:
        		$error[]='Image 2 : Veuillez selectionner un fichier';
        		break; 

        		case 6:
        		$error[]='Image 2 : Erreur serveur code 90001 : Le téléchargement n\'a pus ce faire. Veuillez réessayer plus tard';
        		break;
        		//90001 doit etre inscrit chez nous afin de pouvoir identifier l'erreur facilement 

        		case 7:
        		$error[]='Image 2 : Le téléchargement n\'a pu ce faire. Veuillez réessayer plus tard';
        		break;

        		case 8:
        		$error[]='Image 2 : Le téléchargement était interrompu';
        		break;

                case !0://comme on a sauté des erreurs il faut verifier qu'il n'y en ai pas d'autres
                $error[]= 'Erreur inconnue.';

                default://si aucune erreur a été envoyer
                $success[] = 'le téléchargement s\'est bien effectué';


                $extension = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['image_2']['tmp_name']);

                if(($_FILES['image_2']['size'])<=512000)
                {
                	$success[] = 'La taille est acceptable';

                	if($extension=='image/jpeg' || $extension=='image/png'|| $extension=='image/bmp' || $extension=='image/gif')
                	{
        				//recupération, deplacement et chgt du nom du fichier
                		if(null!==($error)){

                			$newFileName =  $this->createFileName(10);

                			if($extension == 'image/jpeg')
                			{
                				$newFileExt = '.jpg';
                			}
                			elseif ($extension == 'image/png')
                			{
                				$newFileExt = '.png';
                			}

                			$finalFileName2 = $newFileName .$newFileExt;

                		}

                        move_uploaded_file($_FILES['image_2']['tmp_name'],ROOT.'web/assets/images/'. $finalFileName2);

                		$success[]= 'image sauvegardée';

                	}
                	else
                	{
                		$error[] = 'Le format de l\'image 2 n\'est pas valide';
                	}
                }
                else
                {
                	$error[] = 'Veuillez choisir un fichier inférieur à 500Ko pour l\'image 2!';
                }

                break;
            }


        }
        else
        {
        	if(!empty($request->get('image_2-hidden')))
        	{
        		if(preg_match('#^[a-z0-9A-Z \.]{3,}$#i',$request->get('image_2-hidden')))
        		{
        			$finalFileName2 = ($request->get('image_2-hidden'));
        		}
        		else
        		{
        			$finalFileName2 = null;
        		}

        	}
        	else
        	{
        		$finalFileName2 = null;
        	}
        }


        //verification du champ image_3
        if($_FILES['image_3']['name']>"")
        {
        	switch ($_FILES['image_3']['error'])
        	{

        		case 1:
        		$error[]='Image 3 : La taille de fichier est supérieur à celle acceptée';
        		break;

        		case 2:
        		$error[]='Image 3 : La taille de fichier est supérieur à celle acceptée';
        		break;

        		case 3:
        		$error[]='Image 3 : Le téléchargement est incomplet. Veuillez réessayer';
        		break;

        		case 4:
        		$error[]='Image 3 : Veuillez selectionner un fichier';
        		break; 

        		case 6:
        		$error[]='Image 3 : Erreur serveur code 90001 : Le téléchargement n\'a pus ce faire. Veuillez réessayer plus tard';
        		break;
        		//90001 doit etre inscrit chez nous afin de pouvoir identifier l'erreur facilement 

        		case 7:
        		$error[]='Image 3 : Le téléchargement n\'a pu ce faire. Veuillez réessayer plus tard';
        		break;

        		case 8:
        		$error[]='Image 3 : Le téléchargement était interrompu';
        		break;

                case !0://comme on a sauté des erreurs il faut verifier qu'il n'y en ai pas d'autres
                $error[]= 'Image 3 : Erreur inconnue.';

                default://si aucune erreur a été envoyer
                $success[] = 'le téléchargement s\'est bien effectué';


                $extension = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['image_3']['tmp_name']);

                if(($_FILES['image_3']['size'])<=512000){

                	$success[] = 'La taille est acceptable';

                	if($extension=='image/jpeg' || $extension=='image/png'|| $extension=='image/bmp' || $extension=='image/gif')
                	{

        				//recupération, deplacement et chgt du nom du fichier
                		if(null!==($error))
                		{
                			$newFileName =  $this->createFileName(10);

                			if($extension == 'image/jpeg')
                			{
                				$newFileExt = '.jpg';
                			}
                			elseif ($extension == 'image/png')
                			{
                				$newFileExt = '.png';
                			}

                			$finalFileName3 = $newFileName .$newFileExt;
                		}

                		move_uploaded_file($_FILES['image_3']['tmp_name'],ROOT.'web/assets/images/'. $finalFileName3);


                		$success[]= 'image sauvegardée';


                	}
                	else
                	{
                		$error[] = 'Le format de l\'image 3 n\'est pas valide';
                	}
                }
                else
                {
                	$error[] = 'Veuillez choisir un fichier inférieur à 500Ko pour l\'image 3!';
                }

                break;
            }


        }
        else
        {
        	if(!empty($request->get('image_3-hidden')))
        	{
        		if(preg_match('#^[a-z0-9 \.]{3,}$#i',$request->get('image_3-hidden')))
        		{
        			$finalFileName3 = ($request->get('image_3-hidden'));

        		}
        		else
        		{
        			$finalFileName3 = null;
        		}

        	}
        	else
        	{
        		$finalFileName3 = null;
        	}
        }

        return array(
        	'errors'        => $errors,
        	'error'         => $error,
        	'finalFileName1'=> $finalFileName1,
        	'finalFileName2'=> $finalFileName2,
        	'finalFileName3'=> $finalFileName3,

        );

    }

    public function VerificationContact($request, $app)
    {
    	/*  Verification du formulaire de contact*/
    	$errors = [];

		//verification des champs name et surname
    	if(null!=($request->get('name')) && !empty($request->get('name')))
    	{
    		if (!preg_match('#^[a-z \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{3,30}$#i',$request->get('name')))
    		{
    			$errors[] = 'Nom  invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ nom";
    	}

    	if(null!=($request->get('surname')) && !empty($request->get('surname')))
    	{
    		if (!preg_match('#^[a-z \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{3,30}$#i',$request->get('surname')))
    		{
    			$errors[] = 'Prénom  invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ prénom";
    	}

		//verification du champ sujet
    	if(null!=($request->get('subject')) && !empty($request->get('subject')))
    	{
    		if (!preg_match('#^[a-z \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{3,30}$#i',$request->get('subject')))
    		{
    			$errors[] = 'sujet  invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ sujet";
    	}

		//Verification du champ email
    	if(null!=($request->get('mail')) && !empty($request->get('mail')))
    	{
    		if (!filter_var(htmlspecialchars($request->get('mail')),FILTER_VALIDATE_EMAIL))
    		{
    			$error[] = 'email n\'est pas valide';
    		}

    	}
    	else
    	{
    		$error[] = 'Veuillez remplir le champ email';
    	}

    	return array(
    		'errors'  => $errors,
    	);

    }


    public function VerificationNewPost($request, $app)
    {

    	/*-----------Verification du formulaire----------*/
    	$errors = [];

        //verification du champ description
    	if(null!=($request->get('description')) && !empty($request->get('description')))
    	{
    		if (preg_match('#(<script.)#i',$request->get('description')))
    			{
    			$errors[] = 'Description invalide';
    			}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ description";
    	}


    }

    public function VerificationNewTopic($request, $app)
    {
    	$errors = [];

        //verification du champ title
    	if(null!=($request->get('title')) && !empty($request->get('title')))
    	{
    		if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_\s\;\!\.\?\:\,\'+\(\)]{3,100}$#i',$request->get('title')))
    		{
    			$errors[] = 'Titre invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ title";
    	}

        ////verification du champ category
    	if(null!=($request->get('category')) && !empty($request->get('category')))
    	{

    		if(!filter_var($request->get('category'), FILTER_VALIDATE_INT))
    		{
    			$errors[] = 'Catégorie invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ catégorie";
    	}
    }

    public function VerificationNewEvent($request, $app)
    {
        $errors = [];
        $error = [];

        //verification du champ title
        if(null!=($request->get('event_title')) && !empty($request->get('event_title')))
        {
            if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_\s\;\!\.\?\:\,\'+\(\)]{3,100}$#i',$request->get('event_title')))
            {
                $errors[] = 'Nom de l\'événement invalide';
            }
        }
        else
        {
            $errors[] = "Veuillez remplir le champ nom de l\'événement";
        }

        ////verification du champ category
        if(null!=($request->get('category')) && !empty($request->get('category')))
        {

            if(!filter_var($request->get('category'), FILTER_VALIDATE_INT))
            {
                $errors[] = 'Catégorie invalide';
            }
        }
        else
        {
            $errors[] = "Veuillez remplir le champ catégorie";
        }


        //Verification des champs date
        if(null!=($request->get('start_date')) && !empty($request->get('start_date')))
        {
            if (!preg_match('#^([0-9]{2}[/]){2}([0-9]){4}$#i',$request->get('start_date')))
            {
                $errors[] ='Veuillez rentrer un format de date valide (ex: 01/01/2001)';
            } 
        }
        else
        {
            $errors[]= "Veuillez remplir le champ Date de début";
        }

        if(null!=($request->get('end_date')) && !empty($request->get('end_date')))
        {
            if (!preg_match('#^([0-9]{2}[/]){2}([0-9]){4}$#i',$request->get('end_date')))
                {
                    $errors[] ='Veuillez rentrer un format de date valide (ex: 01/01/2001)';
                }
         }
         
        //verification du champ street name
        if(null!=($request->get('street_name')) && !empty($request->get('street_name')))
        {
            if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\-\/)]{3,100}$#i',$request->get('street_name')))
            {
                $errors[] = 'Nom de la rue invalide';
            }
        }
        else
        {
            $errors[] = "Veuillez remplir le champ nom de la rue";
        }

        //verification du champ zip_code
        if(null!=($request->get('zip_code')) && !empty($request->get('zip_code')))
        {
            if (!preg_match('#^[0-9]{5}$#i',$request->get('zip_code')))
            {
                $errors[] = 'Code postal invalide';
            }
        }
        else
        {
            $errors[] = "Veuillez remplir le champ code postal";
        }

        //verification du champ image
        if($_FILES['image']['name']>"")
        {
            switch ($_FILES['image']['error'])
            {

                case 1:
                $error[]='Image 1 : La taille de fichier est supérieur à celle acceptée';
                break;

                case 2:
                $error[]='Image 1 : La taille de fichier est supérieur à celle acceptée';
                break;

                case 3:
                $error[]='Le téléchargement est incomplet. Veuillez réessayer';
                break;

                case 4:
                $error[]='Image 1 : Veuillez selectionner un fichier';
                break; 

                case 6:
                $error[]='Image 1 : Erreur serveur code 90001 : Le téléchargement n\'a pus ce faire. Veuillez réessayer plus tard';
                break;
                //90001 doit etre inscrit chez nous afin de pouvoir identifier l'erreur facilement 

                case 7:
                $error[]='Image 1 : Le téléchargement n\'a pu ce faire. Veuillez réessayer plus tard';
                break;

                case 8:
                $error[]='Image 1 : Le téléchargement était interrompu';
                break;

                case !0://comme on a sauté des erreurs il faut verifier qu'il n'y en ai pas d'autres
                $error[]= 'Erreur inconnue.';

                default://si aucune erreur a été envoyer
                $success[] = 'le téléchargement s\'est bien effectué';

                $extension = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['image']['tmp_name']);

                if(($_FILES['image']['size'])<=512000)
                {

                    $success[] = 'La taille est acceptable';

                    if($extension=='image/jpeg' || $extension=='image/png'|| $extension=='image/bmp' || $extension=='image/gif')
                    {


                        //recupération, deplacement et chgt du nom du fichier
                        if(null!==($error))
                        {
                            $newFileName = $this->createFileName(10);

                            if($extension == 'image/jpeg')
                            {
                                $newFileExt = '.jpg';
                            }

                            elseif ($extension == 'image/png')

                            {
                                $newFileExt = '.png';
                            }

                            $finalFileName1 = $newFileName .$newFileExt;
                        }


                        move_uploaded_file($_FILES['image']['tmp_name'],ROOT.'web/assets/images/'. $finalFileName1);

                        $success[]= 'image sauvegardée';


                    }
                    else
                    {
                        $error[] = 'Le format de l\'image 1 n\'est pas valide';
                    }
                }
                else
                {
                    $error[] = 'Veuillez choisir un fichier inférieur à 500Ko pour l\'image 1!';
                }

                break;
            }

        }
        else
        {
            if(!empty($request->get('image-hidden')))
            {
                if(preg_match('#^[a-z0-9A-Z \.]{3,}$#i',$request->get('image-hidden')))
                {
                    $finalFileName1 = ($request->get('image-hidden'));
                }
                else
                {
                    $finalFileName1 = null;
                }

            }
            else
            {
                $finalFileName1 = null;
            }

        }

         //verification du champ url_1
        if(null!=($request->get('url_1')) && !empty($request->get('url_1')))
        {
            if (!filter_var($request->get('url_1'), FILTER_VALIDATE_URL))
            {
                $errors[] = 'L\'adresse de site est invalide';
            }
        }
        

          //verification du champ url_2
        if(null!=($request->get('url_2')) && !empty($request->get('url_2')))
        {
            if (!filter_var($request->get('url_2'), FILTER_VALIDATE_URL))
            {
                $errors[] = 'L\'adresse de réseau social est invalide';
            }
        }
        

          //verification du champ url_3
        if(null!=($request->get('url_3')) && !empty($request->get('url_3')))
        {
            if (!filter_var($request->get('url_3'), FILTER_VALIDATE_URL))
            {
                $errors[] = 'L\'adresse de réseau social est invalide';
            }
        }

        //Verification du champ email
        if(null!=($request->get('mail')) && !empty($request->get('mail')))
        {
            if (!filter_var(htmlspecialchars($request->get('mail')),FILTER_VALIDATE_EMAIL))
            {
                $error[] = 'email n\'est pas valide';
            }

        }
        else
        {
            $error[] = 'Veuillez remplir le champ email';
        }

        
        //verification du champ phone
        if(null!=($request->get('phone')) && !empty($request->get('phone')))
        {
            if (!preg_match('#^([0-9]{2}[\- .]?){4}\d{2}$#i',$request->get('phone')))
            {
                $errors[] = 'Téléphone invalide';
            }
        }
       
        //verification du champ description
        if(null!=($request->get('event_description')) && !empty($request->get('event_description')))
        {
            if (preg_match('#(<script.)#i',$request->get('description')))
            {
                $errors[] = 'Description invalide';
            }
        }
        else
        {
            $errors[] = "Veuillez remplir le champ description";
        }

        //verification du champ latitude
        if(null!=($request->get('latitude')) && !empty($request->get('latitude')))
        {
            if (!preg_match('#(^\d){1,2}\.(\d){6}$#i',$request->get('latitude')))
            {
                $errors[] = 'Coordonnées de latitude';
            }
        }

        //verification du champ longitude
        if(null!=($request->get('longitude')) && !empty($request->get('longitude')))
        {
            if (!preg_match('#(^\d){1,2}\.(\d){6}$#i',$request->get('longitude')))
            {
                $errors[] = 'Coordonnées de longitude';
            }
        }

            return array(
            'errors'        => $errors,
            'error'         => $error,
            'finalFileName1'=> $finalFileName1,
        );

    }
    


}

?>