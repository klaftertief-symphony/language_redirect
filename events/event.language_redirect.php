<?php

	if(!defined('__IN_SYMPHONY__')) die('<h2>Error</h2><p>You cannot directly access this file</p>');

	require_once(TOOLKIT . '/class.event.php');
	
	Class eventlanguage_redirect extends Event{
		
		const ROOTELEMENT = 'language-redirect';
		
		public static function about(){

			$description = new XMLElement('p', 'This event redirects users to a language version of the page depending on browser settings or cookies.');

			return array(
						 'name' => 'Language Redirect',
						 'author' => array('name' => 'Jonas Coch',
										   'website' => 'http://klaftertief.de',
										   'email' => 'jonas@klaftertief.de'),
						 'version' => '1.0',
						 'release-date' => '2010-03-19',
						 'trigger-condition' => '');
		}

		public function load(){
			return $this->__trigger();
		}

		public static function documentation(){
			return 'This event redirects users to a language version of the page depending on browser settings or cookies.';
		}

		protected function __trigger(){
			$current_language = $_REQUEST['language'];
			$current_region = $_REQUEST['region'];
			$current_language_code = $_REQUEST['region'] ? $_REQUEST['language'].'-'.$_REQUEST['region'] : $_REQUEST['language'];
			
			$result = new XMLElement('language-redirect');
			
			$supported_language_codes = explode(',', General::Sanitize($this->_Parent->Configuration->get('languages', 'language_redirect')));
			$supported_language_codes = array_map('trim', $supported_language_codes);
			$supported_language_codes = array_filter($supported_language_codes);
			
			// only do something when there is a set of supported languages defined
			if (is_array($supported_language_codes) and !empty($supported_language_codes)) {
				// no redirect, set current language and region in cookie
				if (isset($current_language_code) and in_array($current_language_code, $supported_language_codes)) {
					$Cookie = new Cookie(__SYM_COOKIE_PREFIX__ . 'language-redirect', TWO_WEEKS, __SYM_COOKIE_PATH__);
					$Cookie->set('language', $current_language);
					$Cookie->set('region', $current_region);
				}
				// redirect to language-code depending on cookie or browser settings
				else {
					$current_path = !isset($current_language_code) ? $this->_env['param']['current-path'] : substr($this->_env['param']['current-path'],strlen($current_language_code)+1);
					$browser_languages = Lang::getBrowserLanguages();
					foreach ($browser_languages as $language) {
						if (in_array($language, $supported_language_codes)) {
							$in_browser_languages = true;
							$browser_language = $language;
							break;
						};
					}
					$Cookie = new Cookie(__SYM_COOKIE_PREFIX__ . 'language-redirect', TWO_WEEKS, __SYM_COOKIE_PATH__);
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
				
				$current_language_code_xml = new XMLElement('current-language-code', $current_language_code);
				$current_language_code_xml->setAttribute('language', $current_language);
				if (strlen($current_region) > 0) $current_language_code_xml->setAttribute('region', $current_region);
				$result->appendChild($current_language_code_xml);
				
				$supported_languages_xml = new XMLElement('supported-language-codes');
				foreach($supported_language_codes as $language) {
					$language_code = new XMLElement('item', $language);
					$region = substr(strrchr($language, '-'),1);
					$language_code->setAttribute('language', substr($language,0,2));
					if (strlen($region) > 0) $language_code->setAttribute('region', $region);
					$supported_languages_xml->appendChild($language_code);
				}
				$result->appendChild($supported_languages_xml);
				
				return $result;
			}
			return false;
		}
	}
