<?php
/* Suspicious-content check for listings and products.
   It does NOT block anything – it only shows warnings to the admin, who decides.
   To add a word: put it in the list below (lowercase, the beginning of the word is enough). */
function content_flag_words(){
	return array(
		'измама / предплащане' => array('предплат', 'предварително плащане', 'western union', 'moneygram', 'уестърн юниън', 'биткойн', 'bitcoin', 'крипто', 'usdt', 'бърза печалба', 'гарантирана печалба', 'пасивен доход'),
		'оръжия'               => array('оръжи', 'пистолет', 'револвер', 'боеприпас', 'взрив', 'газов пистолет'),
		'лекарства / дрога'    => array('наркот', 'марихуан', 'канабис', 'cbd','кокаин', 'амфетамин', 'виагра', 'viagra', 'стероид', 'анаболн', 'без рецепта', 'лекува рак'),
		'фалшиви стоки'        => array('реплика', 'replica', 'копие на', 'aaa качество', '1:1', 'fake', 'фалшив'),
		'документи / данни'    => array('лична карта', 'шофьорска книжка', 'диплома за продан', 'имейл бази', 'акаунти за продан'),
		'хазарт / възрастни'   => array('казино', 'casino', 'залагания', 'ескорт', 'escort', 'еротичн', 'xxx', '18+'),
	);
}

/* returns array of "category: word" strings found in the text (empty = nothing suspicious) */
function content_flags($text){
	$t = html_entity_decode(strip_tags((string)$text), ENT_QUOTES, 'UTF-8');
	$t = function_exists('mb_strtolower') ? mb_strtolower($t, 'UTF-8') : strtolower($t);
	$found = array();
	foreach(content_flag_words() as $cat => $words){
		foreach($words as $w){
			if(strpos($t, $w) !== false){ $found[] = $cat.': „'.$w.'“'; break; }
		}
	}
	// many links or messenger contacts in the text often mean spam
	if(preg_match_all('~(https?://|www\.)~i', $t, $m) >= 3) $found[] = 'много линкове';
	if(preg_match('~(t\.me/|telegram|whatsapp\s*\+|wa\.me/)~i', $t)) $found[] = 'контакт през Telegram/WhatsApp';
	return $found;
}

/* small HTML badge for admin tables */
function content_flags_html($text){
	$f = content_flags($text);
	if(!$f) return '<span style="color:#2a8a3a">✓</span>';
	return '<span style="color:#c0392b;font-weight:bold" title="'.htmlspecialchars(implode("\n", $f), ENT_QUOTES, 'UTF-8').'">⚠ '.htmlspecialchars(implode(', ', $f), ENT_QUOTES, 'UTF-8').'</span>';
}
