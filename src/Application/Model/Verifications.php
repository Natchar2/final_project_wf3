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
        if(null!=($request->get('name')) && !empty($request->get('name'))){

            if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,50}$#i',$request->get('name'))) {

                $errors[] = 'Nom ou modèle invalide';
            }
        }else{

            $errors[] = "Veuillez remplir le champ nom ou modèle";

        }
//verification du champ marque
        if(null!=($request->get('brand'))&& !empty($request->get('brand'))){

            if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,30}$#i',$request->get('brand'))) {

                $errors[] = 'Marque invalide';
            }
        }else{

            $errors[] = "Veuillez remplir le champ marque";

        }
////verification du champ category
        if(null!=($request->get('category')) && !empty($request->get('category'))){

            if(!filter_var($request->get('category'), FILTER_VALIDATE_INT)){

                $errors[] = 'Catégorie invalide';
            }
        }else{

            $errors[] = "Veuillez remplir le champ catégorie";

        }
//verification du champ sous-catégorie
        if(null!=($request->get('sub_category'))&& !empty($request->get('sub_category'))){

            if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_]{3,30}$#i',$request->get('sub_category'))) {

                $errors[] = 'Sous-catégorie invalide';
            }
        }else{

            $errors[] = "Veuillez remplir le champ sous-catégorie";

        }
//verification du champ prix
        if(null!=($request->get('price')) && !empty($request->get('price'))){

            if (!filter_var($request->get('price'), FILTER_VALIDATE_FLOAT)){

                $errors[] = 'Prix invalide';
            }
        }else{

            $errors[] = "Veuillez remplir le champ prix";

        }
//verification du champ frais de livraison
        if(null!=($request->get('shipping_charges')) && !empty($request->get('shipping_charges'))){

            if (!preg_match('#^[\d.]*$#',$request->get('shipping_charges'))){

                $errors[] = 'Frais de livraisons invalides';

            }
        }
//verification du champ description
        if(null!=($request->get('description')) && !empty($request->get('description'))){

            if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_\s\;\!\.\?\:\,\'+\(\)]{3,}$#i',$request->get('description'))) {

                $errors[] = 'Description invalide';
            }
        }else{

            $errors[] = "Veuillez remplir le champ description";

        }


