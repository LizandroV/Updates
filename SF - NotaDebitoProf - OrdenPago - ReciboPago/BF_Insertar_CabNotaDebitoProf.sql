  
CREATE PROCEDURE [dbo].[BF_Insertar_CabNotaDebitoProf]  
@codOrdNotaDebito numeric(9,0),  
@cod_negocio  numeric(9,0),  
@cod_notafac  numeric(9,0),  
@cod_empresa  numeric(9,0),  
@cod_notacli  numeric(9,0),  
@fecha_nota   varchar(10),  
@cod_mot   int,  
@subTotal   numeric(9,2),  
@montoTotal   numeric(9,2),  
@montoCambio  numeric(9,2),  
@usuario   numeric(9,0),  
@doc_nota   varchar(12),  
@comentario   text,  
@tipo_almacen  numeric(9,0)  
AS  
BEGIN  
  
 SET NOCOUNT ON;  
  
    -- Insert statements for procedure here  
 set dateformat dmy  
 INSERT INTO [dbo].[CABNOTADEBITO_PROF]  
           ([CodOrdNotaDeb]  
     ,[CodNotaNeg]  
           ,[CodNotaProf]  
           ,[CodNotaEmp]  
     ,[CodNotaCli]  
           ,[FechaNota]  
           ,[MotCod]  
           ,[SubTotal]  
     ,[MontTotal]  
           ,[MontCambio]  
           ,[FecReg]  
           ,[UsuReg]             
           ,[Estado]  
           ,[Comentario]  
           ,[NotaDebito]  
     ,[tipo_almacen])  
     VALUES  
           (@codOrdNotaDebito  
     ,@cod_negocio  
           ,@cod_notafac  
           ,@cod_empresa  
     ,@cod_notacli  
           ,@fecha_nota  
           ,@cod_mot  
     ,@subTotal  
           ,@montoTotal  
           ,@montoCambio  
           ,getdate()  
           ,@usuario          
           ,'A'  
           ,@comentario  
           ,rtrim(ltrim(@doc_nota))  
     ,@tipo_almacen  
     )  
END  