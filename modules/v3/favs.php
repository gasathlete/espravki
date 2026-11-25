<?php
/* ==========================================================================
   "Любими" (favourites, like OLX): a visitor marks products with a heart and
   follows their price. Logged-in customers: kept in the table `favorites`
   (created automatically on first use). Guests: kept in the cookie "v3fav"
   and moved into the account after login.
   The price at the moment of saving is stored, so the list can show
   "поевтиня с X €" / "поскъпна с X €".
   ========================================================================== */
if(!defined('MyConst')) exit;

define('V3_FAV_COOKIE', 'v3fav');
define('V3_FAV_MAX', 100);

function v3_fav_cid(){ return (isset($_SESSION['customer']) && (int)$_SESSION['customer'] > 0) ? (int)$_SESSION['customer'] : 0; }

function v3_fav_table(){
	static $ok = null; if($ok !== null) return $ok;
	$ok = (bool)mysql_query('CREATE TABLE IF NOT EXISTS favorites (
		id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		customer_id INT UNSIGNED NOT NULL,
		product_id INT UNSIGNED NOT NULL,
		saved_price DECIMAL(10,2) NOT NULL DEFAULT 0,
		added DATETIME NOT NULL,
		UNIQUE KEY customer_product (customer_id, product_id),
		KEY product (product_id)
	) DEFAULT CHARSET=utf8');
	return $ok;
}

// the price a buyer pays now (promo when it is lower)
function v3_fav_price($p){
	$price = (float)$p['price']; $promo = isset($p['promo_price']) ? (float)$p['promo_price'] : 0;
	return ($promo > 0 && ($promo < $price || $price <= 0)) ? $promo : $price;
}

function v3_fav_cookie_read(){
	$o = array();
	if(empty($_COOKIE[V3_FAV_COOKIE])) return $o;
	foreach(explode(',', (string)$_COOKIE[V3_FAV_COOKIE]) as $pair){
		$x = explode(':', $pair);
		$id = (int)$x[0]; if($id > 0) $o[$id] = isset($x[1]) ? round((float)$x[1], 2) : 0;
		if(count($o) >= V3_FAV_MAX) break;
	}
	return $o;
}
function v3_fav_cookie_write($list){
	$parts = array(); foreach($list as $id => $pr) $parts[] = (int)$id.':'.number_format((float)$pr, 2, '.', '');
	$v = implode(',', array_slice($parts, -V3_FAV_MAX));
	if(headers_sent()) return;
	if($v === '') setcookie(V3_FAV_COOKIE, '', time() - 3600, '/');
	else setcookie(V3_FAV_COOKIE, $v, time() + 365 * 86400, '/', '', !empty($_SERVER['HTTPS']), true);
	$_COOKIE[V3_FAV_COOKIE] = $v;
}

/* product id => saved price */
function v3_fav_list(){
	$cache =& $GLOBALS['V3_FAV_CACHE']; if($cache !== null) return $cache;
	$cid = v3_fav_cid();
	if(!$cid) return $cache = v3_fav_cookie_read();
	if(!v3_fav_table()) return $cache = array();
	// first visit after login: move the guest list into the account
	$guest = v3_fav_cookie_read();
	if($guest){
		foreach($guest as $pid => $pr) mysql_query('INSERT IGNORE INTO favorites SET customer_id = '.$cid.', product_id = '.(int)$pid.', saved_price = "'.number_format($pr, 2, '.', '').'", added = NOW()');
		v3_fav_cookie_write(array());
	}
	$cache = array();
	$r = mysql_query('SELECT product_id, saved_price FROM favorites WHERE customer_id = '.$cid.' ORDER BY added DESC');
	if($r) while($x = mysql_fetch_row($r)) $cache[(int)$x[0]] = (float)$x[1];
	return $cache;
}
function v3_fav_has($pid){ $l = v3_fav_list(); return isset($l[(int)$pid]); }
function v3_fav_count(){ return count(v3_fav_list()); }

/* add or remove; returns array(on, count) or false when the product is not available */
function v3_fav_toggle($pid){
	$pid = (int)$pid;
	$r = mysql_query('SELECT p.price, p.promo_price FROM products p JOIN adverts a ON a.id = p.advert_id
		WHERE p.id = '.$pid.' AND p.deleted = "0" AND p.active = "1" AND a.active = "1" AND a.deleted = "0" LIMIT 1');
	$on_now = v3_fav_has($pid);
	if(!$on_now && (!$r || !mysql_num_rows($r))) return false;
	$price = ($r && mysql_num_rows($r)) ? v3_fav_price(mysql_fetch_assoc($r)) : 0;
	$cid = v3_fav_cid();
	$GLOBALS['V3_FAV_CACHE'] = null;   // the list changes: read it again next time
	if($cid){
		v3_fav_table();
		if($on_now) mysql_query('DELETE FROM favorites WHERE customer_id = '.$cid.' AND product_id = '.$pid);
		else mysql_query('INSERT IGNORE INTO favorites SET customer_id = '.$cid.', product_id = '.$pid.', saved_price = "'.number_format($price, 2, '.', '').'", added = NOW()');
		$n = 0; $c = mysql_query('SELECT COUNT(*) FROM favorites WHERE customer_id = '.$cid); if($c){ $x = mysql_fetch_row($c); $n = (int)$x[0]; }
		return array(!$on_now, $n);
	}
	$list = v3_fav_cookie_read();
	if($on_now) unset($list[$pid]); else $list[$pid] = $price;
	v3_fav_cookie_write($list);
	return array(!$on_now, count($list));
}

/* heart button; v3.js sends it to ajax/fav.php */
function v3_fav_btn($pid, $extra_class = '', $with_text = false){
	$on = v3_fav_has($pid);
	return '<button type="button" class="v3-fav'.($on ? ' is-on' : '').($extra_class ? ' '.$extra_class : '').'" data-fav="'.(int)$pid.'" aria-pressed="'.($on ? 'true' : 'false').'" aria-label="'.($on ? 'Махни от любими' : 'Добави в любими').'" title="'.($on ? 'В любими' : 'Добави в любими').'">'
		.'<svg class="v3-ic" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20.5s-7.5-4.6-9.3-9.4C1.5 7.8 3.6 4.5 7 4.5c2 0 3.6 1.1 5 3 1.4-1.9 3-3 5-3 3.4 0 5.5 3.3 4.3 6.6-1.8 4.8-9.3 9.4-9.3 9.4z"/></svg>'
		.($with_text ? '<span>'.($on ? 'В любими' : 'Добави в любими').'</span>' : '').'</button>';
}
