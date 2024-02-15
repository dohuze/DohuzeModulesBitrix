<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/clementin.authsms/functions.php');

/* if(count($_POST) > 0) {
	foreach ($_POST as $key => $value) {
		$_POST[$key] = htmlspecialcharsbx($value);
	}
}
if(count($_GET) > 0) {
	foreach ($_GET as $key => $value) {
		$_GET[$key] = htmlspecialcharsbx($value);
	}
} */

global $USER;
//echo "<pre>"; print_r($_POST); echo "</pre>";

// подключаем модуль
use Bitrix\Main\Loader;
Loader::includeModule('clementin.authsms');

// проверка IP на чёрный список
/* $black_ip_json = (empty(COption::GetOptionString("clementin.authsms", "black_ip_json"))) ? array() : COption::GetOptionString("clementin.authsms", "black_ip_json");
$black_ip_arr = unserialize($black_ip_json);
if(in_array(getIp(), $black_ip_arr)) {
    echo 'ERROR IP block list';
	die();
} */

// zapis v sessiyu

session_start();
if(!empty($_SESSION['counter_json_dohuze'])) {
	$counter_json_dohuze_arr = json_decode($_SESSION['counter_json_dohuze'], true);
	$counter_json_dohuze_arr[] = time();
} else {
	$counter_json_dohuze_arr[] = time();
}
//echo '<pre>counter_json_dohuze_arr: '; print_r($counter_json_dohuze_arr); echo '</pre>';
$_SESSION['counter_json_dohuze'] = json_encode($counter_json_dohuze_arr);

// proverka na formulu
/* $tttime_sec = COption::GetOptionString("clementin.authsms", "tttime_sec");
//echo "<pre>tttime_sec "; print_r($tttime_sec); echo "</pre>";
$tttime_count = COption::GetOptionString("clementin.authsms", "tttime_count");
//echo "<pre>tttime_count "; print_r($tttime_count); echo "</pre>";
$time_1 = $counter_json_dohuze_arr[0];
$time_2 = $counter_json_dohuze_arr[count($counter_json_dohuze_arr) - 1];
if($time_1 > 0 && $time_2 > 0 && ($time_2 - $time_1) / count($counter_json_dohuze_arr) < ($tttime_sec / $tttime_count)) {
	$black_ip_json = (empty(COption::GetOptionString("clementin.authsms", "black_ip_json"))) ? array() : COption::GetOptionString("clementin.authsms", "black_ip_json");
	$black_ip_arr = unserialize($black_ip_json);
	$black_ip_arr[] = getIp();
	$black_ip_arr = array_unique($black_ip_arr);
	COption::SetOptionString("clementin.authsms", "black_ip_json", serialize($black_ip_arr));
	
	//echo '<pre>$time_1: '; print_r($time_1); echo '</pre>';
	//echo '<pre>$time_2: '; print_r($time_2); echo '</pre>';
	//echo '<pre>$black_ip_json: '; print_r($black_ip_json); echo '</pre>';
	
	
	die();
} */

$nom_tel = trim($_POST['nom_tel']);
$msg = base64_decode(COption::GetOptionString('clementin.authsms', 'module_clementin_authsms_user_id_' . trim($_POST['msg'])));

$type_mess = COption::GetOptionString("clementin.authsms", "type_mess");
//echo "<pre>type_mess "; print_r($type_mess); echo "</pre>";
$provider = COption::GetOptionString("clementin.authsms", "provider");
//echo "<pre>provider "; print_r($provider); echo "</pre>";
$strlen_generir_code = COption::GetOptionString("clementin.authsms", "strlen_generir_code");
//echo "<pre>strlen_generir_code "; print_r($strlen_generir_code); echo "</pre>";
$str_email_from = COption::GetOptionString("clementin.authsms", "str_email_from");
$str_email_subject = COption::GetOptionString("clementin.authsms", "str_email_subject");
$str_email_body = COption::GetOptionString("clementin.authsms", "str_email_body");



if(empty($strlen_generir_code)) {
	$strlen_generir_code = 4;
}

