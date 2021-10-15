<?php
namespace App\lightAPI;

use App\finder\timetable;
use Goutte\Client;
use DateInterval;
use DateTime;

    class lightTimeTable{



        public static function get(String $login, String $password, Array $data) : Array{
            $month = $data["Month"];
            $day = $data["Day"];
            $year = $data["Year"];
            $group = $data["Group"];
            if($group == "auto"){
                $group = timetable::getPlanningThor($login,$password);
            }
            $client = new Client();
            $crawler = $client->request('GET', 'https://cas.univ-reims.fr/cas/login?service=https%3a%2f%2fcelcat-auth.univ-reims.fr%2f910913%2f'.$group);
            $form = $crawler->selectButton('SE CONNECTER')->form();
            $crawler = $client->submit($form, array('username' => $login , 'password' => $password));
            $date = new DateTime($year.'-'.$month.'-'.$day);
            $count = 0;

                switch($date->format('D')){

                  case 'Mon':
                      $date = $date;
                      $count = 0;
                      break;
                  case 'Tue':
                      $date->sub(new DateInterval('P1D'));
                      $count = 1;
                      break;
                  case 'Wed':
                      $date->sub(new DateInterval('P2D'));
                      $count = 2;
                      break;
                  case 'Thu':
                      $date->sub(new DateInterval('P3D'));
                      $count= 3;
                      break;
                  case 'Fri':
                      $date->sub(new DateInterval('P4D'));
                      $count= 4;
                      break;
                  case 'Sat':
                      $date->sub(new DateInterval('P5D'));
                      $count= 5;
                      break;
                  case 'Sun':
                      $date->sub(new DateInterval('P6D'));
                      $count= 6;
                      break;    
                  default :
                      $date->sub(new DateInterval('P1D'));
                      $count= 0;
                      break;
    
                }

                $date  = $date->format('d/m/Y');
                $selector = "event[date='".$date."']";

                $test = $crawler->filter($selector." > day")->each(function($node,$i) use ($count){
                    if($node->text()==$count){
                        return $node->siblings();
                    } 
                });

                $cleandata = array();

                /* Clean DATA*/
                foreach($test as $node){
                    if(gettype($node)!="NULL" ){
                        array_push($cleandata,$node);
                    }else{}
                }
                /*-----------*/

                $json = array();
                foreach($cleandata as $data){
                    $tempArray = array("Type" => null, "starttime" => null, "endtime" => null , "category" => null , "room" => null);
                        $tempArray["Type"] = $data->filter("module > item > a")->text("INDISPONIBLE");
                        $tempArray["starttime"] = $data->filter("starttime")->text("INDISPONIBLE");
                        $tempArray["endtime"] = $data->filter("endtime")->text("INDISPONIBLE");
                        $tempArray["category"] = $data->filter("category")->text("INDISPONIBLE");
                        $tempArray["room"] = $data->filter("room")->text("INDISPONIBLE");
                        array_push($json,$tempArray);
                }
                return $json;
        }
    }
?>
