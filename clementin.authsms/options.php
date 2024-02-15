<?
//error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
Loc::loadMessages(__FILE__);
CJSCore::Init(array("jquery"));
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
<link rel="stylesheet" href="/bitrix/modules/clementin.authsms/install/assets/styles/custom_spectrum.css">

<?
// получаем идентификатор модуля
$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialchars($request['mid'] != '' ? $request['mid'] : $request['id']);

$pathToFile = \Bitrix\Main\Loader::getLocal('modules/' .$module_id. '/functions.php');
require_once  $pathToFile;

// подключаем наш модуль
Loader::includeModule($module_id);

// обработка удаления помеченных галочкой
$black_ip_json = (empty(COption::GetOptionString("clementin.authsms", "black_ip_json"))) ? '' : COption::GetOptionString("clementin.authsms", "black_ip_json");
if(strlen($black_ip_json) > 0) {
	$black_ip_arr = unserialize($black_ip_json);
}
if(!empty($black_ip_arr)) {
	foreach($black_ip_arr as $key => $value) {
		if(COption::GetOptionString("clementin.authsms", 'black_list_arr_del_' . $key) == 'Y') {
			COption::RemoveOption('clementin.authsms', 'black_list_arr_del_' . $key);
			unset($black_ip_arr[$key]);
		}
	}
}
if(!empty($black_ip_arr)) {
	COption::SetOptionString("clementin.authsms", "black_ip_json", serialize($black_ip_arr));
}

// обработка добавления нового IP в список запрещённых
$black_ip_new = COption::GetOptionString("clementin.authsms", "black_ip_new");
if(!empty($black_ip_new)) {
	if(!is_valid_ip($black_ip_new)) {
		ShowError(Loc::getMessage('clementin.authsms_IP_NO_VALID'));
	} else {
		$black_ip_json = (empty(COption::GetOptionString("clementin.authsms", "black_ip_json"))) ? array() : COption::GetOptionString("clementin.authsms", "black_ip_json");
		$black_ip_arr = unserialize($black_ip_json);
		$black_ip_arr[] = $black_ip_new;
		COption::SetOptionString("clementin.authsms", "black_ip_json", serialize($black_ip_arr));
		COption::SetOptionString("clementin.authsms", "black_ip_new", "");
	}
}
$black_ip_arr = unserialize(COption::GetOptionString("clementin.authsms", "black_ip_json"));
//echo '<pre>black_ip_arr: '; print_r($black_ip_arr); echo '</pre>';

// подготовка ветки для вкладки "Чёрный список IP-адресов"
if(!empty($black_ip_arr)) {
	foreach($black_ip_arr as $key => $value) {
		$optyions_edit2[] = array(
						'black_list_arr_del_' . $key,
						Loc::getMessage('clementin.authsms_OPTIONS_AUTOR_DELL') . '<b>' . $value . '</b>:',
						'N',
						array('checkbox')
		);
	}
}

$optyions_edit2[] = array(
				'black_ip_new',
				Loc::getMessage('clementin.authsms_IP_ADRESS_NEW'),
				'',
				array('text', 15)
);
//echo '<pre>optyions_edit2: '; print_r($optyions_edit2); echo '</pre>';

/*
 * Параметры модуля со значениями по умолчанию
 */
