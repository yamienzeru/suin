<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
			<?$arItem = $arResult["ITEMS"][0];
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
			<div class="b-about__col__slider" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="slides_container">
				<?foreach($arItem["PROPERTIES"]["LIST"]["~VALUE"] as $list):?>
					<div class="b-about__col__slider__slide">
						<span class="b-about__col__slider__slide__text"><?=$list["TEXT"]?></span>
					</div>
				<?endforeach?>
				</div>
			</div>