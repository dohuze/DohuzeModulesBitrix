<?
Class clementin_authsms extends CModule {
    var $MODULE_ID = "clementin.authsms";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $MODULE_GROUP_RIGHTS = "Y";

    function clementin_authsms() {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->PARTNER_NAME = GetMessage("clementin.authsms_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("clementin.authsms_PARTNER_URI");

        $this->MODULE_NAME = GetMessage("clementin.authsms_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("clementin.authsms_MODULE_DESCRIPTION");
    }

    function InstallDB() {
        RegisterModule("clementin.authsms");
		// удаляем из БД
		$names_var_arr = array("provider", "str_email_body", "str_email_subject", "str_email_from", "e_mail_on", "color_str_zagolovok", "color_buttom", "color_str_knopki", "radius_zakrugl", "PATH_TO_ORDER", "PATH_TO_AUTOR", "PATH_TO_REG", "autor_on", "zakaz_on", "black_ip_json", "login_smscru", "password_smscru", "api_key_smsru", "token_smsintru");
		foreach($names_var_arr as $value) {
			if(!empty(COption::GetOptionString('clementin.authsms', $value))) {
				COption::RemoveOption('clementin.authsms', $value);
			}
		}
        return true;
    }

    function UnInstallDB() {
        UnRegisterModule("clementin.authsms");
        return true;
    }

    function InstallEvents() {
        RegisterModuleDependences("main", "OnBeforeProlog", "clementin.authsms", "YourModuleClassName", "YourHandlerMethodName");
		RegisterModuleDependences("sale", "OnOrderSave", "clementin.authsms", "YourModuleClassName", "YourHandlerMethodName1");
		return true;
    }

    function UnInstallEvents() {
        UnRegisterModuleDependences("main", "OnBeforeProlog", "clementin.authsms", "YourModuleClassName", "YourHandlerMethodName");
		UnRegisterModuleDependences("sale", "OnOrderSave", "clementin.authsms", "YourModuleClassName", "YourHandlerMethodName1");
		return true;
    }

    function InstallFiles() {
		if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/sms'))
			mkdir($_SERVER["DOCUMENT_ROOT"] . '/sms');
		if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/sms/css'))
			mkdir($_SERVER["DOCUMENT_ROOT"] . '/sms/css');
		if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/include'))
			mkdir($_SERVER["DOCUMENT_ROOT"] . '/include');
		if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms'))
			mkdir($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms');
		if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/css'))
			mkdir($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/css');
		if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/js'))
			mkdir($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/js');
		if(!is_dir($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/fonts'))
			mkdir($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/fonts');
		if(!file_exists($_SERVER["DOCUMENT_ROOT"] . '/sms/css/custom.css')) {
			file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/sms/css/custom.css', '');
		}

        return true;
    }

    function UnInstallFiles() {
        return true;
    }

    function DoInstall() {
        global $APPLICATION;
        if(!IsModuleInstalled("clementin.authsms")) {
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
        }
    }

    function DoUninstall() {
		$this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/sms'); //26.08
    }
}