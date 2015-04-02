<?
IncludeModuleLangFile(__FILE__);
class CUserTypeIBlockModel extends CUserTypeEnum
{
	function GetUserTypeDescription()
	{
		//print_p(GetMessage("USER_TYPE_IBEL_DESCRIPTION"));
		//die();
		return array(
			"USER_TYPE_ID" => "iblock_model",
			"CLASS_NAME" => "CUserTypeIBlockModel",
			"DESCRIPTION" => GetMessage("USER_TYPE_IBEL_DESCRIPTION"),
			"BASE_TYPE" => "int",
		);
	}

	function PrepareSettings($arUserField)
	{
		$height = intval($arUserField["SETTINGS"]["LIST_HEIGHT"]);
		$disp = $arUserField["SETTINGS"]["DISPLAY"];
		if($disp!="CHECKBOX" && $disp!="LIST")
			$disp = "LIST";
		$iblock_id = intval($arUserField["SETTINGS"]["IBLOCK_ID"]);
		if($iblock_id <= 0)
			$iblock_id = "";
		$element_id = intval($arUserField["SETTINGS"]["DEFAULT_VALUE"]);
		if($element_id <= 0)
			$element_id = "";
		$property_code = $arUserField["SETTINGS"]["PROPERTY_CODE"];

		$active_filter = $arUserField["SETTINGS"]["ACTIVE_FILTER"] === "Y"? "Y": "N";

		return array(
			"DISPLAY" => $disp,
			"LIST_HEIGHT" => ($height < 1? 1: $height),
			"IBLOCK_ID" => $iblock_id,
			"PROPERTY_CODE" => $property_code,
			"DEFAULT_VALUE" => $element_id,
			"ACTIVE_FILTER" => $active_filter,
		);
	}

