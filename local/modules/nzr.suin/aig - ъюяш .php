<?
class SUIN_AIG {
	
	public $suinId = "";
	public $tables = array(
		"years_dif" => "/aig/years_dif.csv",
		"years_rus" => "/aig/years_rus.csv",
		"year" => "/aig/year.csv",
		"driving" => "/aig/driving.csv",
		"franshiza" => "/aig/franshiza.csv"
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
		$suin = new SUINImport();
		$arModels = array();
		print_p(SUIN_AIG::getFileData($this->tables["years_dif"]));
		die();
		if (($handle = fopen($this->tables["years_dif"], "r")) !== FALSE)
		{
			while (($data = fgetcsv($handle, 10000, ";", "\"")) !== FALSE) 
				if(strlen($data[0]))
				{
					if($data[1])
					{
						foreach(array_keys($arModels) as $model)
							if(stripos($data[0], $model) !== false)
							{
								$arModels[$model][] = str_ireplace($model." ", "",$data[0]);
								break;
							}
					
					}
					elseif(!in_array($data[0], array_keys($arModels)))
					{
						$arModels[$data[0]] = array();
					}
				}
			fclose($handle);
		}
		foreach($arModels as $mark => $arModels)
		{
			if($markId = $suin->addMark($this->suinId, $mark, $mark))
				foreach($arModels as $model_name)
					$suin->addModel($this->suinId, $model_name, $model_name, $markId);
		}
	}
	