//verification du champ image_1
        if($_FILES['image_1']['name']>""){


            switch ($_FILES['image_1']['error']) {

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

                if(($_FILES['image_1']['size'])<=512000){

                    $success[] = 'La taille est acceptable';

                    if($extension=='image/jpeg' || $extension=='image/png'|| $extension=='image/bmp' || $extension=='image/gif'){


                //recupération, deplacement et chgt du nom du fichier
                        if(null!==($error)){


                            $newFileName = $this->createFileName(10);

                            if($extension == 'image/jpeg'){
                                $newFileExt = '.jpg';
                            }elseif ($extension == 'image.png') {
                                $newFileExt = '.png';
                            }

                            $finalFileName1 = $newFileName .$newFileExt;
                        }


                        move_uploaded_file($_FILES['image_1']['tmp_name'],ROOT.'web/assets/images/'. $finalFileName1);

                        $success[]= 'image sauvegardée';


                    }else{

                        $error[] = 'Le format de l\'image 1 n\'est pas valide';

                    }
                }else{

                    $error[] = 'Veuillez choisir un fichier inférieur à 500Ko pour l\'image 1!';
                }

                break;
                }

                }else{

                    if(!empty($request->get('image_1-hidden'))){

                        if(preg_match('#^[a-z0-9A-Z \.]{3,}$#i',$request->get('image_1-hidden'))){

                            $finalFileName1 = ($request->get('image_1-hidden'));

                        }else{

                            $finalFileName1 = null;
                        }

                    }else{

                        $finalFileName1 = null;
                    }

                }
        //verification du champ image_2
        if(($_FILES['image_2']['name'])>""){


            switch ($_FILES['image_2']['error']) {

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

        if(($_FILES['image_2']['size'])<=512000){

            $success[] = 'La taille est acceptable';

            if($extension=='image/jpeg' || $extension=='image/png'|| $extension=='image/bmp' || $extension=='image/gif'){


        //recupération, deplacement et chgt du nom du fichier
                if(($error)){

                    $newFileName =  $this->createFileName(10);

                    if($extension == 'image/jpeg'){
                        $newFileExt = '.jpg';
                    }elseif ($extension == 'image.png') {
                        $newFileExt = '.png';
                    }

                    return $finalFileName2 = $newFileName .$newFileExt;
                }

                $image_2 = $_FILES['image_2']['tmp_name'];
                $image_2->move(ROOTH.'web/assets/images/',$finalFileName2);

                $success[]= 'image sauvegardée';


            }else{

                $error[] = 'Le format de l\image 2 n\'est pas valide';

            }
        }else{

            $error[] = 'Veuillez choisir un fichier inférieur à 500Ko pour l\'image 2!';
        }

        break;
        }

        }else{

            if(!empty($request->get('image_2-hidden'))){

                if(preg_match('#^[a-z0-9A-Z \.]{3,}$#i',$request->get('image_2-hidden'))){

                    $finalFileName2 = ($request->get('image_2-hidden'));

                }else{

                    $finalFileName2 = null;
                }

            }else{

                $finalFileName2 = null;
            }

        }

        //verification du champ image_3
        if($_FILES['image_3']['name']>""){


            switch ($_FILES['image_3']['error']) {

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

            if($extension=='image/jpeg' || $extension=='image/png'|| $extension=='image/bmp' || $extension=='image/gif'){


        //recupération, deplacement et chgt du nom du fichier
                if(($error)){

                    $newFileName =  $this->createFileName(10);

                    if($extension == 'image/jpeg'){
                        $newFileExt = '.jpg';
                    }elseif ($extension == 'image.png') {
                        $newFileExt = '.png';
                    }

                    return $finalFileName3 = $newFileName .$newFileExt;
                }

                $image_3 = $_FILES['image_3']['tmp_name'];
                $image_3->move(ROOTH.'web/assets/images/',$finalFileName3);

                $success[]= 'image sauvegardée';


            }else{

                $error[] = 'Le format de l\'image 3 n\'est pas valide';

            }
        }else{

            $error[] = 'Veuillez choisir un fichier inférieur à 500Ko pour l\'image 3!';
        }

        break;
        }

        }else{

            if(!empty($request->get('image_3-hidden'))){

                if(preg_match('#^[a-z0-9 \.]{3,}$#i',$request->get('image_3-hidden'))){

                    $finalFileName3 = ($request->get('image_3-hidden'));

                }else{

                    $finalFileName3 = null;
                }

            }else{

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

public function VerificationContact($request, $app){

    /*  Verification du formulaire de contact*/

    $errors = [];

//verification des champs name et surname
    if(null!=($request->get('name')) && !empty($request->get('name'))){

        if (!preg_match('#^[a-z \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{3,30}$#i',$request->get('name'))) {

            $errors[] = 'Nom  invalide';
        }
    }else{

        $errors[] = "Veuillez remplir le champ nom";

    }

    if(null!=($request->get('surname')) && !empty($request->get('surname'))){

        if (!preg_match('#^[a-z \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{3,30}$#i',$request->get('surname'))) {

            $errors[] = 'Prénom  invalide';
        }
    }else{

        $errors[] = "Veuillez remplir le champ prénom";

    }
//verification du champ sujet
    if(null!=($request->get('subject')) && !empty($request->get('subject'))){

        if (!preg_match('#^[a-z \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{3,30}$#i',$request->get('subject'))) {

            $errors[] = 'sujet  invalide';
        }
    }else{

        $errors[] = "Veuillez remplir le champ sujet";

    }

//Verification du champ email
    if(null!=($request->get('email')) && !empty($request->get('email'))){

        if (!filter_var(htmlspecialchars($request->get('email')),FILTER_VALIDATE_EMAIL)) {

            $error[] = 'email n\'est pas valide';

        }

    }else{

        $error[] = 'Veuillez remplir le champ email';

    }

    return array(
        'errors'  => $errors,
    );

}


public function VerificationNewAd($request, $app){


        /*-----------Verification du formulaire----------*/
        $errors = [];

        //verification du champ description
        if(null!=($request->get('description')) && !empty($request->get('description'))){

            if (!preg_match('#^[a-z0-9 \-áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\_\s\;\!\.\?\:\,\'+\(\)]{3,}$#i',$request->get('description'))) {

                $errors[] = 'Description invalide';
            }
        }else{

            $errors[] = "Veuillez remplir le champ description";

        }

        
    }
}

?>