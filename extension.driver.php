<?php

	Class extension_language_redirect extends Extension{
	
		public function about(){
			return array(
				'name' => 'Language Redirect',
				'version' => '1.0beta',
				'release-date' => '2010-03-24',
				'author' => array(
					'name' => 'Jonas Coch',
					'website' => 'http://klaftertief.de',
					'email' => 'jonas@klaftertief.de'
					)
				);
		}
		
			public function getSubscribedDelegates(){
				return array(
							array(
								'page' => '/system/preferences/',
								'delegate' => 'AddCustomPreferenceFieldsets',
								'callback' => 'appendPreferences'
							),

							array(
								'page' => '/system/preferences/',
								'delegate' => 'Save',
								'callback' => '__SavePreferences'
							),
				);
			}

			public function appendPreferences($context){

				$group = new XMLElement('fieldset');
				$group->setAttribute('class', 'settings');
				$group->appendChild(new XMLElement('legend', __('Language Redirect')));

				$label = Widget::Label(__('Languages'));
				$label->appendChild(Widget::Input('settings[language_redirect][languages]', General::Sanitize($context['parent']->Configuration->get('languages', 'language_redirect'))));

				$group->appendChild($label);

				$group->appendChild(new XMLElement('p', __('Comma separated list of supported language codes. First language ist the default language. Leave empty to disable <code>.htaccess</code>-rules.'), array('class' => 'help')));

				$context['wrapper']->appendChild($group);

			}

			public function __SavePreferences($context){
				
				$languages = explode(',', $_POST['settings']['language_redirect']['languages']);
				$languages = array_map('trim', $languages);
				$languages = array_filter($languages);

				$htaccess = @file_get_contents(DOCROOT . '/.htaccess');

				if($htaccess === false) return false;

				$htaccess = self::__editLanguageRules($htaccess, $languages);

				return @file_put_contents(DOCROOT . '/.htaccess', $htaccess);
			}

			public function enable(){
				return $this->install();
			}

			public function disable(){
				$htaccess = @file_get_contents(DOCROOT . '/.htaccess');

				if($htaccess === false) return false;

				$htaccess = self::__removeLanguageRules($htaccess);

				return @file_put_contents(DOCROOT . '/.htaccess', $htaccess);
			}

			public function install(){

				$htaccess = @file_get_contents(DOCROOT . '/.htaccess');

				if($htaccess === false) return false;

				$rule = '	### LANGUAGE REDIRECT RULES start
	### LANGUAGE REDIRECT RULES end';

				## Remove existing rules
				$htaccess = self::__removeLanguageRules($htaccess);

				$htaccess = preg_replace('/(\s?### FRONTEND REWRITE)/', "{$rule}\n\n$1", $htaccess);

				return @file_put_contents(DOCROOT . '/.htaccess', $htaccess);

			}

			public function uninstall(){

				$htaccess = @file_get_contents(DOCROOT . '/.htaccess');

				if($htaccess === false) return false;

				$htaccess = self::__removeLanguageRules($htaccess);

				return @file_put_contents(DOCROOT . '/.htaccess', $htaccess);
			}

			private static function __editLanguageRules($string,$languages = NULL){

				## Cannot use $1 in a preg_replace replacement string, so using a token instead
				$token_1 = md5('dollar_1');
				$token_2 = md5('dollar_2');
				
				if (is_array($languages) and !empty($languages)) {
					$supported_languages = implode("|", $languages);
					$rule = "
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^({$supported_languages})\/(.*\/?)$ index.php?language={$token_1}&symphony-page={$token_2}&%{QUERY_STRING} [L]";
				} else {
					$rule = NULL;
				}
				
				$htaccess = preg_replace('/(\s*### LANGUAGE REDIRECT RULES start)(.*?)(\s*### LANGUAGE REDIRECT RULES end)/s', "$1{$rule}$3", $string);

				## Replace the token with the real value
				$htaccess = str_replace($token_1, '$1', $htaccess);
				$htaccess = str_replace($token_2, '$2', $htaccess);

				return $htaccess;
			}
		
			private static function __removeLanguageRules($string){
				return preg_replace('/### LANGUAGE REDIRECT RULES start(.*?)### LANGUAGE REDIRECT RULES end/s', NULL, $string);
			}
		
	}

