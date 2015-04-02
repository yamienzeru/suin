<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(is_array($arResult["HELPS"]))
	foreach($arResult["HELPS"] as &$arHelp)
		$arHelp["DISPLAY"] = "			<div class=\"b-calculation__form__section__bottom__hint\">
				<span class=\"b-calculation__form__section__bottom__hint__title\">".$arHelp["NAME"]."</span>
				<p>".$arHelp["DETAIL_TEXT"]."</p>".($arHelp["DETAIL_PICTURE"] ? "
				<p><img src=\"".ResizeImage($arHelp["DETAIL_PICTURE"], 200, 600)."\" /></p>" : "")."
			</div>";
?>
	<section class="b-calculation" id="calculation">
		<div class="b-wrap">
			<div class="b-calculation__hot-line">
				<span class="b-calculation__hot-line__phone"><?=COption::GetOptionString("nzr.suin", "SUIN_PHONE")?></span>
				<span class="b-calculation__hot-line__call">звонок бесплатный</span>
			</div>
			<h1 class="b-calculation__title">Расчет и оформление полиса КАСКО</h1>
			<div class="b-calculation__steps">
				<div class="b-calculation__steps__line">
					<span class="b-calculation__steps__line__step" data-step="<?if(is_array($arResult["SUIN"])):?>2<?else:?>1<?endif?>"></span>
				</div>
				<span class="b-calculation__steps__first">расчет</span>
				<span class="b-calculation__steps__second">выбор варианта</span>
				<span class="b-calculation__steps__third">способ получения полиса</span>
			</div>
			<form class="b-calculation__form form_calc" action="/" method="post">
			<?if($_REQUEST["is_ajax"] == "Y") $APPLICATION->RestartBuffer();?>
			<?//print_p($arResult["DATA"]);?>
				<div class="b-calculation__form__section b-calculation__form__section_first">
					<div class="b-calculation__form__section__top">
						<i class="b-calculation__form__section__top__arrow"></i>
						<i class="b-calculation__form__section__top__ico b-calculation__form__section__top__ico__first">Автомобиль</i>
						<span class="b-calculation__form__section__top__edit">изменить</span>
						<div class="b-calculation__form__section__top__choice">
							<span class="b-calculation__form__section__top__choice__title">Ваш выбор:</span>
							<span class="b-calculation__form__section__top__choice__options"></span>
						</div>
					</div>
					<div class="b-calculation__form__section__bottom">
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["mark"]):?> b-calculation__form__section__bottom__option_error<?endif?> js-ajax">
						<?=$arResult["HELPS"]["mark"]["DISPLAY"]?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:news.list",
							"marks",
							Array(
								"IBLOCK_TYPE"	=>	$arParams["MARKS_IBLOCK_TYPE"],
								"IBLOCK_ID"	=>	$arParams["MARKS_IBLOCK_ID"],
								"NEWS_COUNT"	=>	5000,
								"SORT_BY1"	=>	$arParams["MARKS_SORT_BY"],
								"SORT_ORDER1"	=>	$arParams["MARKS_SORT_ORDER"],
								"SORT_BY2"	=>	$arParams["MARKS_SORT_BY"],
								"SORT_ORDER2"	=>	$arParams["MARKS_SORT_ORDER"],
								"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
								"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
								"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"SELECTED_ID" => strlen($_POST["step2"]) ? (int) $arResult["DATA"]["mark"] : $arResult["DATA"]["mark"],
							),
							$component
						);?>
						</div>
					<?if($arResult["DATA"]["mark"] > 0):
						GLOBAL $arrModel;
						$arrModel = array("PROPERTY_".$arParams["MODELS_MARK"] => $arResult["DATA"]["mark"]);?>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["model"]):?> b-calculation__form__section__bottom__option_error<?endif?>">
						<?=$arResult["HELPS"]["model"]["DISPLAY"]?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:news.list",
							"models",
							Array(
								"IBLOCK_TYPE"	=>	$arParams["MODELS_IBLOCK_TYPE"],
								"IBLOCK_ID"	=>	$arParams["MODELS_IBLOCK_ID"],
								"NEWS_COUNT"	=>	5000,
								"SORT_BY1"	=>	$arParams["MODELS_SORT_BY"],
								"SORT_ORDER1"	=>	$arParams["MODELS_SORT_ORDER"],
								"SORT_BY2"	=>	$arParams["MODELS_SORT_BY"],
								"SORT_ORDER2"	=>	$arParams["MODELS_SORT_ORDER"],
								"CACHE_TYPE"	=>	"N",
								"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
								"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"FILTER_NAME" => "arrModel",
								"SELECTED_ID" => $arResult["DATA"]["model"],
								//"SELECTED_ID" => strlen($_POST["step2"]) ? (int) $arResult["DATA"]["model"] : $arResult["DATA"]["model"],
							),
							$component
						);?>
						</div>
					<?endif?>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["year"]):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?=$arResult["HELPS"]["year"]["DISPLAY"]?>
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["year"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title">Год выпуска</span>
							<ul class="b-calculation__form__section__bottom__option__line">
							<?for($year = (int) date('Y'); $year > $arParams["YEAR_START"] - 1; $year--):?>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="year" value="<?=$year?>"<?if($arResult["DATA"]["year"] == $year):?> checked<?endif?> />
										<label><?=$year?></label>
									</div>
								</li>
							<?endfor?>
							</ul>
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<div class="b-calculation__form__section__bottom__option__col">
								<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Мощность, л.с.:</span>
								<input class="f-input js-number-input" type="text" name="power" value="<?=$arResult["DATA"]["power"]?>" data-min="30" data-max="1500" />
							</div>
							<?/*<div class="b-calculation__form__section__bottom__option__col">
								<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Объем двигателя, л.:</span>
								<input class="f-input js-number-input" type="text" name="litr" value="<?=$arResult["DATA"]["litr"]?>" data-min="0.4" data-max="10" />
							</div>*/?>
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Регион регистрации ТС</span>
							<span class="b-calculation__form__section__bottom__option__city">Москва</span>
						</div>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["cost"]):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?=$arResult["HELPS"]["cost"]["DISPLAY"]?>
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["cost"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Оценочная стоимость ТС (руб.)</span>
							<input class="f-input js-number-input" type="text" name="cost" value="<?=$arResult["DATA"]["cost"]?>" data-min="10000" data-max="1000000000" />
						</div>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["date"]):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?=$arResult["HELPS"]["date"]["DISPLAY"]?>
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["date"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Дата начала использования ТС:</span>
							<input class="f-input b-calculation__form__section__bottom__option__date" type="text" name="date" value="<?=$arResult["DATA"]["date"]?>" size="14" readonly="readonly" />
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<?=$arResult["HELPS"]["credit"]["DISPLAY"]?>
							<div class="b-calculation__form__section__bottom__option__col">
								<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">ТС куплено в кредит?</span>
								<ul class="b-calculation__form__section__bottom__option__line">
									<li class="b-calculation__form__section__bottom__option__line__item">
										<div class="f-radio">
											<input class="f-radio__input" type="radio" name="credit" value="1"<?if($arResult["DATA"]["credit"] == 1):?> checked<?endif?> />
											<label>Да</label>
										</div>
									</li>
									<li class="b-calculation__form__section__bottom__option__line__item">
										<div class="f-radio">
											<input class="f-radio__input" type="radio" name="credit" value="2"<?if($arResult["DATA"]["credit"] == 2):?> checked<?endif?> />
											<label>Нет</label>
										</div>
									</li>
								</ul>
							</div>
							<?/*<div class="b-calculation__form__section__bottom__option__col">
								<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Банк:</span>
								<select class="f-select" name="credit_bank">
									<option value="0"></option>
									<option>Альфа-Банк</option>
									<option>Сбербанк</option>
									<option>ВТБ 24</option>
									<option>Уралсиб</option>
									<option>Уральский банк реконструкции и развития</option>
								</select>
							</div>*/?>
						</div>
					</div>
				</div>
				<div class="b-calculation__form__section b-calculation__form__section_second">
					<div class="b-calculation__form__section__top">
						<i class="b-calculation__form__section__top__arrow"></i>
						<i class="b-calculation__form__section__top__ico b-calculation__form__section__top__ico__second">Водитель</i>
						<span class="b-calculation__form__section__top__edit">изменить</span>
						<div class="b-calculation__form__section__top__choice">
							<span class="b-calculation__form__section__top__choice__title">Ваш выбор:</span>
							<span class="b-calculation__form__section__top__choice__options"></span>
						</div>
					</div>
					<div class="b-calculation__form__section__bottom">
						<?=$arResult["HELPS"]["people-type"]["DISPLAY"]?>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["people-type"]):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["people-type"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Собственник ТС:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-type" value="1"<?if($arResult["DATA"]["people-type"] == 1):?> checked<?endif?> />
										<label>Физ. лицо</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-type" value="2"<?if($arResult["DATA"]["people-type"] == 2):?> checked<?endif?> />
										<label>Юр. лицо</label>
									</div>
								</li>
							</ul>
						</div>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["people-num"]):?> b-calculation__form__section__bottom__option_error<?endif?> js-ajax">
							<?=$arResult["HELPS"]["people-num"]["DISPLAY"]?>
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["people-num"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Кол-во водителей:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
							<?foreach($arResult["CALC_PARAMS"]["people-num"] as $key => $val):?>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-num" value="<?=$key?>"<?if($arResult["DATA"]["people-num"] == $key):?> checked<?endif?> />
										<label><?=$val?></label>
									</div>
								</li>
							<?endforeach?>
							</ul>
						</div>
						<?/*<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Пол:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-gender" value="1"<?if($arResult["DATA"]["people-gender"] == 1):?> checked<?endif?> />
										<label>мужчина</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-gender" value="2"<?if($arResult["DATA"]["people-gender"] == 2):?> checked<?endif?> />
										<label>женщина</label>
									</div>
								</li>
							</ul>
						</div>*/?>
				<?if($arResult["DATA"]["people-num"] == 100):
					$people_year = is_array($arResult["DATA"]["people-year"]) ? min($arResult["DATA"]["people-year"]) : $arResult["DATA"]["people-year"];
					$people_driving = is_array($arResult["DATA"]["people-driving"]) ? min($arResult["DATA"]["people-driving"]) : $arResult["DATA"]["people-driving"];?>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && (!$arResult["DATA"]["people-year"])):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?=$arResult["HELPS"]["people-year"]["DISPLAY"]?>
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["people-year"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Количество полных лет: (младшему водителю)</span>
							<input class="f-input js-number-input js-driver-age" type="text" name="people-year" value="<?=$people_year?>" data-min="18" data-max="70" />
						</div>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && (!$arResult["DATA"]["people-driving"] || ($people_driving > ($people_year - 18)))):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?=$arResult["HELPS"]["people-driving"]["DISPLAY"]?>
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["people-driving"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<?if(strlen($_POST["step2"]) && ($people_driving > ($people_year - 18)) && strlen($people_year)):?><div class="b-calculation__form__section__bottom__option__error">При возрасте <?=$people_year?> <?=getWord($people_year, array('год', 'года', 'лет'))?> Ваш стаж не может составлять <?=$people_driving?> <?=getWord($people_driving, array('год', 'года', 'лет'))?></div><?endif?>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Водительский стаж: (наименьший)</span>
							<input class="f-input js-number-input js-driver-exp" type="text" name="people-driving" value="<?=$people_driving?>" data-min="0" data-max="54" />
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Пол:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-gender" value="0"<?if(!$arResult["DATA"]["people-gender"]):?> checked<?endif?> />
										<label>мужчина</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-gender" value="1"<?if($arResult["DATA"]["people-gender"]):?> checked<?endif?> />
										<label>женщина</label>
									</div>
								</li>
							</ul>
						</div>
				<?elseif($arResult["DATA"]["people-num"]):
					for($peopn = 0; $peopn < $arResult["DATA"]["people-num"]; $peopn++):
						$people_year = !is_array($arResult["DATA"]["people-year"]) && !$peopn ? $arResult["DATA"]["people-year"] : $arResult["DATA"]["people-year"][$peopn];
						$people_driving = !is_array($arResult["DATA"]["people-driving"]) && !$peopn ? $arResult["DATA"]["people-driving"] : $arResult["DATA"]["people-driving"][$peopn];
						$people_gender = !is_array($arResult["DATA"]["people-gender"]) && !$peopn ? $arResult["DATA"]["people-gender"] : $arResult["DATA"]["people-gender"][$peopn];?>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && (!$arResult["DATA"]["people-year"][$peopn] || !$arResult["DATA"]["people-driving"][$peopn] || ($people_driving > ($people_year - 18)))):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?if(strlen($_POST["step2"]) && (!$people_year || !$people_driving)):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<?if(strlen($_POST["step2"]) && ($people_driving > ($people_year - 18)) && strlen($people_year)):?><div class="b-calculation__form__section__bottom__option__error">При возрасте <?=$people_year?> <?=getWord($people_year, array('год', 'года', 'лет'))?> Ваш стаж не может составлять <?=$people_driving?> <?=getWord($people_driving, array('год', 'года', 'лет'))?></div><?endif?>
							<span class="b-calculation__form__section__bottom__option__name">Водитель <?=($peopn + 1)?></span>
							<div class="b-calculation__form__section__bottom__option__col">
								<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Возраст, лет:</span>
								<input class="f-input js-number-input js-driver-age" type="text" name="people-year[<?=$peopn?>]" value="<?=$people_year?>" data-min="18" data-max="70" size="3" />
							</div>
							<div class="b-calculation__form__section__bottom__option__col">
								<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Стаж, лет:</span>
								<input class="f-input js-number-input js-driver-exp" type="text" name="people-driving[<?=$peopn?>]" value="<?=$people_driving?>" data-min="0" data-max="54" size="3" />
							</div>
							<div class="b-calculation__form__section__bottom__option__col">
								<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Пол:</span>
								<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-gender[<?=$peopn?>]" value="0"<?if(!$people_gender):?> checked<?endif?> />
										<label>мужчина</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="people-gender[<?=$peopn?>]" value="1"<?if($people_gender):?> checked<?endif?> />
										<label>женщина</label>
									</div>
								</li>
							</ul>
							</div>
						</div>
					<?endfor;
				endif;?>
						<div class="b-calculation__form__section__bottom__note js-driver-note">
							<b>Компания AIG</b> не осуществляет страхование для водителей <b>младше 21 года</b> и со <b>стажем 1 год</b>
						</div>
					</div>
				</div>
				<div class="b-calculation__form__section b-calculation__form__section_third">
					<div class="b-calculation__form__section__top">
						<i class="b-calculation__form__section__top__arrow"></i>
						<i class="b-calculation__form__section__top__ico b-calculation__form__section__top__ico__third">Дополнительная информация</i>
						<span class="b-calculation__form__section__top__edit">изменить</span>
						<div class="b-calculation__form__section__top__choice">
							<span class="b-calculation__form__section__top__choice__title">Ваш выбор:</span>
							<span class="b-calculation__form__section__top__choice__options"></span>
						</div>
					</div>
					<div class="b-calculation__form__section__bottom">
						<?/*<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Руль:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="rudder" value="1"<?if($arResult["DATA"]["rudder"] == 1):?> checked<?endif?> />
										<label>Левый (европейский)</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="rudder" value="1"<?if($arResult["DATA"]["rudder"] == 1):?> checked<?endif?> />
										<label>Правый (японский)</label>
									</div>
								</li>
							</ul>
						</div>*/?>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["risk"]):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?=$arResult["HELPS"]["risk"]["DISPLAY"]?>
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["risk"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__name">Способы экономии</span>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Страховое покрытие</span>
							<ul class="b-calculation__form__section__bottom__option__line">
							<?foreach($arResult["CALC_PARAMS"]["risk"] as $key => $val):?>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="risk" value="<?=$key?>"<?if($arResult["DATA"]["risk"] == $key):?> checked<?endif?> />
										<label><?=$val?></label>
									</div>
								</li>
							<?endforeach?>
							</ul>
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<?=$arResult["HELPS"]["franshiza"]["DISPLAY"]?>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Франшиза, руб.:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
							<?foreach($arResult["CALC_PARAMS"]["franshiza"] as $key => $val):?>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="franshiza" value="<?=$key?>"<?if($arResult["DATA"]["franshiza"] == $key):?> checked<?endif?> />
										<label><?=$val?></label>
									</div>
								</li>
							<?endforeach?>
							</ul>
						</div>
						<?/*<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Противоугонная система:</span>
							<span>
							  	<select class="f-select" name="security">
									<option value="0"></option>
									<option>Ivics</option>
									<option>StarLine</option>
								</select>
							</span>
							<span>
								<select class="f-select" name="secur">
									<option value="0"></option>
									<option>iVICS TELECOM</option>
									<option>StarLine B94 CAN GSM/GPS</option>
								</select>
							</span>
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Поисковая система:</span>
							<select class="f-select" name="searchsystem">
								<option value="0"></option>
								<option>Не установлена</option>
								<option>Поисковая система 1</option>
								<option>Поисковая система 2</option>
							</select>
						</div>*/?>
						<div class="b-calculation__form__section__bottom__option<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["repair"]):?> b-calculation__form__section__bottom__option_error<?endif?>">
							<?=$arResult["HELPS"]["repair"]["DISPLAY"]?>
							<?if(strlen($_POST["step2"]) && !$arResult["DATA"]["repair"]):?><div class="b-calculation__form__section__bottom__option__error">Это поле обязательно для заполнения</div><?endif?>
							<span class="b-calculation__form__section__bottom__option__name">Дополнительные услуги и опции</span>
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Способ урегулирования:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
							<?foreach($arResult["CALC_PARAMS"]["repair"] as $key => $val):?>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="repair" value="<?=$key?>"<?if($arResult["DATA"]["repair"] == $key):?> checked<?endif?> />
										<label><?=$val?></label>
									</div>
								</li>
							<?endforeach?>
							</ul>
							<?/*<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Аварийный комиссар:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="commissioner" />
										<label>Не важно</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="commissioner" />
										<label>Только при ДТП</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="commissioner" />
										<label>Всегда</label>
									</div>
								</li>
							</ul>*/?>
						</div>
						<?/*<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Возмещение ущерба:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="repair" />
										<label>Ремонт на СТОА по направлению СК</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="repair" />
										<label>Ремонт на СТОА официального дилера по направлению СК</label>
									</div>
								</li>
							</ul>
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Эвакуация автомобиля:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="evacuation" />
										<label>Нет</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="evacuation" />
										<label>Да</label>
									</div>
								</li>
							</ul>
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Техпомощь:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="tech-help" />
										<label>Нет</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="tech-help" />
										<label>Да</label>
									</div>
								</li>
							</ul>
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Выплата без справок ГИБДД:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="pay" />
										<label>Нет</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="pay" />
										<label>Только стелка</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="pay" />
										<label>Стекла + кузов</label>
									</div>
								</li>
							</ul>
						</div>
						<div class="b-calculation__form__section__bottom__option">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Расширение ОСАГО, руб.:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="dsago" />
										<label>Нет</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="dsago" />
										<label>до 500 000</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="dsago" />
										<label>до 1 000 000</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="dsago" />
										<label>до 1 500 000</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="dsago" />
										<label>до 3 000 000</label>
									</div>
								</li>
							</ul>
						</div>
						<div class="b-calculation__form__section__bottom__option b-calculation__form__section__bottom__option_no-bottom">
							<span class="b-calculation__form__section__bottom__option__title b-calculation__form__section__bottom__option__title_inline">Расширение ОСАГО, руб.:</span>
							<ul class="b-calculation__form__section__bottom__option__line">
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="pay-type" />
										<label>Без рассрочки</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="pay-type" />
										<label>Рассрочка 50%/50%</label>
									</div>
								</li>
								<li class="b-calculation__form__section__bottom__option__line__item">
									<div class="f-radio">
										<input class="f-radio__input" type="radio" name="pay-type" />
										<label>Максимальная рассрочка</label>
									</div>
								</li>
							</ul>
						</div>*/?>
					</div>
				</div>
				<div class="f-button">
					<input type="submit" value="Рассчитать" class="f-button__text" name="step2" />
				</div>
				<?if($_REQUEST["is_ajax"] == "Y") die();?>
			</form>
			<div class="b-calculation__loading"></div>
			<div class="b-calculation__overlay"></div>
		</div>
	</section>
<?if(is_array($arResult["SUIN"])):?>
	<section class="b-result">
		<div class="b-wrap">
			<h2 class="b-result__title">Результаты</h2>
			<span class="b-result__number">(Номер расчёта: <?=$arResult["CALC_ID"]?>)</span>
			<?/*<div class="b-result__sort">
				<span class="b-result__sort__text">Сортировать</span>
				<select class="f-select b-result__sort__select">
					<option>от низкой цены к высокой</option>
					<option>от высокой цены к низкой</option>
					<option>по страховщику</option>
				</select>
			</div>*/?>
			<table class="b-result__list">
				<thead>
					<tr class="b-result__list__head">
						<th class="b-result__list__head__item">страховщик</th>
						<th class="b-result__list__head__item">пакет</th>
						<th class="b-result__list__head__item">цена в страховой</th>
						<th class="b-result__list__head__item"><strong>цена с нашей скидкой</strong></th>
						<th class="b-result__list__head__item"></th>
					</tr>
				</thead>
				<tbody>
			<?foreach($arResult["SUIN"] as $keySuin => $arSuin)
				if(is_array($arSuin["RESULT"])):?>
					<tr class="b-result__list__col">
						<td class="b-result__list__col__item b-result__list__col__item_image"<?if(strlen($arSuin["RESULT"]["MESSAGE"])):?> rowspan="2"<?endif?>>
							<img src="<?=ResizeImage($arResult["SUIN_INFO"][$arSuin["CODE"]]["LOGO"], 165, 50)?>">
						</td>
						<td class="b-result__list__col__item"<?if(strlen($arSuin["RESULT"]["MESSAGE"])):?> rowspan="2"<?endif?>>
							<span class="b-result__list__company"><?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["NAME"]?></span>
							<span class="b-result__list__rate"><?=$arSuin["RESULT"]["NAME"]?></span>
						<?if(!is_array($arSuin["RESULT"]["ERROR"])):?>
							<a href="#" class="b-result__list__more b-modal" data-page="/result/<?=$arResult["CALC_ID"]?>/?suin=<?=$keySuin?>&ajax=y">подробнее</a>
						<?endif?>
						</td>
					<?if(is_array($arSuin["RESULT"]["ERROR"])):?>
						<td class="b-result__list__col__item b-result__list__col__item_notices" colspan="3">
						<?foreach($arSuin["RESULT"]["ERROR"] as $error):?>
							<div class="b-notice b-notice_warning"><?=$error?></div>
						<?endforeach?>
 						</td>
					<?elseif($arSuin["RESULT"]["PRICE"]):?>
						<td class="b-result__list__col__item">
							<strike class="b-result__list__old-price"><?=PriceFormat($arSuin["RESULT"]["PRICE"])?> руб.</strike>
						</td>
						<td class="b-result__list__col__item">
							<span class="b-result__list__new-price"><?=PriceFormat(round($arSuin["RESULT"]["PRICE"] * $arResult["SALE"]))?> руб.</span>
						</td>
						<td class="b-result__list__col__item">
							<div class="b-result__list__col__item__block">
								<span class="b-result__list__col__item__button">Заказать</span>
								<a class="f-button f-button_hover f-button_result" href="/order/<?=$arResult["CALC_ID"]?>/<?=$keySuin?>/">
									<span class="f-button__text">Заказать</span>
								</a>
							</div>
						</td>
					<?endif?>
					</tr>
				<?if(strlen($arSuin["RESULT"]["MESSAGE"])):?>
					<tr class="b-result__list__col">
						<td class="b-result__list__col__item b-result__list__col__item_notices" colspan="3">
							<div class="b-notice b-notice_warning"><?=$arSuin["RESULT"]["MESSAGE"]?></div>
 						</td>
					</tr>
				<?endif?>
				<?endif?>
				</tbody>
			</table>
		</div>
	</section>
<?endif?>