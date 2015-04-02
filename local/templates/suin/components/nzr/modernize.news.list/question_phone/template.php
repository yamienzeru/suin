<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
			<div class="b-business__content__call">
				<?$arItem = $arResult["ITEMS"][0];
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
				<span class="b-business__content__call__title" id="<?=$this->GetEditAreaId($arItem['ID']);?>"><?echo $arItem["PREVIEW_TEXT"];?></span>
				<?/*<?$arItem = $arResult["ITEMS"][1];
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
				<span class="b-business__content__call__phone" id="<?=$this->GetEditAreaId($arItem['ID']);?>"><?echo $arItem["PREVIEW_TEXT"];?></span>*/?>
				<span class="b-business__content__call__phone"><?=COption::GetOptionString("nzr.suin", "SUIN_PHONE")?></span>
				<span class="b-business__content__call__free">звонок бесплатный</span>
			</div>