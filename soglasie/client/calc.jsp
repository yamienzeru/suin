<%@ include file="utils.jsp"%>
<%@ page import="java.util.ArrayList"%>
<%@ page import="java.util.Collections"%>
<%@ page import="java.util.Comparator"%>
<%@ page import="java.util.Date"%>
<%@ page import="java.util.List"%>
<%@ page import="java.util.Map"%>
<%@ page import="javax.xml.ws.BindingProvider"%>
<%@ page import="ru.soglasie.b2b.ccm.client.*"%>
<%@ page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="content-language" content="ru"/>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <title>CCMC</title>
  </head>
  <body>
    <a href="calc.jsp">
      <img src="images/logo.png" alt="soglasie.ru" title="Страховая компания Согласие" border="0"/></a>
     
    <%
  long page_time = System.currentTimeMillis();

  CCMService service = new CCMService();
  CCM client = service.getCCMPort();
  
  // Set username and password for BASIC auth
  Map context = ((BindingProvider) client).getRequestContext();
  context.put(BindingProvider.USERNAME_PROPERTY, "test_user");
  context.put(BindingProvider.PASSWORD_PROPERTY, "jashgj675237512");
  
  boolean debug = getParameterBool(request, "debug", false);
  int productid = getParameterInt(request, "product", -1);
  String date = getParameterDate(request, "date");
  String course = request.getParameter("course");
  
  try
  {
    if(productid < 1)
    {
      List<DescBase> products = getProductListCache(client);

      if(products != null && !products.isEmpty())
      {
        out.println("<ul>");
        
        for(DescBase pr : products)
        {
          out.print("<li>");
          out.print(String.format("<a href=\"calc.jsp?product=%d\">%s</a>", pr.getId(), pr.getName()));
          out.print(String.format("<sup><a href=\"info.jsp?product=%d\">?</a></sup>", pr.getId()));
          out.println("</li>");
        }
        
        out.println("</ul>");
      }
    }
    else // productid > 0
    {
      DescProduct product = getProductDescCache(client, productid, false);
      
      if(product == null)
        throw new java.lang.Exception("Нет описания продукта");
        
      out.println(String.format("<h3>%s<sup><a href=\"info.jsp?product=%d\">?</a></sup></h3>", 
          product.getName(), product.getId()));
      
      javax.xml.datatype.XMLGregorianCalendar cdate = null;
        
      try
      {
        cdate = javax.xml.datatype.DatatypeFactory.newInstance().newXMLGregorianCalendarDate(
          Integer.parseInt(date.substring(0, 4)), Integer.parseInt(date.substring(4, 6)), Integer.parseInt(date.substring(6, 8)), 0);
        
        cdate.setTime(0,0,0); // Marshal error without
      }
      catch(java.lang.Exception ne) {}
          
      out.println("<form action=\"calc.jsp\" method=\"get\">");
      out.println("<input type=\"hidden\" name=\"product\" value=\"" + Integer.toString(productid) + "\">");
        
      // Генерация таблицы параметров
      out.println("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">");
  
      out.println("<tr><td colspan=3 bgcolor=\"lightgray\"><b>Базовые параметры</b></td></tr>");
      out.println("<tr>");
      out.println("<td width=\"20px\">&nbsp</td>");
      out.println("<td align=\"right\">Дата расчета</td>");
      out.print("<td><input type=\"text\" name=\"date\" value=\"" + date + "\">YYYYMMDD");
      if(cdate == null)
        out.print(" <font color=red>Ошибка</font>");
      out.println("</td>");
      out.println("</tr>");

      out.println("<tr>");
      out.println("<td width=\"20px\">&nbsp</td>");
      out.println("<td align=\"right\">Курс</td>");
      
      if(course != null)
        out.print("<td><input type=\"text\" name=\"course\" value=\"" + course + "\"></td>");
      else
        out.print("<td><input type=\"text\" name=\"course\" value=\"\"></td>");
        
      out.println("</tr>"); 

      out.println("<tr>");
      out.println("<td width=\"20px\">&nbsp</td>");
      out.println("<td align=\"right\">Отладка</td>");
      
      if(debug)
        out.print("<td><input type=\"checkbox\" name=\"debug\" value=\"true\" checked></td>");
      else
        out.print("<td><input type=\"checkbox\" name=\"debug\" value=\"true\"></td>");
        
      out.println("</tr>");
      out.println("");
      
      String pname;
      boolean send_object, send_risk;
      boolean first_time = request.getParameter("first") == null;
      
      CalcData calc_data;
      CalcBase calc_product;
      
      CalcContract calc_contract;
      CalcObject calc_object;
      CalcRisk calc_risk;
      
      calc_product = new CalcBase();
      //calc_product.setId(product.getId());
      calc_product.setBrief(product.getBrief());
      
      calc_contract = new CalcContract();
      calc_contract.setDatebeg(cdate);
      
      if(course != null && !course.isEmpty())
        calc_contract.setCourse(Double.parseDouble(course));
      
      calc_data = new CalcData();
      calc_data.setDebug(debug);
      calc_data.setProduct(calc_product);
      calc_data.getContract().add(calc_contract);
      
      out.println("<tr><td bgcolor=\"red\" colspan=3>Контракт</td></tr>");
      printParamBlock(request, out, client, product.getId(), 0, 0, product, calc_contract, first_time);
      
      List<DescObject> objects = product.getObjects() != null ? product.getObjects().getObject() : null;
    
      if(objects != null)
        for(DescObject object : objects)
        {
          out.print("<tr>");
          out.print("<td bgcolor=\"orange\" colspan=3>");
          
          if(Boolean.TRUE.equals(object.isRequired()))
            out.print("<font color=red>R</font>");
          
          pname = String.format("o%db", object.getId());
          send_object = (first_time || getParameterBool(request, pname, false));
          calc_object = null;
          
          if(send_object)
            out.println("<input type=\"checkbox\" name=\"" + pname + "\" value=\"true\" checked>");
          else
            out.println("<input type=\"checkbox\" name=\"" + pname + "\" value=\"true\">");
          
          out.println(String.format("Объект \"%s\"</td></tr>", object.getName()));
          
          if(send_object)
          {
            calc_object = new CalcObject();
            //calc_object.setId(object.getId());
            calc_object.setBrief(object.getBrief());
            calc_contract.getObject().add(calc_object);
          }
          
          printParamBlock(request, out, client, product.getId(), object.getId(), 0, object, calc_object, first_time);
          List<DescRisk> risks = object.getRisks() != null ? object.getRisks().getRisk() : null;
          
          if(risks != null)
            for(DescRisk risk : risks)
            {
              out.print("<tr><td width=25></td>");
              out.print("<td bgcolor=\"silver\" colspan=2>");
              
              if(Boolean.TRUE.equals(risk.isRequired()))
                out.print("<font color=red>R</font>");
              
              pname = String.format("r%d_%db", object.getId(), risk.getId());
              send_risk = (first_time || getParameterBool(request, pname, false));
              calc_risk = null;
            
              if(send_risk)
                out.println("<input type=\"checkbox\" name=\"" + pname + "\" value=\"true\" checked>");
              else
                out.println("<input type=\"checkbox\" name=\"" + pname + "\" value=\"true\">");
              
              out.println(String.format("Риск \"%s\"</td></tr>", risk.getName()));
              
              if(send_object && send_risk)
              {
                calc_risk = new CalcRisk();
                //calc_risk.setId(risk.getId());
                calc_risk.setBrief(risk.getBrief());
                calc_object.getRisk().add(calc_risk);
              }
              
              printParamBlock(request, out, client, product.getId(), object.getId(), risk.getId(), risk, calc_risk, first_time);
            }
        }

      long result_time = System.currentTimeMillis();
      ResultData result_data = client.calcProduct(calc_data);
      result_time = System.currentTimeMillis() - result_time;
      
      out.println("</table>");
      out.println("<input type=\"hidden\" name=\"first\" value=\"0\">");
      out.println("<input type=\"submit\" value=\"Рассчитать\">");
      out.println("</form>");
      
      out.println(String.format("<hr><h3>Результат: (%.3f c)</h3>", result_time/1000.0));
      
      ResultContract result = null;
      
      if(result_data != null && result_data.getContract().size() > 0)
        result = result_data.getContract().get(0);
      
      if(result == null)
      {
        out.println("нет результата");
        
        if(result_data.getProduct() != null)
        {
          List<String> errors = result_data.getProduct().getError();
          boolean berrs = !(errors == null || errors.isEmpty());
        
          if(berrs)
          {
            out.println("<ol type=\"1\">");
            
            for(String err: errors)
              out.println("<li>" + err + "</li>");
            
            out.println("</ol>");
          }
        }        
      }
      else
      {
        out.println("<table border=\"1\" cellspacing=\"0\" cellpadding=\"2\">");
        out.println("<tr><th>Имя</th><th>Значение</th><th>Ошибки/Информация</th></tr>\n");
                
        printResult(out, result, "Премия", "red");
        List<ResultObject> object_results = result.getObject();
        
        if(object_results != null)
          for(ResultObject object_result : object_results)
          {
            printResult(out, object_result, null, "orange");
            
            if(product.getCalclevel() != CalcLevel.OBJECT)
            {
              List<ResultRisk> risk_results = object_result.getRisk();
              
              if(risk_results != null)
                for(ResultRisk risk_result : risk_results)
                  printResult(out, risk_result, null, "silver");
            }
          }
        
        out.println("</table>");
      }
    }
  }
  catch (java.lang.Exception e)
  {
    out.println("<br><font color=red>Ошибка: " + e.getMessage() + "</font>");
    e.printStackTrace();
  }
  
  page_time = System.currentTimeMillis() - page_time;
  out.println(String.format("<br/><font size=-3 color=grey>%1$tY/%1$tm/%1$te (%2$.3f c)</font>", new java.util.Date(), page_time/1000.0));
%>
  </body>
</html>
<%!
  public void printParamBlock(javax.servlet.http.HttpServletRequest request, javax.servlet.jsp.JspWriter out, 
    CCM client, int productid, int objectid, int riskid, DescCalc data_desc, CalcBase data_calc, boolean first) throws java.io.IOException
  {
    boolean bvalue;
    String note, pname, pvalue, name_prefix;

    if(objectid < 1)
      name_prefix = "";
    else if(riskid < 1)
      name_prefix = String.format("%d_", objectid);
    else
      name_prefix = String.format("%d_%d_", objectid, riskid);

    List<DescParam> params = data_desc.getParams() != null ? data_desc.getParams().getParam() : null;
  
    if(params != null && !params.isEmpty())
      for(DescParam pdesc : params)
      {
        pname = String.format("p%s%d", name_prefix, pdesc.getId());
        
        pvalue = request.getParameter(pname);
        bvalue = pvalue != null && !pvalue.isEmpty();
        
        out.println("<tr>");
        out.println("<td width=\"20px\">&nbsp</td>");
        out.println("<td align=\"right\">");
        
        note = (pdesc.getCatalog() > 0) ? null : getHtmlNote(pdesc.getNote());
  
        if(note != null)
        {
          out.print("<span title=\"");
          out.print(note);
          out.print("\">");
        }
        
        out.print(pdesc.getName());
  
        if(note != null)
          out.print("<sup>?</sup></span>");
        
        out.println("</td>");
        out.println("<td>");
      
        if(pdesc.getCatalog() > 0)
        {
          Catalog catalog = null;
          int curval = -1;
          
          try
          {
            catalog = getCatalogCache(client, data_desc.getCalcid(), pdesc.getCatalog());
            
            if(catalog == null)
              throw new java.lang.Exception("Нет каталога");
          }
          catch (java.lang.Exception e)
          {
            out.println("<font color=red>Ошибка: " + e.getMessage() + "</font></td></tr>");
            continue;
          }
                    
          try
          {
            if(bvalue)
              curval = Integer.parseInt(pvalue);
          }
          catch (NumberFormatException ne)
          {
            curval = -1;
          }
          
          boolean cursel = false;
          List<CatalogValue> cval = catalog.getValues() != null ? catalog.getValues().getData() : null;
          
          out.println("<select name=\"" + pname + "\">");
          
          if(cval != null)
            for(CatalogValue val : cval)
            {
              if(val.getVal() == curval)
              {
                out.println(String.format("<option selected value=\"%d\">%s</option>", val.getVal(), val.getStr()));
                cursel = true;
              }
              else
                out.println(String.format("<option value=\"%d\">%s</option>", val.getVal(), val.getStr()));
            }
          
          if(!cursel)
          {
            out.print("<option selected value=\"0\">-- нет --</option>");
  
            bvalue = true;
            pvalue = "0";
          }
          else
            out.print("<option value=\"0\">-- нет --</option>");
          
          out.println("</select>");
        }
        else
        {
          int dt = pdesc.getDatatype();
        
          if(dt == 2) // boolean
          {
            bvalue = true;
          
            if(pvalue == null || pvalue.compareToIgnoreCase("false") == 0 || pvalue.compareTo("0") == 0)
              pvalue = "0";
            else
              pvalue = "1";
          }
        
          // Валидация передаваемого значения по типу
          // TODO array list
          if(bvalue)
          {
            int nval;
            double dval;
            
            switch(dt)
            {
            case 0: // integer
              {
                try
                {
                   nval = Integer.parseInt(pvalue);
                }
                catch(NumberFormatException ne)
                {
                  try
                  {
                    dval = Double.parseDouble(pvalue);
                    pvalue = Integer.toString((int)dval);
                  }
                  catch(NumberFormatException ne2)
                  {
                    bvalue = false;
                  }
                }
              }
              break;
                  
            case 1: // double
              {
                try
                {
                   dval = Double.parseDouble(pvalue);
                }
                catch(NumberFormatException ne)
                {
                   bvalue = false;
                }
              }
              break;
            }
                      
            if(dt == 2)
            {
              if(pvalue.charAt(0) == '1')
                  out.println("<input type=\"checkbox\" name=\"" + pname + "\" value=\"1\" checked>");
              else
                  out.println("<input type=\"checkbox\" name=\"" + pname + "\" value=\"1\">");
            }
            else
            {
              out.println("<input name=\"" + pname + "\" value=\"" + pvalue + "\">");
              
              if(pdesc.isArray())
                out.println("[1;2;...]");
              
              if(!bvalue)
                out.println("<font color=red>Ошибка</font>");
            }
          }
          else
          {
            out.print("<input name=\"" + pname + "\" value=\"\">");
            
            if(pdesc.isArray())
              out.println("[1;2;...]");
          }
        }
        
        out.println("</td>");
        out.println("</tr>");
        
        if(data_calc != null)
        {
          CalcParam pval = new CalcParam();
          
          //pval.setId(pdesc.getId());
          pval.setBrief(pdesc.getBrief());
          pval.getVal().add(bvalue ? pvalue : "0"); // TODO: parse array values as val1;val2;val3;
  
          if(data_calc != null)
            data_calc.getParam().add(pval);
        }
      }
      
    List<DescCoeff> coeffs = data_desc.getCoeffs() != null ? data_desc.getCoeffs().getCoeff() : null;
    
    if(coeffs != null && !coeffs.isEmpty())
    {
      bvalue = false; 
    
      for(DescCoeff cdesc: coeffs)
        if(cdesc.isCanchange())
        {
          bvalue = true;
          break;
        }

      if(bvalue)
      {
        out.println("<tr><td width=\"20px\">&nbsp</td><td colspan=3 bgcolor=\"lightgray\">Коэффициенты</td></tr>");
        
        for(DescCoeff cdesc: coeffs)
        {
          if(!cdesc.isCanchange())
            continue;
            
          boolean send = false;
          double val = 0.0;
        
          out.println("<tr>");
          out.println("<td width=\"20px\">&nbsp</td>");
          out.println("<td align=\"right\">");
        
          note = getHtmlNote(cdesc.getNote());
    
          if(note != null)
          {
            out.print("<span title=\"");
            out.print(note);
            out.print("\">");
          }
          
          out.print(cdesc.getName());
    
          if(note != null)
            out.print("<sup>?</sup></span>");
          
          out.println("</td>");
          out.println("<td>");
          
          ///////////////////////////////////
          
          pname = String.format("c%s%d", name_prefix, cdesc.getId());
                
          pvalue = request.getParameter(pname);
          bvalue = pvalue != null && !pvalue.isEmpty();
         
          if(bvalue)
          {
            try
            {
              val = Double.parseDouble(pvalue);
              send = true;
            }
            catch (NumberFormatException ne)
            {
              out.println("<font color=red>Ошибка</font>");
            }
            
            out.println("<input name=\"" + pname + "\" value=\"" + pvalue + "\">");
          }
          else
            out.println("<input name=\"" + pname + "\" value=\"\">");        
          
          ///////////////////////////////////
          
          pname += "b";
          pvalue = request.getParameter(pname);
          bvalue = pvalue != null && !pvalue.isEmpty();
          
          if(bvalue && pvalue.compareToIgnoreCase("true") == 0)
          {
            out.println("<input type=\"checkbox\" name=\"" + pname + "\" value=\"true\" checked>");
            
            if(send && data_calc != null)
            {
              CalcCoeff cval = new CalcCoeff();
              
              //cval.setId(cdesc.getId());
              cval.setBrief(cdesc.getBrief());
              cval.setVal(val);
              cval.setForceuse(false);
              
              data_calc.getCoeff().add(cval);
            }
          }
          else
            out.println("<input type=\"checkbox\" name=\"" + pname + "\" value=\"true\">");
          
          out.println("</td>");
          out.println("</tr>");
        }
      }
    }
  }
  
  public void printResult(javax.servlet.jsp.JspWriter out, ResultCalc data, String name, String color)
     throws java.io.IOException
  {
    out.println("<tr>");
    
    if(name == null)
      name = data.getName();
    
    if(name == null)
      name = data.getBrief();
    
    if(color != null)
      out.println(String.format("<td align='right' bgcolor=%s>%s</td>", color, name));
    else
      out.println(String.format("<td align='right'>%s</td>", name));

    if(data == null)
    {
      out.println("<td colspan=2>Нет данных</td>");
      out.println("</tr>");
    }
    else
    {
      boolean berrs;
      List<String> errors;
    
      if(data.getResult() != null)
        out.println(String.format("<td align='right'>%.4f</td>", data.getResult()));
      else
        out.println("<td></td>");
    
      out.println("<td>");
      
      if(data.getInsuredsum() != null)
        out.println(String.format("Стр.Сумма: %.4f<br/>", data.getInsuredsum()));      
      
      if(data.getTariff() != null)
        out.println(String.format("Тариф: %.4f<br/>", data.getTariff()));
        
      if(data.getThreshold() != null)
        out.println(String.format("Порог: %.4f<br/>", data.getThreshold()));
      
      errors = data.getError();
      berrs = !(errors == null || errors.isEmpty());
  
      if(berrs)
      {
        out.println("<ol type=\"1\">");
        
        for(String err: errors)
          out.println("<li>" + err + "</li>");
        
        out.println("</ol>");
      }
      
      out.println("</td>");
      out.println("</tr>");
      
      List<ResultCoeff> coeffs = data.getCoeff();
      
      if(coeffs != null)
        for(ResultCoeff coeff : coeffs)
        {
          out.println("<tr>");
          out.print("<td align='right'>");
          
          name = coeff.getName();
          
          if(name != null)
          {
            out.print("<span title=\"");
            out.print(name);
            out.print("\">");
          }
          
          if(coeff.getRetkind() == 2)
            out.println(String.format("<font color=purple>%s</font>", coeff.getBrief()));
          else
            out.println(String.format("%s", coeff.getBrief()));
            
          if(name != null)
            out.print("</span>");
          
          out.println("</td>");
          out.print("<td align='right'>");
                     
          if(coeff.getResult() != null)
          {
            if(coeff.isUser() != null && coeff.isUser() == Boolean.TRUE)
              out.println(String.format("<font color=green>%.4f</font>", coeff.getResult()));
            else
              out.println(String.format("%.4f", coeff.getResult()));
          }
          
          out.println("</td>");
          out.println("<td>");
          
          errors = coeff.getError();
          berrs = !(errors == null || errors.isEmpty());
          
          if(berrs)
          {
            out.println("<ol type=\"1\">");
            
            for(String err: errors)
              out.println("<li>" + err + "</li>");
            
            out.println("</ol>");
          }
          
          out.println("</td>");
          out.println("</tr>");
        }
    }
  }
%>