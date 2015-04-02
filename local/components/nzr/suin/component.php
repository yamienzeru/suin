<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$componentPage = "calc";

//$arParams["SHOW_WORKFLOW"] = $_REQUEST["show_workflow"]=="Y";

/*$arDataNames = array("mark", "model", "year", "power", "litr", "cost", "credit", "credit_bank", "people-type", "people-num", "people-gender", "people-year", "people-driving", 
	"rudder", "date", "risk", "franshiza", "security", "secur", "searchsystem", "commissioner", "repair", "evacuation", "tech-help", "pay", "dsago", "pay-type");*/
$arDataNames = $arParams["REQUIRE"];
	
$arReq = array("mark", "model", "year", "power", "cost", "people-type", "people-num", "people-year", "people-driving", "date", "risk", "repair");
	
foreach($arDataNames as $dataName)
	$arResult["DATA"][$dataName] = isset($_REQUEST[$dataName]) ? $_REQUEST[$dataName] : "";
	
include_once(__DIR__."/config.php");
$arResult["CALC_PARAMS"] = $arCalcParams;

foreach(array("people-type", "people-num", "risk", "franshiza", "repair") as $dataName)
	if(empty($_REQUEST[$dataName]))
		$arResult["DATA"][$dataName] = current(array_keys($arResult["CALC_PARAMS"][$dataName]));

$data_all = true;
foreach($arReq as $dataName)
{
	if(empty($arResult["DATA"][$dataName])) 
	{
		$data_all = false;
		break;
	}
}	

if(is_array($arResult["DATA"]["people-year"]))
{
	foreach($arResult["DATA"]["people-year"] as $key => $people_year)
		if($arResult["DATA"]["people-driving"][$key] > ($people_year - 18))
		{
			$data_all = false;
			break;
		}
}
elseif($arResult["DATA"]["people-driving"] > ($arResult["DATA"]["people-year"] - 18))
{
	$data_all = false;
}