$aTabs = array(
    array(
        /*
         * Первая вкладка «Основные настройки»
         */
        'DIV'     => 'edit0',
        'TAB'     => Loc::getMessage('clementin.authsms_OPTIONS_TAB_GENERAL'),
        'TITLE'   => Loc::getMessage('clementin.authsms_OPTIONS_TAB_GENERAL'),
		'ONSELECT'=>'',
    ),
    array(
        /*
         * Вторая вкладка «Дополнительные настройки»
         */
        'DIV'     => 'edit1',
        'TAB'     => Loc::getMessage('clementin.authsms_OPTIONS_TAB_ADDITIONAL'),
        'TITLE'   => Loc::getMessage('clementin.authsms_OPTIONS_TAB_ADDITIONAL'),
        'OPTIONS' => array(
			
/*             array(
                'autor_on',                                   												// имя элемента формы
                Loc::getMessage('clementin.authsms_OPTIONS_AUTOR_ON'),								// поясняющий текст — «Включить прокрутку»
                'N',                                           												// значение по умолчанию «да»
                array('checkbox')                             												// тип элемента формы — checkbox
            ), */
            array(
                'PATH_TO_AUTOR',                                   												// имя элемента формы
                Loc::getMessage('clementin.authsms_OPTIONS_PATH_TO_AUTOR'),								// поясняющий текст — «Включить прокрутку»
                '',                                           												// значение по умолчанию «да»
                array('text', 65)
            ),
			
/*             array(
                'reg_on',                                   												// имя элемента формы
                Loc::getMessage('clementin.authsms_OPTIONS_REG_ON'),								// поясняющий текст — «Включить прокрутку»
                'N',                                           												// значение по умолчанию «да»
                array('checkbox')                             												// тип элемента формы — checkbox
            ), */
            array(
                'PATH_TO_REG',                                   												// имя элемента формы
                Loc::getMessage('clementin.authsms_OPTIONS_PATH_TO_REG'),								// поясняющий текст — «Включить прокрутку»
                '',                                           												// значение по умолчанию «да»
                array('text', 65)
            ),
			
/*             array(
                'zakaz_on',                                   												// имя элемента формы
                Loc::getMessage('clementin.authsms_OPTIONS_ZAKAZ_ON'),								// поясняющий текст — «Включить прокрутку»
                'N',                                           												// значение по умолчанию «да»
                array('checkbox')                              												// тип элемента формы — checkbox
            ), */
            array(
                'PATH_TO_ORDER',                                   												// имя элемента формы
                Loc::getMessage('clementin.authsms_OPTIONS_PATH_TO_ORDER'),	
							// поясняющий текст — «Включить прокрутку»
				'',  																					// значение по умолчанию «да»
                array('text', 65)
            ),
            array(
                'PATH_TO_PERSONAL',                                   												// имя элемента формы
                Loc::getMessage('clementin.authsms_OPTIONS_PATH_TO_PERSONAL'),	
							// поясняющий текст — «Включить прокрутку»
				'',  																					// значение по умолчанию «да»
                array('text', 65)
            ),
			
        ),
    ),
    array(
        /*
         * Третья вкладка «Чёрный список IP-адресов»
         */
        'DIV'     => 'edit2',
        'TAB'     => Loc::getMessage('clementin.authsms_OPTIONS_TAB_BLOCK_IP'),
        'TITLE'   => Loc::getMessage('clementin.authsms_OPTIONS_TAB_BLOCK_IP'),
        'OPTIONS' => $optyions_edit2,
    ),
	
);

/* 
// переопределяем значения
if(!empty($path_file_auth)) {
	$aTabs[1]['OPTIONS'][1][2] = $path_file_auth; 
}
if(!empty($path_file_personal)) {
	$aTabs[1]['OPTIONS'][3][2] = $path_file_personal; 
}
if(!empty($path_file_basket)) {
	$aTabs[1]['OPTIONS'][3][2] = $path_file_basket; 
}
//echo '<pre>'; print_r($aTabs[1]['OPTIONS']); echo '</pre>';
//echo '<pre>'; print_r(getIp()); echo '</pre>';
 */

$aTabs[0]['OPTIONS'][-11] = Loc::getMessage('clementin.authsms_OPTIONS_SECTION_SELECTION_TYPE_0'); // Первоначальные настройки
$aTabs[0]['OPTIONS'][-10] = array(
								'autor_on',                                   												// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_AUTOR_ON'),								// поясняющий текст — «Включить прокрутку»
								'N',                                           												// значение по умолчанию «да»
								array('checkbox')                             												// тип элемента формы — checkbox
							);
							
							
$aTabs[0]['OPTIONS'][-9] = array(
								'zakaz_on',                                   												// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_ZAKAZ_ON'),								// поясняющий текст — «Включить прокрутку»
								'N',                                           												// значение по умолчанию «да»
								array('checkbox')                              												// тип элемента формы — checkbox
							);
$aTabs[0]['OPTIONS'][-8] = array(
								'e_mail_on',                                   												// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_EMAIL_ON'),								// поясняющий текст — «Включить прокрутку»
								'',                                           												// значение по умолчанию «да»
								array('checkbox')                              												// тип элемента формы — checkbox
							);