	function calc($params)
	{	
		$params["date"] = floor((time() - strtotime($params["date"])) / 60 / 60 / 24 / 365);
		$params["people-num"] = $params["people-num"] == 100 ? 0 : $params["people-num"];
		$params["min-people-year"] = is_array($params["people-year"]) ? min($params["people-year"]) : $params["people-year"];
		$params["min-people-driving"] = is_array($params["people-driving"]) ? min($params["people-driving"]) : $params["people-driving"];
		$arRusMarks = array("ВАЗ", "ГАЗ", "ЗАЗ", "УАЗ", "ОКА");
		
		GLOBAL $arError;
		$arError = array();
		$isRus = in_array($params["mark"], $arRusMarks);
		$minSp = $isRus ? 9000 : ($params["risk"] == 2 ? 17500 : 20000); //Минимальный размер страховой премии
		$kTk = 0; //коээфициент для Базовый тариф по КАСКО

		if($isRus)
		{
			if (($handle = fopen($this->tables["years_rus"], "r")) !== FALSE)
			{
				while (($data = fgetcsv($handle, 10000, ";", "\"")) !== FALSE) 
					if((int) $data[0])
					{
						$kTk = floatval(str_replace(",", ".", preg_replace("/[^0-9,.]/", "", $data[($params["date"] > 8 ? 8 : $params["date"])]))); //коээфициент для Базовый тариф по КАСКО
						break;
					}
				fclose($handle);
			}
		}
		elseif(strlen($params["mark"]) && strlen($params["model"]))
		{
			if (($handle = fopen($this->tables["years_dif"], "r")) !== FALSE)
			{
				while (($data = fgetcsv($handle, 10000, ";", "\"")) !== FALSE) 
					if(stripos($data[0], $params["mark"]." ".$params["model"]) !== false)
					{
						$kTk = floatval(str_replace(",", ".", preg_replace("/[^0-9,.]/", "", $data[($params["date"] > 8 ? 8 : $params["date"]) + 1]))); //коээфициент для Базовый тариф по КАСКО
						break;
					}
				fclose($handle);
			}
		}
		if($kTk)
		{
			$Tk = $params["cost"] * $kTk * ($params["risk"] == 3 ? 0.88 : 1) / 100; //Базовый тариф по КАСКО
			
			if($params["franshiza"])
			{
				if (($handle = fopen($this->tables["franshiza"], "r")) !== FALSE)
				{
					$franshiza = array();
					while (($data = fgetcsv($handle, 10000, ";", "\"")) !== FALSE) 
						if(!count($franshiza))
						{
							$franshiza = array_flip($data);
						}
						else
						{
							$min_max = explode("-", str_replace(array(",", " "), "", $data[0]));
							$min_max[0] = floatval($min_max[0]);
							$min_max[1] = floatval($min_max[1]);
							if($params["cost"] >= $min_max[0] && $params["cost"] <= $min_max[1])
							{
								$Kfr = floatval(str_replace(",", ".", preg_replace("/[^0-9,.]/", "", $data[$franshiza[$params["franshiza"]]])));
								break;
							}
						}
					fclose($handle);
				}	
			}
			else $Kfr = 1; //Коэффициент безусловной франшизы
			
			if($Kfr)
			{
				$Pk = $Tk * $Kfr; //Страховая премия по КАСКО
			
				$Pk = $Pk < $minSp ? $minSp : $Pk;
				
				$Pgo = 0; //Страховая премия по ГО
				//$kPgo = 1; //коээфициент для Страховые премии по КАСКО и ГО
				
				if (($handle = fopen($this->tables["year"], "r")) !== FALSE)
				{
					$people_years = array();
					while (($data = fgetcsv($handle, 10000, ";", "\"")) !== FALSE) 
					{
						$tmp = array_diff(explode("*", preg_replace("/[^0-9]/", "*", $data[0])), array(""));
						if(count($tmp) == 2)
							for($i = current($tmp); $i <= end($tmp); $i++)
								$people_years[$i] = floatval(str_replace(",", ".", preg_replace("/[^0-9,.]/", "", $data[1])));
					}
					fclose($handle);
				}
				
				if (($handle = fopen($this->tables["driving"], "r")) !== FALSE)
				{
					$people_driving = array();
					while (($data = fgetcsv($handle, 10000, ";", "\"")) !== FALSE) 
					{
						$tmp = array_diff(explode("*", preg_replace("/[^0-9]/", "*", $data[0])), array(""));
						if(count($tmp))
							for($i = current($tmp); $i <= end($tmp); $i++)
								$people_driving[$i] = floatval(str_replace(",", ".", preg_replace("/[^0-9,.]/", "", $data[1])));
					}
					fclose($handle);
				}
				
				/*if($params["people-num"])
				{
					for($i = 0; $i < $params["people-num"]; $i++)
					{
						$Kst = $people_driving[$params["people-driving"][$i]]; //$params["people-driving"][$i];
						$Kvozr = $people_years[$params["people-year"][$i]]; //$params["people-year"][$i];
						if($Kst && $Kvozr) $kPgo *= $Kst*$Kvozr;
						else $arError[] = "Невозможно рассчитать Коэффициент стажа вождения и Коэффициент возраста";
					}
				}
				else
				{
					$Kst = $people_driving[$params["min-people-driving"]]; //$params["min-people-driving"];
					$Kvozr = $people_years[$params["min-people-year"]]; //$params["min-people-year"];
					if($Kst && $Kvozr) $kPgo *= pow($Kst*$Kvozr, 5);
					else $arError[] = "Невозможно рассчитать Коэффициент стажа вождения и Коэффициент возраста";
				}*/
				
				$Kst = $people_driving[$params["min-people-driving"]];
				$Kvozr = $people_years[$params["min-people-year"]];
				//print_p($Kst);
				/*print_p($people_years);
				print_p($params["min-people-year"]);
				print_p($Kvozr);*/
				if($Kst && $Kvozr) 
				{
					$Pp = ($Pk + $Pgo) * $Kst * $Kvozr; //Страховые премии по КАСКО и ГО - Итоговая Страховая премия по полису
					$Pp = round($Pp);
					$arResult[] = array("NAME" => "АвтоКаско", "PRICE" => $Pp);
					$arResult[] = array("NAME" => "ПРЕМИУМ статус", "PRICE" => $Pp + 2000);
				}
				else $arError[] = "Невозможно рассчитать Коэффициент стажа вождения и Коэффициент возраста";
			}
			else $arError[] = "Невозможно рассчитать Коэффициент безусловной франшизы";
		}
		else $arError[] = "Невозможно рассчитать Базовый тариф по КАСКО";
		
		/*print_p($arResult);
		die();*/
		
		if($params["min-people-driving"] < 1 || $params["min-people-year"] < 21)
			return array(array("NAME" => "АвтоКаско", "ERROR" => array("Компания AIG не осуществляет страхование для водителей младше 21 года и со стажем 1 год")));
		
		if(count($arError) || !count($arResult))
		{
			$params["suin_id"] = $this->suinId;
			$calc_result = SUINImport::calc("SUIN_DEFAULT", $params);
			if($calc_result) $arResult[] = array("NAME" => "АвтоКаско", "PRICE" => $calc_result);
		}
		
		if(count($arResult)) return $arResult;
		else return false;
		
		/*if(count($arError)) return array(array("NAME" => "АвтоКаско", "ERROR" => $arError));
		elseif(count($arResult)) return $arResult;
		else return false;*/
	}
}
?>