if (CModule::IncludeModule('nzr.suin') && CModule::IncludeModule('iblock'))
{	
	if(strlen($_POST["step2"]) && $data_all)
	{	
		if(stripos($arResult["DATA"]["model"], "GROUP_") !== false)
		{
			GLOBAL $USER_FIELD_MANAGER;
			$group_id = IntVal(str_ireplace("GROUP_", "", $arResult["DATA"]["model"]));
		}
		$suin = new SUINImport;
		foreach($suin->suinMods as $suinMod)
			if($suinMod["CODE"] != "default" && $suinMod["ACTIVE"] == "Y")
			{
				$arCalcFilter = array();
				if($group_id > 0)
				{
					$model_id = "";
					$arUserFields = $USER_FIELD_MANAGER->GetUserFields("IBLOCK_".$arParams["MODELS_IBLOCK_ID"]."_SECTION", $group_id, LANGUAGE_ID);
					foreach($arUserFields as $arUserField)
						if($arUserField["SETTINGS"]["PROPERTY_CODE"] == $suinMod["CLASS"]."_ID")
						{
							$model_id = $arUserField["VALUE"];
							break;
						}
					if(strlen($model_id)) $arCalcFilter["model"] = $model_id;
					else continue;
				}
				$calc_result = $suin->calc($suinMod["CLASS"], array_merge($arResult["DATA"], $arCalcFilter));
				if(count($calc_result))
					foreach($calc_result as $cr)
						$arResult["SUIN"][] = array_merge($suinMod, array("RESULT" => $cr));
			}
		$save_res = false;
		foreach($arResult["SUIN"] as $arSuin)
			if($arSuin["RESULT"]["PRICE"])
			{
				$save_res = true;
				break;
			}
		if($save_res)
		{
			$result = serialize($arResult);
			if($res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $arParams["RESULT_IBLOCK_ID"], "PROPERTY_".$arParams["RESULT_PROPERTY"] => $result), false, Array("nPageSize"=>1), array("ID"))->GetNext())
				$RESULT_ID = $res["ID"];
			else
			{
				$el = new CIBlockElement;
				$RESULT_ID = $el->Add(Array(
					"MODIFIED_BY"    => 1,
					"IBLOCK_SECTION_ID" => false,
					"IBLOCK_ID"      => $arParams["RESULT_IBLOCK_ID"],
					"PROPERTY_VALUES"=> array($arParams["RESULT_PROPERTY"] => $result),
					"NAME"           => "Расчет №",
					"ACTIVE"         => "Y"));
			}
			LocalRedirect("/result/".$RESULT_ID."/");
		}
	}
	//print_p($_REQUEST);
	
	//if($arParams["SHOW_WORKFLOW"] || $this->StartResultCache(false) || $_SERVER["REQUEST_METHOD"] == "POST")
	{
		//if($_SERVER["REQUEST_METHOD"] == "POST") $this->AbortResultCache();

		if(isset($_REQUEST["result"]) && $res = CIBlockElement::GetByID($_REQUEST["result"])->GetNextElement())
		{	
			$result = $res->GetProperty($arParams["RESULT_PROPERTY"]);
			$arResult = unserialize($result["~VALUE"]);
			$arResult["SUIN_SALE"] = (int) COption::GetOptionString("nzr.suin", "SUIN_SALE");
			$arResult["SALE"] = (100 -  $arResult["SUIN_SALE"]) / 100;
			$arResult["CALC_ID"] = $_REQUEST["result"];
			$suin = new SUINImport;
			$arResult["SUIN_INFO"] = $suin->getSuinInfo();
			if(isset($_POST["recall"]) && strlen($_REQUEST["email"]))
				CIBlockElement::SetPropertyValuesEx($arResult["CALC_ID"], false, array("RECALL" => $_REQUEST["email"]));
		}
		//print_p($arResult);die();
		if(isset($_REQUEST["suin"]) || isset($_REQUEST["order"]))
		{
			$componentPage = isset($_REQUEST["suin"]) ? "detail" : "order";
			$_REQUEST["suin"] = isset($_REQUEST["order"]) ? $_REQUEST["order"] : $_REQUEST["suin"];
			$arResult["DATA_PINT"] = $arResult["DATA"];
			foreach(array("mark", "model") as $prm)
				if($res = CIBlockElement::GetByID($arResult["DATA_PINT"][$prm])->GetNext())
					$arResult["DATA_PINT"][$prm] = $res["NAME"];
			foreach(array_keys($arResult["CALC_PARAMS"]) as $prm)
				if(isset($arResult["DATA_PINT"][$prm]))
					$arResult["DATA_PINT"][$prm] = $arResult["CALC_PARAMS"][$prm][$arResult["DATA_PINT"][$prm]];
			if(strlen($arResult["DATA_PINT"]["date"])>0)
						$arResult["DATA_PINT"]["date"] = strtolower(CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($arResult["DATA_PINT"]["date"], CSite::GetDateFormat())));
			$suin = new SUINImport;
			$arResult["SUIN_INFO"] = $suin->getSuinInfo();
			if(isset($_REQUEST["send"]) && $_REQUEST["send"] == "y")
			{
				$componentPage = "send";
				CIBlockElement::SetPropertyValuesEx($arResult["CALC_ID"], false, array("SEND" => serialize($_REQUEST["info"])));
				$arResult["SEND"] = array_merge($arResult["DATA_PINT"], $_REQUEST["info"]);
				$arResult["SEND"]["drivers"] = '';
				$arResult["SEND"]["people-year"] = (array) $arResult["SEND"]["people-year"];
				$arResult["SEND"]["people-driving"] = (array) $arResult["SEND"]["people-driving"];
				foreach($arResult["SEND"]["people-year"] as $driver => $val)
				{
					$fio = $arResult["SEND"]["people-lname"][$driver].' '.$arResult["SEND"]["people-name"][$driver].' '.$arResult["SEND"]["people-sname"][$driver];
					$arResult["SEND"]["drivers"] .= 'Водитель'.(count($arResult["SEND"]["people-year"]) > 1 ? ' '.($driver + 1) : '').': возраст - '.$val.', стаж - '.$arResult["SEND"]["people-driving"][$driver].(strlen($fio) ? ', ФИО - '.$fio : '').';
';
				}
				unset($arResult["SEND"]["people-year"]);
				unset($arResult["SEND"]["people-driving"]);
				if($_REQUEST["type"] == 1 || $_REQUEST["type"] == 2) $arResult["SEND"]["EMAIL_TO"] = $arResult["SUIN_INFO"]["sis"]["EMAIL"];
				elseif($_REQUEST["type"] == 3) $arResult["SEND"]["EMAIL_TO"] = $arResult["SUIN_INFO"][$arResult["SUIN"][$_REQUEST["suin"]]["CODE"]]["EMAIL"];
				if(strlen($arResult["SEND"]["EMAIL_TO"])) CEvent::Send("SUIN_FORM", SITE_ID, $arResult["SEND"]);
			}
		}
	}
	
	if($componentPage == "calc" && $arParams["HELP_IBLOCK_ID"] > 0 && count($arParams["REQUIRE"]))
	{
		$arIDs = array();
		foreach($arParams["REQUIRE"] as $require)
			if($arParams["HELP_".strtoupper($require)])
			{
				$arIDs[] = $arParams["HELP_".strtoupper($require)];
			}
		if(count($arIDs))
		{
			$arHelps = array();
			$rsProp = CIBlockElement::GetList(Array(), Array("ACTIVE"=>"Y", "IBLOCK_ID" => $arParams["HELP_IBLOCK_ID"], "INCLUDE_SUBSECTIONS" => "Y", "ID" => $arIDs));
			while ($arr=$rsProp->Fetch())
				$arHelps[$arr["ID"]] = $arr;
			if(count($arHelps))
			{
				foreach($arParams["REQUIRE"] as $require)
					if($arParams["HELP_".strtoupper($require)])
					{
						$arResult["HELPS"][$require] = $arHelps[$arParams["HELP_".strtoupper($require)]];
					}
			}
			//print_p($arResult["HELPS"]);die();
		}
	}

}

$this->IncludeComponentTemplate($componentPage);
?>