$aTabs[0]['OPTIONS'][-7] = array(
								'fon_on',                                   												// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_FON_ON'),								// поясняющий текст — «Включить прокрутку»
								'',                                           												// значение по умолчанию «да»
								array('checkbox')                              												// тип элемента формы — checkbox
							);
							
$aTabs[0]['OPTIONS'][-6] = array(
								'std_reg_on',                                   												// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_STD_REG_BX_ON'),								// поясняющий текст — «Включить прокрутку»
								'',                                           												// значение по умолчанию «да»
								array('checkbox')                              												// тип элемента формы — checkbox
							);
							
if(COption::GetOptionString("clementin.authsms", "e_mail_on") == 'Y') {
	$aTabs[0]['OPTIONS'][-5] = Loc::getMessage('clementin.authsms_OPTIONS_SECTION_SETTINGS_EMAIL'); // Настройки электронного письма
	$aTabs[0]['OPTIONS'][-4] = array(
								'str_email_from',                             										// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_STR_EMAIL_FROM'), 				// поясняющий текст — «Длина генерируемого кода»
								'',                                              								// значение по умолчанию 10px
								array('text', 55)                                   										// тип элемента формы — input type="text"
							);
	$aTabs[0]['OPTIONS'][-3] = array(
								'str_email_subject',                             										// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_STR_EMAIL_SUBJECT'), 				// поясняющий текст — «Длина генерируемого кода»
								'',                                              								// значение по умолчанию 10px
								array('text', 55)                                   										// тип элемента формы — input type="text"
							);
	$aTabs[0]['OPTIONS'][-2] = array(
								'str_email_body',                             										// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_STR_EMAIL_BODY'), 				// поясняющий текст — «Длина генерируемого кода»
								'',                                              								// значение по умолчанию 10px
								array('text', 55)                                   										// тип элемента формы — input type="text"
							);
}

$aTabs[0]['OPTIONS'][0] = Loc::getMessage('clementin.authsms_OPTIONS_SECTION_SELECTION_TYPE'); // Выберите способ получения кода по телефону
$aTabs[0]['OPTIONS'][1] = array(
							'type_mess',                                   									// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_SPEED'), 
							'type_mess_sms',                                  								// значение по умолчанию «smsru»
							array(
								'selectbox',                           										// тип элемента формы — <select>
								array(
									'type_mess_sms'   => Loc::getMessage('clementin.authsms_OPTIONS_SECTION_SELECTION_TYPE_1'),
									'type_mess_zvonok'   => Loc::getMessage('clementin.authsms_OPTIONS_SECTION_SELECTION_TYPE_2'),
								)
							)
						);
$aTabs[0]['OPTIONS'][2] = Loc::getMessage('clementin.authsms_OPTIONS_SECTION_SELECTION_PROVIDER');
$aTabs[0]['OPTIONS'][3] = array(
							'provider',                                   									// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_SPEED'), 
							'smsru',                                  										// значение по умолчанию «smsru»
							array(
								'selectbox',                           										// тип элемента формы — <select>
								array()
							)
						);

//echo '<pre>aTabs: '; print_r($aTabs); echo '</pre>';
//echo "<pre>"; print_r($aTabs[0]['OPTIONS']); echo "</pre>";

if($type_mess == 'type_mess_zvonok') {
	$aTabs[0]['OPTIONS'][3][3][1] = array(
									'smsru'   => 'sms.ru',
									'smscru'   => 'smsc.ru',
									'smsintru'   => 'smsint.ru'
								);
}
if($type_mess == 'type_mess_sms') {
	$aTabs[0]['OPTIONS'][3][3][1] = array(
									'smsru'   => 'sms.ru',
									'smscru'   => 'smsc.ru',
									'smsintru'   => 'smsint.ru', 
									'targetsmsru'   => 'targetsms.ru', 
									'smsprostoru'   => 'sms-prosto.ru', 
								);
}

