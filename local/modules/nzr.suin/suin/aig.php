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
		if (($arData = SUIN_AIG::getFileData($this->tables["years_dif"])) !== FALSE)
		{
			foreach($arData as $data)
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
		
		/*$arUnderriteCheckTmp = array(
			"ALFA ROMEO" => array("GT", "Brera", "Spider"),
			"ASTON MARTIN" => array("DB7", "DB9", "DBS", "Rapide", "V12 Zagato", "V12 Vanquish", "V8 Vantage", "V12 Vantage", "Virage"),
			"AUDI" => array("A8", "Allroad", "Q7", "R8", "RS5", "RS6", "S4", "S5", "S6", "S8", "TT"),
			"BENTLEY" => array("Continental", "Continental GT", "SuperSport Coupe", "Arnage", "Mulsanne", "Brooklands2", "Azure"),
			"BMW" => array("5-series", "5-series GT", "6-series", "7-series", "M3", "M5", "M6", "X5", "X5M", "X6", "X6M", "Z4"),
			"CHEVROLET" => array("Camaro", "Corvette"),
			"FERRARI" => array("612 Scaglietti", "360 Modena", "458 Italia", "California", "430 Scuderia", "430 Spider", "599 GTB", "Fiorano"),
			"FORD" => array("GT", "Mustang", "Shelby"),
			"JAGUAR" => array("S-type", "X-type", "XJR", "XF", "XJ", "XK", "XKR"),
			"LEXUS" => array("GS", "GX", "LS", "LX", "RX"),
			"MASERATI" => array("Granturismo", "Quattroporte", "Gransport"),
			"MAYBACH" => array("57", "62"),
			"MERCEDES BENZ" => array("CL", "CLK", "CLS", "R-Classe", "S-Classe", "SL", "SLK", "SLS"),
			"PORSCHE" => array(),
			"TOYOTA" => array("Land Cruiser", "Land Cruiser Prado"),
			"ROLLS-ROYCE" => array("Phantom", "Phantom Drophead Coupe", "Phantom Coupe", "Ghost"),
		);
		
		$arUnderriteCheck = array();
		
		foreach($arUnderriteCheckTmp as $mark => $models)
		{
			$mark = mb_strtoupper(preg_replace("/[^a-zA-ZА-Яа-я0-9]/ui","",$mark), LANG_CHARSET);
			if(is_array($models) && !count($models))
				$arUnderriteCheck[$mark] = array();
			else foreach($models as $model)
			{
				$model = mb_strtoupper(preg_replace("/[^a-zA-ZА-Яа-я0-9]/ui","",$model), LANG_CHARSET);
				$arUnderriteCheck[$mark][] = $model;
			}
		}*/
		
		if(strtoupper($params["mark"]) == "BMW" && strlen($params["model"]) == 3)
		{
			$series = IntVal(substr($params["model"], 0, 1));
			if($series) $params["model"] = $series."-series";
		}
		
		GLOBAL $arError;
		$arError = array();
		$isRus = in_array($params["mark"], $arRusMarks);
		$minSp = $isRus ? 9000 : ($params["risk"] == 2 ? 17500 : 20000); //Минимальный размер страховой премии
		$kTk = 0; //коээфициент для Базовый тариф по КАСКО
		
		if($params["min-people-driving"] >= 1 && $params["min-people-year"] >= 21)
		{

			if($isRus)
			{
				if (($arData = SUIN_AIG::getFileData($this->tables["years_rus"])) !== FALSE)
				{
					foreach($arData as $data)
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
				if (($arData = SUIN_AIG::getFileData($this->tables["years_dif"])) !== FALSE)
				{
					foreach($arData as $data)
						if(stripos($data[0], $params["mark"]." ".$params["model"]) !== false)
						{
							$kTk = floatval(str_replace(",", ".", preg_replace("/[^0-9,.]/", "", $data[($params["date"] > 8 ? 8 : $params["date"]) + 1]))); //коээфициент для Базовый тариф по КАСКО
							break;
						}
					fclose($handle);
				}
			}
			
			if(!($params["date"] > 4 && $params["franshiza"] < 9000))
			{
				if($kTk)
				{
					$Tk = $params["cost"] * $kTk * ($params["risk"] == 3 ? 0.88 : 1) / 100; //Базовый тариф по КАСКО

					if($params["franshiza"])
					{
						if (($arData = SUIN_AIG::getFileData($this->tables["franshiza"])) !== FALSE)
						{
							$franshiza = array();
							foreach($arData as $data)
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
						
						if (($arData = SUIN_AIG::getFileData($this->tables["year"])) !== FALSE)
						{
							$people_years = array();
							foreach($arData as $data)
							{
								$tmp = array_diff(explode("*", preg_replace("/[^0-9]/", "*", $data[0])), array(""));
								if(count($tmp) == 2)
									for($i = current($tmp); $i <= end($tmp); $i++)
										$people_years[$i] = floatval(str_replace(",", ".", preg_replace("/[^0-9,.]/", "", $data[1])));
							}
							fclose($handle);
						}
						
						if (($arData = SUIN_AIG::getFileData($this->tables["driving"])) !== FALSE)
						{
							$people_driving = array();
							foreach($arData as $data)
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
							$arResult[] = array("NAME" => "АвтоКаско", "PRICE" => $Pp/*, "MESSAGE" => "Возможно потребуется согласование с андеррайтером."*/);
							$arResult[] = array("NAME" => "ПРЕМИУМ статус", "PRICE" => $Pp + 2000 /*, "MESSAGE" => "Возможно потребуется согласование с андеррайтером."*/);
						}
						else $arError[] = "Невозможно рассчитать Коэффициент стажа вождения и Коэффициент возраста";
					}
					else $arError[] = "Невозможно рассчитать Коэффициент безусловной франшизы";
				}
				//else $arError[] = "Невозможно рассчитать Базовый тариф по КАСКО";
				else $arError[] = "Необходимо подтверждение принятия на страхование и согласование тарифов с андеррайтером";
			}
			else $arError[] = "Для ТС, возраст которых 4 года и старше безусловная франшиза должна составлять не менее 9 000 рублей";
		}
		else $arError[] = "Компания AIG не осуществляет страхование для водителей младше 21 года и со стажем 1 год";
		
		if(count($arResult) && $params["repair"] == 2)
			foreach($arResult as $key => $val)
				$arResult[$key]["PRICE"] *= 1.1;
		
		/*print_p($arResult);
		die();*/
		
		/*if($params["min-people-driving"] < 1 || $params["min-people-year"] < 21)
			return array(array("NAME" => "АвтоКаско", "ERROR" => array("Компания AIG не осуществляет страхование для водителей младше 21 года и со стажем 1 год")));*/
		
		/*if(count($arError) || !count($arResult))
		{
			$params["suin_id"] = $this->suinId;
			$calc_result = SUINImport::calc("SUIN_DEFAULT", $params);
			if($calc_result) $arResult[] = array("NAME" => "АвтоКаско", "PRICE" => $calc_result);
		}*/
		
		/*if(count($arResult)) return $arResult;
		else return false;*/
		
		if(count($arError)) return array(array("NAME" => "АвтоКаско", "ERROR" => $arError));
		elseif(count($arResult)) return $arResult;
		else return false;
	}
}
?>