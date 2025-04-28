ALTER PROCEDURE [dbo].[BF_DevolverDatos]   
@cod_negocio numeric(9,0),     
@cod_cobro  char(1),      
@cod_pago numeric(9,0)      
  
AS  
BEGIN  
 SET NOCOUNT ON;  
 SET DATEFORMAT DMY  
  
 DECLARE @tipoDocumento char(1)  
 SET @tipoDocumento = ( SELECT TIPODOC   
       FROM CABORDPAGO   
       WHERE CODORDPAG=@cod_pago    
         AND CODPAGNEG=@cod_negocio  
         AND TIPOCOB=@cod_cobro  
      )  
  
 -------------------------------------  
 IF @tipoDocumento='F'  
 BEGIN  
  SELECT CODDOC AS CODIGO,  
    isnull(( SELECT FACTURA   
     FROM CABORDFAC   
     WHERE CODORDFAC=CODDOC AND CODFACNEG=@cod_negocio  
    ),0) AS FISICO,  
    isnull(( SELECT CODFACSERV   
     FROM CABORDFAC   
     WHERE CODORDFAC=CODDOC AND CODFACNEG=@cod_negocio  
    ),0) AS CODSERVICIO,  
    isnull(( SELECT TIPCAMBIO  
     FROM CABORDFAC   
     WHERE CODORDFAC=CODDOC AND CODFACNEG=@cod_negocio  
    ),0) AS TIPCAMBIO,  
    FECDOC, MONTDOC, MONTCOB, MONTDET, TIPMON, CODPAG, @tipoDocumento as doc ,MONTRET       ,FECREG    
  FROM DETORDPAGO   
  WHERE CODORDPAG=@cod_pago AND   
    CODPAGNEG=@cod_negocio AND   
    TIPOCOB=@cod_cobro and Estado not in ('E','C') and tipodoc in ('F','P')  
  UNION ALL  
  SELECT CODDOC AS CODIGO,  
    isnull(( SELECT NotaCredito   
       FROM CABNOTACREDITO   
       WHERE CodOrdNotaCre=CODDOC AND CODNOTANEG=@cod_negocio  
    ),0) AS FISICO,  
    isnull(( SELECT a.CODFACSERV   
       FROM CABORDFAC a, CABNOTACREDITO b  
       WHERE a.CodFacNeg = b.CodNotaNeg and  
         a.CodOrdFac = b.CodNotaFac and   
         a.CodFacCli = b.CodNotaCli and  
         a.CodFacEmp = b.CodNotaEmp and       
         b.CodOrdNotaCre = CODDOC AND   
         a.CODFACNEG=@cod_negocio  
    ),0) AS CODSERVICIO,  
    isnull(( SELECT a.TIPCAMBIO  
       FROM CABORDFAC a, CABNOTACREDITO b  
       WHERE a.CodFacNeg = b.CodNotaNeg and  
         a.CodOrdFac = b.CodNotaFac and   
         a.CodFacCli = b.CodNotaCli and  
         a.CodFacEmp = b.CodNotaEmp and       
         b.CodOrdNotaCre = CODDOC AND  
         CODFACNEG=@cod_negocio  
    ),0) AS TIPCAMBIO,  
    FECDOC, MONTDOC, MONTCOB, MONTDET, TIPMON, CODPAG, 'C' as doc , MONTRET           ,FECREG    
  FROM DETORDPAGO   
  WHERE CODORDPAG=@cod_pago AND   
    CODPAGNEG=@cod_negocio AND   
    TIPOCOB=@cod_cobro and Estado not in ('E','C') and tipodoc = 'C'  
  UNION ALL  
  SELECT CODDOC AS CODIGO,  
    isnull(( SELECT NotaDebito   
       FROM CABNOTADEBITO   
       WHERE CodOrdNotaDeb=CODDOC AND CODNOTANEG=@cod_negocio  
    ),0) AS FISICO,  
    isnull(( SELECT a.CODFACSERV   
       FROM CABORDFAC a, CABNOTADEBITO b  
       WHERE a.CodFacNeg = b.CodNotaNeg and  
         a.CodOrdFac = b.CodNotaFac and   
         a.CodFacCli = b.CodNotaCli and  
         a.CodFacEmp = b.CodNotaEmp and       
         b.CodOrdNotaDeb = CODDOC AND   
         a.CODFACNEG=@cod_negocio  
    ),0) AS CODSERVICIO,  
    isnull(( SELECT a.TIPCAMBIO  
       FROM CABORDFAC a, CABNOTADEBITO b  
       WHERE a.CodFacNeg = b.CodNotaNeg and  
         a.CodOrdFac = b.CodNotaFac and   
         a.CodFacCli = b.CodNotaCli and  
         a.CodFacEmp = b.CodNotaEmp and       
         b.CodOrdNotaDeb = CODDOC AND  
         CODFACNEG=@cod_negocio  
    ),0) AS TIPCAMBIO,  
    FECDOC, MONTDOC, MONTCOB, MONTDET, TIPMON, CODPAG, 'D' as doc , MONTRET, FECREG    
  FROM DETORDPAGO   
  WHERE CODORDPAG=@cod_pago AND   
    CODPAGNEG=@cod_negocio AND   
    TIPOCOB=@cod_cobro and Estado not in ('E','C')  and tipodoc = 'D'         
  ORDER BY FECREG asc  
  
  
 END  
 -------------------------------------  
 -------------------------------------  
 IF @tipoDocumento='P'  
 BEGIN  
  SELECT CODDOC AS CODIGO,  
    isnull(( SELECT convert(varchar(50), PROFORMA)   
     FROM CABORDPROF   
     WHERE CODORDPROF=CODDOC AND CODPROFNEG=@cod_negocio  
    ),0) AS FISICO,  
    isnull(( SELECT convert(varchar(50),CODPROFSERV)   
     FROM CABORDPROF   
     WHERE CODORDPROF=CODDOC AND CODPROFNEG=@cod_negocio  
    ),'') AS CODSERVICIO,  
    isnull(( SELECT TIPCAMBIO   
     FROM CABORDPROF   
     WHERE CODORDPROF=CODDOC AND CODPROFNEG=@cod_negocio  
    ),0) AS TIPCAMBIO,  
    FECDOC, MONTDOC, MONTCOB, MONTDET, TIPMON, CODPAG, @tipoDocumento as doc,MONTRET ,FECREG    
  FROM DETORDPAGO   
  WHERE CODORDPAG=@cod_pago AND   
    CODPAGNEG=@cod_negocio AND   
    TIPOCOB=@cod_cobro and Estado not in ('E','C') and tipodoc in ('F','P')  
  UNION ALL  
  SELECT CODDOC AS CODIGO,  
    isnull(( SELECT NotaCredito   
       FROM CABNOTACREDITO_PROF   
       WHERE CodOrdNotaCre=CODDOC AND CODNOTANEG=@cod_negocio  
    ),'') AS FISICO,  
    isnull(( SELECT convert(varchar(50),a.CODPROFSERV)   
       FROM CABORDPROF a, CABNOTACREDITO_PROF b  
       WHERE a.CodProfNeg = b.CodNotaNeg and  
         a.CodOrdProf = b.CodNotaProf and   
         a.CodProfCli = b.CodNotaCli and  
         a.CodProfEmp = b.CodNotaEmp and       
         b.CodOrdNotaCre = CODDOC AND   
         a.CODPROFNEG=@cod_negocio  
    ),'') AS CODSERVICIO,  
    isnull(( SELECT a.TIPCAMBIO  
       FROM CABORDPROF a, CABNOTACREDITO_PROF b  
       WHERE a.CodProfNeg = b.CodNotaNeg and  
         a.CodOrdProf = b.CodNotaProf and   
         a.CodProfCli = b.CodNotaCli and  
         a.CodProfEmp = b.CodNotaEmp and       
         b.CodOrdNotaCre = CODDOC AND  
         CODPROFNEG=@cod_negocio  
    ),0) AS TIPCAMBIO,  
    FECDOC, MONTDOC, MONTCOB, MONTDET, TIPMON, CODPAG, 'H' as doc, MONTRET, FECREG    
  FROM DETORDPAGO   
  WHERE CODORDPAG=@cod_pago AND   
    CODPAGNEG=@cod_negocio AND   
    TIPOCOB=@cod_cobro and Estado not in ('E','C') and tipodoc='H'  
  UNION ALL  
  SELECT CODDOC AS CODIGO, 
	  isnull(( SELECT NotaDebito FROM CABNOTADEBITO_PROF WHERE CodOrdNotaDeb=CODDOC AND CODNOTANEG=@cod_negocio),'') AS FISICO, 
	  isnull(( SELECT convert(varchar(50),a.CODPROFSERV) FROM CABORDPROF a, CABNOTADEBITO_PROF b  
	   WHERE a.CodProfNeg = b.CodNotaNeg and  
			 a.CodOrdProf = b.CodNotaProf and   
			 a.CodProfCli = b.CodNotaCli and  
			 a.CodProfEmp = b.CodNotaEmp and       
			 b.CodOrdNotaDeb = CODDOC AND   
			 a.CODPROFNEG=@cod_negocio  
		),'') AS CODSERVICIO,  
		isnull(( SELECT a.TIPCAMBIO  
		   FROM CABORDPROF a, CABNOTADEBITO_PROF b  
		   WHERE a.CodProfNeg = b.CodNotaNeg and  
			 a.CodOrdProf = b.CodNotaProf and   
			 a.CodProfCli = b.CodNotaCli and  
			 a.CodProfEmp = b.CodNotaEmp and       
			 b.CodOrdNotaDeb = CODDOC AND  
			 CODPROFNEG=@cod_negocio  
		),0) AS TIPCAMBIO,  
    FECDOC, MONTDOC, MONTCOB, MONTDET, TIPMON, CODPAG, 'I' as doc, MONTRET, FECREG    
  FROM DETORDPAGO   
  WHERE CODORDPAG=@cod_pago AND   
    CODPAGNEG=@cod_negocio AND   
    TIPOCOB=@cod_cobro and Estado not in ('E','C') and tipodoc='I'  
  ORDER BY FECREG  asc
 END  
  
END  