if($provider == 'smsru' || empty($provider)) {
	$aTabs[0]['OPTIONS'][4] = '<a href="https://sms.ru/price" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_TARIF_1'). '</a>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="https://sms.ru/?panel=register" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_REG'). '</a>';
}
if($provider == 'smscru') {
	$aTabs[0]['OPTIONS'][4] = '<a href="https://smsc.ru/tariffs/" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_TARIF_2'). '</a>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="https://smsc.ru/" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_REG'). '</a>';
}
if($provider == 'smsintru') {
	$aTabs[0]['OPTIONS'][4] = '<a href="http://smsint.ru/prices/" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_TARIF_3'). '</a> ' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="http://smsint.ru/" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_REG'). '</a>';
}
if($provider == 'targetsmsru') {
	$aTabs[0]['OPTIONS'][4] = '<a href="https://targetsms.ru/tarify" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_TARIF_4'). '</a> ' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="https://sms.targetsms.ru/ru/reg.html?ref=registrationbutton" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_REG'). '</a>';
}
if($provider == 'smsprostoru') {
	$aTabs[0]['OPTIONS'][4] = '<a href="https://sms-prosto.ru/tseny/" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_TARIF_4'). '</a> ' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="https://lk.sms-prosto.ru/reg.php" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_REG'). '</a>';
}

if($provider == 'smsru') {
	$aTabs[0]['OPTIONS'][5][0] = 'api_key_smsru';
	$aTabs[0]['OPTIONS'][5][1] = Loc::getMessage('clementin.authsms_OPTIONS_API_KEY');
	$aTabs[0]['OPTIONS'][5][2] = '';
	$aTabs[0]['OPTIONS'][5][3][0] = 'text';
	$aTabs[0]['OPTIONS'][5][3][1] = 40;

}

if($provider == 'smscru') {
	$aTabs[0]['OPTIONS'][5][0] = 'login_smscru';
	$aTabs[0]['OPTIONS'][5][1] = Loc::getMessage('clementin.authsms_OPTIONS_LOGIN');
	$aTabs[0]['OPTIONS'][5][2] = '';
	$aTabs[0]['OPTIONS'][5][3][0] = 'text';
	$aTabs[0]['OPTIONS'][5][3][1] = 40;
	
	$aTabs[0]['OPTIONS'][6][0] = 'password_smscru';
	$aTabs[0]['OPTIONS'][6][1] = Loc::getMessage('clementin.authsms_OPTIONS_PASSWORD');
	$aTabs[0]['OPTIONS'][6][2] = '';
	$aTabs[0]['OPTIONS'][6][3][0] = 'password';
	$aTabs[0]['OPTIONS'][6][3][1] = 40;
}
if($provider == 'smsintru') {
	$aTabs[0]['OPTIONS'][5][0] = 'login_smsintru';
	$aTabs[0]['OPTIONS'][5][1] = Loc::getMessage('clementin.authsms_OPTIONS_LOGIN');
	$aTabs[0]['OPTIONS'][5][2] = '';
	$aTabs[0]['OPTIONS'][5][3][0] = 'text';
	$aTabs[0]['OPTIONS'][5][3][1] = 40;
	
	$aTabs[0]['OPTIONS'][6][0] = 'password_smsintru';
	$aTabs[0]['OPTIONS'][6][1] = Loc::getMessage('clementin.authsms_OPTIONS_PASSWORD');
	$aTabs[0]['OPTIONS'][6][2] = '';
	$aTabs[0]['OPTIONS'][6][3][0] = 'password';
	$aTabs[0]['OPTIONS'][6][3][1] = 40;
	
	$aTabs[0]['OPTIONS'][7][0] = 'token_smsintru';
	$aTabs[0]['OPTIONS'][7][1] = Loc::getMessage('clementin.authsms_OPTIONS_API_TOKEN');
	$aTabs[0]['OPTIONS'][7][2] = '';
	$aTabs[0]['OPTIONS'][7][3][0] = 'text';
	$aTabs[0]['OPTIONS'][7][3][1] = 40;
	
	//if(empty(COption::GetOptionString("clementin.authsms", "token_smsintru"))) {
	//	$aTabs[0]['OPTIONS'][7] = '<a href="https://lcab.smsint.ru/cabinet/callPassword/api" target="_blank">' .Loc::getMessage('clementin.authsms_OPTIONS_API_TOKEN_GET'). '</a>';
	//}
	
	COption::SetOptionString("clementin.authsms", "strlen_generir_code", "4");
}
if($provider == 'targetsmsru') {
	$aTabs[0]['OPTIONS'][5][0] = 'login_targetsmsru';
	$aTabs[0]['OPTIONS'][5][1] = Loc::getMessage('clementin.authsms_OPTIONS_LOGIN');
	$aTabs[0]['OPTIONS'][5][2] = '';
	$aTabs[0]['OPTIONS'][5][3][0] = 'text';
	$aTabs[0]['OPTIONS'][5][3][1] = 40;
	
	$aTabs[0]['OPTIONS'][6][0] = 'password_targetsmsru';
	$aTabs[0]['OPTIONS'][6][1] = Loc::getMessage('clementin.authsms_OPTIONS_PASSWORD');
	$aTabs[0]['OPTIONS'][6][2] = '';
	$aTabs[0]['OPTIONS'][6][3][0] = 'password';
	$aTabs[0]['OPTIONS'][6][3][1] = 40;
	
	$aTabs[0]['OPTIONS'][7][0] = 'name_otpr_targetsmsru';
	$aTabs[0]['OPTIONS'][7][1] = Loc::getMessage('clementin.authsms_OPTIONS_NAME');
	$aTabs[0]['OPTIONS'][7][2] = '';
	$aTabs[0]['OPTIONS'][7][3][0] = 'text';
	$aTabs[0]['OPTIONS'][7][3][1] = 40;
	
	COption::SetOptionString("clementin.authsms", "strlen_generir_code", "4");
}
if($provider == 'smsprostoru') {
	$aTabs[0]['OPTIONS'][5][0] = 'login_smsprostoru';
	$aTabs[0]['OPTIONS'][5][1] = Loc::getMessage('clementin.authsms_OPTIONS_LOGIN');
	$aTabs[0]['OPTIONS'][5][2] = '';
	$aTabs[0]['OPTIONS'][5][3][0] = 'text';
	$aTabs[0]['OPTIONS'][5][3][1] = 40;
	
	$aTabs[0]['OPTIONS'][6][0] = 'password_smsprostoru';
	$aTabs[0]['OPTIONS'][6][1] = Loc::getMessage('clementin.authsms_OPTIONS_PASSWORD');
	$aTabs[0]['OPTIONS'][6][2] = '';
	$aTabs[0]['OPTIONS'][6][3][0] = 'password';
	$aTabs[0]['OPTIONS'][6][3][1] = 40;
	
	$aTabs[0]['OPTIONS'][7][0] = 'name_otpr_smsprostoru';
	$aTabs[0]['OPTIONS'][7][1] = Loc::getMessage('clementin.authsms_OPTIONS_NAME');
	$aTabs[0]['OPTIONS'][7][2] = '';
	$aTabs[0]['OPTIONS'][7][3][0] = 'text';
	$aTabs[0]['OPTIONS'][7][3][1] = 40;
}




