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
			);
		}	
		
	}

