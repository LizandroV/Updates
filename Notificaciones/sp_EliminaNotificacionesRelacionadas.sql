ALTER PROCEDURE sp_EliminarNotificacion_Relacion
    @tipodoc NVARCHAR(5),
    @nrodoc NVARCHAR(50)
AS
BEGIN
    SET NOCOUNT ON;
	DECLARE @codPL as NUMERIC(9,0);
	DECLARE @codGuia as NVARCHAR(15);
	DECLARE @codFac as NVARCHAR(15);
	DECLARE @codProf as NUMERIC(9,0);

    IF @tipodoc = 'PT'
    BEGIN
        SELECT TOP 1 @codPL=p.CodPL, @codGuia=d.GuiaDespacho, @codFac=f.Factura, @codProf=pp.Proforma
        FROM des.PLIST_CAB_TIENDA p 
        LEFT JOIN dbo.CABORDSERV s ON p.CodPL = s.cod_hilo_quimico 
            AND s.TipOrdServ = 'N' 
            AND s.CodServEmp IN (4,9) 
            AND s.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDDESP d ON s.CodOrdServ = d.CodOrdServ 
            AND s.CodServNeg = d.CodDespNeg 
            AND d.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDFAC f ON s.CodOrdServ = f.CodFacServ 
            AND s.CodServNeg = f.CodFacNeg 
            AND s.CodServEmp = f.CodFacEmp 
            AND f.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDPROF pp ON s.CodOrdServ = pp.CodProfServ 
            AND s.CodServNeg = pp.CodProfNeg 
            AND s.CodServEmp = pp.CodProfEmp 
            AND pp.Estado NOT IN ('C') 
        WHERE p.codemp_origen IN (4,9) 
            AND p.EstadoGeneral = 0 
            AND p.CodPL = @nrodoc
		ORDER BY s.FecReg DESC;
		
		DELETE FROM dbo.notificacion_vigilancia
		WHERE numero_doc IN (
			CAST(@codPL AS NVARCHAR(15)), 
			@codGuia, 
			@codFac, 
			CAST(@codProf AS NVARCHAR(15))
		);
	
    END
    ELSE IF @tipodoc = 'GR'
    BEGIN
        SELECT TOP 1 @codPL=p.CodPL, @codGuia=d.GuiaDespacho, @codFac=f.Factura, @codProf=pp.Proforma
        FROM des.PLIST_CAB_TIENDA p 
        LEFT JOIN dbo.CABORDSERV s ON p.CodPL = s.cod_hilo_quimico 
            AND s.TipOrdServ = 'N' 
            AND s.CodServEmp IN (4,9) 
            AND s.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDDESP d ON s.CodOrdServ = d.CodOrdServ 
            AND s.CodServNeg = d.CodDespNeg 
            AND d.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDFAC f ON s.CodOrdServ = f.CodFacServ 
            AND s.CodServNeg = f.CodFacNeg 
            AND s.CodServEmp = f.CodFacEmp 
            AND f.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDPROF pp ON s.CodOrdServ = pp.CodProfServ 
            AND s.CodServNeg = pp.CodProfNeg 
            AND s.CodServEmp = pp.CodProfEmp 
            AND pp.Estado NOT IN ('C') 
        WHERE p.codemp_origen IN (4,9) 
            AND p.EstadoGeneral = 0 
            AND d.GuiaDespacho = @nrodoc
		ORDER BY s.FecReg DESC;

		DELETE FROM dbo.notificacion_vigilancia
		WHERE numero_doc IN (
			CAST(@codPL AS NVARCHAR(15)), 
			@codGuia, 
			@codFac, 
			CAST(@codProf AS NVARCHAR(15))
		);
    END
    ELSE IF @tipodoc IN ('FT', 'BV')
    BEGIN
        SELECT TOP 1 @codPL=p.CodPL, @codGuia=d.GuiaDespacho, @codFac=f.Factura, @codProf=pp.Proforma
        FROM des.PLIST_CAB_TIENDA p 
        LEFT JOIN dbo.CABORDSERV s ON p.CodPL = s.cod_hilo_quimico 
            AND s.TipOrdServ = 'N' 
            AND s.CodServEmp IN (4,9) 
            AND s.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDDESP d ON s.CodOrdServ = d.CodOrdServ 
            AND s.CodServNeg = d.CodDespNeg 
            AND d.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDFAC f ON s.CodOrdServ = f.CodFacServ 
            AND s.CodServNeg = f.CodFacNeg 
            AND s.CodServEmp = f.CodFacEmp 
            AND f.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDPROF pp ON s.CodOrdServ = pp.CodProfServ 
            AND s.CodServNeg = pp.CodProfNeg 
            AND s.CodServEmp = pp.CodProfEmp 
            AND pp.Estado NOT IN ('C') 
        WHERE p.codemp_origen IN (4,9) 
            AND p.EstadoGeneral = 0 
            AND f.Factura = @nrodoc
		ORDER BY s.FecReg DESC;

		DELETE FROM dbo.notificacion_vigilancia
		WHERE numero_doc IN (
			CAST(@codPL AS NVARCHAR(15)), 
			@codGuia, 
			@codFac, 
			CAST(@codProf AS NVARCHAR(15))
		);
    END
    ELSE IF @tipodoc = 'PF'
    BEGIN
        SELECT TOP 1 @codPL=p.CodPL, @codGuia=d.GuiaDespacho, @codFac=f.Factura, @codProf=pp.Proforma
        FROM des.PLIST_CAB_TIENDA p 
        LEFT JOIN dbo.CABORDSERV s ON p.CodPL = s.cod_hilo_quimico 
            AND s.TipOrdServ = 'N' 
            AND s.CodServEmp IN (4,9) 
            AND s.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDDESP d ON s.CodOrdServ = d.CodOrdServ 
            AND s.CodServNeg = d.CodDespNeg 
            AND d.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDFAC f ON s.CodOrdServ = f.CodFacServ 
            AND s.CodServNeg = f.CodFacNeg 
            AND s.CodServEmp = f.CodFacEmp 
            AND f.Estado NOT IN ('C') 
        LEFT JOIN dbo.CABORDPROF pp ON s.CodOrdServ = pp.CodProfServ 
            AND s.CodServNeg = pp.CodProfNeg 
            AND s.CodServEmp = pp.CodProfEmp 
            AND pp.Estado NOT IN ('C') 
        WHERE p.codemp_origen IN (4,9) 
            AND p.EstadoGeneral = 0 
            AND pp.CodOrdProf = @nrodoc
		ORDER BY s.FecReg DESC;

		DELETE FROM dbo.notificacion_vigilancia
		WHERE numero_doc IN (
			CAST(@codPL AS NVARCHAR(15)), 
			@codGuia, 
			@codFac, 
			CAST(@codProf AS NVARCHAR(15))
		);
    END
    ELSE
    BEGIN
        PRINT 'Tipo de documento no v�lido.';
    END
END;