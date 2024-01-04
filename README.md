# tell2MyFriend
recommander à un(e) ami(e)

<div>
	<h1>Aide du plugin tell2MyFriend</h1>
	<p>Un bouton <mark>Recommander à un ami </mark> peut-etre afficher dans vos pages à l'endroit où vous le souhaitez</p>
	<p>Pour cela, il faut ajouter le Hook du widget</p>
	<h2>Dans les fichiers du thème</h2>
	<p>Le code suivant est à copier coller : 
	<mark style="color:purple;font-weight:bold;padding:0.5em;display:inline-block;vertical-align:middle;"><code>&lt;?php eval($plxShow->callHook('tell2MyFriendwidget')); ?&gt;</code></mark>
	dans l'un des fichier de votre thème à l'endroit où vous voulez qu'il apparaisse.
	</p>
	<h2>Dans une page statique</h2>
	<p>Le code suivant est à copier coller : 
	<mark style="color:purple;font-weight:bold;padding:0.5em;display:inline-block;vertical-align:middle;"><code>&lt;?php eval($this->callHook('tell2MyFriendwidget')); ?&gt;</code></mark>
	dans une page statique en l'éditant à l'endroit où vous voulez qu'il apparaisse.
	</p>
	<h2>Widget(S?)</h2>
	<p>Pour des raisons technique , un seul widget, bouton, sera afficher par page, ce sera determiner par le premier hook apparaissant dans le code.</p>
</div>
