<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="b-wrap b-wrap_popup">
	<div class="arcticmodal-close"></div>
	<div class="b-about">
		<h1 class="b-about__title"><?$APPLICATION->GetTitle(false)?></h1>
		<div class="b-about__col b-about__left">
			<?$APPLICATION->IncludeComponent("nzr:modernize.news.list", "about_percent", Array(
				"IBLOCK_TYPE" => "text",	// Тип информационного блока (используется только для проверки)
				"IBLOCK_ID" => "4",	// Код информационного блока
				"PARENT_SECTION" => "3",	// ID раздела
				"TEXT_ID" => array(	// Тексты
					0 => "30",
					1 => "",
				),
				"SORT_BY1" => "ID",	// Поле для первой сортировки новостей
				"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
				"FIELD_CODE" => array(	// Поля
					0 => "",
					1 => "",
				),
				"PROPERTY_CODE" => array(	// Свойства
					0 => "LIST",
					1 => "",
				),
				"AJAX_MODE" => "N",	// Включить режим AJAX
				"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
				"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
				"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
				"CACHE_TYPE" => "A",	// Тип кеширования
				"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
				"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
				"CACHE_GROUPS" => "Y",	// Учитывать права доступа
				"DISPLAY_DATE" => "N",	// Выводить дату элемента
				"DISPLAY_NAME" => "Y",	// Выводить название элемента
				"DISPLAY_PICTURE" => "N",	// Выводить изображение для анонса
				"DISPLAY_PREVIEW_TEXT" => "N",	// Выводить текст анонса
				"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
				),
				false
			);?>
			<nav class="b-about__col__links">
			<?foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
				<a href="#<?=$this->GetEditAreaId($arItem['ID']);?>" class="b-about__col__links__item"><?echo $arItem["NAME"]?></a>
			<?endforeach;?>
			</nav>
		</div>
		<div class="b-about__col b-about__right">
			<?$APPLICATION->IncludeComponent("nzr:modernize.news.list", "about_slider", Array(
				"IBLOCK_TYPE" => "text",	// Тип информационного блока (используется только для проверки)
				"IBLOCK_ID" => "4",	// Код информационного блока
				"PARENT_SECTION" => "3",	// ID раздела
				"TEXT_ID" => array(	// Тексты
					0 => "39",
					1 => "",
				),
				"SORT_BY1" => "ID",	// Поле для первой сортировки новостей
				"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
				"FIELD_CODE" => array(	// Поля
					0 => "",
					1 => "",
				),
				"PROPERTY_CODE" => array(	// Свойства
					0 => "LIST",
					1 => "",
				),
				"AJAX_MODE" => "N",	// Включить режим AJAX
				"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
				"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
				"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
				"CACHE_TYPE" => "A",	// Тип кеширования
				"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
				"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
				"CACHE_GROUPS" => "Y",	// Учитывать права доступа
				"DISPLAY_DATE" => "N",	// Выводить дату элемента
				"DISPLAY_NAME" => "Y",	// Выводить название элемента
				"DISPLAY_PICTURE" => "N",	// Выводить изображение для анонса
				"DISPLAY_PREVIEW_TEXT" => "N",	// Выводить текст анонса
				"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
				),
				false
			);?>
		<?foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div class="b-about__col__text-block" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<span class="b-about__col__text-block__title"><?echo $arItem["NAME"]?></span>
				<p class="b-about__col__text-block__text"><?echo $arItem["PREVIEW_TEXT"];?></p>
			</div>
		<?endforeach;?>
		</div>
	</div>
</div>