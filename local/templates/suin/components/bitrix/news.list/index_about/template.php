<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
		<section class="b-section-about">
			<div class="b-wrap">
				<h2 class="b-section-about__title">Sureinsure – первый и единственный в России<br /> полномасштабный страховой дискаунтер.</h2>
				<div class="b-section-about__list">
				<? $index = 1; ?>
				<?foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
					<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
					<div class="b-section-about__list__item" data-about="<?=$index?>" data-src="<?=ResizeImage($arItem["PREVIEW_PICTURE"], 113, 80)?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<p class="b-section-about__list__item__text"><?echo $arItem["PREVIEW_TEXT"];?></p>
						<i class="b-section-about__list__item__circle"></i>
						<i class="b-section-about__list__item__ico"><?=($keyItem + 1)?></i>
						<p class="b-section-about__list__item__text_hover"><?echo $arItem["DETAIL_TEXT"];?></p>
						<? $index++ ?>
					</div>
				<?endforeach;?>
				</div>
			</div>
		</section>