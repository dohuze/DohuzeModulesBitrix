<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
use Bitrix\Main\Loader;
Loader::includeModule('clementin.authsms');

// для тестирования
/* if(trim($_POST['module_clementin_authsms_user_id']) == '5481067306') {
	echo 'Y';
	die();
} */

$module_clementin_authsms_user_id = COption::GetOptionString('clementin.authsms', 'module_clementin_authsms_user_id_' . trim($_POST['module_clementin_authsms_user_id']));



if(base64_decode($module_clementin_authsms_user_id) == trim($_POST['code'])) {
	echo 'Y';
} else {
	echo 'N';
}



