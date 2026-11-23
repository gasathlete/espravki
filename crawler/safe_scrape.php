<?php
/*
 * Safe replacement for eval() of the scraper "identifiers" stored in table adverts.
 * Understands ONLY lines like:
 *   $title = $html->find('div.prohead > h1',0)->outertext();
 *   $price = $html->find('div.price',0)->plaintext;
 *   $image = $html->find('div#imgw > img');
 * Anything else is refused - no PHP code from the database is ever executed.
 * PHP 5.4 compatible.
 */
if(!function_exists('safe_scrape')){
function safe_scrape($html, $code){
	$allowed_vars  = array('title','description','price','image','categoryname','promo_price');
	$allowed_props = array('plaintext','innertext','outertext','src','href','alt','title','content','value');

	$re = '/^\s*\$([a-z_]+)\s*=\s*\$html->find\(\s*([\'"])([^\'"]{1,300})\2\s*(?:,\s*(-?\d{1,3})\s*)?\)'
	    . '(?:\s*->\s*([a-z]+)\s*(\(\s*\))?)?\s*;?\s*$/i';
	if(!is_object($html) || !preg_match($re, (string)$code, $m)) return array();

	$var = strtolower($m[1]);
	if(!in_array($var, $allowed_vars)) return array();

	$selector = $m[3];
	$index    = (isset($m[4]) && $m[4] !== '') ? intval($m[4]) : null;
	$prop     = isset($m[5]) ? strtolower($m[5]) : '';

	$found = ($index === null) ? $html->find($selector) : $html->find($selector, $index);

	if($prop !== ''){
		if(!in_array($prop, $allowed_props)) return array();
		if(is_array($found)) $found = isset($found[0]) ? $found[0] : null;
		if(!is_object($found)) return array($var => '');
		$found = $found->$prop; // simple_html_dom: ->outertext() and ->outertext give the same text
	}
	return array($var => $found);
}
}

/* Only allow public http/https addresses (blocks reading local files and the internal network) */
if(!function_exists('safe_public_url')){
function safe_public_url($url){
	$p = @parse_url($url);
	if(empty($p['scheme']) || empty($p['host'])) return false;
	if(!in_array(strtolower($p['scheme']), array('http','https'))) return false;
	$ip = gethostbyname($p['host']);
	if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) return false;
	return true;
}
}
