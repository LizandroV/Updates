ALTER TRIGGER [dbo].[pf_tr_notifica_insert]
ON [dbo].[CABORDPROF]
FOR INSERT
AS
BEGIN TRY

    DECLARE @proforma numeric(9,0)
    DECLARE @fecha DATETIME
	DECLARE @codser numeric(9,0)


    SELECT TOP 1 @proforma = p.Proforma, @fecha = p.FecReg, @codser = p.CodProfServ
    FROM inserted p LEFT JOIN dbo.EMPRESA e ON p.CodProfEmp=e.EmpCod 
    WHERE e.EmpEst = 'A'
	and e.EmpCod IN (4,9) 
	and p.Estado NOT IN ('C')

	IF @proforma IS NOT NULL
    BEGIN
        INSERT INTO dbo.notificacion_vigilancia 
        VALUES ('Proforma: PF N° ' + CAST(@proforma AS NVARCHAR(20)), 'PF', @proforma, @codser, @fecha, 0)
    END

END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION;
    THROW;  
END CATCH;