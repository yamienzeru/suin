<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock"))
	return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arTemplateParameters = array(
		"HELP_IBLOCK_TYPE" => array(
			"NAME" => GetMessage("BN_P_IBLOCK_TYPE")." (подсказки)",
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"HELP_IBLOCK_ID" => array(
			"NAME" => GetMessage("BN_P_IBLOCK")." (подсказки)",
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
			"ADDITIONAL_VALUES" => "Y",
		),
);
if(isset($arCurrentValues["HELP_IBLOCK_ID"]) && count($arCurrentValues["REQUIRE"]))
{
	$arText_ID = array("" => " - ");
	$rsProp = CIBlockElement::GetList(Array("ID"=>"ASC"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["HELP_IBLOCK_ID"], "INCLUDE_SUBSECTIONS" => "Y"));
	while ($arr=$rsProp->GetNext())
		$arText_ID[$arr["ID"]] = $arr["NAME"];
	
	foreach($arCurrentValues["REQUIRE"] as $require)
		$arTemplateParameters["HELP_".strtoupper($require)] = array(
			"NAME" => $require,
			"TYPE" => "LIST",
			"DEFAULT" => "-",
			"VALUES" => $arText_ID,
		);
}
?>
