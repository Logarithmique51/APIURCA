<?php
namespace App\finder;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

    class informations{

        public static function getInformations(String $login,String $password) : array{
            $client = new Client();
            $crawler = $client->request('GET', 'https://cas.univ-reims.fr/cas/login?service=https://ebureau.univ-reims.fr/uPortal/Login');
            $form = $crawler->selectButton('SE CONNECTER')->form();
            $crawler = $client->submit($form, array('username' => $login , 'password' => $password));
            $crawler = $client->request('GET', 'https://ebureau.univ-reims.fr/uPortal/render.userLayoutRootNode.uP?uP_sparam=activeTab&activeTab=3&uP_root=u19l1n9');
            $newform = $crawler->filterXPath('//*[@id="formMenu"]')->form();
            $crawler = $client->submit($newform, array('formMenu:_idcl' => 'formMenu:linketacivil1'));
           
            $image = $crawler->filterXPath('//*[@id="photo"]')->each(function($c,$i){
                return $c;
            });
           
            informations::getImageUniv($login,$password,$image[0]->attr('src'));
           
            $array = $crawler->filter('.portlet-body > table');
            $array = $array->each(function($c,$i){
                return $c;
            });
            $test = $array[0]->filter('tr > td > table');
            $test = $test->filter('tr')->each(function(Crawler $a,$i){
                return $a;
            });
            $myjson = array();

            foreach($test as $def){
            $array = $def->children()->each(function($c,$i){
                    return $c;
                });
                if(count($array)==2){
                    switch($array[0]->text()){
                        case 'Nom et Prénom': 
                            $key = 'Name';
                            break;
                        case 'Nationalité':
                            $key = 'Nationality';
                            break;
                        case 'Né(e) le':
                            $key = 'Birthday';
                            break;
                        case 'A':
                            $key = 'BirthCity';
                            break;
                        case 'Département ou Pays':
                            $key = 'Department_Country';
                            break;
                        case 'Dossier':
                            $key = 'ID';
                            break;
                        case 'Email':
                            $key = 'Mail';
                            break;
                        default:
                            $key = $array[0]->text();
                    }
                    $value = $array[1]->text(); 
                    array_push($myjson,array($key => $value));

                }        
            }
            return $myjson;
        }

        private static function getImageUniv(String $newlogin,String $newpassword,String $path){
            
            $newClient = new Client();
            $crawler = $newClient->request('GET',$path);
            $form = $crawler->selectButton('SE CONNECTER')->form();
            $crawler = $newClient->submit($form, array('username' => $newlogin , 'password' => $newpassword));

        }
    }
?>