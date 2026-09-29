<?php

class search_keywords
{
	var $referer;

	var $search_engine;

	var $keys;

	var $sep;

	function search_keywords($url=''){
		if(empty($url)) $this->referer=WebSite;
		else $this->referer = urldecode($url);
		$this->sep = (@eregi('(\?q=|\?qt=|\?p=)', $this->referer)) ? '\?' : '\&';
	}

	function get_keys(){
		if (!empty($this->referer)){
				if (@eregi('www\.google', $this->referer)){
					// Google
					preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
					$this->search_engine = 'Google';
				}
				elseif (@eregi('www\.bing', $this->referer)){
					// Bing
					preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
					$this->search_engine = 'Bing';
				}
				elseif (@eregi('\.facebook.com', $this->referer)){
					// Facebook
					preg_match("#{$this->sep}u=(.*?)\&#si", $this->referer, $this->keys);
					$this->search_engine = 'Facebook';
				}
				else if (@eregi('(yahoo\.com|search\.yahoo)', $this->referer)){
					// Yahoo
					preg_match("#{$this->sep}p=(.*?)\&#si", $this->referer, $this->keys);
					$this->search_engine = 'Yahoo';
				}
				else if (@eregi('search\.msn', $this->referer)){
					// MSN
					preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
					$this->search_engine = 'MSN';
				}
				else if (@eregi('www\.alltheweb', $this->referer)){
					// AllTheWeb
					preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
					$this->search_engine = 'AllTheWeb';
				}
				else if (@eregi('(looksmart\.com|search\.looksmart)', $this->referer)){
					// Looksmart
					preg_match("#{$this->sep}qt=(.*?)\&#si", $this->referer, $this->keys);
					$this->search_engine = 'Looksmart';
				}
				else if (@eregi('(askjeeves\.com|ask\.com)', $this->referer)){
					// AskJeeves
					preg_match("#{$this->sep}q=(.*?)\&#si", $this->referer, $this->keys);
					$this->search_engine = 'AskJeeves';
				}
				else{
					$this->keys = 'Not available';
					$this->search_engine = 'Unknown';
				}
				return array(
					$this->referer,
					(!is_array($this->keys) ? $this->keys : $this->keys[1]),
					$this->search_engine
				);
		}
		return array();
	}
}
?>