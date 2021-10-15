<?php

namespace App\finder;
use DateInterval;
use DateTime;
use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class timetable{

        private DateTime $date;


        function __construct(Datetime $date)
        {
            $this->data = $date;
            $madate = new DateTime('now');
            timetable::formatDate($madate);

        }

        public static function formatDate(Datetime $date) : void{   
              switch($date->format('D')){
                case 'Mon':
                    $date = $date;
                    break;
                case 'Tue':
                    $date->sub(new DateInterval('P1D'));
                    break;
                case 'Wed':
                    $date->sub(new DateInterval('P2D'));
                    break;
                case 'Thu':
                    $date->sub(new DateInterval('P3D'));
                    break;
                case 'Fri':
                    $date->sub(new DateInterval('P4D'));
                    break;
                case 'Sat':
                    $date->sub(new DateInterval('P5D'));
                    break;
                case 'Sun':
                    $date->sub(new DateInterval('P6D'));
                    break;    
                default :
                    $date->sub(new DateInterval('P1D'));
                    break;
                 }
           
        }

        public static function getPlanningThor(string $login , string $password) : String{
            $client = new Client();
            $crawler = $client->request('GET', 'https://cas.univ-reims.fr/cas/login?service=https%3A%2F%2Fthor.univ-reims.fr%2Flogincas.php');
            $form = $crawler->selectButton('SE CONNECTER')->form();
            $crawler = $client->submit($form, array('username' => $login , 'password' => $password));
            $crawler = $client->request('GET', 'https://thor.univ-reims.fr/mesinfos/groupes.php');
            $mylink = $crawler->filter('td')->each(function(Crawler $c, $i){
                return $c;
            });   
            $planninglink = $mylink[5]->children()->attr('href');     
            $planninglink = str_replace("html","xml",$planninglink);
            $array = explode("/",$planninglink);
            return $array[4];
        }

        public static function getPlanningUrca(String $login , string $password, string $extension) : Crawler{
            $client = new Client();
            $crawler = $client->request('GET', 'https://cas.univ-reims.fr/cas/login?service=https%3a%2f%2fcelcat-auth.univ-reims.fr%2f910913%2f'.$extension);
            $form = $crawler->selectButton('SE CONNECTER')->form();
            $crawler = $client->submit($form, array('username' => $login , 'password' => $password));
            return $crawler;


        }
    


    }   


?>