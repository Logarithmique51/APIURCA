<?php
namespace App\finder;
use Goutte\Client;


class grades{
 
   public static function getGrades(String $login, String $password) : Array{ 

        $client = new Client();
        $crawler = $client->request('GET', 'https://cas.univ-reims.fr/cas/login?service=https://ebureau.univ-reims.fr/uPortal/Login');
        $form = $crawler->selectButton('SE CONNECTER')->form();
        $crawler = $client->submit($form, array('username' => $login , 'password' => $password));
        $crawler = $client->request('GET', 'https://ebureau.univ-reims.fr/uPortal/render.userLayoutRootNode.uP?uP_sparam=activeTab&activeTab=3&uP_root=u19l1n9');
        $newform = $crawler->filterXPath('//*[@id="formMenu"]')->form();
        $crawler = $client->submit($newform, array('formMenu:_idcl' => 'formMenu:linknotes1'));
        
        $form = $crawler->filter('form')->each(function($c,$i){
            return $c;
        });

        $crawler = $crawler->filter('.portlet-table')->each(function($c,$i){
        return $c;
        });

        $notes = $crawler[1]->filter('tbody')->children()->each(function($c,$i){
            return $c;
        });

        $all = array();
        $rowCount = 0;
        foreach($notes as $note){

            // <-- Query all steps of current scholar year --> 
            $row = $note->children()->each(function($c,$i){
                return $c;
            });
            // <--------------------------------------------> 
            
            $nbSession = count($row[3]->filter('tr'));

            //<-- Query all constant of current scholar year --> 
            $years = $row[0]->text();
            $name = $row[2]->text();
            $formData = array( 
                    'link' =>  $row[2]->filter('a')->attr('id'),
                    'row' => $rowCount
                );
            //<---------------------------------------------->

            // <-- Check if one or two session or current session --> 
                if($nbSession==1){
                    
                    $nameSession = $row[3]->text();
                    $note = $row[4]->text();
                    $resulat = $row[5]->text();
                    $mention = $row[6]->text();
                    array_push($all,array(
                        'Years' => $years,
                        'Name' => $name,
                        'FormData' => $formData,
                        'SessionName' => $nameSession,
                        'Note' => $note,
                        'Result' => $resulat,
                        'Mention' => $mention,
                        'NumberOfSession' => 1,
                        'CurrentSession' => false
                    ));
                }else if($nbSession==2){  
        
                    $firstNameSession = $row[3]->filter('tr')->first()->text();
                    $secondNameSession = $row[3]->filter('tr')->last()->text();
                    $firstNote = $row[4]->filter('tr')->first()->text();
                    $secondNote = $row[4]->filter('tr')->last()->text();
                    $firstResult = $row[5]->filter('tr')->first()->text();
                    $secondResult = $row[5]->filter('tr')->last()->text();
                    $mention = $row[6]->text();
                    array_push($all,array(
                        'Years' => $years,
                        'Name' => $name,
                        'FormData' => $formData,
                        'SessionName' => array(
                                $firstNameSession => array(
                                    'Note' => $firstNote,
                                    'Result' => $firstResult
                                ),
                                $secondNameSession => array(
                                    'Note' => $secondNote,
                                    'Result' => $secondResult
                                )
                        ),
                        'Mention' => $mention,
                        'NumberOfSession' => 2,
                        'CurrentSession' => false
                    ));
                }else if($nbSession==0){

                    array_push($all,array(
                        'Years' => $years,
                        'Name' => $name,
                        'FormData' => $formData,
                        'CurrentSession' => true
                    ));
                };
                $rowCount++;
        };
        return $all;
    }
}
    ?>
