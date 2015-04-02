	<?if($_REQUEST["ajax"] == "y") die();?>
	<?if($APPLICATION->GetCurPage() == "/"):?>	
		<?/*<section class="b-section-social">
			<div class="b-wrap">
				<div class="networks">
					<div class="networks__item vk-like"></div>
					<a class="networks__item twitter-share-button" href="https://twitter.com/share" lang="en">Tweet</a>
					<div class="networks__item fb-like" data-width="110" data-layout="button_count" data-show-faces="false" data-send="false"></div>
				</div>
				<span class="b-section-social__like">Мне нравится</span>
			</div>
			<div class="b-section-social__descore"></div>
		</section>*/?>
		<?$APPLICATION->IncludeComponent("bitrix:news.list", "index_about", Array(
			"IBLOCK_TYPE" => "text",	// Тип информационного блока (используется только для проверки)
			"IBLOCK_ID" => "2",	// Код информационного блока
			"NEWS_COUNT" => "500",	// Количество новостей на странице
			"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
			"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
			"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
			"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
			"FILTER_NAME" => "",	// Фильтр
			"FIELD_CODE" => array(	// Поля
				0 => "DETAIL_TEXT",
				1 => "",
			),
			"PROPERTY_CODE" => array(	// Свойства
				0 => "",
				1 => "",
			),
			"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
			"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
			"AJAX_MODE" => "N",	// Включить режим AJAX
			"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
			"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
			"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
			"CACHE_TYPE" => "A",	// Тип кеширования
			"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
			"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
			"CACHE_GROUPS" => "Y",	// Учитывать права доступа
			"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
			"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
			"SET_TITLE" => "N",	// Устанавливать заголовок страницы
			"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
			"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
			"PARENT_SECTION" => "",	// ID раздела
			"PARENT_SECTION_CODE" => "",	// Код раздела
			"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
			"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
			"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
			"PAGER_TITLE" => "Новости",	// Название категорий
			"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
			"PAGER_TEMPLATE" => "",	// Название шаблона
			"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
			"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
			"DISPLAY_DATE" => "N",	// Выводить дату элемента
			"DISPLAY_NAME" => "Y",	// Выводить название элемента
			"DISPLAY_PICTURE" => "N",	// Выводить изображение для анонса
			"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
			"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
			),
			false
		);?>
	<?endif?>
		<!-- Footer -->
		<footer class="b-footer">
			<div class="b-wrap">
				<?$APPLICATION->IncludeComponent("bitrix:news.list", "footer_partners", Array(
					"IBLOCK_TYPE" => "text",	// Тип информационного блока (используется только для проверки)
					"IBLOCK_ID" => "3",	// Код информационного блока
					"NEWS_COUNT" => "500",	// Количество новостей на странице
					"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
					"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
					"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
					"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
					"FILTER_NAME" => "",	// Фильтр
					"FIELD_CODE" => array(	// Поля
						0 => "",
						1 => "",
					),
					"PROPERTY_CODE" => array(	// Свойства
						0 => "URL",
						1 => "",
					),
					"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
					"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
					"AJAX_MODE" => "N",	// Включить режим AJAX
					"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
					"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
					"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
					"CACHE_TYPE" => "A",	// Тип кеширования
					"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
					"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
					"CACHE_GROUPS" => "Y",	// Учитывать права доступа
					"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
					"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
					"SET_TITLE" => "N",	// Устанавливать заголовок страницы
					"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
					"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
					"PARENT_SECTION" => "",	// ID раздела
					"PARENT_SECTION_CODE" => "",	// Код раздела
					"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
					"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
					"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
					"PAGER_TITLE" => "Новости",	// Название категорий
					"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
					"PAGER_TEMPLATE" => "",	// Название шаблона
					"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
					"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
					"DISPLAY_DATE" => "N",	// Выводить дату элемента
					"DISPLAY_NAME" => "Y",	// Выводить название элемента
					"DISPLAY_PICTURE" => "N",	// Выводить изображение для анонса
					"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
					"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
					),
					false
				);?>
				<div class="b-footer__copyright">
					<span class="b-footer__copyright__copy">&copy; 2013 ООО &laquo;Sure in sure&raquo;</span>
					<span class="b-footer__copyright__legal">Содержание сайта не является рекомендацией или офертой и носит информационно-справочный характер.</span>
					<span class="b-footer__copyright__ruformat">Разработка сайта <a href="http://ruformat.ru" class="b-footer__copyright__ruformat__link" target="_blank">ruformat.ru</a></span>
				</div>
			</div>
		</footer>
		<!--/Footer -->
	</div>
	
	<!-- JS -->
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery-ui.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.ui.datepicker-ru.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/social-likes.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.color.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.uniform.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/select2.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/select2_locale_ru.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.splitter.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.textchange.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/slides.jquery.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.arcticmodal-0.3.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.scrollTo.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.ketchup.all.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.placeholder.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.form.js"></script>
	<script src="//vk.com/js/api/openapi.js?105"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/script.js"></script>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=602901263080718";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	<!--/JS -->

</body>
</html>