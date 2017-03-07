<?php
	$this->load->helper('url');
?>
<div id="contenu">

	<h2>Liste des fiches de frais en attente de validation</h2>
	 	
	<?php if(!empty($notify)) echo '<p id="notify" >'.$notify.'</p>';?>
	 
	<table class="listeLegere">
		<thead>
			<tr>
				<th >Mois</th>
				<th >ID Utilisateur </th>
				<th >Etat</th>  
				<th >Montant</th>  
				<th >Date modif.</th>  
				<th  colspan="4">Actions</th>              
			</tr>
		</thead>
		<tbody>
          
		<?php    
			foreach( $mesFiches as $uneFiche) 
			{
				$validLink = '';
				$denyLink = '';
				$modLink= '';

				if ($uneFiche['id'] == 'CL') {
					$validLink = anchor('c_comptable/validFiche/'.$uneFiche['mois'], 'Valider',  'title="Signer"onclick="return confirm(\'Voulez-vous vraiment valider cette fiche ?\');"');
					$denyLink = anchor('c_comptable/refuFicheCom/'.$uneFiche['mois'], 'Refuser',  'title="Modifier la fiche" onclick="return confirm(\'Voulez-vous vraiment refuser cette fiche ?\');"');
					$modLink = anchor('c_comptable/modFiche/'.$uneFiche['mois'], 'modifier',  'title="Modifier la fiche"');
					
					echo
					'<tr>
					<td class="date">'.anchor('c_comptable/voirFiche/'.$uneFiche['mois'], $uneFiche['mois'],  'title="Consulter la fiche"').'</td>
					<td class="date">'.$uneFiche['idUtilisateur'].'</td>
					<td class="libelle">'.$uneFiche['libelle'].'</td>
					<td class="montant">'.$uneFiche['montantValide'].'</td>
					<td class="date">'.$uneFiche['dateModif'].'</td>
					<td class="action">'.$validLink.'</td>
					<td class="action">'.$denyLink.'</td>
					<td class="action">'.$modLink.'</td>
							
							
				</tr>';
				}
				
				
			}
		?>	  
		</tbody>
    </table>

</div>

