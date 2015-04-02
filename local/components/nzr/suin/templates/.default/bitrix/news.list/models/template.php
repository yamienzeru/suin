<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
							<?if(strlen($_POST["step2"]) && !$arParams["SELECTED_ID"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title">Модель</span>
							<ul class="b-calculation__form__section__bottom__option__line">
						<?foreach($arResult["ITEMS"] as $arItem)
							if($arItem["HIDE"] !== "Y"):?>
								<li class="b-calculation__form__section__bottom__option__line__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="model" value="<?=$arItem['ID']?>"<?if($arParams["SELECTED_ID"] == $arItem['ID']):?> checked<?endif?> />
										<label><?echo $arItem["NAME"]?></label>
									</div>
								</li>
							<?endif;?>
							</ul>