<?
class SUIN_SOGLASIE {
	
	public $suinId = "";
	public $client;
	
	function __construct() {
		$path = pathinfo(__FILE__);
		$this->suinId = "SUIN_".strtoupper($path["filename"]);
		$this->client = new SoapClient("https://b2b.soglasie.ru/CCM/CCMPort.wsdl", 
			array(
				"login" => "test_user",
				"password" => "jashgj675237512"
			)
		);
	}
	
	function importLib()
	{
		$suin = new SUINImport();
		$result = $this->client->getCatalog(array("product" => "93", "catalog" => "1436"));
		foreach($result->catalog->values->data as $arModel)
		{
			$arMarkModel = explode(";", $arModel->str);
			$arModels[$arMarkModel[0]][$arModel->val] = $arMarkModel[1];
		}
		foreach($arModels as $mark => $arModels)
		{
			if($markId = $suin->addMark($this->suinId, $mark))
				foreach($arModels as $model_id => $model_name)
					$suin->addModel($this->suinId, $model_name, $model_id, $markId);
		}
	}
	
	function calc($params)
	{
		//print_p($params);die();
		$params["date"] = round((time() - strtotime($params["date"])) / 60 / 60 / 24 / 365);
		$params["people-num"] = $params["people-num"] == 100 ? 0 : $params["people-num"];
		$params["min-people-year"] = is_array($params["people-year"]) ? min($params["people-year"]) : $params["people-year"];
		$params["min-people-driving"] = is_array($params["people-driving"]) ? min($params["people-driving"]) : $params["people-driving"];
		$risk_hishenie = array(
			"id" => 784, //Риск "Хищение"
			"param" => array(
				array(
					"id" => 8, //Величина франшизы
					"val" => $params["franshiza"]
				),
				array(
					"id" => 33, //Страховая сумма
					"val" => $params["cost"]
				)
			)
		);
		$risk_usherb = array(
			"id" => 844, //Риск "Ущерб"
			"param" => array(
				array(
					"id" => 8, //Величина франшизы
					"val" => $params["franshiza"]
				),
				array(
					"id" => 16, //Количество допущенных
					"val" => $params["people-num"] //0 - в случае мультидрайва
				),
				array(
					"id" => 33, //Страховая сумма
					"val" => $params["cost"]
				)
			)
		);
		if($params["people-num"])
		{
			$risk_usherb["param"][] = array(
				"id" => 11, //Возраст лица
				"val" => $params["people-year"]
			);
			$risk_usherb["param"][] = array(
				"id" => 31, //Стаж водителя
				"val" => $params["people-driving"]
			);
		}
		$mk = false;
		if($params["risk"] == 1) $params["risk"] = array($risk_hishenie, $risk_usherb);
		elseif($params["risk"] == 2) {$params["risk"] = array($risk_usherb); $mk = true;}
		elseif($params["risk"] == 3) $params["risk"] = array($risk_hishenie);
		
		$arParams = array(
			"product" => array("id" => 93), //Каско ФЛ
			//"debug" => "false",
			//"checkonly" => "false",
			"contract" => array(
				"param" => array(
					array(
						"id" => 5, //Безагрегатное страхование (Безагрегат)
						"val" => 1
					),
					array(
						"id" => 6, //(1005) Валюта
						"val" => 1 //Российский рубль
					),
					array(
						"id" => 7, //(1969) СправочникВариантовРассрочки
						"val" => 2 //Единовременно
					),
					array(
						"id" => 20, //Мин возраст допущенных
						"val" => $params["min-people-year"]
					),
					array(
						"id" => 21, //Мин стаж допущенных
						"val" => $params["min-people-driving"]
					),
					array(
						"id" => 27, //(1974) Способ урегулирования ущерба
						"val" => $params["repair"] == 1 ? 79 : ($params["repair"] == 2 ? 78 : 80) //По калькуляции Страховщика (по-умолчанию)
					),
					array(
						"id" => 29, //(1722) СпрСроковСтрахования
						"val" => 8 //1 год
					),
					array(
						"id" => 32, //(1) Сущность
						"val" => 1001 //Физическое лицо
					),
					array(
						"id" => 38, //(1075) СтруктураОрганизации
						"val" => 28 //Москва
					),					
					array(
						"id" => 481, //(1354) ПериодичностьОплаты
						"val" => 1 //Единовременно
					),
				),
				"object" => array(
					"id" => 526,
					"param" => array(
						array(
							"id" => 37, //(1716) ТипТС
							"val" => 2 //Легковые автомобили
						),
						array(
							"id" => 601, //Год выпуска ТС
							"val" => $params["year"]
						),
						array(
							"id" => 22, //(1436) МодельТС
							"val" => $params["model"]
						),
						array(
							"id" => 30, //Срок эксплуатации
							"val" => $params["date"]
						),
						array(
							"id" => 82, //Страховая сумма КАСКО
							"val" => $params["cost"]
						),
					),
					"risk" => $params["risk"], /*array(
						array(
							"id" => 784, //Риск "Хищение"
							"param" => array(
								array(
									"id" => 8, //Величина франшизы
									"val" => 10000
								),
								array(
									"id" => 33, //Страховая сумма
									"val" => $params["cost"]
								),
							),
						),
						array(
							"id" => 844, //Риск "Ущерб"
							"param" => array(
								array(
									"id" => 8, //Величина франшизы
									"val" => 10000
								),
								array(
									"id" => 11, //Возраст лица
									"val" => array()
								),
								array(
									"id" => 16, //Количество допущенных
									"val" => 0 //0 - в случае мультидрайва
								),
								array(
									"id" => 31, //Стаж водителя
									"val" => array()
								),
								array(
									"id" => 33, //Страховая сумма
									"val" => $params["cost"]
								),
							),
						),
					)*/
				)
			)
		);
		
		$arResult = array();
		try 
		{
			$result = $this->client->calcProduct(array("data" => $arParams));
			if($summ = $result->data->contract->result) $arResult[] = array("NAME" => "Каско", "PRICE" => $summ);

			$arParams["product"] = array("id" => 555); //ё-полис. Каско
			$result = $this->client->calcProduct(array("data" => $arParams));
			if($summ = $result->data->contract->result) $arResult[] = array("NAME" => "Ё-полис", "PRICE" => $summ);
			
			if($mk)
			{
				$arParams = array(
					"product" => array("id" => 554), //ё-полис. МиниКаско
					"contract" => array(
						"param" => array(
							array(
								"id" => 6, //(1005) Валюта
								"val" => 1 //Российский рубль
							),
						),
						"object" => array(
							"id" => 526,
							"param" => array(
								array(
									"id" => 22, //(1436) МодельТС
									"val" => $params["model"]
								),
								array(
									"id" => 30, //Срок эксплуатации
									"val" => $params["date"]
								),
							),
							"risk" => $params["risk"],
						)
					)
				);
				$result = $this->client->calcProduct(array("data" => $arParams));
				if($summ = $result->data->contract->result) $arResult[] = array("NAME" => "Ё-полис. МиниКаско", "PRICE" => $summ);
			}
			
			//if(count($arResult)) return $arResult;
			
			GLOBAL $arError;
			$arError = array();
			
			function setArray($obj)
			{
				if(in_array(gettype($obj), array("object", "array")))
				{
					$array = (array) $obj;
					if(count($array))
						foreach($array as &$ar)
							$ar = setArray($ar);
				}
				else $array = $obj;
				return $array;
			}
			
			function getError(&$item, $key)
			{
				if($key == "_")
				{
					GLOBAL $arError;
					$arError[] = $item;
				}
			}
			$result = setArray($result);
			array_walk_recursive($result, 'getError');
			
			//if(!count($arError)) $arError[] = "По введенным параметрам не удалось произвести расчет.";
			
			//return array(array("NAME" => "Авто", "ERROR" => $arError));
			
			if(!count($arResult))
			{
				$params["suin_id"] = $this->suinId;
				$calc_result = SUINImport::calc("SUIN_DEFAULT", $params);
				if($calc_result) $arResult[] = array("NAME" => "Каско", "PRICE" => $calc_result);
			}
		} 
		catch (Exception $e) 
		{
    		//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
		}
		
		if(count($arResult)) return $arResult;
		else return false;
	}
}
?>