<?php
	$this->load->helper('url');
	$path = base_url();
	
?>
<div id="contenu">

<form method="POST" action="<?php echo $path.'c_comptable/accueil';?>">
	<h2>Veuiller inscrire un commentaire justifiant le refus</h2>
  <textarea name='comment' style="height:200px; width:600px" id='comment'></textarea><br />
	 				<input type="submit" value="Valider" name="valider"/>
	 	
	<?php if(!empty($notify)) echo '<p id="notify" >'.$notify.'</p>';?>
	 

          
		<?php    
			
			
		?>	  
		</tbody>
    </table>
</form>
</div>

