<%@ page import="java.util.Date"%>
<%@ page import="java.util.List"%>
<%@ page import="java.io.*"%>
<%@ page import="javax.xml.datatype.*"%>
<%@ page import="javax.xml.bind.*"%>
<%@ page import="javax.xml.namespace.QName"%>
<%@ page import="javax.xml.transform.stream.StreamSource"%>
<%@ page import="ru.soglasie.b2b.ccm.client.*"%>
<%!
  public boolean getParameterBool(javax.servlet.http.HttpServletRequest request, String name, boolean default_value)
  {
    try
    {  
      String param = request.getParameter(name);
  
      if(param != null && !param.isEmpty())
        return Boolean.parseBoolean(param);
    }
    catch (java.lang.Exception e) {}
    
    return default_value;
  }
  
  public int getParameterInt(javax.servlet.http.HttpServletRequest request, String name, int default_value)
  {
    try
    {  
      String param = request.getParameter(name);
  
      if(param != null && !param.isEmpty())
        return Integer.parseInt(param);
    }
    catch (java.lang.Exception e) {}
    
    return default_value;
  }

  // get date YYYYMMDD default now  
  public String getParameterDate(javax.servlet.http.HttpServletRequest request, String name)
  {
    String param = request.getParameter(name);

    if(param != null && !param.isEmpty())
      return param;
    
	//return String.format("%1$tY%1$tm%1$te", new java.util.Date());

    java.util.Date date = new java.util.Date();
    return String.format("%04d%02d%02d", date.getYear() + 1900, date.getMonth() + 1, date.getDate());
  }
  
  public String getHtmlNote(String note)
  {
    if(note != null)
    {
      final char[] endlinec = { '&', '#', '1', '3' };
      final char[] quotec = { '&', '#', '3', '4' };
      
      final String endline = new String(endlinec);
      final String quote = new String(quotec);
            
      note = note.replaceAll("\r\n", endline);
      note = note.replaceAll("\r", endline);
      note = note.replaceAll("\n", endline);
      note = note.replaceAll("\"", quote);
    }
    
    return note;
  }
    
  public String getCacheDir()
  {
    try
    {
      String cachedir = System.getenv("WL_HOME") + "/Cache";
    
      // Create multiple directories
      (new File(cachedir)).mkdirs();
            
      return cachedir;
    }
    catch (java.lang.Exception e)
    {
      System.err.println("Error: " + e.getMessage());
    }
  
    return "";
  }
  
  public void clearCacheDir()
  {
    FileFilter filter = new FileFilter()
      {
        public boolean accept(File file)
        {
          return !file.isDirectory() && file.getName().endsWith(".xml");
        }
      };
      
    File dir = new File(getCacheDir());
    File[] files = dir.listFiles(filter);

    for (File f: files)
        f.delete();
  }
  
  public List<DescBase> getProductListCache(CCM client) throws java.lang.Exception
  {
    return client.getProductList();
  }
  
  public DescProduct getProductDescCache(CCM client, int productid, boolean update) throws java.lang.Exception
  {
    DescProduct product = null;
  
    if(productid > 0)
    {
      String filename = String.format("%s/ccmc4_p%d.xml", getCacheDir(), productid);
      File file = new File(filename);
      
      try
      {      
        if(file.exists() && !update)
        {
          JAXBContext jc = JAXBContext.newInstance("ru.soglasie.b2b.ccm.client");
          Unmarshaller um = jc.createUnmarshaller();
          
          JAXBElement<DescProduct> root = um.unmarshal(new StreamSource(file), DescProduct.class);
          product = root.getValue();
          
          if(product != null)
            return product;
        }
      }
      catch (java.lang.Exception e)
      {
        System.out.println("getProductDescCache:" + e.getMessage());
        file.delete();
      }
      
      long process_time = System.currentTimeMillis();
      product = client.getProductDesc(productid, null, null);
      process_time = System.currentTimeMillis() - process_time;
      
      System.out.println(String.format("getProductDesc(%d) = %.2f c", productid, process_time/1000.0));
      
      try
      {
        JAXBContext jcc = JAXBContext.newInstance(product.getClass());
        Marshaller m = jcc.createMarshaller();
  
        m.setProperty(Marshaller.JAXB_ENCODING, "UTF-8");
        m.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, Boolean.TRUE);
  
        JAXBElement xproduct = new JAXBElement(new QName("", "product"), 
            ru.soglasie.b2b.ccm.client.DescProduct.class, product);
  
        m.marshal(xproduct, file);
      }
      catch (java.lang.Exception e)
      {
        System.out.println("getProductDescCache:" + e.getMessage());
        file.delete();
      }
    }
  
    return product;
  }
  
  public Catalog getCatalogCache(CCM client, int calcid, int catalogid) throws java.lang.Exception
  {
    Catalog catalog = null;
 
    if(catalogid > 0)
    {
      String dist_catalog = String.format("%s/ccmc4_c%d.xml", getCacheDir(), catalogid);
      File dist_file = new File(dist_catalog);
      File calc_file = null;

      try
      {
        if(dist_file.exists())
        {
          JAXBContext jc = JAXBContext.newInstance("ru.soglasie.b2b.ccm.client");
          Unmarshaller um = jc.createUnmarshaller();
          
          JAXBElement<Catalog> root = um.unmarshal(new StreamSource(dist_file), Catalog.class);
          catalog = root.getValue();
          
          if(catalog != null)
            return catalog;
        }
      }
      catch (java.lang.Exception e)
      {
        System.out.println("getCatalogCache:" + e.getMessage());
        dist_file.delete();
      }
      
      if(calcid > 0)
      {
        String calc_catalog = String.format("%s/ccmc4_c%d_%d.xml", getCacheDir(), catalogid, calcid);
        calc_file = new File(calc_catalog);
  
        try
        {    
          if(calc_file.exists())
          {
            JAXBContext jc = JAXBContext.newInstance("ru.soglasie.b2b.ccm.client");
            Unmarshaller um = jc.createUnmarshaller();
            
            JAXBElement<Catalog> root = um.unmarshal(new StreamSource(calc_file), Catalog.class);
            catalog = root.getValue();
            
            if(catalog != null)
              return catalog;
          }
        }
        catch (java.lang.Exception e)
        {
          System.out.println("getCatalogCache:" + e.getMessage());
          calc_file.delete();
        }
      }
  
      long process_time = System.currentTimeMillis();
      catalog = client.getCatalog(calcid, catalogid);
      process_time = System.currentTimeMillis() - process_time;
      
      System.out.println(String.format("getCatalog(%d) = %.2f c", catalogid, process_time/1000.0));
      
      try
      {
        JAXBContext jcc = JAXBContext.newInstance(catalog.getClass());
        Marshaller m = jcc.createMarshaller();
  
        m.setProperty(Marshaller.JAXB_ENCODING, "UTF-8");
        m.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, Boolean.TRUE);
  
        JAXBElement xcatalog = new JAXBElement(new QName("", "catalog"), 
            ru.soglasie.b2b.ccm.client.Catalog.class, catalog);
  
        m.marshal(xcatalog, (catalog.isPercalc() && calcid > 0) ? calc_file : dist_file);
      }
      catch (java.lang.Exception e)
      {
        System.out.println("getCatalogCache:" + e.getMessage());
        dist_file.delete();
        calc_file.delete();
      }
    }
        
    return catalog;
  }
%>