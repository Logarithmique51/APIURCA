<?php 

namespace App\event;

use App\finder\timetable;
use DateInterval;
use DateTime;
use SplFixedArray;
use Symfony\Component\DomCrawler\Crawler;

class week{
    private int $number; 
    private array $days = array();
    private Crawler $crawler;


    function __construct(Crawler $crawler,DateTime $dateTime)
    {
       $this->days=$this->initWeek($dateTime,$crawler);
    }


    private function initWeek(DateTime $today , Crawler $crawler) : array
    {
        timetable::formatDate($today);
        $selector = "event[date='".$today->format('d/m/Y')."']";
        
        $day = $crawler->filter($selector." > day")->each(function($node,$i){
            return $node->text();
        });
   
        $starttime = $crawler->filter($selector." > starttime")->each(function($node,$i){
            return $node->text();
        });
   
        $endtime = $crawler->filter($selector." > endtime")->each(function($node,$i){
            return $node->text();
        });
   
        $category = $crawler->filter($selector." > category")->each(function($node,$i){
            return $node->text();
        });
   
        $class = $crawler->filter($selector." > resources > module")->each(function($node,$i){
            return $node->text();
        });

        
   
        $group = $crawler->filter($selector." > resources > group")->each(function($node,$i){
            return $node->text();
        });
   
        $room = $crawler->filter($selector." > resources > room")->each(function($node,$i){
            return $node->text("Indispo");
        });

        $i = 0;
        $a = 0;

        $temp_week = array();
        for($a = 0 ; $a<7 ;$a++){
          array_push($temp_week,array());
        } 

        for($i=0;$i<count($day);$i++){
            
            $current_array = array(
                "day"=>$day[$i],
                "starttime"=>$starttime[$i],
                "endtime"=>$endtime[$i],
                "category"=>$category[$i],
                "class"=>$class[$i],
                "room"=>$room[$i],
                "groups"=>$group[$i]); 
           array_push($temp_week[$day[$i]],new cours($current_array));
        }

        $week_of_days = array();
        $y = 0;

        foreach($temp_week as $test){
            $current_date = clone $today;
            array_push($week_of_days,new day($test,$current_date->add(new DateInterval('P'.$y.'D'))));
            $y++;
        }

        return $week_of_days;
    }


    public function afficher(){
        $module="";
        foreach($this->days as $day){
           $module .= $day->afficher();
           $module .= "<br>";
        }
        return $module;
    }


    public function getDay(DateTime $day) : day {

        switch($day->format('D')){
            case 'Mon':
                return $this->days[0];
                break;
            case 'Tue':
                return $this->days[1];
                break;
            case 'Wed':
                return $this->days[2];
                break;
            case 'Thu':
                return $this->days[3];
                break;
            case 'Fri':
                return $this->days[4];
                break;
            case 'Sat':
                return $this->days[5];
                break;
            case 'Sun':
                return $this->days[6];
                break;    
            default :
                return $this->days[0];
                break;
            }
    
    }


    public function number(){
        return $this->number;
    }

    }


?>