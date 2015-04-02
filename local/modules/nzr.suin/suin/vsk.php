<?
class SUIN_VSK {
	
	public $suinId = "";
	public $book;
	public $calc;
	public $client;
	public $login = "int_rf_kopilov_test_20140130";
	public $password = "Yfj674d6";
	
	function __construct() {
		$path = pathinfo(__FILE__);
		$this->suinId = "SUIN_".strtoupper($path["filename"]);
		$this->book = new SoapClient("http://autotest1.vsk.ru/Companies/VSK/Services/AgentService.asmx?WSDL", array('trace' => 1));
		$this->calc = new SoapClient("http://autotest1.vsk.ru/companies/vsk/services/eltpoiskintegration.asmx?WSDL", array('trace' => 1));
	}
	
	function importLib()
	{
		$arMarks = array();
		$suin = new SUINImport();
		$mark_res = $this->book->GetClassifierItem (
			array(
				"user" => array(
					"UserName" => $this->login, 
					"UserPwd" => $this->password, 
					"IsPassClearText" => true
				),
				"clsId" => "ECEBF857-C8D5-4E54-A23E-621BEC28072B",
			)
		);
		if(isset($mark_res->GetClassifierItemResult->ClassifierItemInfo) && is_array($mark_res->GetClassifierItemResult->ClassifierItemInfo))
		{
			foreach($mark_res->GetClassifierItemResult->ClassifierItemInfo as $arMark)
			{
				if($arMark->ID == "7fc39fa9-90ec-4852-95d7-3a0dbe214f2e")
					$arMark->Name = $arMark->DisplayName = $arMark->Description = "NIVA-".$arMark->DisplayName;
				$arMarks[$arMark->ID] = (array) $arMark;
			}
				
			$model_res = $this->book->GetClassifierItem (
				array(
					"user" => array(
						"UserName" => $this->login, 
						"UserPwd" => $this->password, 
						"IsPassClearText" => true
					),
					"clsId" => "38D0CD83-981B-4163-A95D-73E32DE983F8",
				)
			);
			
			if(isset($model_res->GetClassifierItemResult->ClassifierItemInfo) && is_array($model_res->GetClassifierItemResult->ClassifierItemInfo))
			{
				foreach($model_res->GetClassifierItemResult->ClassifierItemInfo as $arModel)
					$arMarks[$arModel->ParentItemID]["Models"][] = (array) $arModel;
			}
		}
		/*print_p($this->book->GetDistributedProductsList(
				array(
					"user" => array(
						"UserName" => $this->login, 
						"UserPwd" => $this->password, 
						"IsPassClearText" => true
					),
					//"clsId" => "38D0CD83-981B-4163-A95D-73E32DE983F8",
				)));
		print_p($arMarks);die();*/
		foreach($arMarks as $arMark)
			if($markId = $suin->addMark($this->suinId, $arMark["DisplayName"], $arMark["ID"]))
				foreach($arMark["Models"] as $arModel)
					$suin->addModel($this->suinId, $arModel["DisplayName"], $arModel["ID"], $markId);
	}
	
