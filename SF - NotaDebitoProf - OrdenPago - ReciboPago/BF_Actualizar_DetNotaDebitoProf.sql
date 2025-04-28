CREATE PROCEDURE [dbo].[BF_Actualizar_DetNotaDebitoProf]   
@CodOrdNotaDeb numeric(9,0),  
@CodNotaNeg  numeric(9,0),  
@Cantidad  decimal(9,2),  
@Medcod   numeric(9,0),  
@Glosa   text,  
@Punitario  decimal(19,6),  
@Monto   decimal(9,2),   
@usuario  numeric(9,0)     
AS  
BEGIN  
 SET NOCOUNT ON;  
  
 set dateformat dmy   
  
 UPDATE [dbo].[DETNOTADEBITO_PROF]  
  SET [Cantidad] = @Cantidad  
   ,[MedCod] = @Medcod  
   ,[Glosa] = @Glosa  
   ,[Punitario] = @Punitario  
   ,[Monto] = @Monto   
   ,[FecMod] = getdate()  
   ,[UsuMod] = @usuario  
  WHERE [CodDetNotaDeb]=@CodOrdNotaDeb and [CodNotaNeg]=@CodNotaNeg  
END  