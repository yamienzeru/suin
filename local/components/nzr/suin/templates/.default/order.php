<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$arSuin = $arResult["SUIN"][$_REQUEST["suin"]]?>
	<section class="b-calculation b-calculation__step-3">
		<div class="b-wrap">
			<div class="b-calculation__hot-line">
				<span class="b-calculation__hot-line__phone">8 800 220 00 20</span>
				<span class="b-calculation__hot-line__call">звонок бесплатный</span>
			</div>
			<h1 class="b-calculation__title">способ получения полиса каско</h1>
			<div class="b-calculation__steps">
				<div class="b-calculation__steps__line">
					<span class="b-calculation__steps__line__step" data-step="3"></span>
				</div>
				<span class="b-calculation__steps__first">расчет</span>
				<span class="b-calculation__steps__second">выбор варианта</span>
				<span class="b-calculation__steps__third">способ получения полиса</span>
			</div>
			<h2 class="b-calculation__title b-calculation__title_second">выберите удобный для вас способ <br />оформления полиса</h2>
			<div class="b-calculation__step-3">
				<div class="b-calculation__step-3__col">
					<span class="b-calculation__step-3__col__title">приехать в наш офис sureinsure</span>
					<span class="b-calculation__step-3__col__user-num">Ваш номер расчёта: <span class="b-calculation__step-3__col__user-num__value"><?=$arResult["CALC_ID"]?>*</span></span>
					<p class="b-calculation__step-3__col__text">адрес:</p>
					<p class="b-calculation__step-3__col__text_bold"><?=$arResult["SUIN_INFO"]["sis"]["ADDRESS"]?></p>
					<p class="b-calculation__step-3__col__text">часы работы:</p>
					<p class="b-calculation__step-3__col__text_bold"><?=$arResult["SUIN_INFO"]["sis"]["WORK"]?></p>
					<form class="b-calculation__step-3__col__form js-agent-form">
						<div class="b-calculation__step-3__col__form__cell">
							<span class="b-calculation__step-3__col__form__cell__title">Ваши данные:</span>
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Имя" name="info[name]" data-validate="validate(required)" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Телефон" name="info[phone]" data-validate="validate(required)" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="E-mail" name="info[email]" />
						</div>
						<div class="f-button b-calculation__step-3__button">
							<button type="submit" class="f-button__text">оформить полис в офисе sureinsure</button>
						</div>
						<a href="#" class="b-modal b-modal_end hidden" data-page="<?=$APPLICATION->GetCurPageParam("ajax=y&send=y&type=1", array("OTHER"))?>"></a>
					</form>
					<p class="b-calculation__step-3__col__text">*Данные Вашего заказа будут автоматически отправлены сотруднику нашей компании для подтверждения условий и согласования оплаты.</p>
				</div>
				<div class="b-calculation__step-3__col">
					<span class="b-calculation__step-3__col__title">Заказать выезд агента</span>
					<form class="b-calculation__step-3__col__form js-agent-form">
						<div class="b-calculation__step-3__col__form__cell">
							<span class="b-calculation__step-3__col__form__cell__title">Данные страхователя:</span>
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Имя" name="info[name]" data-validate="validate(required)" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Телефон" name="info[phone]" data-validate="validate(required)" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="E-mail" name="info[email]" />
						</div>
						<div class="b-calculation__step-3__col__form__cell">
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Фамилия" name="info[lname]" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Отчество" name="info[sname]" />
						</div>
						<div class="b-calculation__step-3__col__form__cell">
							<span class="b-calculation__step-3__col__form__cell__title">Данные собственника:</span>
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Фамилия" name="info[owner-lname]" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Имя" name="info[owner-name]" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Отчество" name="info[owner-sname]" />
						</div>
					<?$pn = $arResult["DATA"]["people-num"] == 100 ? 1 : $arResult["DATA"]["people-num"];
					for($drn = 0; $drn < $pn; $drn++):?>
						<div class="b-calculation__step-3__col__form__cell" data-cell="driver">
							<span class="b-calculation__step-3__col__form__cell__title">Данные <?if($arResult["DATA"]["people-num"] > 1):?><span class="b-calculation__step-3__col__form__cell__title__driver-num"><?=($drn + 1)?></span><?endif?> водителя:</span>
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Фамилия" name="info[people-lname][]" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Имя" name="info[people-name][]" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Отчество" name="info[people-sname][]" />
						</div>
					<?endfor?>
						<div class="b-calculation__step-3__col__form__cell__more-driver"></div>
						<div class="b-calculation__step-3__col__form__cell">
							<?if($arResult["DATA"]["people-num"] == 100):?><a class="b-calculation__step-3__col__form__cell__add" href="#">+ добавить водителя</a><?endif?>
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input js-number-input" data-no-range="true" placeholder="Номер водительского удостоверения" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input js-number-input" data-no-range="true" placeholder="Номер ПТС" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input js-number-input" data-no-range="true" placeholder="Номер СТС" />
						</div>
						<?/*<div class="b-calculation__step-3__col__form__cell">
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Телефон" name="info[phone]" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="E-mail" name="info[email]" data-validate="validate(required,email)" />
						</div>*/?>
						<div class="f-button b-calculation__step-3__button">
							<button class="f-button__text" type="submit">вызвать агента</button>
						</div>
						<a href="#" class="b-modal b-modal_end hidden" data-page="<?=$APPLICATION->GetCurPageParam("ajax=y&send=y&type=2", array("OTHER"))?>"></a>
					</form>
				</div>
				<div class="b-calculation__step-3__col">
					<span class="b-calculation__step-3__col__title">приехать в офис страховой компании</span>
					<span class="b-calculation__step-3__col__user-num">Ваш номер расчёта: <span class="b-calculation__step-3__col__user-num__value"><?=$arResult["CALC_ID"]?>*</span></span>
					<p class="b-calculation__step-3__col__text">адрес:</p>
					<p class="b-calculation__step-3__col__text_bold"><?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["ADDRESS"]?></p>
					<p class="b-calculation__step-3__col__text">часы работы:</p>
					<p class="b-calculation__step-3__col__text_bold"><?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["WORK"]?></p>
					<form class="b-calculation__step-3__col__form js-agent-form">
						<div class="b-calculation__step-3__col__form__cell">
							<span class="b-calculation__step-3__col__form__cell__title">Ваши данные:</span>
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Имя" name="info[name]" data-validate="validate(required)" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="Телефон" name="info[phone]" data-validate="validate(required)" />
							<input type="text" class="f-input b-calculation__step-3__col__form__cell__input" placeholder="E-mail" name="info[email]" />
						</div>
						<div class="f-button b-calculation__step-3__button">
							<button type="submit" class="f-button__text">оформить полис в офисе страховой</button>
						</div>
						<a href="#" class="b-modal b-modal_end hidden" data-page="<?=$APPLICATION->GetCurPageParam("ajax=y&send=y&type=3", array("OTHER"))?>"></a>
					</form>
					<p class="b-calculation__step-3__col__text">*Данные Вашего заказа будут автоматически отправлены сотруднику компании <?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["NAME"]?> для подтверждения условий и согласования оплаты.</p>
				</div>
			</div>
		</div>
	</section>