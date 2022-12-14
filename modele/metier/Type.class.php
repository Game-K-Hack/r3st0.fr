<?php

namespace modele\metier;

class Type {
    
    private int $idTC;
    private String $labelle;
    
    public function __construct(int $idTC, String $labelle) {
        $this->idTC = $idTC;
        $this->labelle = $labelle;
    }
    
    public function __toString() {
        return $this->labelle;
    }

    public function getIdTC(): int {
        return $this->idTC;
    }

    public function getLabelle(): String {
        return $this->labelle;
    }

    public function setIdTC(int $idTC): void {
        $this->idTC = $idTC;
    }

    public function setLabelle(String $labelle): void {
        $this->labelle = $labelle;
    }   
}

