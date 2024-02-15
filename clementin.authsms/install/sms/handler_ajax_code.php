<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
use Bitrix\Main\Loader;
Loader::includeModule('clementin.authsms');

for($i = 1; $i <= trim($_POST['strlen_generir_code']); $i++) {
	$mi .= '1';
	$ma .= '9';
	$code = mt_rand((int)$mi, (int)$ma);
}

if(COption::SetOptionString('clementin.authsms', 'module_clementin_authsms_user_id_' . trim($_POST['module_clementin_authsms_user_id']), base64_encode($code))) {
	echo trim($_POST['module_clementin_authsms_user_id']) . ' OK';
}