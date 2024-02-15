<?
$MESS['clementin.authsms_OPTIONS_telefon']   = 'Телефон';
$MESS['clementin.authsms_OPTIONS_cecund']   = ' секунд.';
$MESS['clementin.authsms_OPTIONS_sbrosit']   = 'Сбросить';
$MESS['clementin.authsms_OPTIONS_avtorizovat']   = 'Авторизоваться';

$MESS['clementin.authsms_OPTIONS_voiti']   = 'Войти';
$MESS['clementin.authsms_OPTIONS_vvkkbpnaadres']   = 'Введите код, который был выслан на адрес';

$MESS['clementin.authsms_OPTIONS_10']  = "Вам позвонит робот на номер";
$MESS['clementin.authsms_OPTIONS_20']  = "В независимости от того, примите ли Вы вызов или нет, звонок будет сброшен. Ввведите в форму четыре последних цифры номера звонящего";

if($nnnumber_tempplate = 1) {
	$MESS['clementin.authsms_OPTIONS_30']  = "Введите код, который был выслан посредством SMS на номер";
	$MESS['clementin.authsms_OPTIONS_vvedite_prav_email']   = 'Введите правильно E-mail';
	$MESS['clementin.authsms_OPTIONS_vvedite_prav_tel']   = 'Введите правильно телефон';
	$MESS['clementin.authsms_OPTIONS_otobrazit_kod_eche_raz']   = 'Отправить код еще раз через ';
}
if($nnnumber_tempplate = 2) {
	$MESS['clementin.authsms_OPTIONS_30']  = "Код из sms:";
	$MESS['clementin.authsms_OPTIONS_31']  = "Код из e-mail:";
	$MESS['clementin.authsms_OPTIONS_vvedite_prav_email']   = 'email введен неверно';
	$MESS['clementin.authsms_OPTIONS_vvedite_prav_tel']   = 'телефон введен неверно';
	$MESS['clementin.authsms_OPTIONS_otobrazit_kod_eche_raz']   = 'Получить новый код можно через ';
}

$MESS['clementin.authsms_OPTIONS_40']  = "Длина кода меньше необходимой или код пустой";
$MESS['clementin.authsms_OPTIONS_50']  = "Код не верный";

$MESS['clementin.authsms_OPTIONS_60']  = "С Вашего номера";
$MESS['clementin.authsms_OPTIONS_70']  = "позвоните на номер";
$MESS['clementin.authsms_OPTIONS_80']  = "звонок будет сброшен, деньги с телефона не спишутся, после звонка нажмите на кнопку 'Авторизоваться'";
$MESS['clementin.authsms_OPTIONS_90']  = "Ввести другой";
$MESS['clementin.authsms_OPTIONS_100']  = "Выбрать другой способ";