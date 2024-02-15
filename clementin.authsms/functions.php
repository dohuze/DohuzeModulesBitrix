<?
error_reporting(0);





function getIp($default = '') {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$value = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$value = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
		$value = $_SERVER['REMOTE_ADDR'];
	} else {
		return $default;
	}
 
	return $value;
}

function poisk_lev_prav__lev($lev, $prav, $stroka) {
    $razbien_array = explode($lev, $stroka);
	//print_r($razbien_array);
    $razbien_array_1 = explode($prav, $razbien_array[1]);
    $iskom_stroka = trim($razbien_array_1[0]);
    return $iskom_stroka;
}

/** 
 * Проверить строку на формальное соответствие виду ip адреса
 * @return bool
 */
function is_valid_ip($ip) {
    $ipv4 = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
    $ipv6 = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';
    return preg_match("/^($ipv4|$ipv6)\$/", trim($ip));
}

function chistka_nnamee($path) {
	$path = preg_replace('|[\s]+|s', '', $path);
	$path = str_replace("'", '"', $path);
	$path = str_replace('{', '', $path);
	$path = str_replace('}', '', $path);
	$path = str_replace('. ', '', $path);
	$path = str_replace(' .', '', $path);
	$path = str_replace('SITE_DIR.', '', $path);
	$path = str_replace('SITE_DIR', '', $path);
	$path = str_replace('/"', '/index.php', $path);
	$path = str_replace('"', '', $path);
	$path = str_replace('=', '', $path);
	$path = '/' . trim($path);
	return $path;
}

function php_get($url, $content) {
	$headers = stream_context_create(array(
		'http' => array(
			'method' => 'POST',
			'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
			'content' => $content
		),
	));
	return file_get_contents($url, false, $headers);
}
//echo __DIR__;

function bx_mail($email_to, $email_from, $subject, $text) { //аналог mail()
	// бля...    приходится определять что бы почт шаблон создавался
	$rsSites = CSite::GetList($by="sort", $order="desc", array());
	while ($arSite = $rsSites->Fetch()) {
		$arSitesId[] = $arSite["ID"];
	}
	#echo "<pre>arSitesId: "; print_r($arSitesId); #echo "</pre>";

	// создаём или изменяем почтовое событие
	$rsET = CEventType::GetList(array("TYPE_ID" => "BX_MAIL_DOHUZE"));
	$arET = $rsET->Fetch();
	#echo "<pre>arET: "; print_r($arET); #echo "</pre>";

	$arFields = array(
		"LID"           => "ru",
		"EVENT_NAME"    => "BX_MAIL_DOHUZE",
		"NAME"          => "BX_MAIL_DOHUZE",
		"DESCRIPTION"   => "BX_MAIL_DOHUZE"
	);

	if(empty($arET)) {
		CEventType::Add($arFields);
	} else {
		CEventType::Update(array("ID" => $arET['ID']), $arFields);
	}

	// создаём или изменяем прикреплённый к почт соб почтовый шаблон
	$rsMess = CEventMessage::GetList($by="id", $order="desc", array('EVENT_NAME' => 'BX_MAIL_DOHUZE'));
	$arMess = $rsMess->GetNext();
	#echo "<pre>arMess: "; print_r($arMess); #echo "</pre>";

	$arFields = array(
		'ACTIVE'     =>  'Y',
		'EVENT_NAME' =>  'BX_MAIL_DOHUZE',
		'LID' =>  $arSitesId[0],
		'EMAIL_FROM' =>  $email_from,
		'EMAIL_TO' =>  $email_to,
		'BODY_TYPE' =>  'text',
		'SUBJECT' =>  $subject,
		'MESSAGE' =>  $text
	);

	$em = new CEventMessage;
	if(empty($arMess)) {
		if(empty($em->Add($arFields))) {
			#echo $em->LAST_ERROR . "<br>\r\n";
		}
	} else {
		if(empty($em->Update($arMess["ID"], $arFields))) {
			#echo $em->LAST_ERROR . "<br>\r\n";
		}
	}

	// D7
	//$result = \Bitrix\Main\Mail\Event::sendImmediate(array( // В отличие от CEvent::Send не возвращает идентификатор созданного сообщения. При отправке сообщения данным методом запись в таблицу b_event не производится.
	$result = \Bitrix\Main\Mail\Event::send(array(
		"EVENT_NAME" => "BX_MAIL_DOHUZE",
		"LID" => $arSitesId[0],
		"C_FIELDS" => array(
			"email_to" => $email_to,
			"email_from" => $email_from,
			"subject" => $subject,
			"text" => $text,
			"date" => date("d.m.Y H:i")
		)
	));
	if(!$result->isSuccess()) {
		echo "Ошибка отправки почты: "; print_r($result->getErrorMessages()); echo "<br>\r\n";
		return false;
	} else {
		if(strlen(file_get_contents('http://' . $_SERVER["SERVER_NAME"])) > 0)
			return true;
	}
}

