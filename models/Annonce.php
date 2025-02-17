<?php
// models/Annonce.php

    class Annonce {
        public $NoAnnonce;
        public $NoUtilisateur;
        public $DescriptionA;
        public $Description;
        public $Prix;
        public $Parution;
        public $Etat;
        public $Photo;
        public $Categorie;
        public $NomCategories;


    public $NomAuteur;
    public $PrenomAuteur;

    public $CourrielAuteur;

        public function __construct($NoUtilisateur, $NoAnnonce, $DescriptionA, $Description, $Prix, $Parution, $Etat, $Photo, $Categorie) {
            $this->NoUtilisateur = $NoUtilisateur;
            $this->NoAnnonce = $NoAnnonce;
            $this->DescriptionA = $DescriptionA;
            $this->Description = $Description;
            $this->Prix = $Prix;
            $this->Parution = $Parution;
            $this->Etat = $Etat;
            $this->Photo = $Photo;
            $this->Categorie = $Categorie;
        }
    }