<?

    namespace App\event;

    class cours{

        private int $day;
        private array $hourly;
        private string $type;
        private string $subject;
        private string $group;
        private string $room;

        public function __construct(array $event)
        {   
            $this->day = $event['day'];
            $this->hourly = array($event['starttime'],$event['endtime']);
            $this->type = $event['category'];
            $this->subject = $event['class'];
            $this->group = $event['groups'];
            $this->room = $event['room'];

        }

        public function getDay(){ return $this->day ;}
        public function getHourly(){ return $this->hourly ;}
        public function getType(){ return $this->type ;}
        public function getSubject(){ return $this->subject ;}
        public function getGroup(){ return $this->group ;}
        public function getRoom(){ return $this->room ;}
        public function getStartTime() { return $this->hourly[0]; }
        public function getEndTime() { return $this->hourly[1]; }


        public function afficher(){
            $module ="<h1>".$this->getSubject()."</h1>";
            return $module;
        }

    }

?>