function formirovanie_folder_sms() {
	if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/sms'))
		mkdir($_SERVER["DOCUMENT_ROOT"] . '/sms');
	if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/sms/css'))
		mkdir($_SERVER["DOCUMENT_ROOT"] . '/sms/css');
	if(!file_exists($_SERVER["DOCUMENT_ROOT"] . '/sms/css/custom.css')) {
		file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/sms/css/custom.css', '');
	}
	copy(__DIR__ . '/install/sms/index.php', $_SERVER["DOCUMENT_ROOT"] . '/sms/index.php');
}

function formirovanie_folder_include() {
	
	$stroka_css = '.sms-form__title {
		color: ' .COption::GetOptionString("clementin.authsms", "color_str_zagolovok") . ' !important;
	}

	.sms-form__submitter {
		color: ' .COption::GetOptionString("clementin.authsms", "color_str_knopki") . ' !important;
		background: ' .COption::GetOptionString("clementin.authsms", "color_buttom"). ' 0% 0% no-repeat padding-box !important;
		border-radius: ' .COption::GetOptionString("clementin.authsms", "radius_zakrugl"). 'px !important;
	}';
	file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/install/sms/css_1/styleForModule.css', $stroka_css);
	file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/install/sms/css_2/styleForModule.css', $stroka_css);
	
	
	if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/include'))
		mkdir($_SERVER["DOCUMENT_ROOT"] . '/include');

	CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/install/sms', $_SERVER["DOCUMENT_ROOT"] . "/include/clementin.authsms");
	
	CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/install/sms/content', $_SERVER["DOCUMENT_ROOT"] . "/include/clementin.authsms/content");
	CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/install/sms/css_1', $_SERVER["DOCUMENT_ROOT"] . "/include/clementin.authsms/css_1");
	CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/install/sms/css_2', $_SERVER["DOCUMENT_ROOT"] . "/include/clementin.authsms/css_2");
	CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/install/sms/fonts', $_SERVER["DOCUMENT_ROOT"] . "/include/clementin.authsms/fonts");
	CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/install/sms/js', $_SERVER["DOCUMENT_ROOT"] . "/include/clementin.authsms/js");
	unlink($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/index.php');

}

$type_mess = COption::GetOptionString("clementin.authsms", "type_mess");
//echo "<pre>type_mess "; print_r($type_mess); echo "</pre>";
$provider = COption::GetOptionString("clementin.authsms", "provider");
//echo "<pre>provider "; print_r($provider); echo "</pre>";
if(empty($provider)) {
	COption::SetOptionString("clementin.authsms", "provider");
}

if($provider == 'smsru') {
	$api_key_smsru = COption::GetOptionString("clementin.authsms", "api_key_smsru");
	//echo "<pre>api_key_smsru "; print_r($api_key_smsru); echo "</pre>";
	
	$json = json_decode(php_get('https://sms.ru/my/balance', 'api_id=' . $api_key_smsru . '&json=1'), true);
	//echo "<pre>balance "; print_r($json[balance]); echo "</pre>";
	$balance = (empty($json['balance']))?'':$json['balance'];
}

