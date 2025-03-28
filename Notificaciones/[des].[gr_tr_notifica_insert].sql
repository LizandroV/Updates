ALTER TRIGGER [dbo].[gr_tr_notifica_insert]
ON [dbo].[CABORDDESP]
FOR INSERT  
AS
BEGIN TRY
    DECLARE @numguia NVARCHAR(15)
    DECLARE @fecha DATETIME

    SELECT TOP 1 @numguia = d.GuiaDespacho, @fecha = d.FecReg 
    FROM inserted d
    LEFT JOIN dbo.CABORDSERV s ON d.CodOrdServ = s.CodOrdServ AND d.CodDespNeg = s.CodServNeg
    LEFT JOIN dbo.EMPRESA e ON s.CodServEmp = e.EmpCod
    WHERE e.EmpCod IN (4,9) 
    AND d.Estado NOT IN ('C')

	IF @numguia IS NOT NULL
	BEGIN
        INSERT INTO dbo.notificacion_vigilancia 
        VALUES ('Guia Remitente: GR N° ' + @numguia, 'GR', @numguia, 0, @fecha, 0)
	END

END TRY

BEGIN CATCH
    ROLLBACK TRANSACTION;   
    THROW;  
END CATCH;