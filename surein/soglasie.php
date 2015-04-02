<?php
// Создание SOAP-клиента по WSDL-документу
$client = new SoapClient("https://b2b.soglasie.ru/CCM/CCMPort.wsdl", 
	array(
		"login" => "test_user",
		"password" => "jashgj675237512"
	)
);

// Поcылка SOAP-запроса и получение результата
//$result = $client->getCatalog(array("product" => "93", "catalog" => "1436"));//Запрос справочника по его номеру с сортировкой по продукту
//$result = $client->getProductList();//Получить список продуктов доступных для расчета.
//$result = $client->getProductDesc(array("product" => "93"));//Уровень расчета и какие параметры и к-ты можно передавать на каждом уровне.
$arParams = array(
	"product" => array("id" => 93), //Каско ФЛ
	//"debug" => "false",
	//"checkonly" => "false",
	"contract" => array(
		"param" => array(
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
				"val" => 25
			),
			array(
				"id" => 21, //Мин стаж допущенных
				"val" => 2
			),
			array(
				"id" => 27, //(1974) Способ урегулирования ущерба
				"val" => 80 //По калькуляции Страховщика
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
					"val" => 2011
				),
				array(
					"id" => 22, //(1436) МодельТС
					"val" => 35589
				),
				array(
					"id" => 30, //Срок эксплуатации
					"val" => 2
				),
				array(
					"id" => 82, //Страховая сумма КАСКО
					"val" => 2700000
				),
			),
			"risk" => array(
				array(
					"id" => 784, //Риск "Хищение"
					"param" => array(
						array(
							"id" => 8, //Величина франшизы
							"val" => 10000
						),
						array(
							"id" => 33, //Страховая сумма
							"val" => 2700000
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
						/*array(
							"id" => 11, //Возраст лица
							"val" => array()
						),*/
						array(
							"id" => 16, //Количество допущенных
							"val" => 0 //0 - в случае мультидрайва
						),
						/*array(
							"id" => 31, //Стаж водителя
							"val" => array()
						),*/
						array(
							"id" => 33, //Страховая сумма
							"val" => 2700000
						),
					),
				),
			)
		)
	)
);
$result = $client->calcProduct(array("data" => $arParams));

echo '<pre>';print_r($result);echo '</pre>';
?>