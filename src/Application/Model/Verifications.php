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
		if(null!=($request->get('brand'))&& !empty($request->get('brand')))
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
    	if(null!=($request->get('email')) && !empty($request->get('email')))
    	{
    		if (!filter_var(htmlspecialchars($request->get('email')),FILTER_VALIDATE_EMAIL))
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



    public function VerificationInscription($request, $app)
    {
    	/*-----------Verification du formulaire----------*/
    	$error = [];
    	$errors = [];

		//verification du champ name
    	if(null !=  ($request->get('name'))  && !empty($request->get('name')))
    	{
    		if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,50}$#i',$request->get('name')))
    		{
    			$errors[] = 'Nom invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ nom";

    	}

		//verification du champ surname
    	if(null !=  ($request->get('surname'))  && !empty($request->get('surname')))
    	{
    		if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,50}$#i',$request->get('surname')))
    		{
    			$errors[] = 'Prénom invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ prénom";

    	}

		//verification du champ pseudo
    	if(null !=  ($request->get('pseudo'))  && !empty($request->get('pseudo')))
    	{
    		if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,50}$#i',$request->get('pseudo')))
    		{
    			$errors[] = 'Pseudo invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le champ pseudo";

    	}

		//verification du champ rue
    	if(null !=  ($request->get('street'))  && !empty($request->get('street')))
    	{
    		if (!preg_match('#^[a-z0-9. \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,250}$#i',$request->get('street')))
    		{
    			$errors[] = 'Rue invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez renseignez rue";

    	}

		//verification du champ code postal
    	if(null !=  ($request->get('zip_code'))  && !empty($request->get('zip_code')))
    	{
    		if (!preg_match('#^[0-9]{5}$#i',$request->get('zip_code')))
    		{
    			$errors[] = 'Code postal invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez remplir le Code postal";
    	}

		//verification du champ ville
    	if(null !=  ($request->get('city'))  && !empty($request->get('city')))
    	{
    		if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,50}$#i',$request->get('city')))
    		{
    			$errors[] = 'Ville invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez renseignez la ville";
     	}

		//Verification du champ email
    	if(null !=  ($request->get('mail'))  && !empty($request->get('mail')))
    	{
    		if (!filter_var(htmlspecialchars($request->get('mail')),FILTER_VALIDATE_EMAIL))
    		{
    			$error[] = 'email n\'est pas valide';
    		}

    	}
    	else
    	{
    		$error[] = 'Veuillez renseignez le champ email';

    	}

		//verification du champ numéro de téléphone
    	if(null !=  ($request->get('phone'))  && !empty($request->get('phone')))
    	{
    		if (!preg_match('#^[0-9]{15}$#i',$request->get('phone')))
    		{
    			$errors[] = 'Téléphone invalide';
    		}
    	}
    	else
    	{
    		$errors[] = "Veuillez renseignez le numéro de téléphone";
    	}

		//verification du champ Nom de la société
    	if(null !=  ($request->get('society_name'))  && !empty($request->get('society_name')))
    	{
    		if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,100}$#i',$request->get('society_name')))
    		{
    			$errors[] = 'Nom de société invalide';
    		}
    	}
    	else
    	{

    		$errors[] = "Veuillez renseignez le nom de la société";

    	}

    	$finalFileName1 = "";
		//verification du champ image_1
    	if(isset($_FILES['avatar']) && $_FILES['avatar']['name']>"")
    	{


    		switch ($_FILES['avatar']['error'])
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

				$extension = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['avatar']['tmp_name']);

				if(($_FILES['avatar']['size'])<=512000)
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

						move_uploaded_file($_FILES['avatar']['tmp_name'],ROOT.'web/assets/images/'. $finalFileName1);

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

		//verification du mot de passe
		if(null !=  $request->get('password')  && !empty($request->get('password')) && $request->get(null != 'password2')  && !empty($request->get('password2')))
		{
			if($request->get('password') === $request->get('password2'))
			{
				if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_%\$\?!]{3,50}$#i',$request->get('password')))
				{
					$errors[] = 'Mot de passe invalide';
				}
			}
			else
			{
				$errors[] = "Les mots de passe ne sont pas identiques";
			}
		}
		else
		{
			$errors[] = "Mot de passe invalide";
		}

		return array(
        	'errors'        => $errors,
        	'error'         => $error,
        	'finalFileName1'=> $finalFileName1,
        );

	}

}

?>