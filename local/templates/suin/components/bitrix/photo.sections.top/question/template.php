<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//print_p($arResult);?>
<div class="b-wrap b-wrap_popup">
	<div class="arcticmodal-close"></div>
	<div class="b-question">
		<h1 class="b-question__title"><?$APPLICATION->GetTitle(false)?></h1>
		<nav class="b-question__sections">
	<?foreach($arResult["SECTIONS"] as $keySection => $arSection)
		if(count($arSection["ITEMS"])):?>
			<a href="#" class="b-question__sections__item<?if(!$keySection):?> b-question__sections__item_active<?endif?><?if($keySection + 1 == count($arResult["SECTIONS"])):?> b-question__sections__item_last<?endif?>" data-section="<?=$arSection["ID"]?>"><?=$arSection["NAME"]?></a>
		<?endif?>
		</nav>
<?foreach($arResult["SECTIONS"] as $keySection => $arSection)
	if(count($arSection["ITEMS"])):?>
		<?$this->AddEditAction('section_'.$arSection['ID'], $arSection['ADD_ELEMENT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_ADD"), array('ICON' => 'bx-context-toolbar-create-icon'));
		$this->AddEditAction('section_'.$arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
		$this->AddDeleteAction('section_'.$arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BPS_SECTION_DELETE_CONFIRM')));?>
		<div class="b-question__section" data-section="<?=$arSection["ID"]?>" id="<?=$this->GetEditAreaId('section_'.$arSection['ID']);?>">
		<?if(is_array($arSection["UF_LIST"]) && count($arSection["UF_LIST"])):?>
			<div class="b-question__section__slider">
				<div class="slides_container">
				<?foreach($arSection["UF_LIST"] as $list):?>
					<div class="b-question__section__slider__slide">
						<span class="b-question__section__slider__slide__text"><?=$list?></span>
					</div>
				<?endforeach?>
				</div>
			</div>
		<?endif?>
			<div class="b-question__section__question">
			<?foreach($arSection["ITEMS"] as $keyItem => $arItem):?>
				<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BPS_ELEMENT_DELETE_CONFIRM')));?>
				<a href="#" class="b-question__section__question__link" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<span class="b-question__section__question__link__num"><?=($keyItem + 1)?>.</span>
					<span class="b-question__section__question__link__title"><?=$arItem["NAME"]?></span>
					<div class="b-question__section__question__link__answer">
						<div class="b-question__section__question__link__answer__text"><?=$arItem["PREVIEW_TEXT"]?></div>
					</div>
				</a>
			<?endforeach?>
			</div>
		</div>
	<?endif?>
	<?$APPLICATION->IncludeComponent("nzr:modernize.news.list", "question_phone", Array(
		"IBLOCK_TYPE" => "text",	// Тип информационного блока (используется только для проверки)
		"IBLOCK_ID" => "4",	// Код информационного блока
		"PARENT_SECTION" => "1",	// ID раздела
		"TEXT_ID" => array(	// Тексты
			0 => "23",
			1 => "24",
			2 => "",
			3 => "",
		),
		"SORT_BY1" => "ID",	// Поле для первой сортировки новостей
		"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
		"FIELD_CODE" => array(	// Поля
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(	// Свойства
			0 => "",
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
	</div>
</div>