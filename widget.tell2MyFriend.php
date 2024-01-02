<?php	
	$plugName =basename( __DIR__ );
	$plxShow = plxShow::getInstance();
	$plxMotor = plxMotor::getInstance();
	if (!isset($plxMotor->plxPlugins->aPlugins[$plugName])) { exit;}
	else {$plxPlugin = $plxMotor->plxPlugins->aPlugins[$plugName];}
	$pageUrl = !empty($plxShow->plxMotor->get) ? $plxShow->plxMotor->racine . 'index.php?' . $plxShow->plxMotor->get : $plxShow->plxMotor->racine;
?>
<label for="closeTell" class="btn blue"><?= $plxPlugin->getLang('L_TELL_A_FRIEND') ?></label>
<input type="checkbox" id="closeTell" checked="checked">
<div id="TellMyFriend"></div>
<script>const myformtpl =`
<template id="myform">
	<form action="index.php?<?= $plxPlugin->getParam('url') ?>" method="post" class="formtpl">
	<label for="closeTell">X</label>
	<fieldset>
		<legend><?= $plxPlugin->getLang('L_TELL_A_FRIEND') ?></legend>
		<input  name="seeThat" type="hidden" value="<?= $pageUrl ?>">
		<input  name="pageName" type="hidden" value="<?= $plxShow->pageTitle()?>">
		<p>
			<label for="friendMail"><?=  $plxPlugin->getLang('L_MAIL_FRIEND') ?></label>
			<input type="email" name="friendMail" placeholder="<?=  $plxPlugin->getLang('L_MY_MAIL_FRIEND') ?>">
			</p>
		<p>
			<label for="tellIt"><?= $plxPlugin->getLang('L_TELL_A_WORD_TO_A_FRIEND') ?></label>
			<textarea name="tellIt" required placeholder="<?=  $plxPlugin->getLang('L_EXAMPLE') ?>" rows="5"></textarea>
		</p>
		<p>
			<label for="yourMail"><?= $plxPlugin->getLang('L_REMIND_MY_MAIL_2_FRIEND') ?></label>
			<input type="email" name="yourMail" placeholder="<?=  $plxPlugin->getLang('L_MY_MAIL') ?>">
		</p>
		</fieldset>
		<fieldset><legend><?=  $plxPlugin->getLang('L_RGPD') ?></legend>
		<p>
			<label for="iWantTo">
			<span><?=  $plxPlugin->getLang('L_PERSONAL_DATAS') ?></span>
			<span><?=  $plxPlugin->getLang('L_I_ACCEPT_TO') ?></span>
			<input type="checkbox" name="iWantTo" required></label>
		</p>
		<p>
			<label for="rep"><strong><?=  $plxPlugin->getLang('L_ANTISPAM')?></strong> *</label>
			<?php $plxShow->plxMotor->plxCapcha = new plxCapcha(); # Création objet captcha
			$plxShow->capchaQ(); ?>
			<input type="text"  name="rep" id="id_rep"  size=2 style="width:auto;text-align:center" required class="form-imput">
		</p>
		<input type="submit">
	</fieldset>
	</form> 
</template>`;
</script>
