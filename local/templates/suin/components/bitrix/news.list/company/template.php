<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="b-wrap b-wrap_popup">
	<div class="arcticmodal-close"></div>
	<div class="b-company">
		<h1 class="b-company__title"><?$APPLICATION->GetTitle(false)?></h1>
		<?$APPLICATION->IncludeComponent("nzr:modernize.news.list", "company_slider", Array(
			"DISPLAY_DATE" => "N",	// Выводить дату элемента
			"DISPLAY_NAME" => "Y",	// Выводить название элемента
			"DISPLAY_PICTURE" => "N",	// Выводить изображение для анонса
			"DISPLAY_PREVIEW_TEXT" => "N",	// Выводить текст анонса
			"AJAX_MODE" => "N",	// Включить режим AJAX
			"IBLOCK_TYPE" => "text",	// Тип информационного блока (используется только для проверки)
			"IBLOCK_ID" => "4",	// Код информационного блока
			"PARENT_SECTION" => "2",	// ID раздела
			"TEXT_ID" => "",	// Тексты
			"SORT_BY1" => "ID",	// Поле для первой сортировки новостей
			"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
			"FIELD_CODE" => "",	// Поля
			"PROPERTY_CODE" => array(	// Свойства
				0 => "LIST",
			),
			"CACHE_TYPE" => "A",	// Тип кеширования
			"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
			"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
			"CACHE_GROUPS" => "Y",	// Учитывать права доступа
			"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
			"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
			"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
			),
			false
		);?>
		<table class="b-company__list">
			<tr>
			<?foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
				<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
		<?if($keyItem && !($keyItem % 2)):?>
			</tr>
			<tr>
		<?endif?>
				<td class="b-company__list__item<?if($keyItem < 2):?> b-company__list__item_first<?else:?> b-company__list__item_last<?endif?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="b-company__list__item__image">
					<?if(is_array($arItem["PREVIEW_PICTURE"])):?>
						<img src="<?=ResizeImage($arItem["PREVIEW_PICTURE"], 359, 115)?>">
					<?endif;?>
					</div>
					<h2 class="b-company__list__item__title"><?echo $arItem["NAME"];?></h2>
					<p class="b-company__list__item__about"><?echo $arItem["PREVIEW_TEXT"];?></p>
					<p class="b-company__list__item__aspects"><?echo $arItem["DETAIL_TEXT"];?></p>
				</td>
			<?endforeach;?>
			</tr>
		</table>
	</div>
</div>