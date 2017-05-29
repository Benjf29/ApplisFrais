<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class A_comptable extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

		// chargement du modèle d'accès aux données qui est utile à toutes les méthodes
		$this->load->model('dataAccess');
    }

	/**
	 * Accueil du visiteur
	 * La fonction intègre un mécanisme de contrôle d'existence des 
	 * fiches de frais sur les 6 derniers mois. 
	 * Si l'une d'elle est absente, elle est créée
	*/
	public function accueil()
	{	// TODO : Contrôler que toutes les valeurs de $unMois sont valides (chaine de caractère dans la BdD)
	
		// chargement du modèle contenant les fonctions génériques
		$this->load->model('functionsLib');

		// obtention de la liste des 6 derniers mois (y compris celui ci)
		$lesMois = $this->functionsLib->getSixDerniersMois();
		
		// obtention de l'id de l'utilisateur mémorisé en session
		$idUtilisateur = $this->session->userdata('idUser');
		
		
		
		// contrôle de l'existence des 6 dernières fiches et création si nécessaire
		foreach ($lesMois as $unMois){
			if(!$this->dataAccess->ExisteFiche($idUtilisateur, $unMois)) $this->dataAccess->creeFiche($idUtilisateur, $unMois);
		}
		// envoie de la vue accueil du visiteur
		$this->templates->load('t_comptable', 'v_conAccueil');
	}
	
	/**
	 * Liste les fiches existantes du visiteur connecté et 
	 * donne accès aux fonctionnalités associées
	 *
	 * @param $idUtilisateur : l'id du visiteur 
	 * @param $message : message facultatif destiné à notifier l'utilisateur du résultat d'une action précédemment exécutée
	*/
	public function mesFiches ($idUtilisateur, $message=null)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
	
		$idUtilisateur = $this->session->userdata('idUser');

		$data['notify'] = $message;
		$data['mesFiches'] = $this->dataAccess->getFiches($idUtilisateur);		
		$this->templates->load('t_comptable', 'v_conMesFiches', $data);	
	}	
	
	public function mesFichesCon ($idUtilisateur, $message=null)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
	
	$idUtilisateur = $this->session->userdata('idUser');
	
	$data['notify'] = $message;
	$data['mesFiches'] = $this->dataAccess->getFichesCon($idUtilisateur);
	$this->templates->load('t_comptable', 'v_conMesFiches', $data);
	}
	
	public function touteFiches ($idUtilisateur, $message=null)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
	
	$idUtilisateur = $this->session->userdata('idUser');
	
	$data['notify'] = $message;
	$data['mesFiches'] = $this->dataAccess->getFichesCon();
	$this->templates->load('t_comptable', 'v_conToutesFiches', $data);
	}
	

	/**
	 * Présente le détail de la fiche sélectionnée 
	 * 
	 * @param $idUtilisateur : l'id du visiteur 
	 * @param $mois : le mois de la fiche à modifier 
	*/
	public function voirFiche($idUtilisateur, $mois)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session

		$data['numAnnee'] = substr( $mois,0,4);
		$data['numMois'] = substr( $mois,4,2);
		$data['lesFraisHorsForfait'] = $this->dataAccess->getLesLignesHorsForfait($idUtilisateur,$mois);
		$data['lesFraisForfait'] = $this->dataAccess->getLesLignesForfait($idUtilisateur,$mois);		

		$this->templates->load('t_comptable', 'v_conVoirListeFrais', $data);
	}

	/**
	 * Présente le détail de la fiche sélectionnée et donne 
	 * accés à la modification du contenu de cette fiche.
	 * 
	 * @param $idUtilisateur : l'id du visiteur 
	 * @param $mois : le mois de la fiche à modifier 
	 * @param $message : message facultatif destiné à notifier l'utilisateur du résultat d'une action précédemment exécutée
	*/
	public function modFiche($idUtilisateur, $mois, $message=null)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session

		$data['notify'] = $message;
		$data['numAnnee'] = substr( $mois,0,4);
		$data['numMois'] = substr( $mois,4,2);
		$data['lesFraisHorsForfait'] = $this->dataAccess->getLesLignesHorsForfait($idUtilisateur,$mois);
		$data['lesFraisForfait'] = $this->dataAccess->getLesLignesForfait($idUtilisateur,$mois);		

		$this->templates->load('t_comptable', 'v_conModListeFrais', $data);
	}

	/**
	 * Signe une fiche de frais en changeant son état
	 * 
	 * @param $idUtilisateur : l'id du visiteur 
	 * @param $mois : le mois de la fiche à signer
	*/
	public function signeFiche($idUtilisateur, $mois)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
		// TODO : intégrer une fonctionnalité d'impression PDF de la fiche

	    $this->dataAccess->signeFiche($idUtilisateur, $mois);
	}

	public function validFiche($idUtilisateur, $id)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
	// TODO : intégrer une fonctionnalité d'impression PDF de la fiche
	
	$this->dataAccess->validFiche($idUtilisateur, $id);
	}
	
	public function refuFiche($idUtilisateur,$mois,$commentaire)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
	// TODO : intégrer une fonctionnalité d'impression PDF de la fiche
	
	$this->dataAccess->refuFiche($idUtilisateur,$mois,$commentaire);
	
	}
	public function refuFicheCom($idUtilisateur, $mois)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
	// TODO : intégrer une fonctionnalité d'impression PDF de la fiche
	
		$data['mois'] = $mois;
		$data['idUtilisateur'] = $idUtilisateur;
	$this->templates->load('t_comptable', 'v_conRefuFicheCom',$data);
	}
	/**
	 * Modifie les quantités associées aux frais forfaitisés dans une fiche donnée
	 * 
	 * @param $idUtilisateur : l'id du visiteur 
	 * @param $mois : le mois de la fiche concernée
	 * @param $lesFrais : les quantités liées à chaque type de frais, sous la forme d'un tableau
	*/
	public function majForfait($idUtilisateur, $mois, $lesFrais)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
		// TODO : valider les données contenues dans $lesFrais ...
		
		$this->dataAccess->majLignesForfait($idUtilisateur,$mois,$lesFrais);
		$this->dataAccess->recalculeMontantFiche($idUtilisateur,$mois);
	}

	/**
	 * Ajoute une ligne de frais hors forfait dans une fiche donnée
	 * 
	 * @param $idUtilisateur : l'id du visiteur 
	 * @param $mois : le mois de la fiche concernée
	 * @param $lesFrais : les quantités liées à chaque type de frais, sous la forme d'un tableau
	*/
	public function ajouteFrais($idUtilisateur, $mois, $uneLigne)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
		// TODO : valider la donnée contenues dans $uneLigne ...

		$dateFrais = $uneLigne['dateFrais'];
		$libelle = $uneLigne['libelle'];
		$montant = $uneLigne['montant'];

		$this->dataAccess->creeLigneHorsForfait($idUtilisateur,$mois,$libelle,$dateFrais,$montant);
	}

	/**
	 * Supprime une ligne de frais hors forfait dans une fiche donnée
	 * 
	 * @param $idUtilisateur : l'id du visiteur 
	 * @param $mois : le mois de la fiche concernée
	 * @param $idLigneFrais : l'id de la ligne à supprimer
	*/
	public function supprLigneFrais($idUtilisateur, $mois, $idLigneFrais)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session et cohérents entre eux

	    $this->dataAccess->supprimerLigneHorsForfait($idLigneFrais);
	}
	
	
	public function paiementFiche ($idUtilisateur, $message=null)
	{	// TODO : s'assurer que les paramètres reçus sont cohérents avec ceux mémorisés en session
	
	$idUtilisateur = $this->session->userdata('idUser');
	
	$data['notify'] = $message;
	$data['mesFiches'] = $this->dataAccess->getFiches($idUtilisateur);
	$this->templates->load('t_comptable', 'v_conFichesPaiement', $data);
	}
	
}