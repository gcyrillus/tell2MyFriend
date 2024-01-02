window.addEventListener("load", (event) => {
	document.body.insertAdjacentHTML( 'afterbegin', myformtpl);
	const MyTell2Friend =document.querySelector('#TellMyFriend');
	let template = document.getElementById("myform");
	let templateContent = template.content;
	MyTell2Friend.appendChild(templateContent);
});