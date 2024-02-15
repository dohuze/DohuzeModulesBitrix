<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/content/parametr.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/clementin.authsms/lang/ru/install/sms/index.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/clementin.authsms/lang/ru/install/sms/content/content.php');
//echo '<pre>MESS: '; print_r($MESS); echo '</pre>';

if($fon_on == "Y") {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
	$APPLICATION->SetTitle($MESS['clementin.authsms_TITLE']);
	//Loc::loadMessages(__FILE__);
	require_once($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/content/src.php');
	require_once($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/content/content.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
}
?>

<?if($fon_on != "Y"):?>
	<!doctype html>
	<html lang="ru">
		<head>
			<meta charset="utf-8" />
			<title><?=$MESS['clementin.authsms_TITLE']?></title>
			<?require_once($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/content/src.php')?>
		</head>
			<body>
				<?require_once($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/content/content.php')?>
			</body>
	</html>
<?endif?>














