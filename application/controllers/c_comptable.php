<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ContrÃ´leur du module COMPTABLE de l'application
 */
class C_comptable extends CI_Controller {

	/**
	 * Aiguillage des demandes faites au contrÃ´leur
	 * La fonction _remap est une fonctionnalitÃ© offerte par CI destinÃ©e Ã  remplacer
	 * le comportement habituel de la fonction index. GrÃ¢ce Ã  _remap, on dispose
	 * d'une fonction unique capable d'accepter un nombre variable de paramÃ¨tres.
	 *
	 * @param $action : l'action demandÃ©e par le comptable
	 * @param $params : les Ã©ventuels paramÃ¨tres transmis pour la rÃ©alisation de cette action
	 */
	public function _remap($action, $params = array())
	{
		// chargement du modÃ¨le d'authentification
		$this->load->model('authentif');

		// contrÃ´le de la bonne authentification de l'utilisateur
		if (!$this->authentif->estConnecte())
		{
			// l'utilisateur n'est pas authentifiÃ©, on envoie la vue de connexion
			$data = array();
			$this->templates->load('t_connexion', 'v_connexion', $data);
		}
		else
		{
			// Aiguillage selon l'action demandÃ©e
			// CI a traitÃ© l'URL au prÃ©alable de sorte Ã  toujours renvoyer l'action "index"
			// mÃªme lorsqu'aucune action n'est exprimÃ©e
			if ($action == 'index')				// index demandÃ© : on active la fonction accueil du modÃ¨le visiteur
			{
				$this->load->model('a_comptable');

				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');

				$this->a_comptable->accueil();
			}
			elseif ($action == 'mesFiches')		// mesFiches demandÃ© : on active la fonction mesFiches du modÃ¨le visiteur
			{
				$this->load->model('a_comptable');

				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');

				$idVisiteur = $this->session->userdata('idUser');
				$this->a_comptable->mesFiches($idVisiteur);
			}
			elseif ($action == 'mesFichesCon')		// mesFiches demandÃ© : on active la fonction mesFiches du modÃ¨le comptable
			{
				$this->load->model('a_comptable');
			
				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');
			
				$idVisiteur = $this->session->userdata('idUser');
				$this->a_comptable->mesFichesCon($idVisiteur);
			}
			elseif ($action == 'deconnecter')	// deconnecter demandÃ© : on active la fonction deconnecter du modÃ¨le authentif
			{
				$this->load->model('authentif');
				$this->authentif->deconnecter();
			}
			elseif ($action == 'voirFiche')		// voirFiche demandÃ© : on active la fonction voirFiche du modÃ¨le authentif
			{	// TODO : contrÃ´ler la validitÃ© du second paramÃ¨tre (mois de la fiche Ã  consulter)
					
				$this->load->model('a_comptable');

				// obtention du mois de la fiche Ã  modifier qui doit avoir Ã©tÃ© transmis
				// en second paramÃ¨tre
				$mois = $params[0];
				// mÃ©morisation du mode modification en cours
				// on mÃ©morise le mois de la fiche en cours de modification
				$this->session->set_userdata('mois', $mois);
				// obtention de l'id utilisateur courant
				$idVisiteur = $this->session->userdata('idUser');

				$this->a_comptable->voirFiche($idVisiteur, $mois);
			}
			elseif ($action == 'modFiche')		// modFiche demandÃ© : on active la fonction modFiche du modÃ¨le authentif
			{	// TODO : contrÃ´ler la validitÃ© du second paramÃ¨tre (mois de la fiche Ã  modifier)
					
				$this->load->model('a_comptable');

				// obtention du mois de la fiche Ã  modifier qui doit avoir Ã©tÃ© transmis
				// en second paramÃ¨tre
				$mois = $params[0];
				// mÃ©morisation du mode modification en cours
				// on mÃ©morise le mois de la fiche en cours de modification
				$this->session->set_userdata('mois', $mois);
				// obtention de l'id utilisateur courant
				$idVisiteur = $this->session->userdata('idUser');

				$this->a_comptable->modFiche($idVisiteur, $mois);
			}
			elseif ($action == 'signeFiche') 	// signeFiche demandÃ© : on active la fonction signeFiche du modÃ¨le visiteur ...
			{	// TODO : contrÃ´ler la validitÃ© du second paramÃ¨tre (mois de la fiche Ã  modifier)
				$this->load->model('a_comptable');

				// obtention du mois de la fiche Ã  signer qui doit avoir Ã©tÃ© transmis
				// en second paramÃ¨tre
				$mois = $params[0];
				// obtention de l'id utilisateur courant et du mois concernÃ©
				$idVisiteur = $this->session->userdata('idUser');
				$this->a_comptable->signeFiche($idVisiteur, $mois);

				// ... et on revient Ã  mesFiches
				$this->a_comptable->mesFiches($idVisiteur, "La fiche $mois a été signée. <br/>Pensez Ã  envoyer vos justificatifs afin qu'elle soit traitée par le service comptable rapidement.");
			}
			elseif ($action == 'majForfait') // majFraisForfait demandÃ© : on active la fonction majFraisForfait du modÃ¨le visiteur ...
			{	// TODO : conrÃ´ler que l'obtention des donnÃ©es postÃ©es ne rend pas d'erreurs
				// TODO : dans la dynamique de l'application, contrÃ´ler que l'on vient bien de modFiche

				$this->load->model('a_comptable');

				// obtention de l'id du visiteur et du mois concernÃ©
				$idVisiteur = $this->session->userdata('idUser');
				$mois = $this->session->userdata('mois');

				// obtention des donnÃ©es postÃ©es
				$lesFrais = $this->input->post('lesFrais');

				$this->a_comptable->majForfait($idVisiteur, $mois, $lesFrais);

				// ... et on revient en modification de la fiche
				$this->a_comptable->modFiche($idVisiteur, $mois, 'Modification(s) des éléments forfaitisés enregistrée(s) ...');
			}
			elseif ($action == 'ajouteFrais') // ajouteLigneFrais demandÃ© : on active la fonction ajouteLigneFrais du modÃ¨le visiteur ...
			{	// TODO : conrÃ´ler que l'obtention des donnÃ©es postÃ©es ne rend pas d'erreurs
				// TODO : dans la dynamique de l'application, contrÃ´ler que l'on vient bien de modFiche

				$this->load->model('a_comptable');

				// obtention de l'id du visiteur et du mois concernÃ©
				$idVisiteur = $this->session->userdata('idUser');
				$mois = $this->session->userdata('mois');

				// obtention des donnÃ©es postÃ©es
				$uneLigne = array(
						'dateFrais' => $this->input->post('dateFrais'),
						'libelle' => $this->input->post('libelle'),
						'montant' => $this->input->post('montant')
				);

				$this->a_comptable->ajouteFrais($idVisiteur, $mois, $uneLigne);

				// ... et on revient en modification de la fiche
				$this->a_comptable->modFiche($idVisiteur, $mois, 'Ligne "Hors forfait" ajoutÃ©e ...');
			}
			elseif ($action == 'supprFrais') // suppprLigneFrais demandÃ© : on active la fonction suppprLigneFrais du modÃ¨le visiteur ...
			{	// TODO : contrÃ´ler la validitÃ© du second paramÃ¨tre (mois de la fiche Ã  modifier)
				// TODO : dans la dynamique de l'application, contrÃ´ler que l'on vient bien de modFiche
					
				$this->load->model('a_comptable');

				// obtention de l'id du visiteur et du mois concernÃ©
				$idVisiteur = $this->session->userdata('idUser');
				$mois = $this->session->userdata('mois');

				// Quel est l'id de la ligne Ã  supprimer : doit avoir Ã©tÃ© transmis en second paramÃ¨tre
				$idLigneFrais = $params[0];
				$this->a_comptable->supprLigneFrais($idVisiteur, $mois, $idLigneFrais);

				// ... et on revient en modification de la fiche
				$this->a_comptable->modFiche($idVisiteur, $mois, 'Ligne "Hors forfait" supprimÃ©e ...');
			}
			elseif ($action == 'validFiche'){
				$this->load->model('a_comptable');
				
				// obtention du mois de la fiche Ã  signer qui doit avoir Ã©tÃ© transmis
				// en second paramÃ¨tre
				$mois = $params[0];
				// obtention de l'id utilisateur courant et du mois concernÃ©
				$idVisiteur = $this->session->userdata('idUser');
				$this->a_comptable->validFiche($params[1], $mois);
				
				
				$this->load->model('a_comptable');
			
				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');
			
				$idVisiteur = $this->session->userdata('idUser');
				$this->a_comptable->mesFichesCon($idVisiteur, "La fiche $mois  a été valider.");
			}
			elseif ($action == 'refuFiche'){
				$this->load->model('a_comptable');
			
				// obtention du mois de la fiche Ã  signer qui doit avoir Ã©tÃ© transmis
				// en second paramÃ¨tre
				// obtention de l'id utilisateur courant et du mois concernÃ©

						$idUtilisateur = $this->input->post('idUtilisateur');
						$mois = $this->input->post('mois');
						$commentaire =  $this->input->post('comment');
				
				$this->a_comptable->refuFiche($idUtilisateur,$mois,$commentaire);
$idVisiteur = $this->session->userdata('idUser');
				$this->a_comptable->mesFichesCon($idVisiteur,"La fiche $mois a été refusé");				
				// ... et on revient Ã  mesFiches
			}
			elseif ($action == 'refuFicheCom'){
				$this->load->model('a_comptable');
				
				$mois = $params[0];
				// obtention de l'id utilisateur courant et du mois concernÃ©
				$idVisiteur = $params[1]		;
				$this->a_comptable->refuFicheCom($idVisiteur, $mois);
				
			}
			elseif ($action == 'touteFiche')		// mesFiches demandÃ© : on active la fonction mesFiches du modÃ¨le visiteur
			{
				$this->load->model('a_comptable');
			
				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');
			
				$idVisiteur = $this->session->userdata('idUser');
				$this->a_comptable->touteFiches($idVisiteur);
			}
			elseif ($action == 'paiementFiche')		// mesFiches demandÃ© : on active la fonction mesFiches du modÃ¨le visiteur
			{
				$this->load->model('a_comptable');
					
				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');
					
				$idVisiteur = $this->session->userdata('idUser');
				$this->a_comptable->paiementFiche($idVisiteur);
			}
			else								// dans tous les autres cas, on envoie la vue par dÃ©faut pour l'erreur 404
			{
				show_404();
			}
		}
	}
}
