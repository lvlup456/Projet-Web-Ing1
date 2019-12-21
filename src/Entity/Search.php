<?php


namespace App\Entity;


class Search
{
    protected $departement;
    protected $category;
    protected $coaching;
    protected $natation;
    protected $secourisme;
    protected $educateur;
    protected $sportCollectif;
    protected $sportIndividuel;

    public function getDepartement(){
        return $this->departement;
    }
    public function setDepartement($departement){
        $this->departement = $departement;
    }
    public function getCategory(){
        return $this->category;
    }
    public function setCategory($category){
        $this->category = $category;
    }
    public function getCoaching(){
        return $this->coaching;
    }
    public function setCoaching($coaching){
        $this->coaching = $coaching;
    }
    public function getNatation(){
        return $this->natation;
    }
    public function setNatation($natation){
        $this->natation = $natation;
    }
    public function getSecourisme(){
        return $this->secourisme;
    }
    public function setSecourisme($secourisme){
        $this->secourisme = $secourisme;
    }
    public function getEducateur(){
        return $this->educateur;
    }
    public function setEducateur($educateur){
        $this->educateur = $educateur;
    }
    public function getSportCollectif(){
        return $this->sportCollectif;
    }
    public function setSportCollectif($sportCollectif){
        $this->sportCollectif = $sportCollectif;
    }
    public function getSportIndividuel(){
        return $this->sportIndividuel;
    }
    public function setSportIndividuel($sportIndividuel){
        $this->sportIndividuel = $sportIndividuel;
    }

    public function __toString(){
        return "";
    }
}
