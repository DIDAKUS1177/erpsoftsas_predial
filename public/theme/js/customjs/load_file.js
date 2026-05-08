var MAX_FILE_SIZE = 10;
var bar = $('.bar.one');
var percent = $('.porciento.one');

$(document).ready(function() {
    $('#btn_cargar_archivo_resolucion').off('click').on('click', function() {
        $('#load-form').submit();
    });

    $('#file').bind('change', function() {
        reset_bar(false);
        var filesize = this.files[0].size; // On older browsers this can return NULL.
        var filesizeMB = (filesize / (1024 * 1024)).toFixed(2);

        if (filesizeMB <= MAX_FILE_SIZE) {
            // Allow the form to be submitted here.
            $('#btn_cargar_archivo_resolucion').trigger('click');
            $('#error_fileupload').fadeOut();
        } else {
            // Don't allow submission of the form here.
            $('#error_fileupload').fadeIn();
        }
    });

    $('#load-form').ajaxForm({
        beforeSend: function() {
            $.blockUI({
                message: 'Espere un momento por favor...',
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: 0.5,
                    color: '#fff',
                    zIndex: 9999
                }
            });
            reset_bar(true);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function(xhr) {
            $.unblockUI();
        },
        success: function(response, statusText, xhr, $form) {
            if (response.message !== undefined) {
                $('#current_filename').html(response.message);
                if(!response.error) {
                    percent.css('color', '#2eb52a');
                    percent.html('Archivo cargado con &eacute;xito');
                    $('#file_name').val(response.file);
                    if ($('#create-form').length) {
                        // var validatorCreate = $("#create-form").validate();
                        // validatorCreate.resetForm();
                        $.each($('.has-success'), function(i, el) {
                            $(el).removeClass('has-success');
                        });
                        $.each($('.has-error'), function(i, el) {
                            $(el).removeClass('has-error');
                        });
                    }
                }
                else {
                    percent.css('color', 'tomato');
                    percent.html('Error al cargar el archivo');
                    $('#file_name').val('');
                }
            }
        }
    });
});


function reset_bar(before) {
    var percentVal = '0%';
    bar.width(percentVal);
    percent.html(percentVal);
    percent.css('color', '#000000');
    if (before) {
        percent.css('display', 'inline-block');
    } else {
        percent.css('display', 'none');
    }
    $('#current_filename').html('');
}
