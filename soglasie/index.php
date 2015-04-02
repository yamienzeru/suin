<?  
	header("Content-Type: text/xml; charset=utf-8");
	$optional_headers = null;
    //$url='https://test_user:jashgj675237512@b2b.soglasie.ru/CCM/CCMPort.wsdl';
    $url='https://test_user:jashgj675237512@b2b.soglasie.ru/CCM/CCMPort.wsdl';
    //$url='https://test_user:jashgj675237512@b2b.soglasie.ru:8880/CCM/CCMPort?WSDL';
    //$url='https://test_user:jashgj675237512@b2b.soglasie.ru/CCMC';
    $data='<request>
    <debug>false</debug>
    <checkonly>false</checkonly>
    <product>
        <brief>КомплексноеСтрахованиеАвтотранспортаФизическихЛиц</brief>
    </product>
    <contract>
        <param>
            <brief>Автоминимум</brief>
            <val>0</val>
        </param>
        <param>
            <brief>Безагрегат</brief>
            <val>0</val>
        </param>
        <param>
            <brief>ШтатныйСотрудник</brief>
            <val>0</val>
        </param>
        <datecalc>2013-09-20T00:00:00Z</datecalc>
        <object>
            <brief>ТранспортноеСредство</brief>
            <param>
                <brief>МодельТС</brief>
                <val>23269</val>
            </param>
            <param>
                <brief>Техноблок</brief>
                <val>0</val>
            </param>
            <risk>
                <brief>ДополнительноеОборудование</brief>
            </risk>
            <risk>
                <brief>Хищение</brief>
            </risk>
            <risk>
                <brief>Ущерб</brief>
                <param>
                    <brief>50БезСправок</brief>
                    <val>0</val>
                </param>
                <param>
                    <brief>Привилегия</brief>
                    <val>0</val>
                </param>
            </risk>
            <risk>
                <brief>ГражданскаяОтветственность</brief>
            </risk>
            <risk>
                <brief>НесчастныйСлучай</brief>
            </risk>
            <risk>
                <brief>ТехПомощь</brief>
            </risk>
            <risk>
                <brief>ГЭП</brief>
            </risk>
        </object>
    </contract>
</request>';
    
  $params = array('http' => array(
              'method' => 'POST',
              'content' => $data
            ));
  if ($optional_headers !== null) {
    $params['http']['header'] = $optional_headers;
  }
  $ctx = stream_context_create($params);
  //$fp = @fopen($url, 'rb', false, $ctx);
  $ans = file_get_contents ($url, false, $ctx);
  echo $ans;
  
?>