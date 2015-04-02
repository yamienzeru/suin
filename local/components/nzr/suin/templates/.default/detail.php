<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="b-wrap b-wrap_popup">
	<div class="arcticmodal-close"></div>
	<h1 class="b-more__title">Ваш полис</h1>
	<div class="b-more b-more_first">
		<div class="b-more_first__left">
			<span class="b-more_first__left__title">Данные для расчета</span>
			<div class="b-more_first__left__col">
				<i class="b-more_first__left__col__ico_first"></i>
				<div class="b-more_first__left__col__about">
					<span class="b-more_first__left__col__about__type">Автомобиль</span>
					<span class="b-more_first__left__col__about__text">
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["mark"]?></span>
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["model"]?></span>
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["year"]?></span>
						<?/*<span class="b-more_first__left__col__about__text__item">VIN 25YFD21547343</span>*/?>
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["power"]?> л.с.</span>
						<span class="b-more_first__left__col__about__text__item">Москва</span>
						<span class="b-more_first__left__col__about__text__item"><?=PriceFormat($arResult["DATA_PINT"]["cost"])?> руб.</span>
						<?/*<span class="b-more_first__left__col__about__text__item">Газпромбанк</span>*/?>
					</span>
				</div>
			</div>
			<div class="b-more_first__left__col">
				<i class="b-more_first__left__col__ico_second"></i>
				<div class="b-more_first__left__col__about">
					<span class="b-more_first__left__col__about__type">Водитель</span>
					<span class="b-more_first__left__col__about__text">
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["people-type"]?></span>
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["people-num"]?></span>
						<?/*<span class="b-more_first__left__col__about__text__item">мужчина</span>*/?>
				<?if(is_array($arResult["DATA_PINT"]["people-year"])):
					foreach($arResult["DATA_PINT"]["people-year"] as $num => $year):
						$driving = $arResult["DATA_PINT"]["people-driving"][$num];?>
						<span class="b-more_first__left__col__about__text__item">Водитель <?=($num + 1)?> (возраст - <?=$year?>, стаж - <?=$driving?>)</span>
					<?endforeach?>
				<?else:?>
						<span class="b-more_first__left__col__about__text__item">Минимальный возраст - <?=$arResult["DATA_PINT"]["people-year"]?></span>
						<span class="b-more_first__left__col__about__text__item">минимальный стаж - <?=$arResult["DATA_PINT"]["people-driving"]?></span>
				<?endif?>
					</span>
				</div>
			</div>
			<div class="b-more_first__left__col">
				<i class="b-more_first__left__col__ico_third"></i>
				<div class="b-more_first__left__col__about">
					<span class="b-more_first__left__col__about__type">Дополнительная информация</span>
					<span class="b-more_first__left__col__about__text">
						<?/*<span class="b-more_first__left__col__about__text__item">Левый</span>*/?>
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["date"]?></span>
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["risk"]?></span>
						<span class="b-more_first__left__col__about__text__item"><?=$arResult["DATA_PINT"]["franshiza"]?></span>
						<?/*<span class="b-more_first__left__col__about__text__item">Ivics</span>
						<span class="b-more_first__left__col__about__text__item">Ремонт на СТОА</span>
						<span class="b-more_first__left__col__about__text__item">по направлению СК</span>
						<span class="b-more_first__left__col__about__text__item">Эвакуация</span>
						<span class="b-more_first__left__col__about__text__item">Техпомощь</span>
						<span class="b-more_first__left__col__about__text__item">Без рассрочки</span>*/?>
					</span>
				</div>
			</div>
		</div>
		<?$arSuin = $arResult["SUIN"][$_REQUEST["suin"]]?>
		<div class="b-more_first__right">
			<div class="b-more_first__right__block">
				<span class="b-more_first__right__title">Ваш страховой полис</span>
				<div class="b-more_first__right__logo">
					<img src="<?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["LOGO"]["SRC"]?>">
				</div>
				<span class="b-more_first__right__name"><?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["NAME"]?></span>
				<span class="b-more_first__right__rate-about"><?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["ABOUT"]?></span>
				<a href="#" class="b-more_first__right__more b-modal" data-page="/company/?ajax=y">подробнее</a>
				<span class="b-more_first__right__pack-title">Страховой пакет:</span>
				<span class="b-more_first__right__pack"><?=$arSuin["RESULT"]["NAME"]?></span>
				<span class="b-more_first__right__in-price">Цена в страховой</span>
				<strike class="b-more_first__right__price"><?=PriceFormat($arSuin["RESULT"]["PRICE"])?> руб.</strike>
				<span class="b-more_first__right__our-price">Цена с нашей скидкой <?=$arResult["SUIN_SALE"]?>%</span>
				<span class="b-more_first__right__cost"><?=PriceFormat(round($arSuin["RESULT"]["PRICE"] * $arResult["SALE"]))?> руб.</span>
			</div>
			<?//print_p($arResult);?>
			<div class="f-button">
				<a class="f-button__text" href="/order/<?=$arResult["CALC_ID"]?>/<?=$_REQUEST["suin"]?>/">Заказать полис</a>
			</div>
		</div>
	</div>
	<?/*<div class="b-more b-more_second">
		<span class="b-more_second__title">Cостав продукта</span>
		<span class="b-more_second__title_more">и дополнительные услуги</span>
		<div class="b-more_second__list">
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Способы экономии:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">Противоугонная система - Штатная сигнализация , Поисковая система - Не установлена, Франшиза - Нет, Страховое покрытие - Только угон</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Персональный менеджер:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">Нет</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Аварийный комиссар:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">Специалист компании соберет справки в ГИБДД за вас.</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Способ возмещения ущерба:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">Выплата деньгами из расчета страховой стоимости.<br>Годные остатки ТС передаются страховой компании.</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Эвакуация автомобиля:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">Эвакуация автомобиля с места ДТП неограниченное количество раз.</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Техпомощь:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">Нет</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Выплата без справок ГИБДД:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">Нет</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Дополнительные опции:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text"><strong>Расширение ОСАГО</strong> - Нет, <strong>Оплата полиса</strong> - Единовременно</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Особые условия:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">В компании Ингосстрах «Дата начала использования ТС» влияет на стоимость КАСКО. В вашем расчете мы использовали дату 25.09.2012. Вы можете изменить эту дату в блоке "Дополнительные параметры" на странице калькулятора и пересчитать стоимость КАСКО для вас.</span>
				</div>
			</div>
			<div class="b-more_second__list__col b-more_second__list__col_last">
				<span class="b-more_second__list__col__title">Территория страхования:</span>
				<div class="b-more_second__list__col__right">
					<span class="b-more_second__list__col__right__text">Россия и страны СНГ по всем рискам. Опционально можно докупить расширение на территорию всех стран Европы на 2 недели за 20$.</span>
				</div>
			</div>
			<div class="b-more_second__list__col">
				<span class="b-more_second__list__col__title">Полезные документы и особые условия</span>
				<div class="b-more_second__list__col__right">
					<div class="b-more_second__list__col__right__doc">
						<a href="#">
							<i class="b-more_second__list__col__right__doc__image b-more_second__list__col__right__doc__image_doc"></i>
							<span class="b-more_second__list__col__right__doc__text">Правила страхования</span>
						</a>
					</div>
					<div class="b-more_second__list__col__right__doc">
						<a href="#">
							<i class="b-more_second__list__col__right__doc__image b-more_second__list__col__right__doc__image_pdf"></i>
							<span class="b-more_second__list__col__right__doc__text">Доверенность на продажу продуктов Ингосстрах</span>
						</a>
					</div>
				</div>
				<a class="f-button" href="#">
					<span class="f-button__text">Заказать полис</span>
				</a>
			</div>
		</div>
	</div>*/?>
	<div class="b-more b-more_third">
		<span class="b-more_third__title">Напомнить о расчете чуть позже</span>
		<form class="b-more_third__form" method="post">
			<label class="b-more_third__form__text" for="email">Мы вам отправим напоминание по e-mail</label>
			<input class="f-input b-more_third__form__input" type="email" name="email" id="email" />
			<input type="submit" value="Напомнить о расчете" class="b-more_third__form__submit" name="recall" />
		</form>
	</div>
</div>