<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
			<?$arItem = $arResult["ITEMS"][0];
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
			<div class="b-about__col__percent" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<i class="b-about__col__percent__circle"><?=$arItem["NAME"]?><span class="b-about__col__percent__circle__percent">*</span></i>
				<span class="b-about__col__percent__text">*<?=$arItem["PREVIEW_TEXT"]?></span>
			</div>