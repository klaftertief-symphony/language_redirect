<?php
	
	if(!defined('__IN_SYMPHONY__')) die('<h2>Error</h2><p>You cannot directly access this file</p>');
	
	require_once(TOOLKIT . '/class.event.php');
	require_once(EXTENSIONS . '/language_redirect/lib/class.languageredirect.php');
	
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
			$supported_language_codes = LanguageRedirect::instance()->getSupportedLanguageCodes();
			
			// only do something when there is a set of supported languages defined
			if ( !empty($supported_language_codes) ) {
				$current_language_code = LanguageRedirect::instance()->determineLanguageCode();
	
				$all_languages = LanguageRedirect::instance()->getAllLanguages();
	
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
	
	}
