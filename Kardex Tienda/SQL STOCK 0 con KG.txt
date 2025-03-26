SELECT        di.voucher, di.descrip, di.descolor,
                             (SELECT        COUNT(dit.coding_tda) AS Expr1
                               FROM            alm.det_ingresos_tienda AS dit INNER JOIN
                                                         alm.cab_ingresos_tienda AS cit ON dit.coding_tda = cit.coding_tda
                               WHERE        (cit.codalmacen = ci.codalmacen) AND (cit.codemp = ci.codemp) AND (dit.voucher = di.voucher) AND (dit.cdgcolor = di.cdgcolor) AND (dit.cdgart = di.cdgart) AND (dit.estado <> 'C') AND (cit.estado <> 'C') AND 
                                                         (dit.liqacab = 0)) AS stock_rollos, SUM(ISNULL(di.kneto_recibido, 0)) - ISNULL
                             ((SELECT        SUM(ISNULL(dpa.kneto, 0)) AS Expr1
                                 FROM            des.PLIST_DET_TIENDA AS dpa INNER JOIN
                                                          des.PLIST_CAB_TIENDA AS cpa ON dpa.CodPL = cpa.CodPL
                                 WHERE        (dpa.voucher = di.voucher) AND (dpa.cdgcolor = di.cdgcolor) AND (dpa.cdgart = di.cdgart) AND (cpa.codalmacen_origen = ci.codalmacen) AND (cpa.codemp_origen = ci.codemp) AND (cpa.EstadoGeneral <> '1')), 0) 
                         - ISNULL
                             ((SELECT        SUM(ISNULL(dsta.kneto_salida, 0)) AS Expr1
                                 FROM            alm.det_salidas_tienda AS dsta INNER JOIN
                                                          alm.cab_salidas_tienda AS csta ON dsta.codsal_tienda = csta.codsal_tienda
                                 WHERE        (dsta.voucher = di.voucher) AND (dsta.cdgcolor = di.cdgcolor) AND (dsta.cdgart = di.cdgart) AND (csta.codalmacen_origen = ci.codalmacen) AND (csta.codemp_origen = ci.codemp) AND (dsta.estado <> 'C') AND 
                                                          (csta.estado <> 'C')), 0) + ISNULL
                             ((SELECT        SUM(ISNULL(detdev.kneto, 0)) AS Expr1
                                 FROM            alm.det_devolucion_tienda AS detdev INNER JOIN
                                                          alm.cab_devolucion_tienda AS cabdev ON detdev.coddevol_tda = cabdev.coddevol_tda
                                 WHERE        (detdev.voucher = di.voucher) AND (detdev.cdgcolor = di.cdgcolor) AND (detdev.cdgart = di.cdgart) AND (cabdev.codalmacen_destino = ci.codalmacen) AND (cabdev.codemp_destino = ci.codemp) AND 
                                                          (detdev.estado <> 'C') AND (cabdev.estado <> 'C')), 0) AS stock_kg, di.cdgcolor, di.cdgart, ci.codalmacen, alm.Almacen, ci.codemp, emp.EmpRaz
FROM            alm.det_ingresos_tienda AS di INNER JOIN
                         alm.cab_ingresos_tienda AS ci ON di.coding_tda = ci.coding_tda LEFT OUTER JOIN
                         im.ALMACEN AS alm ON ci.codalmacen = alm.CodAlmacen LEFT OUTER JOIN
                         EMPRESA AS emp ON ci.codemp = emp.EmpCod
WHERE        (di.estado <> 'C') AND (ci.estado <> 'C') AND (ci.codalmacen = 10017)
GROUP BY di.voucher, di.descrip, di.cdgcolor, di.cdgart, ci.codalmacen, di.descolor, alm.Almacen, emp.EmpRaz, ci.codemp
HAVING        (SUM(ISNULL(di.kneto_recibido, 0)) - ISNULL
                             ((SELECT        SUM(ISNULL(dpa.kneto, 0)) AS Expr1
                                 FROM            des.PLIST_DET_TIENDA AS dpa INNER JOIN
                                                          des.PLIST_CAB_TIENDA AS cpa ON dpa.CodPL = cpa.CodPL
                                 WHERE        (dpa.voucher = di.voucher) AND (dpa.cdgcolor = di.cdgcolor) AND (dpa.cdgart = di.cdgart) AND (cpa.codalmacen_origen = ci.codalmacen) AND (cpa.codemp_origen = ci.codemp) AND (cpa.EstadoGeneral <> '1')), 0) 
                         - ISNULL
                             ((SELECT        SUM(ISNULL(dsta.kneto_salida, 0)) AS Expr1
                                 FROM            alm.det_salidas_tienda AS dsta INNER JOIN
                                                          alm.cab_salidas_tienda AS csta ON dsta.codsal_tienda = csta.codsal_tienda
                                 WHERE        (dsta.voucher = di.voucher) AND (dsta.cdgcolor = di.cdgcolor) AND (dsta.cdgart = di.cdgart) AND (csta.codalmacen_origen = ci.codalmacen) AND (csta.codemp_origen = ci.codemp) AND (dsta.estado <> 'C') AND 
                                                          (csta.estado <> 'C')), 0) + ISNULL
                             ((SELECT        SUM(ISNULL(detdev.kneto, 0)) AS Expr1
                                 FROM            alm.det_devolucion_tienda AS detdev INNER JOIN
                                                          alm.cab_devolucion_tienda AS cabdev ON detdev.coddevol_tda = cabdev.coddevol_tda
                                 WHERE        (detdev.voucher = di.voucher) AND (detdev.cdgcolor = di.cdgcolor) AND (detdev.cdgart = di.cdgart) AND (cabdev.codalmacen_destino = ci.codalmacen) AND (cabdev.codemp_destino = ci.codemp) AND 
                                                          (detdev.estado <> 'C') AND (cabdev.estado <> 'C')), 0) > 0)
ORDER BY di.voucher DESC