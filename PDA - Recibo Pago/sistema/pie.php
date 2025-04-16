<script>
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Mircoles', 'Jueves', 'Viernes', 'Sbado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi', 'Juv', 'Vie', 'Sb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
</script>
<script>
    $(function() {
        $(".dates").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
<div class="ocultarMovil"> <br><br> <br></div>
</main>
<link rel="stylesheet" href="../css/pie.css">
<div style="clear:both"></div>
<div class="pie">
    <div class="wrapper">
        <div class="pie_p cell a-m">
            <div class="grid_6 des pies">
                &copy; <a href="https://www.ideasweb.com.pe" target="_blank">Ideas Web</a> 2019 - <?php echo date("Y", time()); ?> . Todos los derechos reservados
            </div>
        </div>
    </div>
</div>
<div style="clear:both"></div>
</center>
</body>
<script src="../js/notificaciones.js"></script>

</html>