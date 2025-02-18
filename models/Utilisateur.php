<?php
//model Utilisateur.php
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
    public $autreinfos;


    // Constructeur dynamique
    public function __construct()
    {
        $args = func_get_args(); // Récupérer tous les arguments passés

        if (count($args) > 1) {
            $this->nom = $args[0];
            $this->prenom = $args[1];
            $this->courriel = $args[2];
            $this->mot_de_passe = $args[3];
        } else if (count($args) > 4) {
            $this->no_tel_cellulaire = $args[4] ?? "";
            $this->no_tel_travail = $args[5] ?? "";
            $this->no_tel_maison = $args[6] ?? "";
            $this->statut = $args[7] ?? 0;
            $this->NoUtilisateur = $args[8] ?? "";



        }




    }

}