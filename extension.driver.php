<?php

	Class extension_language_redirect extends Extension{
	
		public function about(){
			return array(
				'name' => 'Language Redirect',
				'version' => '1.0',
				'release-date' => '2011-01-31',
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
				$label->appendChild(Widget::Input('settings[language_redirect][languages]', General::Sanitize(Symphony::Configuration()->get('languages', 'language_redirect'))));

				$group->appendChild($label);

				$group->appendChild(new XMLElement('p', __('Comma separated list of supported language codes. First language ist the default language. Leave empty to disable <code>.htaccess</code>-rules.'), array('class' => 'help')));

				$context['wrapper']->appendChild($group);

			}

			public function __SavePreferences($context){
				
				$language_codes = explode(',', $_POST['settings']['language_redirect']['languages']);
				$language_codes = array_map('trim', $language_codes);
				$language_codes = array_filter($language_codes);
				
				$languages = $language_codes;
				foreach ($languages as &$language) {
					$language = substr($language,0,2);
				}
				$languages = array_unique($languages);

				$regions = $language_codes;
				foreach ($regions as &$region) {
					$region = substr(strrchr($region, '-'),1);
				}
				$regions = array_filter(array_unique($regions));

				$htaccess = @file_get_contents(DOCROOT . '/.htaccess');

				if($htaccess === false) return false;

				$htaccess = self::__editLanguageRules($htaccess, $languages, $regions);

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
	### no language codes set
	### LANGUAGE REDIRECT RULES end';

				## Remove existing rules
				$htaccess = self::__removeLanguageRules($htaccess);

				$htaccess = preg_replace('/(\s?### FRONTEND REWRITE)/', "{$rule}\n\n$1", $htaccess);

				return @file_put_contents(DOCROOT . '/.htaccess', $htaccess);

			}

			public function uninstall(){

				Symphony::Configuration()->remove('language_redirect');
				Administration::instance()->saveConfig();

				$htaccess = @file_get_contents(DOCROOT . '/.htaccess');

				if($htaccess === false) return false;

				$htaccess = self::__removeLanguageRules($htaccess);

				return @file_put_contents(DOCROOT . '/.htaccess', $htaccess);

			}

			private static function __editLanguageRules($string, $languages = NULL, $regions = NULL){

				## Cannot use $1 in a preg_replace replacement string, so using a token instead
				$token_language = md5('language');
				$token_region = md5('region');
				$token_symphony = md5('symphony-page');
				
				if (is_array($languages) and !empty($languages)) {
					$supported_languages = implode("|", $languages);
					$supported_regions = (is_array($languages) and !empty($languages)) ? implode("|", $regions) : NULL;
					$rule = "
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^({$supported_languages})-?({$supported_regions})?\/(.*\/?)$ index.php?language={$token_language}&region={$token_region}&symphony-page={$token_symphony}&%{QUERY_STRING} [L]";
				} else {
					$rule = "
	### no language codes set";
				}
				
				$htaccess = preg_replace('/(\s+### LANGUAGE REDIRECT RULES start)(.*?)(\s*### LANGUAGE REDIRECT RULES end)/s', "$1{$rule}$3", $string);

				## Replace the token with the real value
				$htaccess = str_replace($token_language, '$1', $htaccess);
				$htaccess = str_replace($token_region, '$2', $htaccess);
				$htaccess = str_replace($token_symphony, '$3', $htaccess);

				return $htaccess;
			}
		
			private static function __removeLanguageRules($string){
				return preg_replace('/\s+### LANGUAGE REDIRECT RULES start(.*?)### LANGUAGE REDIRECT RULES end/s', NULL, $string);
			}
		
	}

