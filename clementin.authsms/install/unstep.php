<?php
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if(!check_bitrix_sessid()){
    return;
}

if($errorException = $APPLICATION->GetException()) {
    // ошибка при удалении модуля
    CAdminMessage::ShowMessage(
        Loc::getMessage('clementin.authsms_UNINSTALL_FAILED').': '.$errorException->GetString()
    );
} else {

	$path_to_order = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("clementin.authsms", "PATH_TO_ORDER");
	$PATH_TO_AUTOR = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("clementin.authsms", "PATH_TO_AUTOR");
	$PATH_TO_REG = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("clementin.authsms", "PATH_TO_REG");
	
	if(file_exists($PATH_TO_AUTOR)) {
		$file_phptxt = file_get_contents($PATH_TO_AUTOR);
		$razbien_array = explode('module:clementin.authsms', $file_phptxt);
		// собираем с удалением строк кода
		if(count($razbien_array) > 1) {
			file_put_contents($PATH_TO_AUTOR, $razbien_array[0] . $razbien_array[2]);
		}
	}
	
	if(file_exists($PATH_TO_REG)) {
		$file_phptxt = file_get_contents($PATH_TO_REG);
		$razbien_array = explode('module:clementin.authsms', $file_phptxt);
		// собираем с удалением строк кода
		if(count($razbien_array) > 1) {
			file_put_contents($PATH_TO_REG, $razbien_array[0] . $razbien_array[2]);
		}
	}
	
	if(file_exists($path_to_order)) {
		$file_phptxt = file_get_contents($path_to_order);
		$razbien_array = explode('module:clementin.authsms', $file_phptxt);
		// собираем с удалением строк кода
		if(count($razbien_array) > 1) {
			file_put_contents($path_to_order, $razbien_array[0] . $razbien_array[2]);
		}
	}
	
	
	\Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/sms');
	
    CAdminMessage::ShowNote(
		Loc::getMessage('clementin.authsms_UNINSTALL_SUCCESS')
    );
}
?>

<form action="<?= $APPLICATION->GetCurPage(); ?>"> <!-- Кнопка возврата к списку модулей -->
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>" />
    <input type="submit" value="<?= Loc::getMessage('clementin.authsms_RETURN_MODULES'); ?>">
</form>