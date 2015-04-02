<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arGroups = array();
$arSections = array();
foreach($arResult["ITEMS"] as $keyItem => $arItem) 
{
	$arResult["ITEMS"][$keyItem]["NAME"] = strlen($arItem["NAME"]) < 4 ? mb_strtoupper($arItem["NAME"], LANG_CHARSET) : mb_convert_case($arItem["NAME"], MB_CASE_TITLE, LANG_CHARSET);
	if($arItem["IBLOCK_SECTION_ID"] > 0)
	{
		$arResult["ITEMS"][$keyItem]["HIDE"] = "Y";
		$arGroups[] = $arItem["IBLOCK_SECTION_ID"];
	}
}
$arGroups = array_unique($arGroups);
if(count($arGroups))
{
	$arFilter = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "GLOBAL_ACTIVE" => "Y", "ID" => $arGroups);
	$rsSections = CIBlockSection::GetList(Array("NAME" => "ASC"), $arFilter, false, array("ID", "NAME", "CODE", "UF_*"));
	while($arSection = $rsSections->GetNext())
	{
		$arSections["GROUP_".$arSection["ID"]] = array(
			"ID" => "GROUP_".$arSection["ID"],
			"NAME" => $arSection["NAME"],
			"CODE" => $arSection["CODE"],
		);
	}
}
$arResult["ITEMS"] = array_merge($arSections, $arResult["ITEMS"]);

foreach($arResult["ITEMS"] as $key => $arItem)
    $sort[$key] = $arItem["NAME"];
array_multisort($sort, SORT_ASC, $arResult["ITEMS"]);
//print_p($arResult["ITEMS"]);
//print_p($arResult["ITEMS"]);
?>