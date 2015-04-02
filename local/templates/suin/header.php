<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
	<?$APPLICATION->ShowHead()?>
	<meta charset="utf-8" />
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<!-- Icon -->
	<link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" />
	<link href="/favicon.ico" type="image/x-icon" rel="icon" />
	<!-- Stylesheet -->
	<link href="<?=SITE_TEMPLATE_PATH?>/css/reset.css" rel="stylesheet" />
	<link href="<?=SITE_TEMPLATE_PATH?>/css/jquery.arcticmodal-0.3.css" rel="stylesheet" />
	<link href="<?=SITE_TEMPLATE_PATH?>/css/template.css" rel="stylesheet" />
	<link href="<?=SITE_TEMPLATE_PATH?>/css/jquery-ui.css" rel="stylesheet" />
	<link href="<?=SITE_TEMPLATE_PATH?>/css/select2.css" rel="stylesheet" />
	<link href="<?=SITE_TEMPLATE_PATH?>/css/social-likes.css" rel="stylesheet" />
	<link href="<?=SITE_TEMPLATE_PATH?>/css/jquery.ketchup.css" rel="stylesheet" />
	<link href="<?=SITE_TEMPLATE_PATH?>/css/pages.css" rel="stylesheet" />
	<!--[if lt IE 9]><link href="<?=SITE_TEMPLATE_PATH?>/css/ie.css" rel="stylesheet" /><![endif]-->
	<!--[if lt IE 9]><script src="<?=SITE_TEMPLATE_PATH?>/js/html5.js"></script><![endif]-->
	<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
</head>
<body>
<?$APPLICATION->ShowPanel();?>
	<div class="b-page">
		<header class="b-header">
			<div class="b-wrap">
				<a href="/" class="b-header__logo"></a>
				<?$APPLICATION->IncludeComponent(
					"bitrix:menu", 
					"top_menu", 
					Array(
						"ROOT_MENU_TYPE"	=>	"top",
						"MAX_LEVEL"	=>	"1",
						"USE_EXT"	=>	"N",
						"MENU_CACHE_TYPE" => "A",
						"MENU_CACHE_TIME" => "3600",
						"MENU_CACHE_USE_GROUPS" => "N",
						"MENU_CACHE_GET_VARS" => Array()
					)
				);?>
			</div>
		<?if($APPLICATION->GetCurPage() == "/"):?>
			<?$APPLICATION->IncludeComponent("bitrix:news.list", "index_promo", Array(
				"DISPLAY_DATE" => "N",	// Выводить дату элемента
				"DISPLAY_NAME" => "Y",	// Выводить название элемента
				"DISPLAY_PICTURE" => "N",	// Выводить изображение для анонса
				"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
				"AJAX_MODE" => "N",	// Включить режим AJAX
				"IBLOCK_TYPE" => "text",	// Тип информационного блока (используется только для проверки)
				"IBLOCK_ID" => "1",	// Код информационного блока
				"NEWS_COUNT" => "4",	// Количество новостей на странице
				"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
				"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
				"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
				"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
				"FILTER_NAME" => "",	// Фильтр
				"FIELD_CODE" => "",	// Поля
				"PROPERTY_CODE" => "",	// Свойства
				"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
				"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
				"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
				"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
				"SET_TITLE" => "N",	// Устанавливать заголовок страницы
				"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
				"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
				"PARENT_SECTION" => "",	// ID раздела
				"PARENT_SECTION_CODE" => "",	// Код раздела
				"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
				"CACHE_TYPE" => "A",	// Тип кеширования
				"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
				"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
				"CACHE_GROUPS" => "Y",	// Учитывать права доступа
				"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
				"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
				"PAGER_TITLE" => "Новости",	// Название категорий
				"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
				"PAGER_TEMPLATE" => "",	// Название шаблона
				"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
				"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
				"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
				"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
				"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
				),
				false
			);?>
		<?endif?>
		</header>
		<div class="b-header__decore"></div>
		<?if($_REQUEST["ajax"] == "y") $APPLICATION->RestartBuffer();?>
	