	function calc($params)
	{
		$arParams = array(
			"UserLogin" => $this->login, 
			"UserPass" => $this->password, 
			"Product" => "", //$this->book->GetDistributedProductsList()
			"ProgramType" => "", //$this->book->GetAllowInsuranceProgrammByProduct()
			"DurationMonth" => 12, //Срок страхования в месяцах
			"CalcDiscount" => null, //Скидка
			"DateCalc" => date(DATE_ATOM), //Дата проведения расчета
			"PaymentType" => 0, //Тип платежа
			"Currency" => "RUR", //Валюта страхования
			"InsuredJuridical" => false, //Страхователь юридическое лицо
			"LduType" => $params["people-num"] != 100 ? $params["people-num"] : 0, //Тип лиц допущенных к управлению
			"CalcUnicNum" => "777", //Уникальный номер расчета
			"CalcFinal" => true, //Игнорируется в текущей реализации сервиса
			"PreviousContractID" => null, //Номер предыдущего договора страхования в ВСК
			"DriverList" => array(), //Массив с параметрами лиц допущенных к управлению
			"CarInfo" => array( //Структура описывающая транспортное средство
				"CarModel" => strtoupper($params["model"]), //GUID в строке в верхнее регистре
				"NewCar" => true, //Игнорируется в текущей реализации сервиса
				"Constructed" => $params["year"], //Год выпуска ТС
				"DateRun" => date(DATE_ATOM, strtotime($params["date"])), //Дата начала эксплуатации
				"CarCost" => $params["cost"], //Стоимость в валюте полиса
				"Kilometers" => 0, //Пробег ТС
				"EnginePower" => $params["power"], //Мощность двигателя в л.с.
				"OwnerJuridical" => false, //Игнорируется в текущей реализации сервиса
				"Stoa" => 4, //$params["repair"] == 1 ? 0 : ($params["repair"] == 2 ? 1 : 2), //Форма возмещения ущерба
				"SumType" => 1, //Страховая сумма по договору. 0 – снижаемая. 1 – Неснижаемая
				"BankEnabled" => 0, //0 – незалоговое страхование. > 0 код банка залогодержателя
				"UseType" => null, //Допустимые значения: «Личные», «Такси», «Учебная езда», «Прокат/аренда», «Внутригородской»,  «Междугородний». Не все продукты поддерживают  варианты использования ТС. Значение null – говорит о стандартных для страхового продукта условиях эксплуатации ТС
				"RightWeel" => false, //Игнорируется в текущей реализации сервиса
				"KPPType" => strtoupper("97d82dd6-c346-4b16-b92f-38d4967f42ed"), //КПП для ТС (МКПП)
				"MotorType" => strtoupper("a0c3e3c7-b808-4ce8-91c6-7eda640ab20c"), //Тип двигателя для ТС (Бензиновый)
			),
			"Cover" => array( //Структура содержащая условия страхования
				"CASCO" => (object) array( //Структура с информацией о параметрах страхования
					"InsuredSum" => $params["cost"], //Страховая сумма в валюте полиса
					"FrahchSum" => $params["franshiza"] > 0 ? $params["franshiza"] : null, //Франшиза
					"BonusM" => null, //Игнорируется в текущей реализации сервиса
					"Risks" => $params["risk"] == 2 ? "УЩЕРБ" : ($params["risk"] == 3 ? "УГОН" : "УГОН+УЩЕРБ"), //Риски в полисе. Варианты: «УГОН+УЩЕРБ», «УЩЕРБ»
					/*"CustomOptions" => array( //Массив дополнительных сервисных программ
						"Option" => array(
							array(
								"OptionId" => ,
								"OptionValue" => ,
							),
						),
					),*/
					"IsGAPInsured" => false, //Застрахован ли GAP
				),
				/*"Liab" => array( //Структура для риска добровольной гражданской ответственности
					"LimitSum" => null, //Лимит ответственности в рублях. Список фиксированных сумм из справочника
				),
				"Accident" => array( //Структура для риска несчастный случай
					"LimitSum" => $params["cost"], //Страховая сумма по риску НС в валюте полиса
					"Seats" => 0, //Застрахованное количество мест
				),*/
				/*"DO" => array( //Массив дополнительного оборудования
					"Option" => array(
						array(
							"DOClass" => , //
							"DOSum" => , //
						),
				),*/
			),
		);

		if(is_array($params["people-year"]))
		{
			foreach(array_keys($params["people-year"]) as $people)
			{
				$arParams["DriverList"][$people] = array(
					"DriverAge" => $params["people-year"][$people], //Возраст водителя. Больше или равен 18
					"DrivingExpirience" => $params["people-driving"][$people], //Стаж вождения. Больше 0
					"FamilyState" => 0, //Семейный статус
					"Gender" => $params["people-year"][$people] == 1 ? "F" : "M", //Первый символ строки M – мужчина, F – женщина
					"Owner" => true, //Признак собственника ТС
					"ChildrenCount" => 0, //Игнорируется в текущей реализации сервиса
				);
			}
		}
		else
		{
			$arParams["DriverList"][] = array(
				"DriverAge" => $params["people-year"], //Возраст водителя. Больше или равен 18
				"DrivingExpirience" => $params["people-driving"], //Стаж вождения. Больше 0
				"FamilyState" => 0, //Семейный статус
				"Gender" => $params["people-year"] == 1 ? "F" : "M", //Первый символ строки M – мужчина, F – женщина
				"Owner" => true, //Признак собственника ТС
				"ChildrenCount" => 0, //Игнорируется в текущей реализации сервиса
			);
		}
		
		$arResult = array();
		try 
		{
		
			$result = $this->calc->CALC_DATA($arParams);
			if($result->Results->PremiumSum > 0) 
				$arResult[] = array("NAME" => "Каско классика", "PRICE" => $result->Results->PremiumSum);
			elseif(strlen($result->Results->Message))
			{
				$error = substr(strstr($result->Results->Message, ':'), 1);
				if(!strlen($error)) $error = $result->Results->Message;
				$arResult[] = array("NAME" => "Авто", "ERROR" => array($error));
			}
			else $arResult = false;
		}
		catch (Exception $e) 
		{
    		//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
		}

		if(count($arResult)) return $arResult;
		else return false;
		/*print_p($result, "result");
		print_p($arResult, "arResult");
		die();*/
		
	}
}
?>