<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_SUIN_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_SUIN_DESCRIPTION"),
	"ICON" => "/images/news_all.gif",
	"COMPLEX" => "Y",
	"PATH" => array(
		"ID" => "suin",
		"CHILD" => array(
			"ID" => "suin",
			"NAME" => GetMessage("T_IBLOCK_DESC_SUIN"),
			"SORT" => 10,
			"CHILD" => array(
				"ID" => "suin_cmpx",
			),
		),
	),
);

?>