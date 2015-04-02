<?
global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));
IncludeModuleLangFile($PathInstall."/install.php");
include($PathInstall."/version.php");
if(class_exists("nzr_suin")) return;
Class nzr_suin extends CModule
{
    var $MODULE_ID = "nzr.suin";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
	public function __construct()
	{
		$arModuleVersion = array();
		$PathInstall = str_replace("\\", "/", __FILE__);
		$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));
		include($PathInstall."/version.php");
		if(is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE= $arModuleVersion['VERSION_DATE'];
		}
		$this->PARTNER_NAME = GetMessage('ASD_PARTNER_NAME');
		$this->PARTNER_URI= 'http://vk.com/enzeru';
		$this->MODULE_NAME= GetMessage('ASD_CR_MODULE_NAME');
		$this->MODULE_DESCRIPTION= GetMessage('ASD_CR_MODULE_DESCRIPTION');
	}
	
    function nzr_suin()
    {
        $this->MODULE_VERSION = FORM_VERSION;
        $this->MODULE_VERSION_DATE = FORM_VERSION_DATE;
        $this->MODULE_NAME = GetMessage("FORM_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("FORM_MODULE_DESCRIPTION");
    }

    function DoInstall()
    {
		if($GLOBALS['APPLICATION']->GetGroupRight('main')< 'W')return;
		global $DB, $APPLICATION;
		RegisterModuleDependences('main', 'OnUserTypeBuildList', $this->MODULE_ID, 'CUserTypeIBlockModel', 'GetUserTypeDescription');
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/admin/', true, true);
		if(is_object($GLOBALS['CACHE_MANAGER']))$GLOBALS['CACHE_MANAGER']->Clean('b_module_to_module');
		RegisterModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
		if($GLOBALS['APPLICATION']->GetGroupRight('main')< 'W')return;
        global $DB, $APPLICATION;
		UnRegisterModuleDependences('main', 'OnUserTypeBuildList', $this->MODULE_ID, 'CUserTypeIBlockModel', 'GetUserTypeDescription');
		DeleteDirFilesEx('/bitrix/modules/'.$this->MODULE_ID.'/admin/');
		if(is_object($GLOBALS['CACHE_MANAGER']))$GLOBALS['CACHE_MANAGER']->Clean('b_module_to_module');
		UnRegisterModule($this->MODULE_ID);
    }
}
?>