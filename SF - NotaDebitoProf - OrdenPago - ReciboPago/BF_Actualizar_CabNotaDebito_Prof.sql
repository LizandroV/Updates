CREATE PROCEDURE [dbo].[BF_Actualizar_CabNotaDebito_Prof]   
@codOrdNotaDebito numeric(9,0),  
@cod_negocio  numeric(9,0),  
@cod_empresa  numeric(9,0),  
@cod_notacli  numeric(9,0),  
@fecha_nota   varchar(10),  
@cod_mot   int,  
@subTotal   numeric(9,2),  
@montoTotal   numeric(9,2),  
@montoCambio  numeric(9,2),  
@usuario   numeric(9,0),  
@comentario   text,  
@tipo_almacen  numeric(9,0)  
AS  
BEGIN  
  
SET NOCOUNT ON;  
set dateformat dmy  
  
UPDATE [dbo].[CABNOTADEBITO_PROF]  
 SET [CodNotaEmp] = @cod_empresa  
  ,[CodNotaCli] = @cod_notacli  
  ,[FechaNota] = @fecha_nota  
  ,[MotCod] = @cod_mot  
  ,[SubTotal] = @subTotal  
  ,[MontTotal] = @montoTotal  
  ,[MontCambio] = @montoCambio  
  ,[FecMod] = getdate()  
  ,[UsuMod] = @usuario      
  ,[Comentario] = @comentario  
  ,[tipo_almacen] = @tipo_almacen  
 WHERE [CodOrdNotaDeb]=@codOrdNotaDebito and [CodNotaNeg]=@cod_negocio  
END  