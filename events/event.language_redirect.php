<?php

	if(!defined('__IN_SYMPHONY__')) die('<h2>Error</h2><p>You cannot directly access this file</p>');

	require_once(TOOLKIT . '/class.event.php');
	require_once(EXTENSIONS . '/language_redirect/lib/class.languager.php');
	
	Class eventlanguage_redirect extends Event{
		
		const ROOTELEMENT = 'language-redirect';
		
		public static function about(){
			return array(
						'name' => __('Language Redirect'),
						'author' => array(
										array(	'name' => 'Jonas Coch',
												'website' => 'http://klaftertief.de',
										   		'email' => 'jonas@klaftertief.de'
										),
										
										array(	'name' => 'Vlad Ghita',
										   		'email' => 'vlad_micutul@yahoo.com'
										),
						),
						'version' => '1.1',
						'release-date' => '2011-06-15',
						'trigger-condition' => '');
		}

		public function load(){
			return $this->__trigger();
		}

		public static function documentation(){
			return __('This event redirects users to a language version of the page depending on browser settings or cookies.');
		}

		protected function __trigger(){
			$supported_language_codes = LanguageR::instance()->getSupportedLanguageCodes();
			
			// only do something when there is a set of supported languages defined
			if ( !empty($supported_language_codes) ) {
				
				$current_language_code = LanguageR::instance()->getLanguageCode();
				
				// no redirect, set current language and region in cookie
				if (isset($current_language_code) and in_array($current_language_code, $supported_language_codes)) {
					$Cookie = new Cookie(__SYM_COOKIE_PREFIX_ . 'language-redirect', TWO_WEEKS, __SYM_COOKIE_PATH__);
					$Cookie->set('language', LanguageR::instance()->getLanguage());
					$Cookie->set('region', LanguageR::instance()->getRegion());
				}
				
				// redirect to language-code depending on cookie or browser settings
				else {
					$current_path = !isset($current_language_code) ? $this->_env['param']['current-path'] : substr($this->_env['param']['current-path'],strlen($current_language_code)+1);
					$browser_languages = $this->getBrowserLanguages();
					foreach ($browser_languages as $language) {
						if (in_array($language, $supported_language_codes)) {
							$in_browser_languages = true;
							$browser_language = $language;
							break;
						};
					}
					$Cookie = new Cookie(__SYM_COOKIE_PREFIX_ . 'language-redirect', TWO_WEEKS, __SYM_COOKIE_PATH__);
					$cookie_language_code = $Cookie->get('language');
					if (strlen($cookie_language_code) > 0) {
						$language_code = $Cookie->get('region') ? $cookie_language_code.'-'.$Cookie->get('region') : $cookie_language_code;
					}
					elseif ($in_browser_languages) {
						$language_code = $browser_language;
					}
					else {
						$language_code = $supported_language_codes[0];
					}
					// redirect and exit
					header('Location: '.$this->_env['param']['root'].'/'.$language_code.$current_path);
					die();
				}
				
				$all_languages = LanguageR::instance()->getAllLanguages();
				
				$result = new XMLElement('language-redirect');
				
				$current_language_xml = new XMLElement('current-language', $all_languages[$current_language_code] ? $all_languages[$current_language_code] : $current_language_code);
				$current_language_xml->setAttribute('handle', $current_language_code);
				$result->appendChild($current_language_xml);
				
				$supported_languages_xml = new XMLElement('supported-languages');
				foreach($supported_language_codes as $language) {
					$language_code = new XMLElement('item', $all_languages[$language] ? $all_languages[$language] : $language);
					$language_code->setAttribute('handle', $language);
					$supported_languages_xml->appendChild($language_code);
				}
				$result->appendChild($supported_languages_xml);
				
				return $result;
			}
			
			return false;
		}

		/**
		 * Get browser languages
		 *
		 * Return languages accepted by browser as an array sorted by priority
		 * @return array language codes, e. g. 'en'
		 */	 
		public static function getBrowserLanguages() {
			static $languages;
			if(is_array($languages)) return $languages;

			$languages = array();

			if(strlen(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])) < 1) return $languages;
			if(!preg_match_all('/(\w+(?:-\w+)?,?)+(?:;q=(?:\d+\.\d+))?/', preg_replace('/\s+/', '', $_SERVER['HTTP_ACCEPT_LANGUAGE']), $matches)) return $languages;

			$priority = 1.0;
			$languages = array();
			foreach($matches[0] as $def){
				list($list, $q) = explode(';q=', $def);
				if(!empty($q)) $priority=floatval($q);
				$list = explode(',', $list);
				foreach($list as $lang){
					$languages[$lang] = $priority;
					$priority -= 0.000000001;
				}
			}
			arsort($languages);
			$languages = array_keys($languages);
			// return list sorted by descending priority, e.g., array('en-gb','en');
			return $languages;
		}

	}
