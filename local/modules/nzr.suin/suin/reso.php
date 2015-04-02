<?
class SUIN_RESO {
	
	public $suinId = "";
	public $tables = array(
		"franshiza" => "/reso/franshiza.csv"
	);
	
	function __construct() {
		$path = pathinfo(__FILE__);
		$this->suinId = "SUIN_".strtoupper($path["filename"]);
		foreach($this->tables as $t_key => $t_file)
			$this->tables[$t_key] = __DIR__.$t_file;
		$module_id = "nzr.suin";
		foreach($this->tables as $t_key => $t_file)
		{
			$val = COption::GetOptionString($module_id, $this->suinId."_".strtoupper($t_key));
			if($val) $this->tables[$t_key] = $_SERVER["DOCUMENT_ROOT"].CFile::GetPath($val);
		}
	}
	
	function getFileData($file = "")
	{
		if(strlen($file) && file_exists($file) && is_file($file))
		{
			$extension = pathinfo($file);
			$extension = strtolower($extension['extension']);
			$formats = array("xls", "csv");
			if(in_array($extension, $formats))
			{
				$arData = array();
				if($extension == "xls")
				{
					error_reporting(E_NONE);
					require_once 'aig/excel_reader2.php';
					$data = new Spreadsheet_Excel_Reader();
					$data->setUTFEncoder('mb');
					$data->read($file);
					foreach($data->sheets[0]["cells"] as &$cell)
					{
						$_cell = array();
						foreach($cell as $key => $row)
							$_cell[$key - 1] = $row;
						$cell = $_cell;
					}
					$arData = array_values($data->sheets[0]["cells"]);
				}
				elseif($extension == "csv")
				{
					GLOBAL $APPLICATION;
					if (($handle = fopen($file, "r")) !== FALSE) {
						while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
							foreach($data as &$dat)
								$dat = $APPLICATION->ConvertCharset($dat, "windows-1251", LANG_CHARSET);
							$arResult["DATA"][] = $data;
						}
						fclose($handle);
						$arData = array_values($arResult["DATA"]);
					}
				}
				else $arErrors[] = "Формат файла не соответствует ".implode(", ", $formats);
				
				if(!count($arErrors) && count($arData)) return $arData;
				else return false;
			}
		}
		else return false;
	}
	
	function importLib()
	{
		function OptionsToArray($opt_string)
		{
			$arResul = array();
			$tmpArray = explode("</option>",$opt_string);
			foreach($tmpArray as $tmp)
			{
				$name = str_replace("value=\"", "", strstr($tmp, "value=\""));
				$value = (int) strstr($name, "\"", true);
				if($value) $arResult[$value] = substr(strstr($name, ">"), 1);
			}
			return $arResult;
		}
		
		$suin = new SUINImport();
		$marks_content = file_get_contents("http://calc.kaskometr.ru/form/data/backend.php?action=get_marks");
		$arMarksContent = json_decode($marks_content, true);
		$marks_content = "";
		if(strlen($arMarksContent["option"])) $marks_content = $arMarksContent["option"];
		{
			$arMarks = OptionsToArray($marks_content);
			foreach($arMarks as $mark_id => $mark_name)
			{
				if($markId = $suin->addMark($this->suinId, $mark_name, $mark_id))
				{
					$models_content = file_get_contents("http://calc.kaskometr.ru/form/data/backend.php?action=get_models&id=".$mark_id);
					$arModelsContent = json_decode($models_content, true);
					$models_content = "";
					if(strlen($arModelsContent["option"])) $models_content = $arModelsContent["option"];
					{
						$arModels = OptionsToArray($models_content);
						foreach($arModels as $model_id => $model_name)
							$suin->addModel($this->suinId, $model_name, $model_id, $markId);
					}
				}
				
			}
		}
	}
	
	function calc($params)
	{
		$arResult = array();
		GLOBAL $arError;
		$arError = array();
		$params["date"] = date("Y",  strtotime($params["date"]));
		$params["min-people-year"] = is_array($params["people-year"]) ? min($params["people-year"]) : $params["people-year"];
		$params["min-people-driving"] = is_array($params["people-driving"]) ? min($params["people-driving"]) : $params["people-driving"];
		//$params["people-gender"] = is_array($params["people-year"]) ? array_fill(0, count($params["people-year"]), 1) : 1;
		if(is_array($params["people-gender"]))
			foreach($params["people-gender"] as $key => $gender)
				$params["people-gender"][$key]++;
		else $params["people-gender"]++;
		
		if(IntVal($params["mark"]) && IntVal($params["model"]) && $params["risk"] < 3)
		{
			$arParams = array(
				//"access_CK" => "16", //РЕСО
				"agent_id" => 790,
				"mark" => $params["mark"],
				"model" => $params["model"],
				"type" => $params["risk"],
				"kredit" => 2, //Автомобиль куплен за счет собственных средств
				"bank" => 0, //kredit=1, http://calc.kaskometr.ru/form/data/backend.php?action=get_banks
				"car_hp" => $params["power"],
				"charset" => "utf8",
				"year" => $params["year"],
				"start_year" => $params["date"],
				"price" => $params["cost"],
				"type_access_to_drive" => $params["people-num"] == 100 ? 2 : 1,
				
				"min_age" => $params["min-people-year"],
				"min_experience" => $params["min-people-driving"],
				"driver_age" => $params["people-year"],
				"driver_experience" => $params["people-driving"],
				"driver_sex" => $params["people-gender"],
					
				"insurance_history" => 1, //Автомобиль страхуется впервые
				//"insurance" => 1, //insurance_history=2, http://calc.kaskometr.ru/form/data/backend.php?action=get_insurances
				"damage" => 0, //insurance_history=2, 1 - Были убытки в прошлом году, 2 - Не было убытков в прошлом году
				"franshiza" => $params["franshiza"],
				"agregatnaya" => 0, //Страховая сумма: 0 - Неагрегатная, 1 - Агрегатная
				"rassrochka" => 1,
				"callback" => "",
			);
			//print_p($arParams);die();
			$result = json_decode(substr(file_get_contents("http://calc.kaskometr.ru/form/data/save.php?".http_build_query($arParams)), 1, -1), true);

			if(isset($result["ck"][16]))
			{
				$result = $result["ck"][16];
				if($result["price"]) 
					$arResult[] = array("NAME" => "Каско", "PRICE" => $result["price"]);
				
				if($params["franshiza"])
				{
					$Kfr = 0;
					if (($arData = SUIN_RESO::getFileData($this->tables["franshiza"])) !== FALSE)
					{
						$franshiza = array();
						$pf = 0;
						foreach($arData as $data)
							if(!count($franshiza))
							{
								$franshiza = array_flip(array_slice($data, 2, count($data), true));
								if($params["franshiza"] <= end(array_keys($franshiza)))
									foreach($franshiza as $fr => $key)
										if($params["franshiza"] > $fr)
											$pf = $fr;
							}
							elseif($pf)
							{
								$min_max[0] = floatval($data[0]);
								$min_max[1] = floatval($data[1]);
								if($params["cost"] >= $min_max[0] && $params["cost"] <= $min_max[1])
								{
									$Kfr = floatval(str_replace(",", ".", preg_replace("/[^0-9,.]/", "", $data[$franshiza[$params["franshiza"]]])));
									break;
								}
							}
					}
					if(count($arResult) && $Kfr > 0)
					foreach($arResult as $key => $val)
						$arResult[$key]["PRICE"] *= $Kfr;
				}
				/*print_p($result);
				print_p($arResult);
				die();*/
				//else return false;
			}
			//else return false;
		}
		
		if(count($arResult) && $params["credit"] == 1)
			foreach($arResult as $key => $val)
				$arResult[$key]["PRICE"] *= 1.1;
		//else return false;
		
		/*if(count($arError) || !count($arResult))
		{
			$params["suin_id"] = $this->suinId;
			$calc_result = SUINImport::calc("SUIN_DEFAULT", $params);
			if($calc_result) $arResult[] = array("NAME" => "Каско", "PRICE" => $calc_result);
		}
		
		if(count($arResult)) return $arResult;
		else return false;*/

		if(count($arResult)) return $arResult;
		
		if(!count($arError)) $arError[] = "По введенным параметрам не удалось произвести расчет.";
		return array(array("NAME" => "Авто", "ERROR" => $arError));
		
	}
}
?>