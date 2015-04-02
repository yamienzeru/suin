<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>
<div class="b-wrap b-wrap_popup">
	<div class="arcticmodal-close"></div>
	<div class="b-contacts">
		<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view", "contacts_map", Array(
			"INIT_MAP_TYPE" => $arParams["~INIT_MAP_TYPE"],	// Стартовый тип карты
			"MAP_DATA" => $arParams["~MAP_DATA"],	// Данные выводимые на карте
			),
			null,
			array('HIDE_ICONS' => 'Y')
		);?>
		<i class="b-contacts__map__decore"></i>
		<div class="b-contacts__sub">
			<h1 class="b-contacts__sub__title">Контакты</h1>
			<div class="b-contacts__sub__left">
				<div class="b-contacts__sub__left__col">
					<span class="b-contacts__sub__left__col__title">адрес</span>
					<span class="b-contacts__sub__left__col__text"><?=$arParams["ADDRESS"]?></span>
				</div>
				<div class="b-contacts__sub__left__col">
					<span class="b-contacts__sub__left__col__title">телефон</span>
					<span class="b-contacts__sub__left__col__phone"><?=$arParams["PHONE"]?></span>
				</div>
				<div class="b-contacts__sub__left__col">
					<span class="b-contacts__sub__left__col__title">e-mail</span>
					<a class="b-contacts__sub__left__col__mail" href="mailto:<?=$arParams["EMAIL"]?>"><?=$arParams["EMAIL"]?></a>
				</div>
			</div>
			<div class="b-contacts__sub__right">
				<span class="b-contacts__sub__right__title">Отправить сообщение</span>
				<form class="b-contacts__sub__right__form" action="<?=$APPLICATION->GetCurPage()?>" method="POST">
					<?=bitrix_sessid_post()?>
					<div class="b-contacts__sub__right__form__ajax">
					<?if(!empty($arResult["ERROR_MESSAGE"]))
						foreach($arResult["ERROR_MESSAGE"] as $v)
							echo('<div class="b-notice b-notice_error">'.$v.'</div>');
					if(strlen($arResult["OK_MESSAGE"]) > 0):?>
						<div class="b-notice b-notice_success"><?=$arResult["OK_MESSAGE"]?></div>
					<?endif?>
					</div>
					<div class="b-contacts__sub__right__form__col">
						<input class="f-input b-contacts__sub__right__form__col__input" data-validate="validate(required)" type="text"  name="user_name" value="<?=$arResult["AUTHOR_NAME"]?>" placeholder="Ваше имя..."/>
						<input class="f-input b-contacts__sub__right__form__col__input" data-validate="validate(required, email)" type="email" name="user_email" value="<?=$arResult["AUTHOR_EMAIL"]?>" placeholder="Ваш e-mail..." />
					<?if($arParams["USE_CAPTCHA"] == "Y"):?>
						<input class="hidden" type="text" name="name" value="" />
					<?endif;?>
					</div>
					<div class="b-contacts__sub__right__form__col">
						<textarea class="f-textarea b-contacts__sub__right__form__col__textarea" placeholder="Сообщение..." name="MESSAGE"><?=$arResult["MESSAGE"]?></textarea>
					</div>
					<div class="f-button">
						<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
						<input type="submit" name="submit" value="Отправить" class="f-button__text" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>