<?
$module_id = "nzr.suin";
global $MESS;
include(GetLangFileName($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/main/lang/", "/options.php"));
include(GetLangFileName($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/lang/", "/options.php"));
include_once($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");
ini_set("max_execution_time", 1800000);

$arMessages = array();
$arErrors = array();

$suinImport = new SUINImport;

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-"=>" "));

$arOptions["SUIN_IBLOCK"] = Array(GetMessage("MESS_SUIN_IBLOCK"), Array("list", $arTypesEx));
$arOptions["SUIN_SALE"] = Array(GetMessage("MESS_SUIN_SALE"), Array("text", 10));
$arOptions["SUIN_ADDRESS"] = Array("Адрес", Array("textarea", 40, 5));
$arOptions["SUIN_WORK"] = Array("Часы работы", Array("text", 50));
$arOptions["SUIN_EMAIL"] = Array("E-mail", Array("text", 50));
$arOptions["SUIN_PHONE"] = Array("Телефон", Array("text", 50));

foreach($suinImport->suinMods as $suinMod)
	if($suinMod["CODE"] != "default")
	{
		$arOptions[$suinMod["CLASS"]] = Array("Активно", Array("checkbox", 50, "import" => true, "code" => $suinMod["CODE"]));
		if($suinMod["CODE"] == "aig")
		{
			$arTables = array(
				"years_dif" => "Коэффициент базового тарифа для иномарок",
				"years_rus" => "Коэффициент базового тарифа для отечественных",
				"year" => "Возраст водителя",
				"driving" => "Стаж вождения",
				"franshiza" => "Франшиза",
			);
			foreach($arTables as $t_code => $t_name)
				$arOptions[$suinMod["CLASS"]."_".strtoupper($t_code)] = Array($t_name, Array("file", 50));
			//$arOptions[$suinMod["CLASS"]."_RUSSIAN"] = Array("Минимальная премия за русскую машину", Array("text", 50));
			//$arOptions[$suinMod["CLASS"]."_FOREIGN"] = Array("Минимальная премия за иномарку", Array("text", 50));
		}
		elseif($suinMod["CODE"] == "reso")
		{
			$arTables = array(
				"franshiza" => "Франшиза",
			);
			foreach($arTables as $t_code => $t_name)
				$arOptions[$suinMod["CLASS"]."_".strtoupper($t_code)] = Array($t_name, Array("file", 50));
			//$arOptions[$suinMod["CLASS"]."_RUSSIAN"] = Array("Минимальная премия за русскую машину", Array("text", 50));
			//$arOptions[$suinMod["CLASS"]."_FOREIGN"] = Array("Минимальная премия за иномарку", Array("text", 50));
		}
		$arOptions[$suinMod["CLASS"]."_NAME"] = Array("Название", Array("text", 50));
		$arOptions[$suinMod["CLASS"]."_LOGO"] = Array("Логотип", Array("file", 50));
		$arOptions[$suinMod["CLASS"]."_ABOUT"] = Array("Кратко о компании", Array("textarea", 40, 5));
		$arOptions[$suinMod["CLASS"]."_ADDRESS"] = Array("Адрес", Array("textarea", 40, 5));
		$arOptions[$suinMod["CLASS"]."_WORK"] = Array("Часы работы", Array("text", 50));
		$arOptions[$suinMod["CLASS"]."_EMAIL"] = Array("E-mail", Array("text", 50));
		//$arOptions[$suinMod["CLASS"]."_RUSSIAN"] = Array("Минимальная премия за русскую машину", Array("text", 50));
		//$arOptions[$suinMod["CLASS"]."_FOREIGN"] = Array("Минимальная премия за иномарку", Array("text", 50));
	}
	

$aTabs = array(
	array(
		"DIV" => "index",
		"TAB" => GetMessage("MAIN_TAB"),
		"ICON" => "smtp_post_settings",
		"TITLE" => GetMessage("MAIN_TAB_TITLE"),
		"OPTIONS" => array_merge(
			$arOptions,
			Array(
				/*"NZR_SMTP_SERVER" => Array(GetMessage("MESS_NZR_SMTP_SERVER"), Array("text", 50)),
				"NZR_SMTP_PORT" => Array(GetMessage("MESS_NZR_SMTP_PORT"), Array("text", 50)),
				"NZR_SMTP_USER" => Array(GetMessage("MESS_NZR_SMTP_USER"), Array("text", 50)),
				"NZR_SMTP_PASSWORD" => Array(GetMessage("MESS_NZR_SMTP_PASSWORD"), Array("text", 50)),*/
			)
		)
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
//print_p($Import);die();
if($REQUEST_METHOD=="POST" && (strlen($Update.$Apply.$RestoreDefaults)>0 || is_array($Import) && count($Import)) && check_bitrix_sessid())
{	
	if(strlen($RestoreDefaults)>0)
	{
		foreach($aTab["OPTIONS"] as $name => $arOption)
			COption::SetOptionString($module_id, $name, $arOption[2], $arOption[0]);
		//COption::RemoveOption($module_id);
	}
	else
	{
		foreach($aTabs as $i => $aTab)
		{
			foreach($aTab["OPTIONS"] as $name => $arOption)
			{
				$disabled = array_key_exists("disabled", $arOption)? $arOption["disabled"]: "";
				if($disabled)
					continue;

				$val = $_POST[$name];
				if($arOption[1][0]=="checkbox" && $val!="Y")
					$val="N";
					
				if($arOption[1][0]=="file")
				{
					if(!$_FILES[$name]["error"])
					{
						$_FILES[$name]["del"] = "Y";
						$_FILES[$name]["MODULE_ID"] = $module_id;
						$old_file = COption::GetOptionString($module_id, $name);
						if(!empty($old_file))$_FILES[$name]["old_file"] = $old_file;
						if(CFile::SaveForDB($_FILES, $name, $module_id)) $val = $_FILES[$name];
					}
					elseif($_POST[$name."_del"] != "Y")
						$val = COption::GetOptionString($module_id, $name);
				}

				COption::SetOptionString($module_id, $name, $val, $arOption[0]);
			}
		}
	}
	
	if(is_array($Import) && count($Import))
	{
		$arClasses = array_keys($Import);
		foreach($arClasses as $class)
		{
			$suinClass = new $class;
			$suinClass->importLib();
			//$arMessages = $suinClass->arMessages;
			//$arErrors = $suinClass->arErrors;
		}
	}

	/*if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0)
		LocalRedirect($_REQUEST["back_url_settings"]);
	else
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());*/
}

$tabControl->Begin();
?>
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>" enctype="multipart/form-data">
<?

	foreach($arErrors as $strError)
		CAdminMessage::ShowMessage($strError);
	foreach($arMessages as $strMessage)
		CAdminMessage::ShowMessage(array("MESSAGE"=>$strMessage,"TYPE"=>"OK"));
foreach($aTabs as $keyTab => $aTab):
	$tabControl->BeginNextTab();
	$first = true;
	foreach($aTab["OPTIONS"] as $name => $arOption):
		$val = COption::GetOptionString($module_id, $name);
		$type = $arOption[1];
		$disabled = array_key_exists("disabled", $arOption)? $arOption["disabled"]: "";

	?>
	<?if($first):$first = false;?>
		<tr class="heading"><td colspan="2"><b>Основные настройки</b></td></tr>
	<?elseif($name == "SUIN_ADDRESS"):?>
		<tr class="heading"><td colspan="2"><b>Офис SUREinSURE</b></td></tr>
	<?elseif($type["import"]):?>
		<tr class="heading"><td colspan="2"><b><?=COption::GetOptionString($module_id, $name."_NAME")?> [<?=$type["code"]?>]</b></td></tr>
	<?endif?>
		<tr>
			<td width="40%" nowrap <?if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
				<label for="<?echo htmlspecialcharsbx($name)?>"><?echo $arOption[0]?></label>
			<td width="60%">
					<?if($type[0]=="checkbox"):?>
						<input type="checkbox" name="<?echo htmlspecialcharsbx($name)?>" id="<?echo htmlspecialcharsbx($name)?>" value="Y"<?if($val=="Y")echo" checked";?><?if($disabled)echo' disabled="disabled"';?>><?if($disabled) echo '<br>'.$disabled;?>
						<?if($type["import"]):?><input type="submit" name="Import[<?=$name?>]" value="<?=GetMessage("MAIN_IMPORT")?>" title="<?=GetMessage("MAIN_IMPORT")?>"><?endif;?>
					<?elseif($type[0]=="list"):
						$list = $type[1];?>
						<select name="<?echo htmlspecialcharsbx($name)?>">
						<?foreach($list as $type => $name):?>
							<option value="<?=$type?>"<?if($type == htmlspecialcharsbx($val)):?> selected<?endif?>><?=$name?></option>
						<?endforeach?>
						</select>
					<?elseif($type[0]=="text"):?>
						<input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($name)?>">
					<?elseif($type[0]=="textarea"):?>
						<textarea rows="<?echo $type[2]?>" cols="<?echo $type[1]?> " name="<?echo htmlspecialcharsbx($name)?>"><?echo htmlspecialcharsbx($val)?></textarea>
					<?elseif($type[0]=="file"):?>
						<?echo CFile::InputFile($name, 20, $val, false, 0);?><br>
						<?echo CFile::ShowImage($val, 200, 200, "border=0", "", true)?>
					<?endif?>
			</td>
		</tr>
	<?endforeach;
endforeach;?>

<?$tabControl->Buttons();?>
	<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<?if(strlen($_REQUEST["back_url_settings"])>0):?>
		<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?endif?>
	<input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
<?$tabControl->End();?>
</form>
