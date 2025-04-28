-- =============================================  
ALTER PROCEDURE [dbo].[BF_DatosRegistraPago]   
@cod_negocio numeric(9,0),     
@cod_cobro  char(1),      
@cod_pago numeric(9,0),  
@registro char(4)  --este parametro indica si muestra los datos para reg pago o reg datracc o autodetraccion     
  
AS  
BEGIN  
  
 SET NOCOUNT ON;  
 SET DATEFORMAT DMY  
  
    -- Insert statements for procedure here  
 DECLARE @tipoDocumento char(1)  
 SET @tipoDocumento = ( SELECT TIPODOC   
       FROM CABORDPAGO   
       WHERE CODORDPAG=@cod_pago    
         AND CODPAGNEG=@cod_negocio  
         AND TIPOCOB=@cod_cobro  
      )  
  
 ---================REG. PAGO ================================  
  
 IF @registro = 'RPAG'  
 BEGIN  
 -------------------------------------  
   IF @tipoDocumento='F'  
   BEGIN  
    SELECT CODDOC AS CODIGO,  
      ( SELECT FACTURA   
       FROM CABORDFAC   
       WHERE CODORDFAC=CODDOC AND CODFACNEG=@cod_negocio  
      ) AS FISICO,      
      FECDOC, MONTDOC, MONTCOB,CONTROL, MONTDET,   
      TIPMON, @tipoDocumento as doc, CODPAG ,  
      TIPODOC as TIPODOCDETALLE   
    FROM DETORDPAGO   
    WHERE CODORDPAG=@cod_pago AND   
      CODPAGNEG=@cod_negocio AND   
      TIPOCOB=@cod_cobro and  
      estado not in ('C','E','T')  
    ORDER BY CODPAG  
   END  
     
  
   IF @tipoDocumento='P'  
   BEGIN  
    SELECT CODDOC AS CODIGO,  

      CASE 
		WHEN TIPODOC = 'H' THEN 
			(SELECT NOTACREDITO 
			 FROM CABNOTACREDITO_PROF  
			 WHERE CodOrdNotaCre = CODDOC 
			   AND CodNotaNeg = @cod_negocio 
			   AND Estado NOT IN ('E','C'))

		WHEN TIPODOC = 'I' THEN 
			(SELECT NotaDebito 
			 FROM CABNOTADEBITO_PROF  
			 WHERE CodOrdNotaDeb = CODDOC 
			   AND CodNotaNeg = @cod_negocio 
			   AND Estado NOT IN ('E','C'))

		ELSE 
			(SELECT CONVERT(VARCHAR(50), PROFORMA) 
				FROM CABORDPROF  
				WHERE CODORDPROF = CODDOC 
				AND CODPROFNEG = @cod_negocio)
	END AS FISICO,

      FECDOC, MONTDOC, MONTCOB,CONTROL, MONTDET,   
      TIPMON, @tipoDocumento as doc, CODPAG ,  
      TIPODOC as TIPODOCDETALLE  
    FROM DETORDPAGO   
    WHERE CODORDPAG=@cod_pago AND   
      CODPAGNEG=@cod_negocio AND   
      TIPOCOB=@cod_cobro and  
      estado not in ('C','E','T')  
    ORDER BY CODPAG 
   END  
 ------------------------------------  
 END  
  
  
 ----================DETRACCION ======================  
  
 IF @registro = 'RDET'  
 BEGIN  
 ------------------------------------  
   IF @tipoDocumento='F'  
   BEGIN  
  
    SELECT CODDOC AS CODIGO,  
      ( SELECT FACTURA   
       FROM CABORDFAC   
       WHERE CODORDFAC=CODDOC AND CODFACNEG=@cod_negocio  
      ) AS FISICO,      
      FECDOC, MONTDOC, MONTCOB, MONTDET, CONTROLDETRA,  
      TIPMON, @tipoDocumento as doc, CodPag,   
      ( SELECT TipCambio   
       FROM CABORDFAC   
       WHERE CODORDFAC=CODDOC AND CODFACNEG=@cod_negocio  
      ) AS CAMBIO ,  
      TIPODOC as TIPODOCDETALLE  
    FROM DETORDPAGO   
    WHERE CODORDPAG=@cod_pago AND   
      CODPAGNEG=@cod_negocio AND   
      TIPOCOB=@cod_cobro and  
      estadodet not in ('C','E','T')  
    ORDER BY CODPAG  
   END  
     
  
  
   IF @tipoDocumento='P'  
   BEGIN  
  
    SELECT CODDOC AS CODIGO,  
      ( SELECT PROFORMA   
       FROM CABORDPROF   
       WHERE CODORDPROF=CODDOC AND CODPROFNEG=@cod_negocio  
      ) AS FISICO,  
      FECDOC, MONTDOC, MONTCOB, MONTDET, CONTROLDETRA,  
      TIPMON, @tipoDocumento as doc, CodPag,  
      ( SELECT TipCambio   
       FROM CABORDPROF   
       WHERE CODORDPROF=CODDOC AND CODPROFNEG=@cod_negocio  
      ) AS CAMBIO ,  
      TIPODOC as TIPODOCDETALLE  
    FROM DETORDPAGO   
    WHERE CODORDPAG=@cod_pago AND   
      CODPAGNEG=@cod_negocio AND   
      TIPOCOB=@cod_cobro and  
      estadodet not in ('C','E'         ,'T')  
    ORDER BY CODPAG  
  
   END  
 END  
  
 ----================ AUTODETRACCION ======================  
  
 IF @registro = 'RAUD'  
 BEGIN  
  
   IF @tipoDocumento='F'  
   BEGIN  
  
    SELECT CODDOC AS CODIGO,  
      ( SELECT FACTURA   
       FROM CABORDFAC   
       WHERE CODORDFAC=CODDOC AND CODFACNEG=@cod_negocio  
      ) AS FISICO,      
      FECDOC, MONTDOC, MONTCOB, (MONTDOC * (select detraccion from PARAMETRO))as autodetra,   
      TIPMON, @tipoDocumento as doc, CodPag,   
      ( SELECT TipCambio   
       FROM CABORDFAC   
       WHERE CODORDFAC=CODDOC AND CODFACNEG=@cod_negocio  
      ) AS CAMBIO ,  
      TIPODOC as TIPODOCDETALLE  
    FROM DETORDPAGO   
    WHERE CODORDPAG=@cod_pago AND   
      CODPAGNEG=@cod_negocio AND   
      TIPOCOB=@cod_cobro and  
      estadodet not in ('C','E')  
    ORDER BY CODPAG  
   END  
     
  
  
   IF @tipoDocumento='P'  
   BEGIN  
  
    SELECT CODDOC AS CODIGO,  
      ( SELECT PROFORMA   
       FROM CABORDPROF   
       WHERE CODORDPROF=CODDOC AND CODPROFNEG=@cod_negocio  
      ) AS FISICO,  
      FECDOC, MONTDOC, MONTCOB, (MONTDOC * (select detraccion from PARAMETRO))as autodetra,  
      TIPMON, @tipoDocumento as doc, CodPag,  
      ( SELECT TipCambio   
       FROM CABORDPROF   
       WHERE CODORDPROF=CODDOC AND CODPROFNEG=@cod_negocio  
      ) AS CAMBIO ,  
      TIPODOC as TIPODOCDETALLE  
    FROM DETORDPAGO   
    WHERE CODORDPAG=@cod_pago AND   
      CODPAGNEG=@cod_negocio AND   
      TIPOCOB=@cod_cobro and  
      estadodet not in ('C','E')  
    ORDER BY CODPAG  
  
   END  
   ---------------------------------------------------------  
 END  
  
  
END  