if(isset($_POST['email']) && isset($_POST['msg'])) { // код на e-mail
	if(empty($str_email_from) || empty($str_email_subject) || empty($str_email_body)) {
		echo 'Не указан e-mail отправитель, тема или тело письма';
		die();
	}

	if(bx_mail($_POST['email'], $str_email_from, $str_email_subject, $str_email_body . $msg)) {
		echo 'OK';
	} else {
		echo 'error: --Email was not sent--';
	}
	die();
}


if($type_mess == 'type_mess_sms') {
	
	if($provider == 'smsru') {
		if($nom_tel == '+79823674905') {
			$api_key = COption::GetOptionString('clementin.authsms', 'api_key_smsru') . 'test=1';
		} else {
			$api_key = COption::GetOptionString('clementin.authsms', 'api_key_smsru');
		}
		if(empty($api_key)) {
			die('ERROR API-KEY');
		}
		
		if($nom_tel == '+79823674905') {
			echo 'OK';
		} else {
			$arr = json_decode(htmlspecialcharsbx(file_get_contents('https://sms.ru/sms/send?api_id=' . $api_key . '&to=' . $nom_tel . '&msg=' . $msg . '&json=1')), true);
			//echo "<pre>"; print_r($arr); echo "</pre>";
			echo 'smsru:' . print_r($arr);
		}
	}

	if($provider == 'smscru') {
		$login_smscru = COption::GetOptionString("clementin.authsms", "login_smscru");
		//echo "<pre>login_smscru "; print_r($login_smscru); echo "</pre>";
		$password_smscru = COption::GetOptionString("clementin.authsms", "password_smscru");
		//echo "<pre>password_smscru "; print_r($password_smscru); echo "</pre>";
		
		$arr = htmlspecialcharsbx(file_get_contents('https://smsc.ru/sys/send.php?login=' .$login_smscru. '&psw=' .$password_smscru. '&phones=' .$nom_tel. '&mes=' .$msg. '&charset=utf-8'));
		//echo "<pre>"; print_r($arr); echo "</pre>";
	}
	
	if($provider == 'smsintru') {
		$login_smsintru = COption::GetOptionString("clementin.authsms", "login_smsintru");
		//echo "<pre>login_smsintru:"; print_r($login_smsintru); echo "</pre>";
		$password_smsintru = COption::GetOptionString("clementin.authsms", "password_smsintru");
		//echo "<pre>password_smsintru:"; print_r($password_smsintru); echo "</pre>";
		
		if(!empty($login_smsintru) && !empty($password_smsintru)) {
			$headers = stream_context_create(array(
				'http' =>	array(
								'method' 	=> 'POST',
								'header' 	=> 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
								'content' 	=> 'login=' .$login_smsintru. '&password=' .$password_smsintru. '&txt=' .$msg. '&check=0'
							)
			));
			$json = json_decode(htmlspecialcharsbx(file_get_contents('https://lcab.smsint.ru/lcabApi/sendSms.php', false, $headers)), true);
			//echo "<pre>json: "; print_r($json); echo "</pre>";
			if($json[code] == 1) {
				echo 'OK';
			}
		}
	}
	
	if($provider == 'targetsmsru') {
		$login_targetsmsru = COption::GetOptionString("clementin.authsms", "login_targetsmsru");
		//echo "<pre>login_targetsmsru:"; print_r($login_targetsmsru); echo "</pre>";
		$password_targetsmsru = COption::GetOptionString("clementin.authsms", "password_targetsmsru");
		//echo "<pre>password_targetsmsru:"; print_r($password_targetsmsru); echo "</pre>";
		$name_otpr_targetsmsru = COption::GetOptionString("clementin.authsms", "name_otpr_targetsmsru");
		//echo "<pre>name_otpr_targetsmsru:"; print_r($name_otpr_targetsmsru); echo "</pre>";
		if(!empty($login_targetsmsru) && !empty($password_targetsmsru) && !empty($name_otpr_targetsmsru)) {
			$responce = file_get_contents('https://sms.targetsms.ru/sendsms.php?' . 'user=' .$login_targetsmsru. '&pwd=' .$password_targetsmsru. '&name_delivery=' .$name_otpr_targetsmsru. '&sadr=' .$name_otpr_targetsmsru. '&dadr=' .$nom_tel. '&text=' . $msg);
			if(is_numeric($responce)) {
				echo 'OK';
			} else {
				echo 'ERROR:' . $responce;
			}
		}
	}
	
	if($provider == 'smsprostoru') {
		$login_smsprostoru = COption::GetOptionString("clementin.authsms", "login_smsprostoru");
		//echo "<pre>login_smsprostoru:"; print_r($login_smsprostoru); echo "</pre>";
		$password_smsprostoru = COption::GetOptionString("clementin.authsms", "password_smsprostoru");
		//echo "<pre>password_smsprostoru:"; print_r($password_smsprostoru); echo "</pre>";
		$name_otpr_smsprostoru = COption::GetOptionString("clementin.authsms", "name_otpr_smsprostoru");
		//echo "<pre>name_otpr_smsprostoru:"; print_r($name_otpr_smsprostoru); echo "</pre>";
		
		if(!empty($login_smsprostoru) && !empty($password_smsprostoru) && !empty($name_otpr_smsprostoru)) {
			$responce_arr = json_decode(file_get_contents('https://ssl.bs00.ru/?' . 'method=push_msg&format=JSON&email=' .$login_smsprostoru. '&password=' .$password_smsprostoru. '&text=' .$msg. '&phone=' .$nom_tel. '&sender_name=' . $name_otpr_smsprostoru), true);
			//echo '<pre>: responce_arr'; print_r($responce_arr); echo '</pre>';
			if($responce_arr['response']['msg']['text'] == 'OK') {
				echo 'OK';
			} else {
				'ERROR: ' . $responce_arr['response']['msg']['text'];
			}
		}
	}
	
	

}

