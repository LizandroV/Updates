ALTER TRIGGER [dbo].[ft_bv_tr_notifica_del]
ON [dbo].[CABORDFAC]
FOR UPDATE  
AS   
BEGIN TRY
    DECLARE @doc NVARCHAR(15)
    DECLARE @tipo NVARCHAR(2)

    SELECT TOP 1 
        @doc = f.Factura,
        @tipo = CASE 
                    WHEN f.Factura LIKE 'B%' THEN 'BV' 
                    WHEN f.Factura LIKE 'F%' THEN 'FT' 
                END
    FROM inserted f
    LEFT JOIN dbo.EMPRESA e ON f.CodFacEmp = e.EmpCod 
    WHERE e.EmpEst = 'A'
      AND e.EmpCod IN (4,9) 
      AND f.Estado = 'C'
      AND f.Factura LIKE '[BF]%'

    IF @doc IS NOT NULL
    BEGIN
        DELETE FROM dbo.notificacion_vigilancia 
        WHERE tipo_doc = @tipo AND numero_doc = @doc
    END

END TRY  
BEGIN CATCH
    ROLLBACK TRANSACTION;   
    THROW;  
END CATCH;
