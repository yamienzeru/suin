<?
include_once("usertypesuin.php");
function recallSend()
{
	CModule::IncludeModule("iblock");
	GLOBAL $DB;
	$arFilter = Array(
		"IBLOCK_ID" => 12, 
		"!DATE_CREATE" => false,
		"<DATE_CREATE" => date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), time() + 0*7*24*60*60),
		"!PROPERTY_RECALL" => false
	);
	$res = CIBlockElement::GetList(Array(), $arFilter);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$arFields["RECALL"] = $ob->GetProperty("RECALL");
		$arFields["RECALL"] = $arFields["RECALL"]["VALUE"];
		//print_p($arFields);
		if(strlen($arFields["RECALL"]))
		{
			$arFields["ELEMENT_ID"] = $arFields["ID"];
			CEvent::Send("RECALL", SITE_ID, $arFields);
			CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("RECALL" => ""));
		}
	}
	//die();
}
recallSend();
class SUINImport{ 

	public $module_id = "nzr.suin";
	public $iblock = "";
	/*public $iblocks_list = array(
		//"marks" => "Марки",
		"models" => "Модели",
	);*/
	public $iblocks = array();
	public $site = "";
	public $suinMods = array();
	public $arMessages = array();
	public $arErrors = array();
	public $arResult = array();
	
