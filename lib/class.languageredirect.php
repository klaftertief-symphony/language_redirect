<?php

	if(!defined('__IN_SYMPHONY__')) die('<h2>Error</h2><p>You cannot directly access this file</p>');
	
	Class LanguageRedirect{
		
		private static $_instance;
		
		private $_language;
		private $_region;
		private $_supported_language_codes;
		
		// I don't know those languages, so if You know for sure that browser uses different code,
		// or that native name should be different, please let me know about that :).
		// It would also be great, if whole string could be in native form, including name of country.
		private $_languages = array(					// [English name]
			'ab' => 'аҧсуа бызшәа',					// Abkhazian
			'af' => 'Afrikaans',					// Afrikaans
			'sq' => 'shqip',						// Albanian
			'am' => 'አማርኛ',							// Amharic
			'ar-dz' => 'العربية (Algeria)',			// Arabic
			'ar-bh' => 'العربية (Bahrain)',			// Arabic
			'ar-eg' => 'العربية (Egypt)',			// Arabic
			'ar-iq' => 'العربية (Iraq)',			// Arabic
			'ar-jo' => 'العربية (Jordan)',			// Arabic
			'ar-kw' => 'العربية (Kuwait)',			// Arabic
			'ar-lb' => 'العربية (Lebanon)',			// Arabic
			'ar-ly' => 'العربية (Libya)',			// Arabic
			'ar-ma' => 'العربية (Morocco)',			// Arabic
			'ar-om' => 'العربية (Oman)',			// Arabic
			'ar-qa' => 'العربية (Qatar)',			// Arabic
			'ar-sa' => 'العربية (Saudi Arabia)',	// Arabic
			'ar-sy' => 'العربية (Syria)',			// Arabic
			'ar-tn' => 'العربية (Tunisia)',			// Arabic
			'ar-ae' => 'العربية (U.A.E.)',			// Arabic
			'ar-ye' => 'العربية (Yemen)',			// Arabic
			'ar' => 'العربية',						// Arabic
			'hy' => 'Հայերեն',							// Armenian
			'as' => 'অসমীয়া',								// Assamese
			'az' => 'azərbaycan',					// Azeri
			'eu' => 'euskera',						// Basque
			'be' => 'Беларуская',					// Belarusian
			'bn' => 'বাংলা',								// Bengali
			'bg' => 'Български',					// Bulgarian
			'ca' => 'Català',						// Catalan
			'zh-cn' => '简体中文 (China)',					// Chinese simplified script
			'zh-hk' => '繁體中文 (Hong Kong SAR)',			// Chinese traditional script
			'zh-mo' => '繁體中文 (Macau SAR)',				// Chinese traditional script
			'zh-sg' => '简体中文 (Singapore)',				// Chinese simplified script
			'zh-tw' => '繁體中文 (Taiwan)',				// Chinese traditional script
			'zh' => '中文',							// Chinese
			'hr' => 'Hrvatski',						// Croatian
			'cs' => 'čeština',						// Czech
			'da' => 'Dansk',						// Danish
			'dv' => 'ދިވެހި',							// Divehi
			'nl-be' => 'Nederlands (Belgium)',		// Dutch
			'nl' => 'Nederlands (Netherlands)',		// Dutch
			'en-au' => 'English (Australia)',		// English
			'en-bz' => 'English (Belize)',			// English
			'en-ca' => 'English (Canada)',			// English
			'en-ie' => 'English (Ireland)',			// English
			'en-jm' => 'English (Jamaica)',			// English
			'en-nz' => 'English (New Zealand)',		// English
			'en-ph' => 'English (Philippines)',		// English
			'en-za' => 'English (South Africa)',	// English
			'en-tt' => 'English (Trinidad)',		// English
			'en-gb' => 'English (United Kingdom)',	// English
			'en-us' => 'English (United States)',	// English
			'en-zw' => 'English (Zimbabwe)',		// English
			'en' => 'English',						// English
			'ee' => 'Ɛʋɛ',							// Ewe
			'et' => 'Eesti',						// Estonian
			'fo' => 'føroyskt',						// Faeroese
			'fa' => 'فارسی',						// Farsi
			'fi' => 'suomi',						// Finnish
			'fr-be' => 'Français (Belgium)',		// French (Belgium)
			'fr-ca' => 'Français canadien',			// French (Canada)
			'fr-lu' => 'Français (Luxembourg)',		// French
			'fr-mc' => 'Français (Monaco)',			// French
			'fr-ch' => 'Français (Switzerland)',	// French
			'fr' => 'Français',						// French
			'ff' => 'Fulfulde, Pulaar, Pular',		// Fula, Fulah, Fulani
			'gl' => 'Galego',						// Galician
			'gd' => 'Gàidhlig',						// Gaelic (Scottish)
			'ga' => 'Gaeilge',						// Gaelic (Irish)
			'gv' => 'Gaelg',						// Gaelic (Manx) (Isle of Man)
			'ka' => 'ქართული ენა',						// Georgian
			'de-at' => 'Deutsch (Austria)',			// German
			'de-li' => 'Deutsch (Liechtenstein)',	// German
			'de-lu' => 'Deutsch (Luxembourg)',		// German
			'de-ch' => 'Deutsch (Switzerland)',		// German
			'de' => 'Deutsch',						// German
			'el' => 'Ελληνικά',						// Greek
			'gu' => 'ગુજરાતી',							// Gujarati
			'ha' => 'هَوْسَ',							// Hausa
			'he' => 'עברית',						// Hebrew
			'hi' => 'हिंदी',							// Hindi
			'hu' => 'Magyar',						// Hungarian
			'is' => 'Íslenska',						// Icelandic
			'id' => 'Bahasa Indonesia',				// Indonesian
			'it-ch' => 'italiano (Switzerland)',	// Italian
			'it' => 'italiano',						// Italian
			'ja' => '日本語',							// Japanese
			'kn' => 'ಕನ್ನಡ',							// Kannada
			'kk' => 'Қазақ',						// Kazakh
			'rw' => 'Kinyarwanda',					// Kinyarwanda
			'kok' => 'कोंकणी',							// Konkani
			'ko' => '한국어/조선말',							// Korean
			'kz' => 'Кыргыз',						// Kyrgyz
			'lv' => 'Latviešu',						// Latvian
			'lt' => 'Lietuviškai',					// Lithuanian
			'luo'=> 'Dholuo',						// Luo
			'ms' => 'Bahasa Melayu',				// Malay
			'mk' => 'Македонски',					// Macedonian
			'ml' => 'മലയാളം',								// Malayalam
			'mt' => 'Malti',						// Maltese
			'mr' => 'मराठी',							// Marathi
			'mn' => 'Монгол',						// Mongolian  (Cyrillic)
			'ne' => 'नेपाली',							// Nepali
			'nb-no' => 'Norsk bokmål',				// Norwegian Bokmål
			'nb' => 'Norsk bokmål',					// Norwegian Bokmål
			'nn-no' => 'Norsk nynorsk',				// Norwegian Nynorsk
			'nn' => 'Norsk nynorsk',				// Norwegian Nynorsk
			'no' => 'Norsk',						// Norwegian
			'or' => 'ଓଡ଼ିଆ',								// Oriya
			'ps' => 'پښتو',							// Pashto
			'pl' => 'polski',						// Polish
			'pt-br' => 'português brasileiro',		// Portuguese (Brasil)
			'pt' => 'português',					// Portuguese
			'pa' => 'پنجابی/ਪੰਜਾਬੀ',					// Punjabi
			'qu' => 'Runa Simi/Kichwa',				// Quechua
			'rm' => 'Romansch',						// Rhaeto-Romanic
			'ro-md' => 'Română (Moldova)',			// Romanian
			'ro' => 'Română',						// Romanian
			'rn' => 'kiRundi', 						// Rundi
			'ru-md' => 'Pyccĸий (Moldova)',			// Russian
			'ru' => 'Pyccĸий',						// Russian
			'sg' => 'yângâ tî sängö',				// Sango
			'sa' => 'संस्कृतम्',							// Sanskrit
			'sc' => 'sardu',						// Sardinian
			'sr' => 'Srpski/српски',				// Serbian
			'sn' => 'chiShona',						// Shona
			'ii' => 'ꆇꉙ',							// Sichuan Yi
			'si' => 'සිංහල',						// Sinhalese, Sinhala
			'sk' => 'Slovenčina',					// Slovak
			'ls' => 'Slovenščina',					// Slovenian
			'so' => 'Soomaaliga/af Soomaali',		// Somali
			'st' => 'Sesotho',						// Sotho, Sutu
			'es-ar' => 'Español (Argentina)',		// Spanish
			'es-bo' => 'Español (Bolivia)',			// Spanish
			'es-cl' => 'Español (Chile)',			// Spanish
			'es-co' => 'Español (Colombia)',		// Spanish
			'es-cr' => 'Español (Costa Rica)',		// Spanish
			'es-do' => 'Español (Dominican Republic)',// Spanish
			'es-ec' => 'Español (Ecuador)',			// Spanish
			'es-sv' => 'Español (El Salvador)',		// Spanish
			'es-gt' => 'Español (Guatemala)',		// Spanish
			'es-hn' => 'Español (Honduras)',		// Spanish
			'es-mx' => 'Español (Mexico)',			// Spanish
			'es-ni' => 'Español (Nicaragua)',		// Spanish
			'es-pa' => 'Español (Panama)',			// Spanish
			'es-py' => 'Español (Paraguay)',		// Spanish
			'es-pe' => 'Español (Peru)',			// Spanish
			'es-pr' => 'Español (Puerto Rico)',		// Spanish
			'es-us' => 'Español (United States)',	// Spanish
			'es-uy' => 'Español (Uruguay)',			// Spanish
			'es-ve' => 'Español (Venezuela)',		// Spanish
			'es' => 'Español',						// Spanish
			'sw' => 'kiswahili',					// Swahili
			'sv-fi' => 'svenska (Finland)',			// Swedish
			'sv' => 'svenska',						// Swedish
			'syr' => 'ܣܘܪܝܝܐ',						// Syriac
			'ta' => 'தமிழ்',							// Tamil
			'tt' => 'татарча/تاتارچا',				// Tatar
			'te' => 'తెలుగు',							// Telugu
			'th' => 'ภาษาไทย',						// Thai
			'ti' => 'ትግርኛ',							// Tigrinya
			'ts' => 'Xitsonga',						// Tsonga
			'tn' => 'Setswana',						// Tswana
			'tr' => 'Türkçe',						// Turkish
			'tk' => 'Түркмен',						// Turkmen
			'ug' => 'ئۇيغۇرچە‎/Uyƣurqə/Уйғурчә',		// Uighur, Uyghur
			'uk' => 'Українська',					// Ukrainian
			'ur' => 'اردو',							// Urdu
			'uz' => 'o\'zbek',						// Uzbek
			've' => 'Tshivenḓa',					// Venda
			'vi' => 'Tiếng Việt',					// Vietnamese
			'wa' => 'walon',						// Waloon
			'cy' => 'Cymraeg',						// Welsh
			'wo' => 'Wolof',						// Wolof
			'xh' => 'isiXhosa',						// Xhosa
			'yi' => 'ייִדיש',						// Yiddish
			'yo' => 'Yorùbá',						// Yoruba
			'zu' => 'isiZulu',						// Zulu
		);
		
		
		private function __construct(){
			$this->_language = General::sanitize($_REQUEST['language']);
			$this->_region = General::sanitize($_REQUEST['region']);
			
			$supported_language_codes = explode(',', General::sanitize(Symphony::Configuration()->get('language_codes', 'language_redirect')));
			
			$this->_supported_language_codes = $this->cleanLanguageCodes($supported_language_codes);
		}
		
		
		
		public static function instance() {
			if (!self::$_instance) {
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
		
		public function setLanguage($language){
			$this->_language = $language;
		}
		
		public function setRegion($region){
			$this->_region = $region;
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
			return $this->_region ? $this->_language.'-'.$this->_region : $this->_language;
		}
		
		/**
		 * Get supported language codes.
		 *
		 * @return array
		 */
		public function getSupportedLanguageCodes(){
			return $this->_supported_language_codes;
		}

		/**
		 * Return languages array.
		 */
		public function getAllLanguages(){
			return $this->_languages;
		}
		
		/**
		 * Will determine current language. See method body for order.
		 */
		public function determineLanguageCode(){
			$supported_language_codes = LanguageRedirect::instance()->getSupportedLanguageCodes();
			
			// 1. from Cookie
			$Cookie = new Cookie(__SYM_COOKIE_PREFIX_ . 'language-redirect', TWO_WEEKS, __SYM_COOKIE_PATH__);
			$cookie_language_code = $Cookie->get('language');
			if( strlen($cookie_language_code) > 0 && in_array($cookie_language_code, $supported_language_codes) ){
				return $Cookie->get('region') ? $cookie_language_code.'-'.$Cookie->get('region') : $cookie_language_code;
			}
		
			// 2. from Geolocation Service
			if( Symphony::ExtensionManager()->fetchStatus('geolocation_service') == EXTENSION_ENABLED ){
				require_once(EXTENSIONS . '/geolocation_service/extension.driver.php');
		
				$ip = $this->_env['param']['env'] == 'dev' ? '92.81.156.219' : $_SERVER['REMOTE_ADDR'];
		
				$info = extension_geolocation_service::lookup($ip);
		
				if( $info instanceof XMLElement ){
					$language_code = '';
		
					foreach( $info->getChildren() as $child ){
						/*@var $child XMLElement */
		
						if( $child->getName() == 'country' ){
							$language_code = strtolower($child->getValue());
							break;
						}
					}
		
					if( !empty($language_code) && in_array($language_code, $supported_language_codes) ){
						return $language_code;
					}
				}
			}
		
			// 3. from Brower Languages
			foreach( $this->_getBrowserLanguages() as $language ){
				if( in_array($language, $supported_language_codes) ){
					return $language;
				};
			}
		
			// 4. try english
			if( in_array('en', $supported_language_codes) ){
				return 'en';
			}
		
			// 5. defaults to Reference language
			return $supported_language_codes[0];
		}
		
		
		
		/*------------------------------------------------------------------------------------------------*/
		/*  Utilities  */
		/*------------------------------------------------------------------------------------------------*/
		public function cleanLanguageCodes($language_codes){
			$clean = array_map('trim', $language_codes);
			$clean = array_filter($clean);
			
			return $clean;
		}
		
		
		
		/**
		 * Get browser languages
		 *
		 * Return languages accepted by browser as an array sorted by priority
		 * @return array language codes, e. g. 'en'
		 */
		private function _getBrowserLanguages() {
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
