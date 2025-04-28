  
ALTER PROCEDURE [dbo].[BF_Exportar_Proforma_Detallado]  
@cod_negocio numeric(9,0),   
@cod_cliente numeric(9,0),  
@cod_empresa numeric(9,0),  
@fec_ini varchar(10),  
@fec_fin varchar(10)  
AS  
BEGIN  
 SET NOCOUNT ON;  
  
    -- Insert statements for procedure here  
 DECLARE @r VARCHAR(MAX),  
   @P char(1),  
   @C char(1),  
   @E char(1),  
   @PROF char(4),  
   @DSCTO char(5),  
   @RP char(2)  
  
 set @P='P'  
 set @C='C'  
 set @E='E'  
 set @PROF= 'PROF'   
 set @DSCTO ='DSCTO'  
 set @RP='RP'  
  
 set dateformat dmy  
 SET @r = '  select '''+ CAST(@PROF as char(4))+''' as tipodoc,   
       cp.codordprof as codigo,  
       c.cliraz,  
       e.empraz,     
       LEFT(convert(varchar,cp.Fecreg,103),12) as fecha,  
       cp.monttotal as importe1,  
       0   as importe2,  
       cp.Fecreg  
     from cabordprof cp,  empresa e, cliente c  
     where e.empcod=cp.codprofemp and c.clicod=cp.codprofcli and   
       cp.codprofneg = ' + CAST(@cod_negocio as varchar(9)) + '  and   
       cp.estado in ('''+ CAST(@P as char(1))+''') '  
 IF @cod_cliente!=0  
 BEGIN  
   SET @r = @r + ' and c.CliCod = ' + CAST(@cod_cliente as varchar(9))   
 END  
 IF @cod_empresa!=0  
 BEGIN  
   SET @r = @r + ' and e.empcod = ' + CAST(@cod_empresa as varchar(9))   
 END  
 IF @fec_ini!='' and @fec_fin!=''  
 BEGIN  
   SET @r = @r + ' and convert(datetime,convert(varchar(10),cp.FecReg,103)) between ''' + @fec_ini  + ''' and ''' +@fec_fin +'''   '  
 END  
  
 SET @r = @r+ ' union '  
   
 SET @r = @r+ ' select '''+ CAST(@DSCTO as char(5))+''' as tipodoc,  
       ds.codorddscto as codigo,  
       c.cliraz,  
       e.empraz,   
       LEFT(convert(varchar,ds.fecreg,103),12) as fecha,  
       sum (d.importe)  as importe1,  
       0    as importe2,  
       ds.fecreg  
     from caborddscto ds, empresa e, cliente c      , detorddscto d  
     where   
       ds.codorddscto=d.codorddscto and   
       ds.coddsctoneg=d.coddsctoneg and  
       
       ds.coddsctoemp=e.empcod and ds.coddsctocli=c.clicod and   
       ds.tipodoc in ('''+ CAST(@P as char(1))+''') and   
       ds.estado not in ('''+ CAST(@C as char(1))+''','''+ CAST(@E as char(1))+''')  
       and ds.coddsctoneg = ' + CAST(@cod_negocio as varchar(9)) + ' '         
 IF @cod_cliente!=0  
 BEGIN  
   SET @r = @r + ' and c.CliCod = ' + CAST(@cod_cliente as varchar(9))   
 END  
 IF @cod_empresa!=0  
 BEGIN  
   SET @r = @r + ' and e.empcod = ' + CAST(@cod_empresa as varchar(9))   
 END  
 IF @fec_ini!='' and @fec_fin!=''  
 BEGIN  
   SET @r = @r + ' and convert(datetime,convert(varchar(10),ds.fecreg,103)) between ''' + @fec_ini  + ''' and ''' +@fec_fin +'''  '  
 END  
  
   SET @r = @r+ ' group by ds.codorddscto,c.cliraz,e.empraz,ds.fecreg '  
  
  
 SET @r = @r+ ' union '  
       
  
 SET @r = @r+ ' select '''+ CAST(@RP as char(2))+''' as tipodoc,  
       re.codregpag as codigo,  
       c.cliraz,  
       e.empraz,  
       LEFT(convert(varchar,re.fecreg,103),12) as fecha,   
       CAST(re.SumAbon as money) as importe1,  
       CAST(re.TotalRecib as money) as importe2,  
       re.fecreg  
     from cabregpago re  
     , empresa e, cliente c  
     where re.codregemp = e.empcod and re.codregcli = c.clicod and   
       re.estado not in ('''+ CAST(@C as char(1))+''','''+ CAST(@E as char(1))+''') and  
       re.estado=''I'' and /*re.TipoCob=''C'' and*/   
       re.codregneg = ' + CAST(@cod_negocio as varchar(9)) + ' '         
 IF @cod_cliente!=0  
 BEGIN  
   SET @r = @r + ' and c.CliCod = ' + CAST(@cod_cliente as varchar(9))   
 END  
 IF @cod_empresa!=0  
 BEGIN  
   SET @r = @r + ' and e.empcod = ' + CAST(@cod_empresa as varchar(9))   
 END  
 IF @fec_ini!='' and @fec_fin!=''  
 BEGIN  
   SET @r = @r + ' and convert(datetime,convert(varchar(10),re.fecreg,103)) between ''' + @fec_ini  + ''' and ''' +@fec_fin +'''  '  
 END  
  
 ---- NOTA DE CREDITO DE PROFORMA  
  
 SET @r = @r+ ' union '  
       
 SET @r = @r+ ' select ''NC'' as tipodoc,  
       cp.CodOrdNotaCre as codigo,  
       c.cliraz,  
       e.empraz,  
       LEFT(convert(varchar,cp.fecreg,103),12) as fecha,   
       0 as importe1,  
       CAST(cp.MontTotal as money) as importe2,  
       cp.fecreg  
     from CABNOTACREDITO_PROF cp , empresa e, cliente c  
     where cp.CodNotaEmp=e.empcod and cp.CodNotaCli=c.clicod and   
       cp.estado not in(''C'',''E'') and  
       cp.estado=''A'' and    
       cp.CodNotaNeg=' + CAST(@cod_negocio as varchar(9)) + ' '         
 IF @cod_cliente!=0  
 BEGIN  
   SET @r = @r + ' and c.CliCod = ' + CAST(@cod_cliente as varchar(9))   
 END  
 IF @cod_empresa!=0  
 BEGIN  
   SET @r = @r + ' and e.empcod = ' + CAST(@cod_empresa as varchar(9))   
 END  
 IF @fec_ini!='' and @fec_fin!=''  
 BEGIN  
   SET @r = @r + ' and convert(datetime,convert(varchar(10),cp.fecreg,103)) between ''' + @fec_ini  + ''' and ''' +@fec_fin +'''  '  
 END  
  
 ---- NOTA DE CREDITO DE PROFORMA  

  ---- NOTA DE DEBITO DE PROFORMA  
  
 SET @r = @r+ ' union '  
       
 SET @r = @r+ ' select ''ND'' as tipodoc,  
       cp.CodOrdNotaDeb as codigo,  
       c.cliraz,  
       e.empraz,  
       LEFT(convert(varchar,cp.fecreg,103),12) as fecha,   
       0 as importe1,  
       CAST(cp.MontTotal as money) as importe2,  
       cp.fecreg  
     from CABNOTADEBITO_PROF cp , empresa e, cliente c  
     where cp.CodNotaEmp=e.empcod and cp.CodNotaCli=c.clicod and   
       cp.estado not in(''C'',''E'') and  
       cp.estado=''A'' and    
       cp.CodNotaNeg=' + CAST(@cod_negocio as varchar(9)) + ' '         
 IF @cod_cliente!=0  
 BEGIN  
   SET @r = @r + ' and c.CliCod = ' + CAST(@cod_cliente as varchar(9))   
 END  
 IF @cod_empresa!=0  
 BEGIN  
   SET @r = @r + ' and e.empcod = ' + CAST(@cod_empresa as varchar(9))   
 END  
 IF @fec_ini!='' and @fec_fin!=''  
 BEGIN  
   SET @r = @r + ' and convert(datetime,convert(varchar(10),cp.fecreg,103)) between ''' + @fec_ini  + ''' and ''' +@fec_fin +'''  '  
 END  
  
 ---- NOTA DE DEBITO DE PROFORMA  
  
   SET @r = @r + ' order by 8 desc'  
  
 exec( @r )  
 --print(@r)  
END  