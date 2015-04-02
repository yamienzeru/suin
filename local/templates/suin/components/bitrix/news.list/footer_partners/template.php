<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
				<span class="b-footer__title">Наши официальные партнеры</span>
				<div class="b-footer__slider">
					<div class="slides_container">
						<div class="b-footer__slider__slide">
						<?foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
							<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
							<div class="b-footer__slider__slide__table__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>"><a href="<?=$arItem["PROPERTIES"]["URL"]["VALUE"];?>" target="_blank"><img src="<?=ResizeImage($arItem["PREVIEW_PICTURE"], 500, 50)?>" alt="<?=$arItem["NAME"];?>" /></a></div>
						<?endforeach;?>
						</div>
					</div>
				</div>