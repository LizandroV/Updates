ALTER TRIGGER [des].[tr_notifica_del] 
ON [des].[PLIST_CAB_TIENDA]      
FOR UPDATE  
AS   
BEGIN TRY   
    DECLARE @codpl NUMERIC(9,0)

    SELECT @codpl = CodPL 
    FROM inserted 
    WHERE EstadoGeneral = 1

    IF @codpl IS NOT NULL
    BEGIN
        DELETE FROM dbo.notificacion_vigilancia 
        WHERE tipo_doc = 'PT' AND numero_doc = CONVERT(VARCHAR, @codpl)
    END

END TRY  
BEGIN CATCH   
    ROLLBACK TRANSACTION;   
    THROW;  
END CATCH