<?php 

namespace App\security;

use App\finder\timetable;
use Goutte\Client;


    class credentials{
     
        public static function checkCredentials(String $login,String $password) : bool {
            $client = new Client();
            $crawler = $client->request('GET', 'https://cas.univ-reims.fr/cas/login?service=https%3A%2F%2Fthor.univ-reims.fr%2Flogincas.php');
            $form = $crawler->selectButton('SE CONNECTER')->form();
            $crawler = $client->submit($form, array('username' => $login , 'password' => $password));
            $error = $crawler->filterXPath('//*[@id="msg"]');
            return count($error) != 0 ? false : true; 
        }

        public static function checkValidity(Array $array) : bool {
            
            $login = $array["Login"];
            $password = $array["Password"];
            $action = $array["Action"];
            $data = $array["Data"];
            if(empty($login) || empty($password) || empty($action)){
                
                return false;
            }
            if(!credentials::checkCredentials($login,$password)){
                echo json_encode(array("Erreur" => "Identifiant / Mot de passe incorrect(s)"));
                die();
            }
            if(!in_array($action,array("credentials" , "timetable" , "informations" , "grades"))){
                return false;
            }
            if($action == "timetable"){
                return credentials::checkSimple($data,$login,$password);
            }else{

                return true;  
            }

        }

        private static function checkSimple(Array $data, String $login, String $password) : bool {

            if(!checkdate($data["Month"],$data["Day"],$data["Year"])){

                return false;

            }else{
                if(!empty($data["Group"]) && $data["Group"] != "auto"){

                    $string = file_get_contents("groupe_data.json");
                    $json_a = json_decode($string,true);
                    foreach($json_a as $key => $val){
                        if($val == $data["Group"]){
                            return true;
                        }
                    }
                    return false;
                }elseif($data["Group"]=="auto"){

                    try {
                        $group = timetable::getPlanningThor($login,$password);
                        return true;
                    } catch (\Throwable $th) {
                        echo json_encode(array("Erreur" => "Impossible de recuperer l'EDT automatiquement veuillez specifier un groupe a la cle 'Group' "));
                        die();
                    }
                }else{
                    return false;
                }
            }
        }

    }
    

?>  


