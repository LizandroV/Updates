CREATE PROCEDURE [dbo].[BF_EliminarNotaDebitoProf]   
@cod_nota numeric(9,0),  
@cod_neg  numeric(9,0),  
@usuario  numeric(9,0)  
AS  
BEGIN  
 SET NOCOUNT ON;  
  
 IF EXISTS(SELECT [CodOrdNotaDeb], [CodNotaNeg]  
    FROM [dbo].[CABNOTADEBITO_PROF]   
    WHERE [CodOrdNotaDeb]=@cod_nota and [CodNotaNeg]=@cod_neg)  
  
 BEGIN  
  update [CABNOTADEBITO_PROF] set estado='E', usumod=@usuario, FecReg=getdate() where [CodOrdNotaDeb]=@cod_nota and [CodNotaNeg]=@cod_neg  
  update [DETNOTADEBITO_PROF] set estado='E', usumod=@usuario, FecReg=getdate() where [CodOrdNotaDeb]=@cod_nota and [CodNotaNeg]=@cod_neg  
 END  
  
END  
  
  
  