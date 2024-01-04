window.addEventListener("load", (event) => {
	document.body.insertAdjacentHTML( 'afterbegin', myformtpl);
	const MyTell2Friend =document.querySelector('#TellMyFriend');
	let template = document.getElementById("myform");
	let templateContent = template.content;
	MyTell2Friend.appendChild(templateContent);
	
	/*
	  **** compatibilité avec les antispam des    *****
	  **** commentaires ou formulaires de contact *****
	*/
	// on prend le premier token trouvé
	let token = document.querySelector('[name="capcha_token"]').value;
	// on les cherche tous
	let tokens = document.querySelectorAll('[name="capcha_token"]');
	// on leur donne tous la même valeur
	[...tokens].forEach((el) => (el.value = token));
});
