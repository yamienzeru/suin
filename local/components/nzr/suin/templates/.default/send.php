<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$arSuin = $arResult["SUIN"][$_REQUEST["suin"]]?>
<div class="b-wrap b-wrap_popup">
	<div class="arcticmodal-close"></div>
	<div class="b-thanks">
		<h1 class="b-thanks__title">Спасибо за ваш заказ</h1>
		<span class="b-thanks__text b-thanks__text_bold">Номер расчета: <?=$arResult["CALC_ID"]?></span>
		<span class="b-thanks__text b-thanks__text_bold b-thanks__text_bold_last">Мы отправили ваш заказ в страховую компанию <?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["NAME"]?>.</span>
		<p class="b-thanks__text">Менеджер страховой компании <?=$arResult["SUIN_INFO"][$arSuin["CODE"]]["NAME"]?> позвонит вам в ближайшее время<br>и согласует дальнейшие шаги по оформлению и доставке вашего полиса, а также ответит<br>на все интересующие вас вопросы.</p>
		<div class="b-thanks__request">
			<i class="b-thanks__request__ico"></i>
			<span class="b-thanks__request__title">Список требуемых<br>документов:</span>
			<ol class="b-thanks__request__list">
				<li class="b-thanks__request__list__item">Паспорт</li>
				<li class="b-thanks__request__list__item">ПТС</li>
				<li class="b-thanks__request__list__item">Свидетельство о регистрации</li>
				<li class="b-thanks__request__list__item">Права (обе стороны,<br>на каждого водителя)</li>
			</ol>
		</div>
	</div>
</div>