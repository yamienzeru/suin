<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
		'INIT_MAP_TYPE' => array(
			'NAME' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE'),
			'TYPE' => 'LIST',
			'VALUES' => array(
				'MAP' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_MAP'),
				'SATELLITE' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_SATELLITE'),
				'HYBRID' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_HYBRID'),
				'PUBLIC' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_PUBLIC'),
				'PUBLIC_HYBRID' => GetMessage('MYMS_PARAM_INIT_MAP_TYPE_PUBLIC_HYBRID'),
			),
			'DEFAULT' => 'MAP',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
		),

		'MAP_DATA' => array(
			'NAME' => GetMessage('MYMS_PARAM_DATA'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => '/bitrix/components/bitrix/map.yandex.view/settings/settings.js',
			'JS_EVENT' => 'OnYandexMapSettingsEdit',
			'JS_DATA' => LANGUAGE_ID.'||'.GetMessage('MYMS_PARAM_DATA_SET').'||'.GetMessage('MYMS_PARAM_DATA_NO_KEY').'||'.GetMessage('MYMS_PARAM_DATA_GET_KEY').'||'.GetMessage('MYMS_PARAM_DATA_GET_KEY_URL'),
			'DEFAULT' => serialize(array(
				'yandex_lat' => GetMessage('MYMS_PARAM_DATA_DEFAULT_LAT'),
				'yandex_lon' => GetMessage('MYMS_PARAM_DATA_DEFAULT_LON'),
				'yandex_scale' => 10
			)),
			'PARENT' => 'BASE',
		),
		
		'ADDRESS' => array(
			'NAME' => "Адрес",
			'TYPE' => 'TEXT',
			'PARENT' => 'BASE',
		),
		
		'PHONE' => array(
			'NAME' => "Телефон",
			'TYPE' => 'TEXT',
			'PARENT' => 'BASE',
		),
		
		'EMAIL' => array(
			'NAME' => "E-mail",
			'TYPE' => 'TEXT',
			'PARENT' => 'BASE',
		),
);
?>