/*
 * секция «Баланс на аккаунте»
 */

if($provider == 'targetsmsru') {
	$str_balance = $balance;
} else {
	$str_balance = $balance . ' RUB';
}
$aTabs[0]['OPTIONS'][] = Loc::getMessage('clementin.authsms_OPTIONS_BALANCE') . $str_balance;
$aTabs[0]['OPTIONS'][] = array(
						'qwee',                                	  													// имя элемента формы
						'',                                       													// значение по умолчанию 50px
						array('text', 40)                         													// тип элемента формы — input type="text", ширина 30 симв.
					);
					
/*
 * секция «Выбор шаблона формы ввода телефона»
 */
$aTabs[0]['OPTIONS'][] = Loc::getMessage('clementin.authsms_OPTIONS_SECTION_TPL');
$aTabs[0]['OPTIONS'][] = array(
							'nnnumber_tempplate',                                   									// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_SPEED'), 
							'1',                                  								// значение по умолчанию «smsru»
							array(
								'selectbox',                           										// тип элемента формы — <select>
								array(
									'1'   => Loc::getMessage('clementin.authsms_OPTIONS_SECTION_TPL_1'),
									'2'   => Loc::getMessage('clementin.authsms_OPTIONS_SECTION_TPL_2'),
								)
							)
						);


/*
 * секция «Настройки окна ввода телефона и получения кода»
 */
