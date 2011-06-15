<?php

	if(!defined('__IN_SYMPHONY__')) die('<h2>Error</h2><p>You cannot directly access this file</p>');
	
	Class LanguageR{
		
		private static $_instance;
		
		private $_language;
		private $_region;
		private $_language_code;
		private $_supported_language_codes;
		
		private function __construct(){
			$this->_language = General::sanitize($_REQUEST['language']);
			$this->_region = General::sanitize($_REQUEST['region']);
			$this->_language_code = $this->_region ? $this->_language.'-'.$this->_region : $this->_language;
			
			$supported_language_codes = explode(',', General::sanitize(Symphony::Configuration()->get('language_codes', 'language_redirect')));
			$supported_language_codes = array_map('trim', $supported_language_codes);
			$this->_supported_language_codes = array_filter($supported_language_codes);
		}
		
		public static function instance() {
			if (!self::$_instance) {self::$_instance = new self(); }
			
			return self::$_instance;
		}
		
		/**
		 * Get current language.
		 * 
		 * @return string
		 */
		public function getLanguage(){
			return $this->_language;
		}
		
		/**
		 * Get current region.
		 * 
		 * @return string
		 */
		public function getRegion(){
			return $this->_region;
		}
		
		/**
		 * Get current language code.
		 * 
		 * @return string
		 */
		public function getLanguageCode(){
			return $this->_language_code;
		}
		
		/**
		 * Get supported language codes.
		 * 
		 * @return array
		 */
		public function getSupportedLanguageCodes(){
			return $this->_supported_language_codes;
		}

	}
