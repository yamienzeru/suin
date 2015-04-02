<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arSorts = Array("ASC"=>GetMessage("T_IBLOCK_DESC_ASC"), "DESC"=>GetMessage("T_IBLOCK_DESC_DESC"));
$arSortFields = Array(
		"ID"=>GetMessage("T_IBLOCK_DESC_FID"),
		"NAME"=>GetMessage("T_IBLOCK_DESC_FNAME"),
		"ACTIVE_FROM"=>GetMessage("T_IBLOCK_DESC_FACT"),
		"SORT"=>GetMessage("T_IBLOCK_DESC_FSORT"),
		"TIMESTAMP_X"=>GetMessage("T_IBLOCK_DESC_FTSAMP")
	);
	
$arProperty_LNS = array();
$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["MODELS_IBLOCK_ID"]));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("E")))
	{
		$arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}
$arProperty_LNS2 = array();
$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["RESULT_IBLOCK_ID"]));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("S")))
	{
		$arProperty_LNS2[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}

$arRequire = array(
	"mark" => "Марка авто", 
	"model" => "Модель авто", 
	"year" => "Год выпуска", 
	"power" => "Мощность", 
	"litr" => "Объем двигателя", 
	"cost" => "Оценочная стоимость ТС", 
	"credit" => "ТС куплено в кредит?", 
	"credit_bank" => "Банк", 
	"people-type" => "Собственник ТС", 
	"people-num" => "Кол-во водителей", 
	"people-gender" => "Пол", 
	"people-year" => "Количество полных лет", 
	"people-driving" => "Водительский стаж", 
	"rudder" => "Руль", 
	"date" => "Дата начала использования ТС", 
	"risk" => "Страховое покрытие", 
	"franshiza" => "Франшиза", 
	"repair" => "Способ урегулирования", 
	/*"security" => "Противоугонная система", 
	"secur" => "", 
	"searchsystem" => "Поисковая система", 
	"commissioner" => "", 
	"repair" => "", 
	"evacuation" => "", 
	"tech-help" => "", 
	"pay" => "", 
	"dsago" => "", 
	"pay-type" => "",*/
);

$arComponentParameters = array(
	"GROUPS" => array(
		"MARKS" => array(
			"SORT" => 110,
			"NAME" => GetMessage("SUIN_MARKS_SETTINGS"),
		),
		"MODELS" => array(
			"SORT" => 120,
			"NAME" => GetMessage("SUIN_MODELS_SETTINGS"),
		),
		"YEARS" => array(
			"SORT" => 130,
			"NAME" => GetMessage("SUIN_YEARS_SETTINGS"),
		),
		"RESULT" => array(
			"SORT" => 150,
			"NAME" => GetMessage("SUIN_RESULT_SETTINGS"),
		),
	),
	"PARAMETERS" => array(
		"AJAX_MODE" => array(),
		"REQUIRE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SUIN_REQUIRE"),
			"TYPE" => "LIST",
			"VALUES" => $arRequire,
			"MULTIPLE" => "Y",
			"REFRESH" => "Y",
		),
		"MARKS_IBLOCK_TYPE" => array(
			"PARENT" => "MARKS",
			"NAME" => GetMessage("BN_P_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"MARKS_IBLOCK_ID" => array(
			"PARENT" => "MARKS",
			"NAME" => GetMessage("BN_P_IBLOCK"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
			"ADDITIONAL_VALUES" => "Y",
		),
		"MARKS_SORT_BY" => Array(
			"PARENT" => "MARKS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD1"),
			"TYPE" => "LIST",
			"DEFAULT" => "ACTIVE_FROM",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"MARKS_SORT_ORDER" => Array(
			"PARENT" => "MARKS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"MODELS_IBLOCK_TYPE" => array(
			"PARENT" => "MODELS",
			"NAME" => GetMessage("BN_P_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"MODELS_IBLOCK_ID" => array(
			"PARENT" => "MODELS",
			"NAME" => GetMessage("BN_P_IBLOCK"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
			"ADDITIONAL_VALUES" => "Y",
		),
		"MODELS_SORT_BY" => Array(
			"PARENT" => "MODELS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD1"),
			"TYPE" => "LIST",
			"DEFAULT" => "ACTIVE_FROM",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"MODELS_SORT_ORDER" => Array(
			"PARENT" => "MODELS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"MODELS_SORT_ORDER" => Array(
			"PARENT" => "MODELS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"MODELS_MARK" =>array(
			"PARENT" => "MODELS",
			"NAME" => GetMessage("SUIN_MODELS_MARK"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => array_merge(Array("-"=>" "),$arProperty_LNS),
		),
		"YEAR_START" =>array(
			"PARENT" => "YEARS",
			"NAME" => GetMessage("SUIN_YEAR_START"),
			"TYPE" => "STRING",
			"DEFAULT" => 1970,
		),
		
		
		
		
		
		
		
		
		
		
		"RESULT_IBLOCK_TYPE" => array(
			"PARENT" => "RESULT",
			"NAME" => GetMessage("BN_P_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"RESULT_IBLOCK_ID" => array(
			"PARENT" => "RESULT",
			"NAME" => GetMessage("BN_P_IBLOCK"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
			"ADDITIONAL_VALUES" => "Y",
		),
		"RESULT_PROPERTY" =>array(
			"PARENT" => "RESULT",
			"NAME" => GetMessage("SUIN_RESULT_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => array_merge(Array("-"=>" "),$arProperty_LNS2),
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
		"CACHE_FILTER" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("BN_P_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BN_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);
//CIBlockParameters::AddPagerSettings($arComponentParameters, GetMessage("T_IBLOCK_DESC_PAGER_NEWS"), false, false);
?>
