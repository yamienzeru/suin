<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страхование для бизнеса");
?> <?$APPLICATION->IncludeComponent("nzr:modernize.news.list", "business", array(
	"IBLOCK_TYPE" => "text",
	"IBLOCK_ID" => "4",
	"PARENT_SECTION" => "1",
	"TEXT_ID" => array(
		0 => "",
		1 => "",
	),
	"SORT_BY1" => "ID",
	"SORT_ORDER1" => "ASC",
	"FIELD_CODE" => array(
		0 => "",
		1 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "LIST",
		1 => "PHOTOS",
		2 => "",
	),
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"DISPLAY_DATE" => "N",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>