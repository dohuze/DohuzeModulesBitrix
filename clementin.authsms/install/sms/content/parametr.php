<?
// подключаем модуль
use Bitrix\Main\Loader;
Loader::includeModule('clementin.authsms');

$type_mess = COption::GetOptionString("clementin.authsms", "type_mess");
//echo "<pre>type_mess "; print_r($type_mess); echo "</pre>";
$provider = COption::GetOptionString("clementin.authsms", "provider");
//echo "<pre>provider "; print_r($provider); echo "</pre>";
$maska_tel = COption::GetOptionString("clementin.authsms", "maska_tel");
//echo "<pre>maska_tel "; print_r($maska_tel); echo "</pre>";
$time_sec = COption::GetOptionString("clementin.authsms", "time_sec");
//echo "<pre>time_sec "; print_r($time_sec); echo "</pre>";
$str_zagolovok = COption::GetOptionString("clementin.authsms", "str_zagolovok");
//echo "<pre>str_zagolovok "; print_r($str_zagolovok); echo "</pre>";
$bez_knopki_on = COption::GetOptionString("clementin.authsms", "bez_knopki_on");
//echo "<pre>bez_knopki_on "; print_r($bez_knopki_on); echo "</pre>";
$fon_on = COption::GetOptionString("clementin.authsms", "fon_on");
//echo "<pre>fon_on "; print_r($fon_on); echo "</pre>";
$std_reg_on = COption::GetOptionString("clementin.authsms", "std_reg_on");
//echo "<pre>std_reg_on "; print_r($std_reg_on); echo "</pre>";
$str_npcrbx = COption::GetOptionString("clementin.authsms", "str_npcrbx");
//echo "<pre>str_npcrbx "; print_r($str_npcrbx); echo "</pre>";


// $strlen_generir_code = COption::GetOptionString("clementin.authsms", "strlen_generir_code");
$strlen_generir_code = 4;
//echo "<pre>strlen_generir_code "; print_r($strlen_generir_code); echo "</pre>";

$URL = str_replace('index.php', '', $_SERVER['HTTP_REFERER']);
$url_ref_obj = new \Bitrix\Main\Web\Uri($URL);
parse_str($url_ref_obj->getQuery(), $getQueryList_arr);
//echo '<pre>getQueryList_arr: '; print_r($getQueryList_arr); echo '</pre>';
$url_ref_obj->addParams($getQueryList_arr);
$url_ref = $url_ref_obj->getUri();
if(empty($url_ref) || count(explode('/sms/', $url_ref)) > 1) $url_ref = '/';

$PATH_TO_REG = COption::GetOptionString("clementin.authsms", "PATH_TO_REG");
//echo "<pre>PATH_TO_REG "; print_r($PATH_TO_REG); echo "</pre>";
if(isset($PATH_TO_REG)) {
	$getQueryList_arr['clementin_authsms_type'] = 'N';
	$URL = str_replace('index.php', '', $PATH_TO_REG);
	$url_obj = new \Bitrix\Main\Web\Uri($URL);
	$url_obj->addParams($getQueryList_arr);
	$PATH_TO_REG_STD = $url_obj->getUri();
}
//echo "<pre>PATH_TO_REG_STD "; print_r($PATH_TO_REG_STD); echo "</pre>";