$aTabs[0]['OPTIONS'][] = Loc::getMessage('clementin.authsms_OPTIONS_SECTION_LAYOUT');
$aTabs[0]['OPTIONS'][] = array(
							'str_zagolovok',                             										// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_STR_ZAGOLOVOK'), 				// поясняющий текст — «Длина генерируемого кода»
							Loc::getMessage('clementin.authsms_OPTIONS_STR_ZAGOLOVOK_PRED'),                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0]['OPTIONS'][] = array(
							'str_npcrbx',                             										// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_STR_NPCRBX'), 				// поясняющий текст — «Длина генерируемого кода»
							Loc::getMessage('clementin.authsms_OPTIONS_STR_NPCRBX_PRED'),                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0]['OPTIONS'][] = array(
							'input_tel_placeholder',                             										// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_placeholder_tel'), 				// поясняющий текст — «Длина генерируемого кода»
							'Телефон',                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0]['OPTIONS'][] = array(
							'input_email_placeholder',                             										// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_placeholder_email'), 				// поясняющий текст — «Длина генерируемого кода»
							'E-MAIL',                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0]['OPTIONS'][] = array(
								'bez_knopki_on',                                   												// имя элемента формы
								Loc::getMessage('clementin.authsms_OPTIONS_placeholder_bez_knopki'),								// поясняющий текст — «Включить прокрутку»
								'N',                                           												// значение по умолчанию «да»
								array('checkbox')                              												// тип элемента формы — checkbox
							);
$aTabs[0]['OPTIONS'][] = array(
							'color_buttom',                      		       										// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_COLOR_BUTTOM'), 						// поясняющий текст — «Длина генерируемого кода»
							'#0000ff',                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0]['OPTIONS'][] = array(
							'color_str_zagolovok',                             										// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_COLOR_STR_ZAGOLOVOK'), 				// поясняющий текст — «Длина генерируемого кода»
							'0000a4',                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0]['OPTIONS'][] = array(
							'color_str_knopki',                             										// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_COLOR_STR_KNOPKI'), 					// поясняющий текст — «Длина генерируемого кода»
							'0000a4',                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0]['OPTIONS'][] = array(
							'radius_zakrugl',                             											// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_RADIUS_ZAKRUGL'), 					// поясняющий текст — «Длина генерируемого кода»
							'20',                                              										// значение по умолчанию 10px
							array('text', 3)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0]['OPTIONS'][] = array(
 							'maska_tel',                             										// имя элемента формы
 							Loc::getMessage('clementin.authsms_OPTIONS_MASK'), 						// поясняющий текст — «Длина генерируемого кода»
 							'+{7}(000)000-00-00',                                              										// значение по умолчанию 10px
 							array('text', 20)                                   										// тип элемента формы — input type="text"
 						);
$aTabs[0]['OPTIONS'][] = array(
 							'time_sec',                             										// имя элемента формы
 							Loc::getMessage('clementin.authsms_OPTIONS_TTIME'), 						// поясняющий текст — «Длина генерируемого кода»
 							'90',                                              										// значение по умолчанию 10px
 							array('text', 3)                                   										// тип элемента формы — input type="text"
 						);
						
$aTabs[0]['OPTIONS'][] = array(
 							'tttime_sec',                             										// имя элемента формы
 							Loc::getMessage('clementin.authsms_OPTIONS_TTTIME'), 						// поясняющий текст — «Длина генерируемого кода»
 							'60',                                              										// значение по умолчанию 10px
 							array('text', 3)                                   										// тип элемента формы — input type="text"
 						);
$aTabs[0]['OPTIONS'][] = array(
 							'tttime_count',                             										// имя элемента формы
 							Loc::getMessage('clementin.authsms_OPTIONS_TTTIME_COUNT'), 						// поясняющий текст — «Длина генерируемого кода»
 							'3',                                              										// значение по умолчанию 10px
 							array('text', 3)                                   										// тип элемента формы — input type="text"
 						);
						
						

/* $aTabs[0]['OPTIONS'][] = array(
							'fone_personal_pole',                               									// имя элемента формы
							Loc::getMessage('clementin.authsms_OPTIONS_POLE_TEL'), 							// поясняющий текст — «Выбор поля для проверки телефона»
							'PERSONAL_PHONE',                                  										// значение по умолчанию «PERSONAL_PHONE»
							array(
								'selectbox',                           												// тип элемента формы — <select>
								array(
									'PERSONAL_PHONE'   => 'PERSONAL_PHONE',
									'PERSONAL_MOBILE'   => 'PERSONAL_MOBILE',
								)
							)
						); */
