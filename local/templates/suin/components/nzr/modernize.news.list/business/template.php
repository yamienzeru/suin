<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="b-wrap b-wrap_popup">
	<div class="arcticmodal-close"></div>
	<div class="b-business">
		<div class="b-business__top">
			<h1 class="b-business__top__title"><?$APPLICATION->GetTitle(false)?></h1>
			<?$arItem = $arResult["ITEMS"][0];
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
			<div class="b-business__top__slider" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="slides_container">
				<?foreach($arItem["PROPERTIES"]["PHOTOS"]["VALUE"] as $key => $photo):?>
					<div class="b-business__top__slider__slide">
						<img src="<?=ResizeImage($photo, 1000, 333, true)?>">
					</div>
				<?endforeach?>
				</div>
			</div>
		<?if(is_array($arItem["PROPERTIES"]["PHOTOS"]["VALUE"]) && count($arItem["PROPERTIES"]["PHOTOS"]["VALUE"]) > 1):?>
			<div class="b-business__top__decore"></div>
		<?endif?>
		</div>
		<div class="b-business__content">
			<div class="b-business__content__col">
				<div class="b-business__content__col__left">
					<?$arItem = $arResult["ITEMS"][1];
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
					<span class="b-business__content__col__left__percent" id="<?=$this->GetEditAreaId($arItem['ID']);?>"><?echo $arItem["PREVIEW_TEXT"];?><span class="b-business__content__col__left__percent__star">*</span></span>
					<?$arItem = $arResult["ITEMS"][2];
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
					<?if($USER->IsAdmin()):?><div id="<?=$this->GetEditAreaId($arItem['ID']);?>"><?endif?>
					<span class="b-business__content__col__left__percent-sub"><?echo $arItem["NAME"];?></span>
					<div class="b-business__content__col__left__text"><?echo $arItem["PREVIEW_TEXT"];?></div>
					<?if($USER->IsAdmin()):?></div><?endif?>
				</div>
				<?$arItem = $arResult["ITEMS"][3];
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
				<div class="b-business__content__col__right" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<span class="b-business__content__col__right__title"><?echo $arItem["NAME"];?></span>
					<span class="b-business__content__col__right__text"><?echo $arItem["PREVIEW_TEXT"];?></span>
				</div>
			</div>
			<?$arItem = $arResult["ITEMS"][4];
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
			<div class="b-business__content__col" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<span class="b-business__content__col__first-text"><?echo $arItem["PREVIEW_TEXT"];?></span>
			</div>
			<?$arItem = $arResult["ITEMS"][5];
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
			<div class="b-business__content__col b-business__content__col_type" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<span class="b-business__content__col_type__title"><?echo $arItem["NAME"];?></span>
				<div class="b-business__content__col_type__list">
				<?foreach($arItem["PROPERTIES"]["PHOTOS"]["VALUE"] as $key => $photo):?>
					<div class="b-business__content__col_type__list__item">
						<i class="b-business__content__col_type__list__item__image_<?=($key + 1)?>" data-src="<?=ResizeImage($photo, 121, 184)?>"></i>
						<span class="b-business__content__col_type__list__item__text"><?=$arItem["PROPERTIES"]["PHOTOS"]["DESCRIPTION"][$key]?></span>
					</div>
				<?endforeach?>
				</div>
			</div>
			<?$arItem = $arResult["ITEMS"][6];
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
			<div class="b-business__content__col b-business__content__col_security" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<span class="b-business__content__col_security__title"><?echo $arItem["NAME"];?></span>
			<?foreach($arItem["PROPERTIES"]["LIST"]["~VALUE"] as $list):?>
				<span class="b-business__content__col_security__text"><?=$list["TEXT"]?></span>
			<?endforeach?>
			</div>
			<?$arItem = $arResult["ITEMS"][7];
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
			<div class="b-business__content__col b-business__content__col_risk" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<span class="b-business__content__col_risk__title"><?echo $arItem["NAME"];?></span>
				<div class="b-business__content__col_risk__list">
				<?foreach($arItem["PROPERTIES"]["PHOTOS"]["VALUE"] as $key => $photo):?>
					<div class="b-business__content__col_risk__list__item">
						<i class="b-business__content__col_risk__list__item__image_<?=($key + 1)?>" data-src="<?=ResizeImage($photo, 121, 184)?>"></i>
						<span class="b-business__content__col_risk__list_item__text"><?=$arItem["PROPERTIES"]["PHOTOS"]["DESCRIPTION"][$key]?></span>
					</div>
				<?endforeach?>
				</div>
			</div>
			<div class="b-business__content__call">
				<?$arItem = $arResult["ITEMS"][8];
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
				<span class="b-business__content__call__title" id="<?=$this->GetEditAreaId($arItem['ID']);?>"><?echo $arItem["PREVIEW_TEXT"];?></span>
				<?$arItem = $arResult["ITEMS"][9];
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
				<span class="b-business__content__call__phone" id="<?=$this->GetEditAreaId($arItem['ID']);?>"><?echo $arItem["PREVIEW_TEXT"];?></span>
				<span class="b-business__content__call__free">звонок бесплатный</span>
			</div>
		</div>
	</div>
</div>