if($type_mess == 'type_mess_zvonok') {
	if($provider == 'smsru') {
		$api_key = COption::GetOptionString('clementin.authsms', 'api_key_smsru');
		if(empty($api_key)) {
			die('ERROR API-KEY');
		}
		if($_POST['zapros1'] == 'Y') {
			$arr = file_get_contents('https://' . 'sms.ru/callcheck/add?api_id=' . $api_key . '&phone=' . $nom_tel . '&json=1');
			echo $arr;
			
		}
		
		if($_POST['zapros2'] == 'Y') {
			$arr = file_get_contents('https://' . 'sms.ru/callcheck/status?api_id=' . $api_key . '&check_id=' . $_POST['check_id'] . '&json=1');
			echo $arr;
		}
	}
	
	if($provider == 'smscru') {
		$login_smscru = trim(COption::GetOptionString("clementin.authsms", "login_smscru"));
		if(empty($login_smscru)) {
			die('ERROR no login_smscru');
		}
		$password_smscru = trim(COption::GetOptionString("clementin.authsms", "password_smscru"));
		if(empty($password_smscru)) {
			die('ERROR no password_smscru');
		}

		$arr = json_decode(file_get_contents('https://smsc.ru/sys/send.php?login=' .$login_smscru. '&psw=' .$password_smscru. '&phones=' .$nom_tel. '&mes=code&call=1&fmt=3'), true);
		
		//echo "<pre>arr:"; print_r($arr); echo "</pre>";
		$arr[code] = substr($arr[code], strlen($arr[code]) - $strlen_generir_code);
		echo 'OK CODE:' . base64_encode($arr[code]);
	}
	
	if($provider == 'smsintru') {
		$token_smsintru = COption::GetOptionString('clementin.authsms', 'token_smsintru');
		//echo "<pre>token_smsintru:"; print_r($token_smsintru); echo "</pre>";
		if(!empty($token_smsintru)) {
			$opts = array('http' =>
				array(
					'method'  => 'POST',
					'header'  => 'X-Token: ' . $token_smsintru . PHP_EOL . 'Content-Type: application/json' . PHP_EOL,
					'content' => '{"recipient":"79823674905", "id":"myId123", "validate":true}'
				)
			);
			$context  = stream_context_create($opts);
			$json = json_decode(htmlspecialcharsbx(file_get_contents('https://lcab.smsint.ru/json/v1.0/callpassword/send', false, $context)), true);

			//echo "<pre>json: "; print_r($json); echo "</pre>";
			//mail("cron@clementin.ru", 'json', print_r($json, true));
			if(strlen($json[result][code]) == 4) {
				echo 'OK CODE:' . base64_encode($json[result][code]);
			}
		}
	}
	
}




 





