<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global $USER;
//echo "<pre>"; print_r($_POST); echo "</pre>";
//mail('cron@clementin.ru', '_POST', print_r($_POST, true));

$key = "ibkelOqSguL5R2o4a2tUcWRsF00YoyAOCmuM0lm9";
function encrypt($string, $key) {
	$result = '';
	for ($i = 0; $i < strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key)) - 1, 1);
		$char = chr(ord($char) + ord($keychar));
		$result .= $char;
	}
	return base64url_encode($result);
}

function base64url_encode($data) { 
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
}

// подключаем модуль
use Bitrix\Main\Loader; 
Loader::includeModule('clementin.authsms');
$fone_personal_pole = COption::GetOptionString('clementin.authsms', 'fone_personal_pole');
if(empty($fone_personal_pole)) $fone_personal_pole = 'PERSONAL_PHONE';
//echo '<pre>fone_personal_pole: '; print_r($fone_personal_pole); echo '</pre>';

// группа пользователей, зарегистрировавшаяся по телефону
$rsGroups = CGroup::GetList ($by = "c_sort", $order = "asc", Array ("STRING_ID" => 'authsms_code_group'));
$Groups = $rsGroups->Fetch();
//echo '<pre>'; print_r($Groups); echo '</pre>';
if(empty($Groups)) {
	$group = new CGroup;
	$arFields = Array(
		"ACTIVE"       => "Y",
		"C_SORT"       => 10000,
		"NAME"         => "Клементин: смс авторизация",
		"DESCRIPTION"  => 'Пользователи, зарегистрировшиеся при помощи модуля "Клементин: смс авторизация"',
		"STRING_ID"    => "authsms_code_group"
	);
	$NEW_GROUP_ID = $group->Add($arFields);
	if(strlen($group->LAST_ERROR) > 0) {
		echo __FILE__ . ' ERROR $group->Add($arFields), str 28:<br>';
		echo $group->LAST_ERROR . '<br>';
		die();
	}
} else {
	$NEW_GROUP_ID = $Groups['ID'];
}
//echo '<pre>'; print_r($NEW_GROUP_ID); echo '</pre>';

/////////////  проверяем есть ли в системе ///////
	$res = CUser::GetList(
		($by = 'id'),
		($order = 'desc'),
		array(),
		array('SELECT' => array('*'))
	);
	while($rsUsers = $res->Fetch()) {
		$arUsers[] = $rsUsers;
	}
	
if(isset($_POST['nom_tel'])) {
	foreach($arUsers as $value) { // проверка по полю телефон
		$value[$fone_personal_pole] = str_replace('  ', '', $value[$fone_personal_pole]);
		$value[$fone_personal_pole] = str_replace(' ', '', $value[$fone_personal_pole]);
		$value[$fone_personal_pole] = str_replace('(', '', $value[$fone_personal_pole]);
		$value[$fone_personal_pole] = str_replace(')', '', $value[$fone_personal_pole]);
		$value[$fone_personal_pole] = str_replace('-', '', $value[$fone_personal_pole]);
		$value[$fone_personal_pole] = str_replace('+', '', $value[$fone_personal_pole]);
		//echo "value:<br>\r\n"; print_r($value); echo "<br>\r\n";
		
		$slovo_arr = str_split($value[$fone_personal_pole]);
		$slovo = '';
		for($i = 1; $i < count($slovo_arr); $i++) {
			$slovo .= $slovo_arr[$i];
		}
		$fone = '7' . $slovo;
		//echo "fone\r\n"; print_r($fone); echo "<br>\r\n";

		if($fone == trim($_POST['nom_tel'])) { // проверка по полю телефон
			$otvet = $value['ID'];
			$old_new = 'old';
			CUser::SetUserGroup($otvet, array_merge(CUser::GetUserGroup($otvet), array($NEW_GROUP_ID)));
			break;
		}
		//echo "<pre>otvet"; print_r($otvet); echo "</pre>";
	}
	
	if(empty($otvet)) {
		foreach($arUsers as $value) { // проверка по полю логин
			if($value['LOGIN'] == trim($_POST['nom_tel'])) {
				$otvet = $value['ID'];
				$old_new = 'old';
				CUser::SetUserGroup($otvet, array_merge(CUser::GetUserGroup($otvet), array($NEW_GROUP_ID)));
				break;
			}
		}
	}

	if(empty($otvet)) {
		$pass = mt_rand(111111, 999999);
		$user = new CUser;
		$arFields = array(
			'PERSONAL_PHONE'   => $_POST['nom_tel'],
			'PERSONAL_MOBILE'   => $_POST['nom_tel'],
			'NAME'             => '',
			'LAST_NAME'        => '',
			'EMAIL'            => $_POST['nom_tel'] . '@example.com',
			'LOGIN'            => $_POST['nom_tel'],
			'LID'              => 'ru',
			'ACTIVE'           => 'Y',
			'PASSWORD'         => $pass,
			'CONFIRM_PASSWORD' => $pass,
			'GROUP_ID'         => array($NEW_GROUP_ID),
			'PERSONAL_PHOTO'   => '',
		);
		$ID = $user->Add($arFields);
		if((int)$ID > 0) {
			$otvet = $ID;
			$old_new = 'new';
		} else {
			$otvet = 'Не удалось добавить пользователя модулю clementin.authsms ' .$_POST['nom_tel']. '. Причина — ' . $user->LAST_ERROR;
		}
	}
}

if(isset($_POST['email'])) {
	$res = CUser::GetList(
		($by = 'id'),
		($order = 'desc'),
		array(),
		array('SELECT' => array('*'))
	);	
	foreach($arUsers as $value) {
		if($value['EMAIL'] == trim($_POST['email'])) { // проверка по полю EMAIL
			$otvet = $value['ID'];
			$old_new = 'old';
			CUser::SetUserGroup($otvet, array_merge(CUser::GetUserGroup($otvet), array($NEW_GROUP_ID)));
			break;
		}
	}
	
	if(empty($otvet)) {
		foreach($arUsers as $value) { // проверка по полю логин
			if($value['LOGIN'] == trim($_POST['email'])) {
				$otvet = $value['ID'];
				$old_new = 'old';
				CUser::SetUserGroup($otvet, array_merge(CUser::GetUserGroup($otvet), array($NEW_GROUP_ID)));
				break;
			}
		}
	}

	if(empty($otvet)) {
		$pass = mt_rand(111111, 999999);
		$user = new CUser;
		$arFields = array(
			'PERSONAL_PHONE'   => '',
			'PERSONAL_MOBILE'   => '',
			'NAME'             => '',
			'LAST_NAME'        => '',
			'EMAIL'            => $_POST['email'],
			'LOGIN'            => $_POST['email'],
			'LID'              => 'ru',
			'ACTIVE'           => 'Y',
			'PASSWORD'         => $pass,
			'CONFIRM_PASSWORD' => $pass,
			'GROUP_ID'         => array($NEW_GROUP_ID),
			'PERSONAL_PHOTO'   => '',
		);
		$ID = $user->Add($arFields);
		if((int)$ID > 0) {
			$otvet = $ID;
			$old_new = 'new';
		} else {
			$otvet = 'Не удалось добавить пользователя модулю clementin.authsms по e-mail ' .$_POST['email']. '. Причина — ' . $user->LAST_ERROR;
		}
	}
}




if((int)$otvet > 0) {
	$code = $otvet * $otvet * $otvet * $otvet * $otvet;
	$code = 12578935*$code + 6475893654575;
	//$code = encrypt($code, $key);
	echo (string)$code . $old_new;
}