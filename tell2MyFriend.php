<?php if(!defined('PLX_ROOT')) exit;
	/**
		* Plugin 			tell2MyFriend
		*
		* @CMS required			PluXml 
		*
		* @version			1.0
		* @date				2024-01-01
		* @author 			G.Cyrille
	**/
	class tell2MyFriend extends plxPlugin {
		
		
		
		const BEGIN_CODE = '<?php' . PHP_EOL;
		const END_CODE = PHP_EOL . '?>';
		public $lang = ''; 
		
		private $url = ''; # parametre de l'url pour accèder à la page static		
		
		
		public function __construct($default_lang) {
			# appel du constructeur de la classe plxPlugin (obligatoire)
			parent::__construct($default_lang);
			
			
			
			# droits pour accèder à la page config.php du plugin
			$this->setConfigProfil(PROFIL_ADMIN, PROFIL_MANAGER);		
			// url Page static
			$this->url = $this->getParam('url')=='' ? strtolower(basename(__DIR__)) : $this->getParam('url');	
			
			$mail = $this->getParam('mail') ==''   ?   'string' : $this->getParam('mail') ;
			
			
			# Declaration des hooks		
			$this->addHook('AdminTopBottom', 'AdminTopBottom');
			$this->addHook('ThemeEndHead', 'ThemeEndHead');
			$this->addHook('plxShowConstruct', 'plxShowConstruct');
			$this->addHook('plxMotorPreChauffageBegin', 'plxMotorPreChauffageBegin');
			$this->addHook('plxShowStaticListEnd', 'plxShowStaticListEnd');
			$this->addHook('plxShowPageTitle', 'plxShowPageTitle');
			$this->addHook('tell2MyFriendwidget', 'tell2MyFriendwidget');
			
			
		}
		
		# Activation / desactivation
		
		public function OnActivate() {
			# code à executer à l’activation du plugin
			//nowizards set
		}
		
		public function OnDeactivate() {
			# code à executer à la désactivation du plugin
		}	
		
		
		public function ThemeEndHead() {
			#gestion multilingue
			if(defined('PLX_MYMULTILINGUE')) {		
				$plxMML = is_array(PLX_MYMULTILINGUE) ? PLX_MYMULTILINGUE : unserialize(PLX_MYMULTILINGUE);
				$langues = empty($plxMML['langs']) ? array() : explode(',', $plxMML['langs']);
				$string = '';
				foreach($langues as $k=>$v)	{
					$url_lang="";
					if($_SESSION['default_lang'] != $v) $url_lang = $v.'/';
					$string .= 'echo "\\t<link rel=\\"alternate\\" hreflang=\\"'.$v.'\\" href=\\"".$plxMotor->urlRewrite("?'.$url_lang.$this->getParam('url').'")."\" />\\n";';
				}
				echo '<?php if($plxMotor->mode=="'.$this->getParam('url').'") { '.$string.'} ?>';
			}
			
			echo ' 		<link href="'.PLX_PLUGINS.basename(__DIR__).'/css/site.css" rel="stylesheet" type="text/css" />'."\n";
			echo ' 		<script src="'.PLX_PLUGINS.basename(__DIR__).'/js/site.js"></script>'."\n";
			// ajouter ici vos propre codes (insertion balises link, script , ou autre)
		}
		
		/**
			* Méthode qui affiche un message si le plugin n'a pas la langue du site dans sa traduction
			* Ajout gestion du wizard si inclus au plugin
			* @return	stdio
			* @author	Stephane F
		**/
		public function AdminTopBottom() {
			
			echo '<?php
			$file = PLX_PLUGINS."'.$this->plug['name'].'/lang/".$plxAdmin->aConf["default_lang"].".php";
			if(!file_exists($file)) {
			echo "<p class=\\"warning\\">'.basename(__DIR__).'<br />".sprintf("'.$this->getLang('L_LANG_UNAVAILABLE').'", $file)."</p>";
			plxMsg::Display();
			}
			?>';
		}
		
		/**
			* Méthode statique qui affiche le widget
			*
		**/
		public static function tell2MyFriendwidget($widget=false) {
			# récupération d'une instance de plxMotor
			$plxMotor = plxMotor::getInstance();
			$plxPlug = $plxMotor->plxPlugins->getInstance(basename(__DIR__));		
			include(PLX_PLUGINS.basename(__DIR__).'/widget.'.basename(__DIR__).'.php');
		}
		
		
		/**
			* Méthode de traitement du hook plxShowConstruct
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowConstruct() {
			
			# infos sur la page statique
			$string  = "if(\$this->plxMotor->mode=='".$this->url."') {";
			$string .= "	\$array = array();";
			$string .= "	\$array[\$this->plxMotor->cible] = array(
			'name'		=> '".$this->getParam('mnuName_'.$this->default_lang)."',
			'menu'		=> '',
			'url'		=>  '".basename(__DIR__)."',
			'readable'	=> 1,
			'active'	=> 1,
			'group'		=> ''
			);";
			$string .= "	\$this->plxMotor->aStats = array_merge(\$this->plxMotor->aStats, \$array);";
			$string .= "}";
			echo "<?php ".$string." ?>";
		}
		
		/**
			* Méthode de traitement du hook plxMotorPreChauffageBegin
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxMotorPreChauffageBegin() {				
			$template = $this->getParam('template')==''?'static.php':$this->getParam('template');
			
			$string = "
			if(\$this->get && preg_match('/^".$this->url."\/?/',\$this->get)) {
			\$this->mode = '".$this->url."';
			\$prefix = str_repeat('../', substr_count(trim(PLX_ROOT.\$this->aConf['racine_statiques'], '/'), '/'));
			\$this->cible = \$prefix.\$this->aConf['racine_plugins'].'".basename(__DIR__)."/static';
			\$this->template = '".$template."';
			return true;
			}
			";
			
			echo "<?php ".$string." ?>";
		}
		
		
		/**
			* Méthode de traitement du hook plxShowStaticListEnd
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowStaticListEnd() {
			
			# ajout du menu pour accèder à la page de recherche
			if($this->getParam('mnuDisplay')) {
				echo "<?php \$status = \$this->plxMotor->mode=='".$this->url."'?'active':'noactive'; ?>";
				echo "<?php array_splice(\$menus, ".($this->getParam('mnuPos')-1).", 0, '<li class=\"static menu '.\$status.'\" id=\"static-".basename(__DIR__)."\"><a href=\"'.\$this->plxMotor->urlRewrite('?".$this->lang.$this->url."').'\" title=\"".$this->getParam('mnuName_'.$this->default_lang)."\">".$this->getParam('mnuName_'.$this->default_lang)."</a></li>'); ?>";
			}
		}
		
		/**
			* Méthode qui renseigne le titre de la page dans la balise html <title>
			*
			* @return	stdio
			* @author	Stephane F
		**/
		public function plxShowPageTitle() {
			echo '<?php
			if($this->plxMotor->mode == "'.$this->url.'") {
			$this->plxMotor->plxPlugins->aPlugins["'.basename(__DIR__).'"]->lang("L_PAGE_TITLE");
			return true;
			}
			?>';
		}
		
		/** 
			* Méthode tellByMail
			* 
			* Descrition	: envoi d'un courriel
			* @author		: php
			* 
		**/
		public function tellByMail($fields) {		
			$to =$fields['to'];
			$subject =$fields['subject'];
			$message =$fields['message'].'<p>url <a href="'.$fields['page'].'">'.$fields['pageTitle'].'</a></p>';
			$headers  = "From: ".$name." <no-reply@".$_SERVER['HTTP_HOST'].">\r\n";
			if($fields['from'] !='') $headers .= "Reply-To: ".$fields['from']."\r\n";
			$headers .= 'MIME-Version: 1.0'."\r\n";
			$headers .= 'Content-type: text/html; charset="' .PLX_CHARSET. '"'."\r\n";
			$headers .= 'X-Mailer:PHP/' . phpversion();
			return mail($to, $subject, $message, $headers);
		}
		
		/**
			*
			* Méthode CheckMail
			*
			* Verifie la validité d'un email avec une fonction Native de php
			* @author php
			*
		**/
		public function validateEmail($mail) {
			if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
				return true;
			}			
		}
		
	}	