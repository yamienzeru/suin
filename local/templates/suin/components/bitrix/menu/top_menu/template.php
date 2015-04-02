<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
	<?if (!empty($arResult)):?>
				<nav class="b-menu">
		<?foreach($arResult as $arItem):
			$pos = strpos($arItem["TEXT"], " ");
			if($pos !== false && $pos > 6)
			{
				$first = strstr($arItem["TEXT"], " ", true);
				$arItem["TEXT"] =  str_replace($first." ", $first."<br>", $arItem["TEXT"]);
			}?>
			<?if ($arItem["PERMISSION"] > "D"):?>
				<?if(strpos($arItem["LINK"], "ajax=y") !== false):?>
					<a href="#" class="b-menu__item b-modal" data-page="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
				<?else:?>
					<a href="<?=$arItem["LINK"]?>" class="b-menu__item"><?=$arItem["TEXT"]?></a>
				<?endif?>
			<?endif?>
		<?endforeach?>
				</nav>
	<?endif//7?>