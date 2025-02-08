<?php

    class Utilisateur
    {
        public $NoUtilisateur;
        public $nom;
        public $prenom;
        public $courriel;
        public $mot_de_passe;
        public $no_tel_maison = "";
        public $no_tel_travail = "";
        public $no_tel_cellulaire = "";
        public $statut;

        // Constructeur dynamique
        public function __construct()
        {
            $args = func_get_args(); // Récupérer tous les arguments passés


        // Vérifier qu'il y a au moins 4 arguments
            if (count($args) < 4) {
                throw new InvalidArgumentException("Au moins 4 paramètres sont requis.");
            }

        // Assigner les valeurs aux propriétés
            $this->nom = $args[0];
            $this->prenom = $args[1];
            $this->courriel = $args[2];
            $this->mot_de_passe =  $args[3];

        // Assigner les valeurs optionnelles si elles existent
            $this->no_tel_cellulaire = $args[4] ?? "";
            $this->no_tel_travail = $args[5] ?? "";
            $this->no_tel_maison = $args[6] ?? "";
            $this->statut = $args[7]??0;
            $this->NoUtilisateur = $args[8]??"";
        }




    }