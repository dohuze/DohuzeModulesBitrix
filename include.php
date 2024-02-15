<?php

class YourModuleClassName {
	function YourHandlerMethodName() {
		global $USER;
		$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
		$getQueryList_arr = $request->getQueryList()->toArray();
		$URL = str_replace('index.php', '', $request->getRequestedPage());
		$url_ref_obj = new \Bitrix\Main\Web\Uri($URL);
		$url_ref_obj->setPath('/include/clementin.authsms/handler_ajax_autor.php'); 
		$url_ref_obj->addParams($getQueryList_arr);
		$url_ref = $url_ref_obj->getUri();

		if(!$request->isAdminSection() && !$USER->IsAuthorized()) {
			$flag_redirect = 'N';
			
			if(COption::GetOptionString("clementin.authsms", "autor_on") == 'Y') {
				$uri_auth = new \Bitrix\Main\Web\Uri(COption::GetOptionString("clementin.authsms", "PATH_TO_AUTOR"));
				$uri_reg = new \Bitrix\Main\Web\Uri(COption::GetOptionString("clementin.authsms", "PATH_TO_REG"));
				if($URL == $uri_auth->getPath() || $URL == $uri_reg->getPath()) {
					$flag_redirect = 'Y';
				}
			}

			if(COption::GetOptionString("clementin.authsms", "zakaz_on") == 'Y') {
				$uri_zakaz = new \Bitrix\Main\Web\Uri(COption::GetOptionString("clementin.authsms", "PATH_TO_ORDER"));
				if($URL == $uri_zakaz->getPath()) {
					$flag_redirect = 'Y';
				}
			}
			
			if($_GET['clementin_authsms_type'] == 'N') {
				$flag_redirect = 'N';
			}

			if($flag_redirect == 'Y') {
				$uri = new \Bitrix\Main\Web\Uri($URL);
				$uri->setPath('/sms/index.php'); 
				LocalRedirect($uri->getUri());
			}
		}
	}
	
	function YourHandlerMethodName1($orderID, $fields, $orderFields) {
		if(CModule::IncludeModule('sale')) {
			$dbProps = CSaleOrderProps::GetList(array("SORT" => "ASC"), array());
			while($arProps = $dbProps->Fetch()) {
				if($arProps["CODE"] == "FIO" && $arProps["PERSON_TYPE_ID"] == 1)	$id_fio = $arProps["ID"];
				if($arProps["CODE"] == "EMAIL" && $arProps["PERSON_TYPE_ID"] == 1)	$id_email = $arProps["ID"];
				if($arProps["CODE"] == "PHONE" && $arProps["PERSON_TYPE_ID"] == 1)	$id_phone = $arProps["ID"];
				if($arProps["CODE"] == "FIO" && $arProps["PERSON_TYPE_ID"] == 2)	$id_fio = $arProps["ID"];
				if($arProps["CODE"] == "EMAIL" && $arProps["PERSON_TYPE_ID"] == 2)	$id_email = $arProps["ID"];
				if($arProps["CODE"] == "PHONE" && $arProps["PERSON_TYPE_ID"] == 2)	$id_phone = $arProps["ID"];
			}
		}

		if(!empty($orderFields['ORDER_PROP'][$id_fio])) {
			$fio = preg_replace('/\s+/', ' ', $orderFields['ORDER_PROP'][$id_fio]);
			list($f, $i, $o) = explode(" ", $fio);
			if(!empty($f)) $fields_user['LAST_NAME'] = $f;
			if(!empty($i)) $fields_user['NAME'] = $i;
			if(!empty($o)) $fields_user['SECOND_NAME'] = $o;
		}
		if(!empty($orderFields['ORDER_PROP'][$id_email])) {
			$fields_user['EMAIL'] = $orderFields['ORDER_PROP'][$id_email];
		}
		if(!empty($orderFields['PERSONAL_PHONE'][$id_phone])) {
			$fields_user['PERSONAL_PHONE'] = $orderFields['PERSONAL_PHONE'][$id_phone];
		}
		if(!empty($orderFields['PERSONAL_MOBILE'][$id_phone])) {
			$fields_user['PERSONAL_MOBILE'] = $orderFields['PERSONAL_MOBILE'][$id_phone];
		}

		if(count($fields_user) > 0) {
			$user = new CUser;
			$user->Update($orderFields['USER_ID'], $fields_user);
		}
	}
	
	
	
}