if($provider == 'smscru') {
	$login_smscru = COption::GetOptionString("clementin.authsms", "login_smscru");
	//echo "<pre>login_smscru "; print_r($login_smscru); echo "</pre>";
	$password_smscru = COption::GetOptionString("clementin.authsms", "password_smscru");
	//echo "<pre>password_smscru "; print_r($password_smscru); echo "</pre>";
	$balance = htmlspecialcharsbx(file_get_contents('https://' . 'smsc.ru/sys/balance.php?login=' . $login_smscru . '&psw=' . $password_smscru));
	if(!is_numeric($balance)) $balance = 0;
	//echo '<pre>'; print_r($balance); echo '</pre>';
}

if($provider == 'smsintru') {
	$login_smsintru = COption::GetOptionString("clementin.authsms", "login_smsintru");
	//echo "<pre>login_smsintru:"; print_r($login_smsintru); echo "</pre>";
	$password_smsintru = COption::GetOptionString("clementin.authsms", "password_smsintru");
	//echo "<pre>password_smsintru:"; print_r($password_smsintru); echo "</pre>";
	
	if(!empty($login_smsintru) && !empty($password_smsintru)) {
		$json = json_decode(php_get('https://lcab.smsint.ru/lcabApi/info.php', 'login=' .$login_smsintru. '&password=' . $password_smsintru), true);
		//echo "<pre>json: "; print_r($json); echo "</pre>";
		$balance = $json[account];
		//echo "<pre>balance: "; print_r($json[account]); echo "</pre>";
	}
}

if($provider == 'targetsmsru') {
	$login_targetsmsru = COption::GetOptionString("clementin.authsms", "login_targetsmsru");
	//echo "<pre>login_targetsmsru:"; print_r($login_targetsmsru); echo "</pre>";
	$password_targetsmsru = COption::GetOptionString("clementin.authsms", "password_targetsmsru");
	//echo "<pre>password_targetsmsru:"; print_r($password_targetsmsru); echo "</pre>";
	
	if(!empty($login_targetsmsru) && !empty($password_targetsmsru)) {
		$param = array(
			'security' => array('login' => $login_targetsmsru, 'password' => $password_targetsmsru),
			'type' => 'balance'
		);
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/json; charset=utf-8' . PHP_EOL,
				'content' => json_encode($param)
			)
		);
		$json = json_decode(file_get_contents('https://sms.targetsms.ru/sendsmsjson.php', false, stream_context_create($opts)) , true);
		//echo "<pre>json: "; print_r($json); echo "</pre>";
		$balance = $json['money']['value'] . ' ' . $json['money']['currency'];
	}
}

if($provider == 'smsprostoru') {
	$login_smsprostoru = COption::GetOptionString("clementin.authsms", "login_smsprostoru");
	//echo "<pre>login_smsprostoru:"; print_r($login_smsprostoru); echo "</pre>";
	$password_smsprostoru = COption::GetOptionString("clementin.authsms", "password_smsprostoru");
	//echo "<pre>password_smsprostoru:"; print_r($password_smsprostoru); echo "</pre>";

	if(!empty($login_smsprostoru) && !empty($password_smsprostoru)) {
		$responce_arr = json_decode(file_get_contents('https://ssl.bs00.ru/?' . 'method=get_profile&format=JSON&email=' .$login_smsprostoru. '&password=' . $password_smsprostoru), true);
		//echo '<pre>: responce_arr'; print_r($responce_arr); echo '</pre>';
		$balance = $responce_arr['response']['data']['credits'];
	}
}


/* $nnnumber_tempplate = COption::GetOptionString("clementin.authsms", "nnnumber_tempplate");
echo "<pre>nnnumber_tempplate="; print_r($nnnumber_tempplate); echo "</pre>"; */


formirovanie_folder_sms();
formirovanie_folder_include();				


if(empty($balance)) {
	$balance = 0;
}


