<?php
$client = new SoapClient("https://aisws.ingos.ru/mcalc-test/CalcService.asmx");
$result = $client->Logon(array("UserName" => "1231", "UserPass" => "323"));
echo '<pre>';print_r($result);echo '</pre>';
?>