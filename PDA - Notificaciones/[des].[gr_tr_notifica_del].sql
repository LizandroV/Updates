ALTER TRIGGER [dbo].[gr_tr_notifica_del] 
ON [dbo].[CABORDDESP]      
FOR UPDATE  
AS   
BEGIN TRY
    DECLARE @numguia nvarchar(15)

    SELECT TOP 1 @numguia = d.GuiaDespacho
    FROM inserted d
    LEFT JOIN dbo.CABORDSERV s ON d.CodOrdServ = s.CodOrdServ AND d.CodDespNeg = s.CodServNeg
    LEFT JOIN dbo.EMPRESA e ON s.CodServEmp = e.EmpCod
    WHERE e.EmpCod IN (4,9) 
    AND d.Estado = 'C'

    IF @numguia IS NOT NULL
    BEGIN
        DELETE FROM dbo.notificacion_vigilancia 
        WHERE tipo_doc = 'GR' AND numero_doc = @numguia
    END

END TRY  
BEGIN CATCH
    ROLLBACK TRANSACTION;   
    THROW;  
END CATCH;
