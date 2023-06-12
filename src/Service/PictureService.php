<?php 

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // On donne un nouveau nom à l'image
        $fichier = md5(uniqid()) . '.webp';

        // On récupère les infos de l'image
        $picture_infos =getimagesize($picture);

        if($picture_infos === false){
            throw new Exception('Format d\'image incorrect');
        }

        // On vérifie le format d'image
        switch ($picture_infos['mime']){
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
                default:
                throw new Exception('Format d\'image incorrect');
        }

        // On recadre l'image
        // On récupère les dimensions
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        // On vérifie l'orientation de l'image
        switch ($imageWidth <=> $imageHeight){
            case -1: // portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break;
            case 0: // carré
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: // paysage
                $squareSize = $imageHeight;
                $src_x = ($imageWidth - $squareSize) / 2;
                $src_y = 0;
                break;
        }

        // On crée une nouvelle image "vierge"
        $resized_picture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);
        $path = $this->params->get('images_directory') . $folder;

        //On créerle dossier de destination s'il n'existe pas
        if(!file_exists($path . '/mini/')){
            mkdir($path . '/mini/', 0755, true);
        }

        // On stock l'image recadrée
        imagewebp($resized_picture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);

        $picture->move($path . '/', $fichier);

        return $fichier;
    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // Vérification si le nom de l'image est différente à 'default.webp'
        if($fichier !== 'default.webp'){
            // Variable pour indiquer si la suppression fonctionne ou pas
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            // Vérification du chemin où se trouve l'image
            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;

            // Vérification de l'existence du fichier mini
            if(file_exists($mini)){
                // Supprime le fichier
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;

            // Vérification de l'existence du fichier original
            if(file_exists($original)){
                unlink($original);
                $success = true;
            }
            return $success;
        }
        // Si l'image' s'appelle 'default.webp', elle ne se supprime pas
        return false;
    }
}