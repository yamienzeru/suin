<?
class SUIN_DEFAULT {
	
	public $suinId = "";
	
	function __construct() {
		$path = pathinfo(__FILE__);
		$this->suinId = "SUIN_".strtoupper($path["filename"]);
	}
	
	function calc($params)
	{
		$arRusMarks = array("ВАЗ", "ГАЗ", "ЗАЗ", "УАЗ", "ОКА");
		$isRus = in_array(mb_strtoupper(preg_replace("/[^a-zA-ZА-Яа-я0-9]/ui", "", $params["mark_name"]), LANG_CHARSET), $arRusMarks);
		$suin = new SUINImport();
		$arMinPrice = $suin->getMinPrice($params["suin_id"]);
		$result = $isRus ? $arMinPrice["RUSSIAN"] : $arMinPrice["FOREIGN"];
		return (int) $result;
	}
}
?>