/*
 * секция «Сссылка на страницу авторизации»
 */
/* $aTabs[0]['OPTIONS'][] = Loc::getMessage('clementin.authsms_OPTIONS_URL') . '<script>function myFunction() {var copyText = document.getElementById("myInput"); copyText.select(); document.execCommand("copy"); return false;}</script> <input size="40" type="text" value="http://www.' .$_SERVER['SERVER_NAME']. '/sms/" id="myInput"> <button onclick="myFunction()">' .Loc::getMessage('clementin.authsms_OPTIONS_COPY_URL'). '</button>';
$aTabs[0]['OPTIONS'][] = array(
							'qweee',                                	  											// имя элемента формы
							'',                                       												// значение по умолчанию 50px
							array('text', 40)                         												// тип элемента формы — input type="text", ширина 30 симв.
						); */

// проверка подключаемости модуля "Интернет-магазина"
if(!CModule::IncludeModule('sale')) {
	unset($aTabs[1]['OPTIONS'][2]);
	unset($aTabs[1]['OPTIONS'][3]);
} else {
/* 	// сообщение красвным если нет файла страницы оформления заказа
	if(!file_exists($path_to_order)) {
		ShowError(Loc::getMessage('clementin.authsms_OPTIONS_PATH_TO_ORDER_ERROR'));
	}
	if(!file_exists($PATH_TO_AUTOR)) {
		ShowError(Loc::getMessage('clementin.authsms_OPTIONS_PATH_TO_AUTOR_ERROR'));
	}
	if(!file_exists($PATH_TO_REG)) {
		ShowError(Loc::getMessage('clementin.authsms_OPTIONS_PATH_TO_REG_ERROR'));
	} */
}





//unset($aTabs);
//echo '<pre>aTabs: '; print_r($aTabs); echo '</pre>';

/*
 * Создаем форму для редактирвания параметров модуля
 */
$tabControl = new CAdminTabControl('tabControl', $aTabs, false);
$tabControl->Begin();
?>

<form action="<?= $APPLICATION->GetCurPage(); ?>?mid=<?=$module_id; ?>&lang=<?= LANGUAGE_ID; ?>" method="post">
    <?= bitrix_sessid_post(); ?>
    <?
    foreach ($aTabs as $aTab) { // цикл по вкладкам
        if ($aTab['OPTIONS']) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
        }
    }
    $tabControl->Buttons();
    ?>
    <input id="apply" type="submit" name="apply" value="<?= Loc::GetMessage('clementin.authsms_OPTIONS_INPUT_APPLY'); ?>" class="adm-btn-save" />
</form>

<script>
	let select_provider = document.getElementsByName('provider');
	select_provider[0].addEventListener('change', () => {
		document.querySelector('#apply').click();
	})
	let select_type_mess = document.getElementsByName('type_mess');
	select_type_mess[0].addEventListener('change', () => {
		document.querySelector('#apply').click();
	})
	let checkbox_type_mess_email = document.getElementsByName('e_mail_on');
	checkbox_type_mess_email[0].addEventListener('change', () => {
		document.querySelector('#apply').click();
	})
</script>

