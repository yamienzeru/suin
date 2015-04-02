<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="b-header__promo">
	<div class="b-header__promo__image">
		<div class="b-header__promo__image__img">
		<?$arTrans = array("first", "second", "third", "fourth");
		foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			//$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div class="b-header__promo-text b-header__<?=$arTrans[$keyItem]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" style="display:none;">
				<span class="b-header__title"><?echo $arItem["NAME"]?></span>
				<p class="b-header__text"><?echo $arItem["PREVIEW_TEXT"];?></p>
				<div class="f-button">
					<a class="f-button__text" href="#">Рассчитать КАСКО</a>
				</div>
			</div>
		<?endforeach;?>
				<div class="b-header__promo-decor b-header__promo-decor1"></div>
				<div class="b-header__promo-decor b-header__promo-decor2"></div>
				<div class="b-header__promo-decor b-header__promo-decor3"></div>
				<div class="b-header__promo-decor b-header__promo-decor4"></div>
				<div class="b-header__promo-decor b-header__promo-decor5"></div>
				<div class="b-header__promo-decor b-header__promo-decor6"></div>
				<div class="b-header__promo-decor b-header__promo-decor7"></div>
				<div class="b-header__promo-decor b-header__promo-decor8"></div>
				<div class="b-header__promo-decor b-header__promo-decor9"></div>
				<div class="b-header__promo-decor b-header__promo-decor10"></div>
				<div class="b-header__promo-decor b-header__promo-decor11"></div>
				<div class="b-header__promo-decor b-header__promo-decor12"></div>
				<div class="b-header__promo-decor b-header__promo-decor13"></div>
		</div>
	</div>
	<nav class="b-header__promo__navigation">
	<?foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
		<a href="#" class="b-header__promo__navigation__item<?if(!$keyItem):?> b-header__promo__navigation__item_active<?endif?>" data-slide="<?=($keyItem + 1)?>"></a>
	<?endforeach;?>
	</nav>
	<a class="b-header__promo__arrow" href="#calculation" title="Расчет и оформление">
		<span class="b-header__promo__arrow__icon"></span>
	</a>
</div>