var global_form_to_send = '';
$(document).ready(function() {
    $('#save_resolucion').off('click').on('click', function() {
        var form = $("#resolucion-form");
        if (form.valid()) {
            $('.datohidden').remove();
            var input_numero_resolucion = $('<input class="datohidden" id="numero_resolucion" name="numero_resolucion" type="hidden" value="' + $('#numero_resolucion_modal').val() + '"  />');
            $('#' + global_form_to_send).prepend(input_numero_resolucion);

            var input_fecha_resolucion = $('<input class="datohidden" id="fecha_resolucion" name="fecha_resolucion" type="hidden" value="' + $('#fecha_resolucion_modal').val() + '"  />');
            $('#' + global_form_to_send).prepend(input_fecha_resolucion);

            var input_firma_resolucion = $('<input class="datohidden" id="firma_resolucion" name="firma_resolucion" type="hidden" value="' + $('#firma_resolucion_modal').val() + '"  />');
            $('#' + global_form_to_send).prepend(input_firma_resolucion);

            $('#' + global_form_to_send).first().submit();
        }
    });

    $('#modal-resolucion').on('hidden.bs.modal', function() {
        $('.datohidden').remove();
        $('#resolucion-form')[0].reset();
        clear_form_elements("#resolucion-form");
        validator.resetForm();
    });

    var validator = $("#resolucion-form").validate({
        rules: {
            numero_resolucion_modal: "required",
            fecha_resolucion_modal: "required",
            firma_resolucion_modal: "required"
                /*email: {
                    required: true,
                    email: true
                }*/
        },
        messages: {
            numero_resolucion_modal: "N&uacute;mero de resoluci&oacute;n requerido.",
            fecha_resolucion_modal: "Fecha de resoluci&oacute;n requerida",
            firma_resolucion_modal: "Elaborado por requerido."
                /*email: {
                    required: "We need your email address to contact you",
                    email: "Your email address must be in the format of name@domain.com"
                }*/
        }
    });

});
