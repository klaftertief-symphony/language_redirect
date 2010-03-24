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
			$supported_languages = array('en','de');
			
			// TODO: check for unsupported languages
			if (isset($current_language)) { // no redirect, set current language in cookie
				setcookie(__SYM_COOKIE_PREFIX__ . 'language', $current_language, time()+TWO_WEEKS, __SYM_COOKIE_PATH__);
			}
			else { // redirect to language depending in cookie or browser settings
				$current_path = $this->_env['param']['current-path'];
				$browser_languages = Lang::getBrowserLanguages();
				foreach ($browser_languages as $language) {
					$language = substr($language,0,2);
					if (in_array($language, $supported_languages)) {
						$in_browser_languages = true;
						$browser_language = $language;
						break;
					};
				}
				if (isset($_COOKIE[__SYM_COOKIE_PREFIX__ . 'language'])) {
					$language = $_COOKIE[__SYM_COOKIE_PREFIX__ . 'language'];
				}
				elseif ($in_browser_languages) {
					$language = $browser_language;
				}
				else {
					$language = $supported_languages[0];
				}

				header ('Location: '.$this->_env['param']['root'].'/'.$language.$current_path);
				die();
			}

			return false;
			
		}
	}
