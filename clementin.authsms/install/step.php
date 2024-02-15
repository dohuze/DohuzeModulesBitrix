<?php
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!check_bitrix_sessid()) {
    return;
}

if ($errorException = $APPLICATION->GetException()) {
    // ошибка при установке модуля
    CAdminMessage::ShowMessage(
        Loc::getMessage('clementin.authsms_INSTALL_FAILED').': '.$errorException->GetString()
    );
} else {
    // модуль успешно установлен
    CAdminMessage::ShowNote(
        Loc::getMessage('clementin.authsms_INSTALL_SUCCESS')
    );
}
?>

<form action="<?= $APPLICATION->GetCurPage(); ?>"> <!-- Кнопка возврата к списку модулей -->
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>" />
    <input type="submit" value="<?= Loc::getMessage('clementin.authsms_RETURN_MODULES'); ?>">
</form>