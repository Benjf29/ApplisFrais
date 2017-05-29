function calculFrais(){
	var etp = document.getElementById('ETP').value;
	var km = document.getElementById('KM').value;
	var nui = document.getElementById('NUI').value;
	var rep = document.getElementById('REP').value;
	var coefEtp = document.getElementById('coefETP').innerHTML
	var coefKm = document.getElementById('coefKM').innerHTML
	var coefNui = document.getElementById('coefNUI').innerHTML
	var coefRep = document.getElementById('coefREP').innerHTML
	document.getElementById('totalETP').innerHTML = etp * coefEtp;
	document.getElementById('totalKM').innerHTML = km * coefKm;
	document.getElementById('totalNUI').innerHTML = nui * coefNui;
	document.getElementById('totalREP').innerHTML = rep * coefRep;
	var totalEtp = 	document.getElementById('totalETP').innerHTML
	var totalKm = 	document.getElementById('totalKM').innerHTML
	var totalNui = 	document.getElementById('totalNUI').innerHTML
	var totalRep = 	document.getElementById('totalREP').innerHTML
	document.getElementById('totalFrais').innerHTML = parseInt(totalEtp) + parseInt(totalKm) + parseInt(totalNui) + parseInt(totalRep);
	var tab = document.getElementsByName('lesFrais');
	console.log(tab);

	
}
