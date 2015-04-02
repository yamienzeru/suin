<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?> <?$APPLICATION->IncludeComponent("nzr:main.feedback", "contacts", array(
	"USE_CAPTCHA" => "Y",
	"OK_TEXT" => "Спасибо, ваше сообщение принято.",
	"EMAIL_TO" => "alex@sureinsure.ru",
	"REQUIRED_FIELDS" => array(
	),
	"EVENT_MESSAGE_ID" => array(
		0 => "7",
	),
	"INIT_MAP_TYPE" => "MAP",
	"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.75199499998173;s:10:\"yandex_lon\";d:37.61828599999997;s:12:\"yandex_scale\";i:14;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.653069886502415;s:3:\"LAT\";d:55.757647054105156;s:4:\"TEXT\";s:0:\"\";}}}",
	"ADDRESS" => "377875, г. Москва, Лялин переулок, 22",
	"PHONE" => "8(495)532-47-87",
	"EMAIL" => "hello@sureinsure.ru"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>