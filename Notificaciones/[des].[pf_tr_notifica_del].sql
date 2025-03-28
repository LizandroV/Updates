ALTER TRIGGER [dbo].[pf_tr_notifica_del]
ON [dbo].[CABORDPROF]
FOR UPDATE  
AS   
BEGIN TRY   

    DECLARE @proforma numeric(9,0)
	DECLARE @codser numeric(9,0)

    SELECT TOP 1 @proforma = p.Proforma, @codser = p.CodProfServ
    FROM inserted p LEFT JOIN dbo.EMPRESA e ON p.CodProfEmp=e.EmpCod 
    WHERE e.EmpEst = 'A'
	and e.EmpCod IN (4,9) 
	and p.Estado = 'C'

    IF @proforma IS NOT NULL
    BEGIN
        DELETE FROM dbo.notificacion_vigilancia 
        WHERE tipo_doc = 'PF' AND numero_doc = @proforma and cod_ordserv = @codser
    END

END TRY  
BEGIN CATCH
    ROLLBACK TRANSACTION;   
    THROW; 
END CATCH;
