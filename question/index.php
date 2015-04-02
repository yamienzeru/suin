<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вопросы и ответы");
?> <?$APPLICATION->IncludeComponent("bitrix:photo.sections.top", "question", array(
	"IBLOCK_TYPE" => "question",
	"IBLOCK_ID" => "7",
	"SECTION_FIELDS" => array(
		0 => "",
		1 => "",
	),
	"SECTION_USER_FIELDS" => array(
		0 => "UF_LIST",
		1 => "",
	),
	"SECTION_SORT_FIELD" => "sort",
	"SECTION_SORT_ORDER" => "asc",
	"ELEMENT_SORT_FIELD" => "sort",
	"ELEMENT_SORT_ORDER" => "asc",
	"FILTER_NAME" => "arrFilter",
	"FIELD_CODE" => array(
		0 => "PREVIEW_TEXT",
		1 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"SECTION_COUNT" => "500",
	"ELEMENT_COUNT" => "500",
	"LINE_ELEMENT_COUNT" => "1",
	"SECTION_URL" => "",
	"DETAIL_URL" => "",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>