<script>
	window.onload = function() {
		document.getElementsByName('color_buttom')[0].id = 'color_buttom_id';
		document.getElementsByName('color_str_zagolovok')[0].id = 'color_str_zagolovok_id';
		document.getElementsByName('color_str_knopki')[0].id = 'color_str_knopki_id';
	};
	
	setTimeout(() => {
		$(document).ready(function(){
			$("#color_buttom_id").spectrum({
				color: "<?=COption::GetOptionString('clementin.authsms', 'color_buttom')?>",
				showInput: true,
				className: "full-spectrum",
				showInitial: true,
				showPalette: false,
				showSelectionPalette: true,
				maxSelectionSize: 10,
				preferredFormat: "hex",
				localStorageKey: "spectrum.demo",
				move: function(color) {
					document.getElementById('color_buttom_id').setAttribute("value", color);
				},
				show: function(color) {
					//alert("show " + color);
				},
				beforeShow: function(color) {
					//alert("beforeShow " + color);
				},
				hide: function(color) {
					//alert("hide " + color);
				},
				change: function(color) {
					//alert("change " + color);
				},
				palette: [
					["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
					"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
					["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
					"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
					["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
					"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
					"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
					"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
					"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
					"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
					"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
					"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
					"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
					"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
				]
			});
			
			$("#color_str_zagolovok_id").spectrum({
				color: "<?=COption::GetOptionString('clementin.authsms', 'color_str_zagolovok')?>",
				showInput: true,
				className: "full-spectrum",
				showInitial: true,
				showPalette: false,
				showSelectionPalette: true,
				maxSelectionSize: 10,
				preferredFormat: "hex",
				localStorageKey: "spectrum.demo",
				move: function (color) {
					document.getElementById('color_str_zagolovok').setAttribute("value", color);
				},
				show: function () {

				},
				beforeShow: function () {

				},
				hide: function () {

				},
				change: function() {

				},
				palette: [
					["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
					"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
					["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
					"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
					["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
					"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
					"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
					"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
					"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
					"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
					"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
					"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
					"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
					"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
				]
			});
			
			$("#color_str_knopki_id").spectrum({
				color: "<?=COption::GetOptionString('clementin.authsms', 'color_str_knopki')?>",
				showInput: true,
				className: "full-spectrum",
				showInitial: true,
				showPalette: false,
				showSelectionPalette: true,
				maxSelectionSize: 10,
				preferredFormat: "hex",
				localStorageKey: "spectrum.demo",
				move: function (color) {
					document.getElementById('color_str_knopki').setAttribute("value", color);
				},
				show: function () {

				},
				beforeShow: function () {

				},
				hide: function () {

				},
				change: function() {

				},
				palette: [
					["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
					"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
					["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
					"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
					["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
					"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
					"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
					"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
					"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
					"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
					"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
					"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
					"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
					"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
				]
			});

		});

		document.getElementsByName('color_buttom')[0].style.display = 'inline-block';
		document.getElementsByName('color_str_zagolovok')[0].style.display = 'inline-block';
		document.getElementsByName('color_str_knopki')[0].style.display = 'inline-block';

		let cansel = document.querySelectorAll(".sp-cancel");
		let choose = document.querySelectorAll(".sp-choose");

		for (let i = 0; i < cansel.length; i++) {
			cansel[i].textContent = "Закрыть";
		}

		for (let i = 0; i < choose.length; i++) {
			choose[i].textContent = "Выбрать";
		}

		let table =  document.querySelectorAll(".adm-detail-content-cell-r");

		for (let i = 0; i < table.length; i++) {
			for (let j = 0; j < table[i].childNodes.length; j++) {
				if (table[i].childNodes[j].id == "color_buttom_id" || table[i].childNodes[j].id == "color_str_zagolovok_id" || table[i].childNodes[j].id == "color_str_knopki_id") {
					let div = document.createElement("div");
					div.id = "parent_div";
					table[i].append(div);
					let table2 = table[i].querySelector(`#${table[i].childNodes[3].id}`);
					console.log(table[i].childNodes[3]);
					table2.append(table[i].childNodes[0]);
					table2.append(table[i].childNodes[0]);
				}
			}
		}

	}, 800);
</script>




<?
$tabControl->End();
/*
 * Обрабатываем данные после отправки формы
 */
if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) { // цикл по вкладкам
        foreach ($aTab['OPTIONS'] as $arOption) {
            if (!is_array($arOption)) { // если это название секции
                continue;
            }
            if ($arOption['note']) { // если это примечание
                continue;
            }
            if ($request['apply']) { // сохраняем введенные настройки
                $optionValue = $request->getPost($arOption[0]);
                if ($arOption[0] == 'switch_on') {
                    if ($optionValue == '') {
                        $optionValue = 'N';
                    }
                }
                if ($arOption[0] == 'jquery_on') {
                    if ($optionValue == '') {
                        $optionValue = 'N';
                    }
                }
                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
            } elseif ($request['default']) { // устанавливаем по умолчанию
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . $module_id .'&lang=' . LANGUAGE_ID);
}