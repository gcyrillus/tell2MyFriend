<?php
	if(!defined('PLX_ROOT')) exit;
	/**
	* Plugin 			tell2MyFriend
	*
	* @CMS required		PluXml 
	* @page				config.php
	* @version			1.0
	* @date				2024-01-01
	* @author 			G.Cyrille
	**/	
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
	
	# Liste des langues disponibles et prises en charge par le plugin
	$aLangs = array($plxAdmin->aConf['default_lang']);	
	
	if(!empty($_POST)) {
		
	#multilingue
	$plxPlugin->setParam('mnuDisplay', $_POST['mnuDisplay'], 'numeric');
	$plxPlugin->setParam('mnuPos', $_POST['mnuPos'], 'numeric');
	$plxPlugin->setParam('template', $_POST['template'], 'string');
	$plxPlugin->setParam('url', plxUtils::title2url($_POST['url']), 'string');
	foreach($aLangs as $lang) {
	$plxPlugin->setParam('mnuName_'.$lang, $_POST['mnuName_'.$lang], 'string');
	}
	
	$plxPlugin->saveParams();	
	header("Location: parametres_plugin.php?p=".basename(__DIR__));
	exit;
	}
	
	# initialisation des variables propres à chaque lanque
	$langs = array();
	foreach($aLangs as $lang) {
	# chargement de chaque fichier de langue
	$langs[$lang] = $plxPlugin->loadLang(PLX_PLUGINS.'tell2MyFriend/lang/'.$lang.'.php');
	$var[$lang]['mnuName'] =  $plxPlugin->getParam('mnuName_'.$lang)=='' ? $plxPlugin->getLang('L_DEFAULT_MENU_NAME') : $plxPlugin->getParam('mnuName_'.$lang);
	}
		# initialisation des variables page statique
	$var['mnuDisplay'] =  $plxPlugin->getParam('mnuDisplay')=='' ? 1 : $plxPlugin->getParam('mnuDisplay');
	$var['mnuPos'] =  $plxPlugin->getParam('mnuPos')=='' ? 2 : $plxPlugin->getParam('mnuPos');
	$var['template'] = $plxPlugin->getParam('template')=='' ? 'static.php' : $plxPlugin->getParam('template');
	$var['url'] = $plxPlugin->getParam('url')=='' ? strtolower(basename(__DIR__)) : $plxPlugin->getParam('url');
	
	# On récupère les templates des pages statiques
	$glob = plxGlob::getInstance(PLX_ROOT . $plxAdmin->aConf['racine_themes'] . $plxAdmin->aConf['style'], false, true, '#^^static(?:-[\\w-]+)?\\.php$#');
	if (!empty($glob->aFiles)) {
	$aTemplates = array();
	foreach($glob->aFiles as $v)
	$aTemplates[$v] = basename($v, '.php');
	} else {
	$aTemplates = array('' => L_NONE1);
	}
	/* end template */
	
	
	?>
	<link rel="stylesheet" href="<?php echo PLX_PLUGINS."tell2MyFriend/css/tabs.css" ?>" media="all" />
	<p>Envoie le lien de la page par mail à un ami avec un petit mot de votre part.</p>	
	<h2><?php $plxPlugin->lang("L_CONFIG") ?></h2>
	 
	<div id="tabContainer">
	<form action="parametres_plugin.php?p=<?= basename(__DIR__) ?>" method="post" >
	<div class="tabs">
	<ul>
		<li id="tabHeader_main"><?php $plxPlugin->lang('L_MAIN') ?></li>
	<?php
	foreach($aLangs as $lang) {
	echo '<li id="tabHeader_'.$lang.'">'.strtoupper($lang).'</li>';
	} ?>
	</ul>
	</div>
	<div class="tabscontent">

		
	<div class="tabpage" id="tabpage_main">
	<fieldset>
	<p>
	<label for="id_url"><?php $plxPlugin->lang('L_PARAM_URL') ?>&nbsp;:</label>
	<?php plxUtils::printInput('url',$var['url'],'text','20-20') ?>
	</p>
	<p>
	<label for="id_mnuDisplay"><?php echo $plxPlugin->lang('L_MENU_DISPLAY') ?>&nbsp;:</label>
	<?php plxUtils::printSelect('mnuDisplay',array('1'=>L_YES,'0'=>L_NO),$var['mnuDisplay']); ?>
	</p>
	<p>
	<label for="id_mnuPos"><?php $plxPlugin->lang('L_MENU_POS') ?>&nbsp;:</label>
	<?php plxUtils::printInput('mnuPos',$var['mnuPos'],'text','2-5') ?>
	</p>
	<p>
	<label for="id_template"><?php $plxPlugin->lang('L_TEMPLATE') ?>&nbsp;:</label>
	<?php plxUtils::printSelect('template', $aTemplates, $var['template']) ?>
	</p>	
	</fieldset>
	</div>
	<?php foreach($aLangs as $lang) : ?>
	<div class="tabpage" id="tabpage_<?php echo $lang ?>">
	<?php if(!file_exists(PLX_PLUGINS.basename(__DIR__).'/lang/'.$lang.'.php')) : ?>
	<p><?php printf($plxPlugin->getLang('L_LANG_UNAVAILABLE'), PLX_PLUGINS.basename(__DIR__).'/lang/'.$lang.'.php') ?></p>
	<?php else : ?>
	<fieldset>
	<p>
	<label for="id_mnuName_<?php echo $lang ?>"><?php $plxPlugin->lang('L_MENU_TITLE') ?>&nbsp;:</label>
	<?php plxUtils::printInput('mnuName_'.$lang,$var[$lang]['mnuName'],'text','20-20') ?>
	</p>
	</fieldset>
	<?php endif; ?>
	</div>
	<?php endforeach; ?>
	</div>
	<fieldset>
	<p class="in-action-bar">
	<?php echo plxToken::getTokenPostMethod() ?><br>
	<input type="submit" name="submit" value="<?= $plxPlugin->getLang('L_SAVE') ?>"/>
	</p>
	</fieldset>
	</form>
	</div>
	<script type="text/javascript" src="<?php echo PLX_PLUGINS."tell2MyFriend/js/tabs.js" ?>"></script>