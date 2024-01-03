<?php if(!defined('PLX_ROOT')) exit; 
	$plugName =basename(__DIR__);
	$plxMotor = plxMotor::getInstance();	
	$plxPlugin = $plxMotor->plxPlugins->aPlugins[$plugName];	
	$row="";	
	$color = 'orange';
	
	if(isset($_POST['rep'])){
		# message
		$row ='<p id="com_message" class="#com_class"><strong>#com_message</strong></p>'; 		
		
		#antispam
		if($_SESSION['capcha'] == sha1($_POST['rep'])) {
			$color = 'green';
			$_SESSION['msgcom'] = $plxPlugin->getLang('L_SENT_AND_TOLD_TO') ;
			$fields['subject']= $plxPlugin->getLang('L_RECOMMEND') ;
			$fields['to'] = strip_tags(trim($_POST['friendMail']));
			$fields['message'] = strip_tags($_POST['tellIt'], '<p><br><b>').'<br>'.PHP_EOL;
			$fields['from'] = strip_tags(trim($_POST['yourMail']));
			$fields['page']= strip_tags(trim($_POST['seeThat']));
			$fields['pageTitle']= strip_tags($_POST['pageName']);
			
		}
		else {
			$_SESSION['msgcom'] =  L_NEWCOMMENT_ERR_ANTISPAM;
		}
		
		#capcha ok , verifions le destinataire
		if(isset($fields['to']) && $plxPlugin->validateEmail($fields['to']) == true) {
			if(!$plxPlugin->tellByMail($fields)) {
			$_SESSION['msgcom'] =  $plxPlugin->getLang('L_ERR_VALID_MAIL');
			}
		}
		#renvoi page origin
		header("Location: " . "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		die;
		
	}
	if (!empty($_SESSION['msgcom'])) {	
		# message
		$row ='<p id="com_message" class="#com_class"><strong>#com_message</strong></p>'; 		
		$message=$_SESSION['msgcom'];
		if($message !=L_NEWCOMMENT_ERR_ANTISPAM) $color ='green';
			$row = str_replace('#com_class', 'alert ' . $color, $row);
			unset($_SESSION['msgcom']);
		#maj message
		$row = str_replace('#com_message',$message , $row);
		echo $row;
		

	}
	
	?>
<div>Contenu de votre page</div>
<?php //et/ou code php 
?>
