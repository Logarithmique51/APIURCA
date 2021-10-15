<?php
namespace App\event;


class day{

    private array $cours;
    private \DateTime $dod;
    const LUNDI = 0;
    const MARDI = 1;
    const MERCREDI = 2;
    const JEUDI = 3;
    const VENDREDI = 4;
    const SAMEDI = 5;
    const DIMANCHE = 6;

    public function __construct(array $cours,\DateTime $dod)
    {
        $this->cours=$cours;
        $this->dod = $dod;
    }

    public function afficher(){
        $module ="Date du jour ".$this->dod->format('d/m/Y');

        foreach($this->cours as $cour){
            $module .= $cour->afficher();
            $module .= "<br>";
        }
        return $module;
    }

    public function getCours() : array{
      return $this->cours;
    }


}