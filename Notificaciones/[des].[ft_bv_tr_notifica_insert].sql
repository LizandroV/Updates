ALTER TRIGGER [dbo].[ft_bv_tr_notifica_insert]
ON [dbo].[CABORDFAC]
FOR INSERT
AS
BEGIN TRY
    DECLARE @doc NVARCHAR(15)
    DECLARE @fecha DATETIME
    DECLARE @tipo NVARCHAR(2)
    DECLARE @descripcion NVARCHAR(50)

    SELECT TOP 1 
        @doc = f.Factura, 
        @fecha = f.FecReg,
        @tipo = CASE 
                    WHEN f.Factura LIKE 'B%' THEN 'BV' 
                    WHEN f.Factura LIKE 'F%' THEN 'FT' 
                END
    FROM inserted f
    LEFT JOIN dbo.EMPRESA e ON f.CodFacEmp = e.EmpCod 
    WHERE e.EmpEst = 'A'
      AND e.EmpCod IN (4,9) 
      AND f.Estado NOT IN ('C') 
      AND f.Factura LIKE '[BF]%'

    IF @doc IS NOT NULL
    BEGIN
        SET @descripcion = CASE 
                               WHEN @tipo = 'BV' THEN 'Boleta: BV N° ' + @doc
                               WHEN @tipo = 'FT' THEN 'Factura: FT N° ' + @doc
                           END

        INSERT INTO dbo.notificacion_vigilancia (descrip, tipo_doc, numero_doc, cod_ordserv, fecha_doc, estado) 
        VALUES (@descripcion, @tipo, @doc, 0, @fecha, 0)
    END

END TRY
BEGIN CATCH
    ROLLBACK TRANSACTION;   
    THROW;  
END CATCH;