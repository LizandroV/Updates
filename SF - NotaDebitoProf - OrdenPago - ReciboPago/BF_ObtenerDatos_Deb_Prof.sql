  
CREATE PROCEDURE [dbo].[BF_ObtenerDatos_Deb_Prof]   
@cod_negocio numeric(9,0),  
@cod_empresa numeric(9,0),  
@cod_cliente numeric(9,0),  
@cod_creditos varchar(MAX)  
AS  
BEGIN  
 SET NOCOUNT ON;  
 SET DATEFORMAT DMY  
  
 DECLARE @r VARCHAR(MAX),     
   @C char(1),     
   @E char(1)  
  
 set @C='C'  
 set @E='E'  
  
 --------------AGREGADO ENERO, 2015 -------(No estaba jalando lo de la tabla PARAMETRO)--------  
 declare @val_detrac_tabla money   
 SET @val_detrac_tabla = ( select detraccion from PARAMETRO )  
 ----------------------------------------------------------------------------------------------   
  
 IF @cod_negocio != 5  
 BEGIN  
  SET @r ='SELECT deb.CodOrdNotaDeb as codigo,  
      deb.NotaDebito as fisico,  
      LEFT(convert(varchar,(deb.FechaNota),103),12) as fecreg,  
      deb.montTotal as impconigv,      
      deb.montTotal as impxcobrar,            
      0 as detraccion,  
      0 retencion,  
      c.tipmoneda,  
      c.CodProfServ as codservicio,  
      isnull(c.TipCambio,0) as cambio  
    FROM CABORDPROF c, empresa e, cliente cli, negocio n, CABNOTADEBITO_PROF deb  
    WHERE e.EmpCod=c.CodProfEmp and   
      n.NegCod=c.CodProfNeg and  
      cli.CliCod=c.CodProfCli and  
      deb.CodNotaCli = c.CodProfCli and  
      deb.CodNotaEmp = c.CodProfEmp and   
      deb.CodNotaProf = c.CodOrdProf and       
      deb.CodNotaNeg =' + CAST(@cod_negocio as varchar(9))+ ' and  
      cli.CliCod=' + CAST(@cod_cliente as varchar(9))+ ' and  
      e.EmpCod=' + CAST(@cod_empresa as varchar(9))+ ' and  
      c.estado not in ('''+ CAST(@E as char(1))+''')   
      and deb.CodOrdNotaDeb in ('+ CAST(@cod_creditos as varchar(MAX))+')   
    ORDER BY deb.CodOrdNotaDeb, c.fecreg desc'  
 END  
  
 IF @cod_negocio = 5  
 BEGIN  
  SET @r ='SELECT deb.CodOrdNotaDeb as codigo,    
      deb.NotaDebito as fisico,  
      LEFT(convert(varchar,(deb.FechaNota),103),12) as fecreg,  
      deb.montTotal as impconigv,      
      deb.montTotal as impxcobrar,           
      0 as detraccion,  
      0 retencion,  
      c.tipmoneda,  
      c.CodProfServ as codservicio,  
      c.TipCambio as cambio  
    FROM CABORDPROF c, empresa e, cliente cli, negocio n, CABNOTADEBITO_PROF deb  
    WHERE e.EmpCod=c.CodProfEmp and   
      n.NegCod=c.CodProfNeg and  
      cli.CliCod=c.CodProfCli and  
      deb.CodNotaCli = c.CodProfCli and  
      deb.CodNotaEmp = c.CodProfEmp and   
      deb.CodNotaProf = c.CodOrdProf and 
      deb.CodNotaNeg=' + CAST(@cod_negocio as varchar(9))+ ' and  
      cli.CliCod=' + CAST(@cod_cliente as varchar(9))+ ' and  
      e.EmpCod=' + CAST(@cod_empresa as varchar(9))+ ' and  
      c.estado not in (  '''+ CAST(@E as char(1))+''' )   
      and deb.CodOrdNotaDeb in ('+ CAST(@cod_creditos as varchar(MAX))+')   
    ORDER BY deb.CodOrdNotaDeb, c.fecreg desc'  
 END  
  
  
 exec (@r)  
  
END  