	function GetSettingsHTML($arUserField = false, $arHtmlControl, $bVarsFromForm)
	{
		$result = '';

		if($bVarsFromForm)
			$iblock_id = $GLOBALS[$arHtmlControl["NAME"]]["IBLOCK_ID"];
		elseif(is_array($arUserField))
			$iblock_id = $arUserField["SETTINGS"]["IBLOCK_ID"];
		else
			$iblock_id = "";
		if(CModule::IncludeModule('iblock'))
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_DISPLAY").':</td>
				<td>
					'.GetIBlockDropDownList($iblock_id, $arHtmlControl["NAME"].'[IBLOCK_TYPE_ID]', $arHtmlControl["NAME"].'[IBLOCK_ID]', false, 'class="adm-detail-iblock-types"', 'class="adm-detail-iblock-list"').'
				</td>
			</tr>
			';
		}
		else
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_DISPLAY").':</td>
				<td>
					<input type="text" size="6" name="'.$arHtmlControl["NAME"].'[IBLOCK_ID]" value="'.htmlspecialcharsbx($value).'">
				</td>
			</tr>
			';
		}
		
		if($bVarsFromForm)
			$value = $GLOBALS[$arHtmlControl["NAME"]]["PROPERTY_CODE"];
		elseif(is_array($arUserField))
			$value = $arUserField["SETTINGS"]["PROPERTY_CODE"];
		else
			$value = "";
		if(($iblock_id > 0) && CModule::IncludeModule('iblock'))
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_PROPERTY_CODE").':</td>
				<td>
					<select name="'.$arHtmlControl["NAME"].'[PROPERTY_CODE]">
			';

			$arFilter = Array("IBLOCK_ID"=>$iblock_id);
			$arFilter["CODE"] = "SUIN_%";
				
			$rs = CIBlockProperty::GetList(
				array("SORT" => "ASC", "NAME"=>"ASC"),
				$arFilter
			);
			while($ar = $rs->GetNext())
				$result .= '<option value="'.$ar["CODE"].'"'.($ar["CODE"]==$value? " selected": "").'>['.$ar["CODE"].'] '.$ar["NAME"].'</option>';

			$result .= '</select>';
		}
		else
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_PROPERTY_CODE").':</td>
				<td>
					<input type="text" size="8" name="'.$arHtmlControl["NAME"].'[PROPERTY_CODE]" value="'.htmlspecialcharsbx($value).'">
				</td>
			</tr>
			';
		}

		if($bVarsFromForm)
			$ACTIVE_FILTER = $GLOBALS[$arHtmlControl["NAME"]]["ACTIVE_FILTER"] === "Y"? "Y": "N";
		elseif(is_array($arUserField))
			$ACTIVE_FILTER = $arUserField["SETTINGS"]["ACTIVE_FILTER"] === "Y"? "Y": "N";
		else
			$ACTIVE_FILTER = "N";

		/*if($bVarsFromForm)
			$value = $GLOBALS[$arHtmlControl["NAME"]]["DEFAULT_VALUE"];
		elseif(is_array($arUserField))
			$value = $arUserField["SETTINGS"]["DEFAULT_VALUE"];
		else
			$value = "";
		if(($iblock_id > 0) && CModule::IncludeModule('iblock'))
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_DEFAULT_VALUE").':</td>
				<td>
					<select name="'.$arHtmlControl["NAME"].'[DEFAULT_VALUE]">
						<option value="">'.GetMessage("IBLOCK_VALUE_ANY").'</option>
			';

			$arFilter = Array("IBLOCK_ID"=>$iblock_id);
			if($ACTIVE_FILTER === "Y")
				$arFilter["ACTIVE"] = "Y";

			$rs = CIBlockElement::GetList(
				array("SORT" => "DESC", "NAME"=>"ASC"),
				$arFilter,
				false,
				false,
				array("ID", "NAME")
			);
			while($ar = $rs->GetNext())
				$result .= '<option value="'.$ar["ID"].'"'.($ar["ID"]==$value? " selected": "").'>'.$ar["NAME"].'</option>';

			$result .= '</select>';
		}
		else
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_DEFAULT_VALUE").':</td>
				<td>
					<input type="text" size="8" name="'.$arHtmlControl["NAME"].'[DEFAULT_VALUE]" value="'.htmlspecialcharsbx($value).'">
				</td>
			</tr>
			';
		}*/

		if($bVarsFromForm)
			$value = $GLOBALS[$arHtmlControl["NAME"]]["DISPLAY"];
		elseif(is_array($arUserField))
			$value = $arUserField["SETTINGS"]["DISPLAY"];
		else
			$value = "LIST";
		$result .= '
		<tr>
			<td class="adm-detail-valign-top">'.GetMessage("USER_TYPE_ENUM_DISPLAY").':</td>
			<td>
				<label><input type="radio" name="'.$arHtmlControl["NAME"].'[DISPLAY]" value="LIST" '.("LIST"==$value? 'checked="checked"': '').'>'.GetMessage("USER_TYPE_IBEL_LIST").'</label><br>
				<label><input type="radio" name="'.$arHtmlControl["NAME"].'[DISPLAY]" value="CHECKBOX" '.("CHECKBOX"==$value? 'checked="checked"': '').'>'.GetMessage("USER_TYPE_IBEL_CHECKBOX").'</label><br>
			</td>
		</tr>
		';

		if($bVarsFromForm)
			$value = intval($GLOBALS[$arHtmlControl["NAME"]]["LIST_HEIGHT"]);
		elseif(is_array($arUserField))
			$value = intval($arUserField["SETTINGS"]["LIST_HEIGHT"]);
		else
			$value = 5;
		$result .= '
		<tr>
			<td>'.GetMessage("USER_TYPE_IBEL_LIST_HEIGHT").':</td>
			<td>
				<input type="text" name="'.$arHtmlControl["NAME"].'[LIST_HEIGHT]" size="10" value="'.$value.'">
			</td>
		</tr>
		';

		$result .= '
		<tr>
			<td>'.GetMessage("USER_TYPE_IBEL_ACTIVE_FILTER").':</td>
			<td>
				<input type="checkbox" name="'.$arHtmlControl["NAME"].'[ACTIVE_FILTER]" value="Y" '.($ACTIVE_FILTER=="Y"? 'checked="checked"': '').'>
			</td>
		</tr>
		';

		return $result;
	}

	function CheckFields($arUserField, $value)
	{
		$aMsg = array();
		return $aMsg;
	}

	function GetList($arUserField)
	{
		//print_p($GLOBALS);die();
		$rsElement = false;
		if(CModule::IncludeModule('iblock'))
		{
			/*$obElement = new CIBlockElementEnum;
			$rsElement = $obElement->GetTreeList($arUserField["SETTINGS"]["IBLOCK_ID"], $arUserField["SETTINGS"]["ACTIVE_FILTER"]);*/
			$arFilter = Array("IBLOCK_ID"=>$arUserField["SETTINGS"]["IBLOCK_ID"]);
			if($arUserField["SETTINGS"]["ACTIVE_FILTER"] === "Y")
				$arFilter["ACTIVE"] = "Y";
			if(strlen($arUserField["SETTINGS"]["PROPERTY_CODE"]))
				$arFilter["!PROPERTY_".$arUserField["SETTINGS"]["PROPERTY_CODE"]] = false;
			if($_REQUEST["ID"]>0)
				$arFilter["SECTION_ID"] = $_REQUEST["ID"];
				
			$rsElement = CIBlockElement::GetList(
				array("SORT" => "DESC", "NAME"=>"ASC"),
				$arFilter,
				false,
				false,
				array("ID", "NAME")
			);
			
			if(!$rsElement->SelectedRowsCount())
			{
				unset($arFilter["SECTION_ID"]);
				$rsElement = CIBlockElement::GetList(
					array("SORT" => "DESC", "NAME"=>"ASC"),
					$arFilter,
					false,
					false,
					array("ID", "NAME")
				);
			}
			
			if($rsElement)
			{
				$rsElement = new CIBlockElementEnum($rsElement);
			}

		}
		return $rsElement;
	}

	function OnSearchIndex($arUserField)
	{
		$res = '';

		if(is_array($arUserField["VALUE"]))
			$val = $arUserField["VALUE"];
		else
			$val = array($arUserField["VALUE"]);

		$val = array_filter($val, "strlen");
		if(count($val) && CModule::IncludeModule('iblock'))
		{
			$ob = new CIBlockElement;
			$rs = $ob->GetList(array("sort" => "asc", "id" => "asc"), array(
				"=ID" => $val
			), false, false, array("NAME"));

			while($ar = $rs->Fetch())
				$res .= $ar["NAME"]."\r\n";
		}

		return $res;
	}
}
?>