<?php
use App\security\credentials;
use App\finder\grades;
use App\finder\informations;
use App\lightAPI\lightTimeTable;

require __DIR__ . "/vendor/autoload.php";

    header('Content-Type: application/json');
    $datas = json_decode(file_get_contents("php://input"),true);

    if(!empty($datas)){

        $login = $datas["Login"];
        $password = $datas["Password"];
        $action = $datas["Action"];
        
        if (!credentials::checkValidity($datas)){
            echo json_encode(array("Erreur" => "Requete incorrect"));
            die();
        }
        
        switch($action){
            case "credentials": 
                $status = credentials::checkCredentials($login,$password);
                echo json_encode(array("Status" => $status));
                break;
            case "grades":
                $response = grades::getGrades($login,$password);
                echo json_encode($response);
                break;
            case "informations":
                $response = informations::getInformations($login,$password);
                echo json_encode($response);
                break;
            case "timetable":
                $response = lightTimeTable::get($login,$password,$datas["Data"]);
                echo json_encode($response);
                break;
            default:
                echo json_encode(array("Erreur" => "Requete incorrect"));
                break;
        }
        die();


    }


?>

