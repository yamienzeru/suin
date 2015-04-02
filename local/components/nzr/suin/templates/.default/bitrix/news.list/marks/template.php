<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
							<?if(strlen($_POST["step2"]) && !$arParams["SELECTED_ID"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title">Марка</span>
							<div class="b-calculation__form__section__bottom__option__marks">
							<?foreach($arResult["ITEMS"] as $keyItem => $arItem):?>
								<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));?>
								<div class="f-radio" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<input class="f-radio__input" type="radio" name="mark" value="<?=$arItem['ID']?>"<?if($arParams["SELECTED_ID"] == $arItem['ID']):?> checked<?endif?> />
									<label><?echo $arItem["NAME"]?></label>
								</div>
							<?endforeach;?>
							</div>