	function __construct() {
		CModule::IncludeModule("iblock");
		$this->iblock = COption::GetOptionString($this->module_id, "SUIN_IBLOCK");
		$iblockType = CIBlockType::GetByID($this->iblock)->getNext();
		$this->iblock = is_array($iblockType) ? $iblockType["ID"] : false;
		if ($arSite = CSite::GetList($by="sort", $order="desc", Array("def" => "Y"))->Fetch()) $this->site = $arSite["LID"];
		if ($handle = opendir($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/".$this->module_id."/suin")) {

			while (false !== ($file = readdir($handle))) 
				if(strlen(str_replace(array(" ", "."), "", $file)) && stripos($file, ".php") !== false)
				{ 
					$path = pathinfo($file);
					include_once($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/".$this->module_id."/suin/".$file);
					$suinId = "SUIN_".strtoupper($path["filename"]);
					$suinClass = new $suinId;
					$this->suinMods[] = array(
						"CODE" => $path["filename"],
						"CLASS" => $suinId,
						"ACTIVE" => COption::GetOptionString($this->module_id, $suinId) == "Y" ? "Y" : "N"
					);
					//$arOptions[$suinId] = Array($suinClass->suinName, Array("checkbox", 50, "import" => true));
				}
			closedir($handle); 
		}
		/*$el = new CIBlockElement;
		foreach($this->iblocks_list as $ib_code => $ib_name)
		{
			$ib_id = $this->addIblock($ib_name, $ib_code);
			$arElaments = array();
			$db_res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $ib_id, "NAME" => $suin_val_name));
			while($ar_res = $db_res->GetNext()) 
			{
				$arElements[$ar_res["ID"]] = $ar_res["NAME"];
				$res = $el->Update($ar_res["ID"], array("NAME" => trim(mb_strtoupper($ar_res["NAME"], LANG_CHARSET))));
			}
			$tmp_iblock[$ib_id] = array(
				"ID" => $ib_id,
				"NAME" => $ib_name,
				"CODE" => $ib_code,
				"ELEMENTS" => $arElements
			);
		}
		print_p($tmp_iblock);die();
		//$this->iblocks = $tmp_iblock;*/
	}
	
	function getSuinInfo()
	{
		$arSuinInfo = array();
		foreach($this->suinMods as $suinMod)
			$arSuinInfo[$suinMod["CODE"]] = array(
				"NAME" => COption::GetOptionString($this->module_id, $suinMod["CLASS"]."_NAME"),
				"LOGO" => CFile::GetFileArray(COption::GetOptionString($this->module_id, $suinMod["CLASS"]."_LOGO")),
				"ABOUT" => COption::GetOptionString($this->module_id, $suinMod["CLASS"]."_ABOUT"),
				"ADDRESS" => COption::GetOptionString($this->module_id, $suinMod["CLASS"]."_ADDRESS"),
				"WORK" => COption::GetOptionString($this->module_id, $suinMod["CLASS"]."_WORK"),
				"EMAIL" => COption::GetOptionString($this->module_id, $suinMod["CLASS"]."_EMAIL"),
			);
		$arSuinInfo["sis"] = array(
				"ADDRESS" => COption::GetOptionString($this->module_id, "SUIN_ADDRESS"),
				"WORK" => COption::GetOptionString($this->module_id, "SUIN_WORK"),
				"EMAIL" => COption::GetOptionString($this->module_id, "SUIN_EMAIL"),
			);
		return $arSuinInfo;
	}
	
	function getMinPrice($suinId)
	{
		return array(
			"RUSSIAN" => COption::GetOptionString($this->module_id, $suinId."_RUSSIAN"),
			"FOREIGN" => COption::GetOptionString($this->module_id, $suinId."_FOREIGN"),
		);
	}
	
	function addIblock($name, $code, $arProperties)
	{
		if($this->iblock)
		{
			foreach($this->iblocks as $key => $iblock)
				if($iblock["CODE"] == $code) 
				{
					$IBLOCK_ID = $iblock["ID"];
					break;
				}
			if(!$IBLOCK_ID && !$ar_res = CIBlock::GetList(Array(), Array("TYPE" => $this->iblock, "SITE_ID" => $this->site, "ACTIVE" => "Y", "CODE" => $code), true)->Fetch())
			{
				$ib = new CIBlock;
				$IBLOCK_ID = $ib->Add(Array(
					"ACTIVE" => "Y",
					"NAME" => $name,
					"CODE" => $code,
					"IBLOCK_TYPE_ID" => $this->iblock,
					"SITE_ID" => $this->site,
					"SORT" => "500",
					"GROUP_ID" => Array("2"=>"R")
				));
				$this->iblocks[$IBLOCK_ID] = array(
					"ID" => $IBLOCK_ID,
					"NAME" => $name,
					"CODE" => $code,
				);
			}
			elseif(!$IBLOCK_ID)
			{
				$IBLOCK_ID = $ar_res["ID"];
				//add
				$arElaments = array();
				$db_res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $IBLOCK_ID));
				//while($ar_res = $db_res->GetNext()) $arElements[$ar_res["ID"]] = $ar_res["NAME"];
				while($ar_res = $db_res->GetNext()) $arElements[$ar_res["ID"]] = $ar_res["XML_ID"];
				$this->iblocks[$IBLOCK_ID] = array(
					"ID" => $IBLOCK_ID,
					"NAME" => $name,
					"CODE" => $code,
					"ELEMENTS" => $arElements
				);
				
			}
			if(is_array($arProperties))
				foreach($arProperties as $arProperty)
				{
					if(in_array($arProperty["CODE"], $this->iblocks[$IBLOCK_ID]["PROPERTIES"]))
					{
						//
					}
					elseif(!$prop_fields = CIBlockProperty::GetList(Array(), Array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID, "CODE" => $arProperty["CODE"]))->Fetch())
					{
						$ibp = new CIBlockProperty;
						$PropID = $ibp->Add(Array(
							"NAME" => $arProperty["NAME"],
							"ACTIVE" => "Y",
							"CODE" => $arProperty["CODE"],
							"PROPERTY_TYPE" => strlen($arProperty["PROPERTY_TYPE"]) ? $arProperty["PROPERTY_TYPE"] : "S",
							"USER_TYPE" => strlen($arProperty["USER_TYPE"]) ? $arProperty["USER_TYPE"] : "",
							"LINK_IBLOCK_ID" => strlen($arProperty["LINK_IBLOCK_ID"]) ? $arProperty["LINK_IBLOCK_ID"] : "",
							"IBLOCK_ID" => $IBLOCK_ID,
						));
						$this->iblocks[$IBLOCK_ID]["PROPERTIES"][] = $arProperty["CODE"];
					}
				}
			return $IBLOCK_ID;
		}
		return false;
	}
	
	//function addProperty($name, $code, $arProperties)
	
	
	function addMark($suinId, $suin_val_name, $suin_val_id)
	{
		GLOBAL $USER;
		$suinClass = new $suinId;
		$name_templ = mb_strtoupper(preg_replace("/[^a-zA-ZА-Яа-я0-9]/ui","",$suin_val_name), LANG_CHARSET);
		$arProperties[] = array("NAME" => "ID в ".COption::GetOptionString("nzr.suin", $suinId."_NAME"), "CODE" => $suinId."_ID", "VALUE" => $suin_val_id);
		if($IBLOCK_ID = $this->addIblock("Марки", "marks", $arProperties))
		{
			foreach($arProperties as $arProperty)
				$PROP[$arProperty["CODE"]] = $arProperty["VALUE"];
			if($PRODUCT_ID = array_search($name_templ, $this->iblocks[$IBLOCK_ID]["ELEMENTS"]))
			{
				CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $PROP);
				$this->arMessages[] = "Обновлена информация для: [".$PRODUCT_ID."] ".$suin_val_name;
			}
			else
			{
				$el = new CIBlockElement;
				
				$arLoadProductArray = Array(
					"MODIFIED_BY"    => $USER->GetID(),
					"IBLOCK_SECTION_ID" => false,
					"IBLOCK_ID"      => $IBLOCK_ID,
					"PROPERTY_VALUES"=> $PROP,
					"NAME"           => trim($suin_val_name),
					"XML_ID"           => $name_templ,
					"ACTIVE"         => "Y"
				);

				if($PRODUCT_ID = $el->Add($arLoadProductArray)) $this->iblocks[$IBLOCK_ID]["ELEMENTS"][] = $name_templ;//$this->arMessages[] = "Добавлен: [".$PRODUCT_ID."] ".$suin_val_name;
				else return false;//$this->arErrors[] = "Не удалось добавить: ".$name.". Ошибка: ".$el->LAST_ERROR;
			}
			return $PRODUCT_ID;
		}
		return false;
	}
	
	function addModel($suinId, $suin_val_name, $suin_val_id, $mark_id)
	{
		GLOBAL $USER;
		$suinClass = new $suinId;
		$name_templ = mb_strtoupper(preg_replace("/[^a-zA-ZА-Яа-я0-9]/ui","",$suin_val_name), LANG_CHARSET);
		$arProperties[] = array("NAME" => "ID в ".COption::GetOptionString("nzr.suin", $suinId."_NAME"), "CODE" => $suinId."_ID", "VALUE" => $suin_val_id);
		$ib_marks_id = false;
		foreach($this->iblocks as $id => $iblock)
			if($iblock["CODE"] == "marks")
			{
				$ib_marks_id = $id;
				break;
			}
		if($ib_marks_id)
		{
			$arProperties[] = array("NAME" => "Марка", "CODE" => "MARK", "VALUE" => $mark_id, "PROPERTY_TYPE" => "E", "USER_TYPE" => "EList", "LINK_IBLOCK_ID" => $ib_marks_id);
			if($IBLOCK_ID = $this->addIblock("Модели", "models", $arProperties))
			{
				foreach($arProperties as $arProperty)
					$PROP[$arProperty["CODE"]] = $arProperty["VALUE"];

				if($PRODUCT_ID = array_search($name_templ, $this->iblocks[$IBLOCK_ID]["ELEMENTS"]))
				{
					CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, false, $PROP);
					$this->arMessages[] = "Обновлена информация для: [".$PRODUCT_ID."] ".$suin_val_name;
				}
				else
				{
					$el = new CIBlockElement;
					
					$arLoadProductArray = Array(
						"MODIFIED_BY"    => $USER->GetID(),
						"IBLOCK_SECTION_ID" => false,
						"IBLOCK_ID"      => $IBLOCK_ID,
						"PROPERTY_VALUES"=> $PROP,
						"NAME"           => trim($suin_val_name),
						"XML_ID"           => $name_templ,
						"ACTIVE"         => "Y"
					);

					if($PRODUCT_ID = $el->Add($arLoadProductArray)) $this->iblocks[$IBLOCK_ID]["ELEMENTS"][] = $name_templ;//$this->arMessages[] = "Добавлен: [".$PRODUCT_ID."] ".$suin_val_name;
					else return false;//$this->arErrors[] = "Не удалось добавить: ".$name.". Ошибка: ".$el->LAST_ERROR;
				}
				return $PRODUCT_ID;
			}
			return false;
		}
		return false;
	}
	
	function calc($suinId, $arParams)
	{
		$suinClass = new $suinId;
		foreach(array("mark", "model") as $ib_param)
			if($res = CIBlockElement::GetByID($arParams[$ib_param])->GetNextElement())
			{
				$arFields = $res->GetFields();
				$arParams[$ib_param."_name"] = $arFields["NAME"];
				$vals = $res->GetProperty($suinClass->suinId."_ID");
				if(strlen($vals["VALUE"])) $arParams[$ib_param] = $vals["VALUE"];
				else
				{
					$vals = $res->GetFields();
					$arParams[$ib_param] = $vals["NAME"];
				}
			}
		return $suinClass->calc($arParams);
	}
} 
?>