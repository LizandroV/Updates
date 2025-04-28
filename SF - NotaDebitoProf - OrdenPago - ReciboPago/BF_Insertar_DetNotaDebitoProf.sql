CREATE PROCEDURE [dbo].[BF_Insertar_DetNotaDebitoProf]   
@CodOrdNotaDeb numeric(9,0),  
@CodNotaNeg  numeric(9,0),  
@CodNotaFac  numeric(9,0),  
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
  
 INSERT INTO [dbo].[DETNOTADEBITO_PROF]  
           ([CodOrdNotaDeb]  
           ,[CodNotaNeg]  
           ,[CodNotaProf]  
           ,[Cantidad]  
     ,[MedCod]  
           ,[Glosa]  
           ,[Punitario]  
           ,[Monto]  
           ,[FecReg]  
           ,[UsuReg]  
           ,[Estado])  
     VALUES  
           (@CodOrdNotaDeb  
           ,@CodNotaNeg  
           ,@CodNotaFac  
           ,@Cantidad  
     ,@Medcod  
           ,@Glosa  
           ,@Punitario  
           ,@Monto  
           ,getdate()  
           ,@usuario  
           ,'A');  
  
  -----------------SE AGREGO "ENE 2022", Si hay se registra una  N.C. en esta tabla cambiar de 1 -->  2 -------------------  
  if exists(select *  
     from DETORDPROF_ASOCIADAS a  
     where estado ='P' and isnull(SaldoParaNota,0) > 0    
       and CodOrdProf = @CodNotaFac and CodProfNeg = @CodNotaNeg  
       and EstadoNota = 1 )  
  begin  
    update DETORDPROF_ASOCIADAS set EstadoNota = 2  
    where estado ='P' and isnull(SaldoParaNota,0) > 0 and   
      CodOrdProf = @CodNotaFac and   
      CodProfNeg = @CodNotaNeg and   
      EstadoNota = 1;  
  end;     
END