<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
foreach($arResult["ITEMS"] as &$arItem) $arItem["NAME"] = strlen($arItem["NAME"]) < 4 ? mb_strtoupper($arItem["NAME"], LANG_CHARSET) : mb_convert_case($arItem["NAME"], MB_CASE_TITLE, LANG_CHARSET);?>