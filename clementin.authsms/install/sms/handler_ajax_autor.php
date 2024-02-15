<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global $USER;
//echo "<pre>_GET: "; print_r($_GET); echo "</pre>";

/* $key = "ibkelOqSguL5R2o4a2tUcWRsF00YoyAOCmuM0lm9";
function decrypt($string, $key) {
	$result = '';
	$string = base64url_decode($string);
	for ($i = 0; $i < strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key)) - 1, 1);
		$char = chr(ord($char) - ord($keychar));
		$result .= $char;
	}
	return $result;
}

function base64url_decode($data) { 
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
} 

$id = decrypt(trim($_GET['id']), $key);
echo "<pre>id: "; print_r($id); echo "</pre>"; */
$id = ($id - 6475893654575)/12578935;
if(!is_int($id)) {
	echo __FILE__ . ' (' . __LINE__ . ') ' . ' ERROR<br>';
	die();
}
//echo "<pre>id: "; print_r($id); echo "</pre>";
$id = pow($id, 1/5);
//echo "<pre>id: "; print_r($id); echo "</pre>";
//die();

// ID группы пользователей, зарегистрировавшихся по телефону
$dbGroup = \Bitrix\Main\GroupTable::getList(array(
	'filter' => array("STRING_ID" => 'authsms_code_group')
));
if($arGroup = $dbGroup->Fetch()) {
	$group_id = $arGroup['ID'];
}

if(in_array($group_id, CUser::GetUserGroup($id))) {
	if($USER->IsAuthorized()) {
		$USER->Logout();
	}
	$USER ->Authorize($id);
} else {
	echo __FILE__ . ' (' . __LINE__ . ') ' . ' ERROR<br>';
	echo '<pre>$id: '; print_r($id); echo '</pre>';
	echo '<pre>$group_id: '; print_r($group_id); echo '</pre>';
	echo '<pre>$GetUserGroup: '; print_r(CUser::GetUserGroup($id)); echo '</pre>';
	die();
}

if($_GET['old_new'] == 'new' && !empty(COption::GetOptionString("clementin.authsms", "PATH_TO_PERSONAL"))) {
	if(!empty($_GET["url_ref"])) {
		$URL = str_replace('index.php', '', COption::GetOptionString("clementin.authsms", "PATH_TO_PERSONAL"));
		$url_obj = new \Bitrix\Main\Web\Uri($URL);
		$path = $url_obj->getPath();
		
		$URL = str_replace('index.php', '', $_GET["url_ref"]);
		$url_obj = new \Bitrix\Main\Web\Uri($URL);
		$url_obj->setPath($path);
		$url_obj->deleteParams(\Bitrix\Main\HttpRequest::getSystemParameters());
		$_GET["url_ref"] = $url_obj->getUri();
	}
}
//echo "<pre>_GET: "; print_r($_GET); echo "</pre>";
//die();

// переадресация куда нужно
if($USER->IsAuthorized()) {
	if(!empty($_GET["url_ref"])) {
		header('Location: ' . urldecode($_GET["url_ref"]));
	} else {
		header('Location: /');
	}
}

// http://www.test.demo-clementin.ru/include/clementin.authsms/handler_ajax_autor.php?url_ref=http%3A%2F%2Fwww.test.demo-clementin.ru%2Fshipping%2F%3Fget1%3Dget1%26get2%3Dget2%26get3%3Dget3&id=647592635980902&old_new=new

