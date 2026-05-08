var DTAbonos = null;
var DTPropietarios = null;
var DTAvaluos = null;
var DTEstadoCuenta = null;
var last_propietario = 0;
var idx_update = 0;
var current_page = 0;
var PAGE_LENGTH = 3;
var nuevo_propietario = false;
var identificacion_autocompletada = '';
var propietario_autocompletado = false;
var is_editando_propietario = true;
var identificacion_editando = '';
var is_toast = false;
$(document).ready(function() {

    // $('.tips').powerTip({
    //     placement: 's' // north-east tooltip position
    // });
    if (moment($('#fecha_actual').val()) > moment($('#max_fecha_descuentos').val())) {
        $('#div_fecha_max_pago').fadeIn(function() {
            $('#fecha_max_pago').datepicker('setDaysOfWeekDisabled', '0,6');

            var today = new Date();
            var year = today.toLocaleString("default", { year: "numeric" });
            var month = today.toLocaleString("default", { month: "2-digit" });
            var day = today.toLocaleString("default", { day: "2-digit" });
            if (month === '11') {
                if (day === '01') day = '1';
            }
            // Generate yyyy-mm-dd date string
            var formattedDate = year + "-" + month + "-" + day;

            $('#fecha_max_pago').datepicker('setStartDate', formattedDate);
            $('#fecha_max_pago').datepicker('setDate', formattedDate);

            // $('#fecha_max_pago').datepicker('setStartDate', $('#fecha_actual').val());
            // $('#fecha_max_pago').datepicker('setDate', $('#fecha_actual').val());
        });
    } else {
        $('#div_fecha_max_pago').fadeOut();
        $('#fecha_max_pago').datepicker('clearDates');
    }

    $('[data-tooltip="tooltip"]').tooltip();

    $('.codigo_25').css({
        'display': 'none'
    });

    setDownloadFacturaRow();
    setDownloadPazRow();
    // setPrescribeRow();
    setCopyPredio();
    // setAvaluosRow();
    setAcuerdoPagoRow();

    var validatorPropietario = $("#form-predios-datos-propietarios").validate({
        rules: {
            identificacion: "required",
            nombre: "required",
            direccion: "required",
            correo_electronico: {
                required: true
                // email: true
            }

        },
        messages: {
            identificacion: "N&uacute;mero requerido",
            nombre: "Nombre requerido",
            direccion: "Direcci&oacute;n requerida",
            correo_electronico: {
                required: "Correo electr&oacute;nico requerido"
                // email: "Formato requerido email@dominio.com"
            }
        }
    });

    if ($('#codigo_predio').length > 0) {
        $('#codigo_predio').off('keyup').on('keyup', function() {
            if($('#codigo_predio').val().length > 0) {
                $('.labels_codigo_predio').fadeIn('fast');
            }
            else {
                $('.labels_codigo_predio').fadeOut('fast');
            }
            if($('#codigo_predio').val().length < 16) {
                $('.codigo_15').css('display', '');
                $('.codigo_25').css('display', 'none');
                $('.codigo_25').find('input').val('');
                //TIPO
                if ($('#codigo_predio').val().substr(0, 2) !== undefined && $('#codigo_predio').val().substr(0, 2).length > 0) {
                    $('#tipo').val($('#codigo_predio').val().substr(0, 2));
                    $('#span_tipo').text($('#tipo').val());
                    $('#div_tipo').css('opacity', 1);
                } else {
                    $('#tipo').val('s');
                    $('#span_tipo').text('');
                    $('#div_tipo').css('opacity', 0);
                }
                //SECTOR
                if ($('#codigo_predio').val().substr(2, 2) !== undefined && $('#codigo_predio').val().substr(2, 2).length > 0) {
                    $('#sector').val($('#codigo_predio').val().substr(2, 2));
                    $('#span_sector').text($('#sector').val());
                    $('#div_sector').css('opacity', 1);
                } else {
                    $('#sector').val('');
                    $('#span_sector').text('');
                    $('#div_sector').css('opacity', 0);
                }
                //MANZANA
                if ($('#codigo_predio').val().substr(4, 4) !== undefined && $('#codigo_predio').val().substr(4, 4).length > 0) {
                    $('#manzana').val($('#codigo_predio').val().substr(4, 4));
                    $('#span_manzana').text($('#manzana').val());
                    $('#div_manzana').css('opacity', 1);
                } else {
                    $('#manzana').val('');
                    $('#span_manzana').text('');
                    $('#div_manzana').css('opacity', 0);
                }
                //PREDIO
                if ($('#codigo_predio').val().substr(8, 4) !== undefined && $('#codigo_predio').val().substr(8, 4).length > 0) {
                    $('#predio').val($('#codigo_predio').val().substr(8, 4));
                    $('#span_predio').text($('#predio').val());
                    $('#div_predio').css('opacity', 1);
                } else {
                    $('#predio').val('');
                    $('#span_predio').text('');
                    $('#div_predio').css('opacity', 0);
                }
                //MEJORA
                if ($('#codigo_predio').val().substr(12) !== undefined && $('#codigo_predio').val().substr(12).length > 0) {
                    $('#mejora').val($('#codigo_predio').val().substr(12));
                    $('#span_mejora').text($('#mejora').val());
                    $('#div_mejora').css('opacity', 1);
                } else {
                    $('#mejora').val('');
                    $('#span_mejora').text('');
                    $('#div_mejora').css('opacity', 0);
                }
            }
            else {
                $('.codigo_15').css('display', 'none');
                $('.codigo_15').find('input').val('');
                $('.codigo_25').css('display', '');
                //ZONA
                if ($('#codigo_predio').val().substr(0, 2) !== undefined && $('#codigo_predio').val().substr(0, 2).length > 0) {
                    $('#zona').val($('#codigo_predio').val().substr(0, 2));
                    $('#span_zona').text($('#zona').val());
                    $('#div_zona').css('opacity', 1);
                } else {
                    $('#zona').val('s');
                    $('#span_zona').text('');
                    $('#div_zona').css('opacity', 0);
                }
                //SECTOR
                if ($('#codigo_predio').val().substr(2, 2) !== undefined && $('#codigo_predio').val().substr(2, 2).length > 0) {
                    $('#sector').val($('#codigo_predio').val().substr(2, 2));
                    $('#span_sector').text($('#sector').val());
                    $('#div_sector').css('opacity', 1);
                } else {
                    $('#sector').val('');
                    $('#span_sector').text('');
                    $('#div_sector').css('opacity', 0);
                }
                //COMUNA
                if ($('#codigo_predio').val().substr(4, 2) !== undefined && $('#codigo_predio').val().substr(4, 2).length > 0) {
                    $('#comuna').val($('#codigo_predio').val().substr(4, 2));
                    $('#span_comuna').text($('#comuna').val());
                    $('#div_comuna').css('opacity', 1);
                } else {
                    $('#comuna').val('');
                    $('#span_comuna').text('');
                    $('#div_comuna').css('opacity', 0);
                }
                //BARRIO
                if ($('#codigo_predio').val().substr(6, 2) !== undefined && $('#codigo_predio').val().substr(6, 2).length > 0) {
                    $('#barrio').val($('#codigo_predio').val().substr(6, 2));
                    $('#span_barrio').text($('#barrio').val());
                    $('#div_barrio').css('opacity', 1);
                } else {
                    $('#barrio').val('');
                    $('#span_barrio').text('');
                    $('#div_barrio').css('opacity', 0);
                }
                //MANZANA
                if ($('#codigo_predio').val().substr(8, 4) !== undefined && $('#codigo_predio').val().substr(8, 4).length > 0) {
                    $('#manzana').val($('#codigo_predio').val().substr(8, 4));
                    $('#span_manzana').text($('#manzana').val());
                    $('#div_manzana').css('opacity', 1);
                } else {
                    $('#manzana').val('');
                    $('#span_manzana').text('');
                    $('#div_manzana').css('opacity', 0);
                }
                //TERRENO
                if ($('#codigo_predio').val().substr(12, 4) !== undefined && $('#codigo_predio').val().substr(12, 4).length > 0) {
                    $('#terreno').val($('#codigo_predio').val().substr(12, 4));
                    $('#span_terreno').text($('#terreno').val());
                    $('#div_terreno').css('opacity', 1);
                } else {
                    $('#terreno').val('');
                    $('#span_terreno').text('');
                    $('#div_terreno').css('opacity', 0);
                }
                //CONDICION
                if ($('#codigo_predio').val().substr(16, 1) !== undefined && $('#codigo_predio').val().substr(16, 1).length > 0) {
                    $('#condicion').val($('#codigo_predio').val().substr(16, 1));
                    $('#span_condicion').text($('#condicion').val());
                    $('#div_condicion').css('opacity', 1);
                } else {
                    $('#condicion').val('');
                    $('#span_condicion').text('');
                    $('#div_condicion').css('opacity', 0);
                }
                //EDIFICIO/TORRE
                if ($('#codigo_predio').val().substr(17, 2) !== undefined && $('#codigo_predio').val().substr(17, 2).length > 0) {
                    $('#edificio_torre').val($('#codigo_predio').val().substr(17, 2));
                    $('#span_edificio_torre').text($('#edificio_torre').val());
                    $('#div_edificio_torre').css('opacity', 1);
                } else {
                    $('#edificio_torre').val('');
                    $('#span_edificio_torre').text('');
                    $('#div_edificio_torre').css('opacity', 0);
                }
                //PISO
                if ($('#codigo_predio').val().substr(19, 2) !== undefined && $('#codigo_predio').val().substr(19, 2).length > 0) {
                    $('#piso').val($('#codigo_predio').val().substr(19, 2));
                    $('#span_piso').text($('#piso').val());
                    $('#div_piso').css('opacity', 1);
                } else {
                    $('#piso').val('');
                    $('#span_piso').text('');
                    $('#div_piso').css('opacity', 0);
                }
                //PROPIEDAD
                if ($('#codigo_predio').val().substr(21, 4) !== undefined && $('#codigo_predio').val().substr(21, 4).length > 0) {
                    $('#propiedad').val($('#codigo_predio').val().substr(21, 4));
                    $('#span_propiedad').text($('#propiedad').val());
                    $('#div_propiedad').css('opacity', 1);
                } else {
                    $('#propiedad').val('');
                    $('#span_propiedad').text('');
                    $('#div_propiedad').css('opacity', 0);
                }
            }
        });

        $('#codigo_predio_edit').off('keyup').on('keyup', function() {
            if($('#codigo_predio_edit').val().length < 16) {
                $('.codigo_15_edit').css('display', '');
                $('.codigo_25_edit').css('display', 'none');
                $('.codigo_25_edit').find('input').val('');
                //TIPO
                if ($('#codigo_predio_edit').val().substr(0, 2) !== undefined && $('#codigo_predio_edit').val().substr(0, 2).length > 0) {
                    $('#tipo_edit').val($('#codigo_predio_edit').val().substr(0, 2));
                    $('#span_tipo_edit').text($('#tipo_edit').val());
                    $('#div_tipo_edit').css('opacity', 1);
                } else {
                    $('#tipo_edit').val('');
                    $('#span_tipo_edit').text('');
                    $('#div_tipo_edit').css('opacity', 0);
                }
                //SECTOR
                if ($('#codigo_predio_edit').val().substr(2, 2) !== undefined && $('#codigo_predio_edit').val().substr(2, 2).length > 0) {
                    $('#sector_edit').val($('#codigo_predio_edit').val().substr(2, 2));
                    $('#span_sector_edit').text($('#sector_edit').val());
                    $('#div_sector_edit').css('opacity', 1);
                } else {
                    $('#sector_edit').val('');
                    $('#span_sector_edit').text('');
                    $('#div_sector_edit').css('opacity', 0);
                }
                //MANZANA
                if ($('#codigo_predio_edit').val().substr(4, 4) !== undefined && $('#codigo_predio_edit').val().substr(4, 4).length > 0) {
                    $('#manzana_edit').val($('#codigo_predio_edit').val().substr(4, 4));
                    $('#span_manzana_edit').text($('#manzana_edit').val());
                    $('#div_manzana_edit').css('opacity', 1);
                } else {
                    $('#manzana_edit').val('');
                    $('#span_manzana_edit').text('');
                    $('#div_manzana_edit').css('opacity', 0);
                }
                //PREDIO
                if ($('#codigo_predio_edit').val().substr(8, 4) !== undefined && $('#codigo_predio_edit').val().substr(8, 4).length > 0) {
                    $('#predio_edit').val($('#codigo_predio_edit').val().substr(8, 4));
                    $('#span_predio_edit').text($('#predio_edit').val());
                    $('#div_predio_edit').css('opacity', 1);
                } else {
                    $('#predio_edit').val('');
                    $('#span_predio_edit').text('');
                    $('#div_predio_edit').css('opacity', 0);
                }
                //MEJORA
                if ($('#codigo_predio_edit').val().substr(12) !== undefined && $('#codigo_predio_edit').val().substr(12).length > 0) {
                    $('#mejora_edit').val($('#codigo_predio_edit').val().substr(12));
                    $('#span_mejora_edit').text($('#mejora_edit').val());
                    $('#div_mejora_edit').css('opacity', 1);
                } else {
                    $('#mejora_edit').val('');
                    $('#span_mejora_edit').text('');
                    $('#div_mejora_edit').css('opacity', 0);
                }
            }
            else {
                $('.codigo_15_edit').css('display', 'none');
                $('.codigo_15_edit').find('input').val('');
                $('.codigo_25_edit').css('display', '');
                //ZONA
                if ($('#codigo_predio_edit').val().substr(0, 2) !== undefined && $('#codigo_predio_edit').val().substr(0, 2).length > 0) {
                    $('#zona_edit').val($('#codigo_predio_edit').val().substr(0, 2));
                    $('#span_zona_edit').text($('#zona_edit').val());
                    $('#div_zona_edit').css('opacity', 1);
                } else {
                    $('#zona_edit').val('');
                    $('#span_zona_edit').text('');
                    $('#div_zona_edit').css('opacity', 0);
                }
                //SECTOR
                if ($('#codigo_predio_edit').val().substr(2, 2) !== undefined && $('#codigo_predio_edit').val().substr(2, 2).length > 0) {
                    $('#sector_edit').val($('#codigo_predio_edit').val().substr(2, 2));
                    $('#span_sector_edit').text($('#sector_edit').val());
                    $('#div_sector_edit').css('opacity', 1);
                } else {
                    $('#sector_edit').val('');
                    $('#span_sector_edit').text('');
                    $('#div_sector_edit').css('opacity', 0);
                }
                //COMUNA
                if ($('#codigo_predio_edit').val().substr(4, 2) !== undefined && $('#codigo_predio_edit').val().substr(4, 2).length > 0) {
                    $('#comuna_edit').val($('#codigo_predio_edit').val().substr(4, 2));
                    $('#span_comuna_edit').text($('#comuna_edit').val());
                    $('#div_comuna_edit').css('opacity', 1);
                } else {
                    $('#comuna_edit').val('');
                    $('#span_comuna_edit').text('');
                    $('#div_comuna_edit').css('opacity', 0);
                }
                //BARRIO
                if ($('#codigo_predio_edit').val().substr(6, 2) !== undefined && $('#codigo_predio_edit').val().substr(6, 2).length > 0) {
                    $('#barrio_edit').val($('#codigo_predio_edit').val().substr(6, 2));
                    $('#span_barrio_edit').text($('#barrio_edit').val());
                    $('#div_barrio_edit').css('opacity', 1);
                } else {
                    $('#barrio_edit').val('');
                    $('#span_barrio_edit').text('');
                    $('#div_barrio_edit').css('opacity', 0);
                }
                //MANZANA
                if ($('#codigo_predio_edit').val().substr(8, 4) !== undefined && $('#codigo_predio_edit').val().substr(8, 4).length > 0) {
                    $('#manzana_edit').val($('#codigo_predio_edit').val().substr(8, 4));
                    $('#span_manzana_edit').text($('#manzana_edit').val());
                    $('#div_manzana_edit').css('opacity', 1);
                } else {
                    $('#manzana_edit').val('');
                    $('#span_manzana_edit').text('');
                    $('#div_manzana_edit').css('opacity', 0);
                }
                //TERRENO
                if ($('#codigo_predio_edit').val().substr(12, 4) !== undefined && $('#codigo_predio_edit').val().substr(12, 4).length > 0) {
                    $('#terreno_edit').val($('#codigo_predio_edit').val().substr(12, 4));
                    $('#span_terreno_edit').text($('#terreno_edit').val());
                    $('#div_terreno_edit').css('opacity', 1);
                } else {
                    $('#terreno_edit').val('');
                    $('#span_terreno_edit').text('');
                    $('#div_terreno_edit').css('opacity', 0);
                }
                //CONDICION
                if ($('#codigo_predio_edit').val().substr(16, 1) !== undefined && $('#codigo_predio_edit').val().substr(16, 1).length > 0) {
                    $('#condicion_edit').val($('#codigo_predio_edit').val().substr(16, 1));
                    $('#span_condicion_edit').text($('#condicion_edit').val());
                    $('#div_condicion_edit').css('opacity', 1);
                } else {
                    $('#condicion_edit').val('');
                    $('#span_condicion_edit').text('');
                    $('#div_condicion_edit').css('opacity', 0);
                }
                //EDIFICIO/TORRE
                if ($('#codigo_predio_edit').val().substr(17, 2) !== undefined && $('#codigo_predio_edit').val().substr(17, 2).length > 0) {
                    $('#edificio_torre_edit').val($('#codigo_predio_edit').val().substr(17, 2));
                    $('#span_edificio_torre_edit').text($('#edificio_torre_edit').val());
                    $('#div_edificio_torre_edit').css('opacity', 1);
                } else {
                    $('#edificio_torre_edit').val('');
                    $('#span_edificio_torre_edit').text('');
                    $('#div_edificio_torre_edit').css('opacity', 0);
                }
                //PISO
                if ($('#codigo_predio_edit').val().substr(19, 2) !== undefined && $('#codigo_predio_edit').val().substr(19, 2).length > 0) {
                    $('#piso_edit').val($('#codigo_predio_edit').val().substr(19, 2));
                    $('#span_piso_edit').text($('#piso_edit').val());
                    $('#div_piso_edit').css('opacity', 1);
                } else {
                    $('#piso_edit').val('');
                    $('#span_piso_edit').text('');
                    $('#div_piso_edit').css('opacity', 0);
                }
                //PROPIEDAD
                if ($('#codigo_predio_edit').val().substr(21, 4) !== undefined && $('#codigo_predio_edit').val().substr(21, 4).length > 0) {
                    $('#propiedad_edit').val($('#codigo_predio_edit').val().substr(21, 4));
                    $('#span_propiedad_edit').text($('#propiedad_edit').val());
                    $('#div_propiedad_edit').css('opacity', 1);
                } else {
                    $('#propiedad_edit').val('');
                    $('#span_propiedad_edit').text('');
                    $('#div_propiedad_edit').css('opacity', 0);
                }
            }
        });
    }

    if ($('#excento_impuesto').length > 0) {
        $('#excento_impuesto').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_excento_impuesto').html('SI');
                $(this).val(1);
            } else {
                $('#span_excento_impuesto').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#predio_incautado').length > 0) {
        $('#predio_incautado').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_predio_incautado').html('SI');
                $(this).val(1);
            } else {
                $('#span_predio_incautado').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#aplica_ley44').length > 0) {
        $('#aplica_ley44').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_aplica_ley44').html('SI');
                $(this).val(1);
            } else {
                $('#span_aplica_ley44').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#uso_suelo').length > 0) {
        $('#uso_suelo').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_uso_suelo').html('SI');
                $(this).val(1);
            } else {
                $('#span_uso_suelo').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#acuerdo_pago').length > 0) {
        $('#acuerdo_pago').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_acuerdo_pago').html('SI');
                $(this).val(1);
            } else {
                $('#span_acuerdo_pago').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#responsable_propietario_acuerdo').length > 0) {
        $('#responsable_propietario_acuerdo').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_responsable_propietario_acuerdo').html('SI');
                $(this).val(1);
            } else {
                $('#span_responsable_propietario_acuerdo').html('NO');
                $(this).val(0);
            }
            checkResponsableAcuerdo();
        });
    }

    $('.datepickerforms').datepicker({
        //container: $('#modal-datos-pagos'),
        language: 'es-ES',
        format: 'yyyy-mm-dd',
        autoHide: true
    });

    $('#save_db').off('click').on('click', function() {
        saveDatosPredio('form-predios-datos-basicos', 'modal-datos-basicos', 'predios_datos_basicos', 'db');
    });

    $('#save_dp').off('click').on('click', function() {
        var form = $("#form-predios-datos-propietarios");
        if (form.valid()) {
            saveDatosPredio('form-predios-datos-propietarios', 'modal-datos-propietarios', 'predios_datos_propietarios', 'dp');
        }
    });
    // $('#save_dc').off('click').on('click', function() {
    //     saveDatosPredio('form-predios-datos-calculo', 'modal-datos-calculo', 'predios_datos_calculo', 'dc');
    // });

    // $('#save_dpa').off('click').on('click', function() {
    //     saveDatosPredio('form-predios-datos-pagos', 'modal-datos-pagos', 'predios_datos_pagos', 'dpa');
    // });

    $('#save_dap').off('click').on('click', function() {
        var form = $("#form-predios-datos-acuerdos-pago");
        if (form.valid()) {
            saveDatosPredio('form-predios-datos-acuerdos-pago', 'modal-datos-acuerdo-pago', 'predios_datos_acuerdos_pago', 'dap');
        }
    });

    $('#anular_dap').off('click').on('click', function() {
        swal({
            title: "Atención",
            text: '¿Está seguro que desea anular el acuerdo de pago asociado al predio?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                $('#form-predios-datos-acuerdos-pago').find('#anulado_acuerdo').val('1');
                saveDatosPredio('form-predios-datos-acuerdos-pago', 'modal-datos-acuerdo-pago', 'predios_datos_acuerdos_pago', 'dap');
            }
        });
    });

    $('#save_da').off('click').on('click', function() {
        saveDatosPredio('form-predios-datos-abonos', 'modal-datos-abonos', 'predios_datos_abonos', 'da');
    });

    $('#save_ph').off('click').on('click', function() {
        saveDatosPredio('form-predios-datos-procesos-historicos', 'modal-datos-procesos-historicos', 'predios_datos_procesos_historicos', 'pi');
    });

    /// datos-basicos
    $('#modal-datos-basicos').on('show.bs.modal', function() {
        if ($('#tr_predio_' + $('#id_edit').val()).attr('data-db') !== undefined) {
            var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-db'));
            setFormData('form-predios-datos-basicos', objJson);
        }
    });

    $('#modal-datos-basicos').on('hidden.bs.modal', function() {
        $('.datohidden').remove();
    });

    /// datos-propietarios
    $('#modal-datos-propietarios').on('show.bs.modal', function() {
        if (global_propietarios.length === 0) {
            disable_form_elements('#form-predios-datos-propietarios', true);
            $('#save_dp').attr('disabled', true);
        }
        if ($('#tr_predio_' + $('#id_edit').val()).attr('data-dp') !== undefined) {
            var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-dp'));
            if (objJson.length !== undefined) {
                if (objJson.length > 0) {
                    setFormData('form-predios-datos-propietarios', objJson[0]);
                    identificacion_editando = objJson[0].identificacion;
                    $('#jerarquia').val(objJson[0].jerarquia);
                    $('#form-predios-datos-propietarios').find('#span_jerarquia').html(objJson[0].jerarquia);
                    if (DTPropietarios !== null) {
                        $('#span_de_jererquia').html(' de ' + DTPropietarios.rows().data().length);
                    }
                }
                $('#new_dp').css('display', '');
                $('#cancel_dp').css('display', 'none');
                if (DTPropietarios !== null) {
                    DTPropietarios.page(0).draw('page');
                    DTPropietarios.search('').columns().search('').draw();
                    DTPropietarios.$('.row-selected').toggleClass('row-selected');
                    if (objJson.length > 0) {
                        var tr = $('#propietariosTable').find('tbody').find('tr:eq(0)');
                        $(tr).addClass('row-selected');
                    }
                }
            }
        }
    });

    $('#modal-datos-propietarios').on('hidden.bs.modal', function() {
        // $('.datohidden').remove();
        // $('.control_propietarios').css('display', 'none');
        // $('#cancel_dp').css('display', 'none');
        // last_propietario = 0;
        // $('#form-predios-datos-propietarios')[0].reset();
        // clear_form_elements("#form-predios-datos-propietarios");
        if(propietario_autocompletado) {
            $('#form-predios-datos-propietarios').find('#id_propietario').remove();
            $('#form-predios-datos-propietarios').find('#nombre').val('').prop('readonly', false);
            $('#form-predios-datos-propietarios').find('#direccion').val('').prop('readonly', false);
            $('#form-predios-datos-propietarios').find('#correo_electronico').val('').prop('readonly', false);
            identificacion_autocompletada = '';
            propietario_autocompletado = false;
        }
        $('.datohidden').remove();
        $('#form-predios-datos-propietarios')[0].reset();
        clear_form_elements("#form-predios-datos-propietarios");
        idx_update = 0;
        current_page = 0;
        $('#text_page_propietarios').html('Edici&oacute;n<br />P&aacute;gina: 1');
        $('#text_row_propietarios').html('Fila: 1');
        $('#save_dp').attr('disabled', false);
        $('#cancel_dp').trigger('click');
        validatorPropietario.resetForm();
    });

    /// datos-calculo
    // $('#modal-datos-calculo').on('show.bs.modal', function() {
    //     if ($('#tr_predio_' + $('#id_edit').val()).attr('data-dc') !== undefined) {
    //         var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-dc'));
    //         setFormData('form-predios-datos-calculo', objJson);
    //     }
    // });

    // $('#modal-datos-calculo').on('hidden.bs.modal', function() {
    //     $('.datohidden').remove();
    //     $('#form-predios-datos-calculo')[0].reset();
    //     clear_form_elements("#form-predios-datos-calculo");
    // });

    /// datos-pagos
    // $('#modal-datos-pagos').on('show.bs.modal', function() {
    //     if ($('#tr_predio_' + $('#id_edit').val()).attr('data-dpa') !== undefined) {
    //         var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-dpa'));
    //         setFormData('form-predios-datos-pagos', objJson);
    //     }
    // });

    // $('#modal-datos-pagos').on('hidden.bs.modal', function() {
    //     $('.datohidden').remove();
    //     $('#form-predios-datos-pagos')[0].reset();
    //     clear_form_elements("#form-predios-datos-pagos");
    // });

    /// datos-acuerdos-pago
    // $('#modal-datos-acuerdo-pago').on('show.bs.modal', function() {
    //     if ($('#tr_predio_' + $('#id_edit').val()).attr('data-dap') !== undefined) {
    //         var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-dap'));
    //         setFormData('form-predios-datos-acuerdos-pago', objJson);
    //     }
    // });

    // $('#modal-datos-acuerdo-pago').on('hidden.bs.modal', function() {
    //     $('.datohidden').remove();
    //     $('#form-predios-datos-acuerdos-pago')[0].reset();
    //     clear_form_elements("#form-predios-datos-acuerdos-pago");
    // });

    /// datos-abonos
    $('#modal-datos-abonos').on('show.bs.modal', function() {
        if ($('#tr_predio_' + $('#id_edit').val()).attr('data-da') !== undefined) {
            var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-da'));
            if (objJson.length !== undefined) {
                if (objJson.length > 0) {
                    setFormData('form-predios-datos-abonos', objJson[0]);
                }
                $('#new_da').css('display', '');
                $('#cancel_da').css('display', 'none');
                if (DTAbonos !== null) {
                    DTAbonos.page(0).draw('page');
                    DTAbonos.search('').columns().search('').draw();
                    DTAbonos.$('.row-selected').toggleClass('row-selected');
                    if (objJson.length > 0) {
                        var tr = $('#abonosTable').find('tbody').find('tr:eq(0)');
                        $(tr).addClass('row-selected');
                    }
                }
            }
        }
    });

    $('#modal-datos-abonos').on('hidden.bs.modal', function() {
        $('.datohidden').remove();
        $('#form-predios-datos-abonos')[0].reset();
        clear_form_elements("#form-predios-datos-abonos");
        idx_update = 0;
        current_page = 0;
        $('#text_page_abonos').html('Edici&oacute;n<br />P&aacute;gina: 1');
        $('#text_row_abonos').html('Fila: 1');
    });

    /// datos-procesos-historicos
    $('#modal-datos-procesos-historicos').on('show.bs.modal', function() {
        // if ($('#tr_predio_' + $('#id_edit').val()).attr('data-ph') !== undefined) {
        //     var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-ph'));
        //     setFormData('form-predios-datos-procesos-historicos', objJson);
        // }
        getAvaluosPredio($('#id_edit').val(), undefined);
    });

    $('#modal-datos-procesos-historicos').on('hidden.bs.modal', function() {
        if (DTAvaluos !== null) {
            DTAvaluos.clear().draw();
        }
    });

    /// datos-estado-cuenta
    $('#modal-datos-estado-cuenta').on('show.bs.modal', function() {
        // if ($('#tr_predio_' + $('#id_edit').val()).attr('data-ph') !== undefined) {
        //     var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-ph'));
        //     setFormData('form-predios-datos-estado-cuenta', objJson);
        // }
        getEstadoCuentaPredio($('#id_edit').val(), undefined);
    });

    $('#modal-datos-estado-cuenta').on('hidden.bs.modal', function() {
        if (DTEstadoCuenta !== null) {
            DTEstadoCuenta.clear().draw();
        }
    });

    /// calculo batch
    $('#btn_ejecutar_calculo_batch').off('click').on('click', function() {
        $('#btn_ejecutar_calculo_batch').attr('disabled', true);
        ejecutarCalculoBatch($('#id_predio_inicial').val(), $('#id_predio_final').val(), moment($('#fecha_actual').val()).year());
    });

    $('#modal-batch').on('hidden.bs.modal', function() {
        $('#span_total_predios').html('');
        $('#span_disponibles_predios').html('');
        $('#id_predio_inicial').empty();
        $('#id_predio_final').empty();
        $('#div_id_predio_final').fadeOut();
        $('.resumen_batch').fadeOut();
    });

    $('#modal-batch').on('show.bs.modal', function() {
        $('#span_anio_batch').html(moment($('#fecha_actual').val()).year());
        $('#span_total_predios').html('');
        $('#span_disponibles_predios').html('');
        $('#id_predio_inicial').empty();
        $('#id_predio_final').empty();
        $('#div_id_predio_final').fadeOut();
        $('.resumen_batch').fadeOut();
        getPrediosNoCalculados(0);
        $('#id_predio_inicial').off('change').on('change', function(e) {
            $('#li_predio_final').css('display', 'none');
            $('#li_cantidad').css('display', 'none');
            $('#div_btn_ejecutar_calculo_batch').css('display', 'none');
            $('#id_predio_final').empty();
            $('#span_disponibles_predios').html('');
            $('#span_predio_final').html('');
            $('#span_cantidad').html('');
            getPrediosNoCalculados($(this).val());
        });

        $('#id_predio_final').off('change').on('change', function(e) {
            $('#span_predio_final').html($('#id_predio_final option:selected').html());
            $('#li_predio_final').fadeIn();
            $('#span_cantidad').html($(this).prop('selectedIndex') + 1);
            $('#li_cantidad').fadeIn();
            $('#div_btn_ejecutar_calculo_batch').fadeIn();
        });
    });

    $('#btn_cartera').off('click').on('click', function() {
        $.blockUI({
            message: "Generando archivo EXCEL con reporte de cartera.<br />Espere un momento.",
            css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff',
                zIndex: 9999
            },
            overlayCSS:  {
                zIndex: 1100
            },
        });
        fetch('/export-cartera')
        .then(resp => resp.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            // the filename you want
            a.download = `reporte-cartera.xlsx`; // -${moment().format("YYYY-MM-DD_hh-mm-ssa")}
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            $.unblockUI();
        })
        .catch((err) => {
            $.unblockUI();
            console.log(err);
        });
    });

    $('#new_dp').off('click').on('click', function() {
        // $('.datohidden').remove();
        // $('.control_propietarios').css('display', 'none');
        // $('#cancel_dp').css('display', '');
        // $('#form-predios-datos-propietarios')[0].reset();
        // clear_form_elements("#form-predios-datos-propietarios");
        nuevo_propietario = true;
        is_editando_propietario = false;
        $('#div_set_jerarquia').fadeOut(function() {
            $('#div_jerarquia').fadeIn();
        });
        if ($('#tr_predio_' + $('#id_edit').val()).attr('data-dp') !== undefined) {
            var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-dp'));
            if (objJson.length !== undefined) {
                $('#form-predios-datos-propietarios').find('#jerarquia').val(objJson.length + 1);
                $('#form-predios-datos-propietarios').find('#span_jerarquia').html(String(objJson.length + 1).padStart(3, '0'));
                $('#span_de_jererquia').html('');
            }
        }
        $('.datohidden').remove();
        $(this).css('display', 'none');
        $('#cancel_dp').css('display', '');
        $('.info_propietarios').css('display', 'none');
        $('#form-predios-datos-propietarios')[0].reset();
        clear_form_elements("#form-predios-datos-propietarios");
        disable_form_elements('#form-predios-datos-propietarios', false);
        $('#save_dp').attr('disabled', false);
        $('#form-predios-datos-propietarios').find('#nombre').prop('readonly', false);
        $('#form-predios-datos-propietarios').find('#direccion').prop('readonly', false);
        $('#form-predios-datos-propietarios').find('#correo_electronico').prop('readonly', false);

        $('#divPropietariosTable').css('display', 'none');
        if (DTPropietarios !== null) {
            DTPropietarios.$('.row-selected').toggleClass('row-selected');
        }
        $('#form-predios-datos-propietarios').find('#identificacion').focus();
        $('#save_dp').attr('disabled', false);
    });

    $('#new_da').off('click').on('click', function() {
        $('.datohidden').remove();
        $(this).css('display', 'none');
        $('#cancel_da').css('display', '');
        $('.info_abonos').css('display', 'none');
        $('#form-predios-datos-abonos')[0].reset();
        clear_form_elements("#form-predios-datos-abonos");
        $('#divAbonosTable').css('display', 'none');
        if (DTAbonos !== null) {
            DTAbonos.$('.row-selected').toggleClass('row-selected');
        }
    });

    $('#cancel_dp').off('click').on('click', function() {
        nuevo_propietario = false;
        is_editando_propietario = true;
        if(propietario_autocompletado) {
            $('#form-predios-datos-propietarios').find('#id_propietario').remove();
            $('#form-predios-datos-propietarios').find('#nombre').val('').prop('readonly', false);
            $('#form-predios-datos-propietarios').find('#direccion').val('').prop('readonly', false);
            $('#form-predios-datos-propietarios').find('#correo_electronico').val('').prop('readonly', false);
            identificacion_autocompletada = '';
            propietario_autocompletado = false;
        }
        $('#new_dp').css('display', '');
        $(this).css('display', 'none');
        $('.info_propietarios').css('display', '');
        if (DTPropietarios !== null) {
            var data = DTPropietarios.rows().data();
            //if (data.length > 1) {
                if (idx_update >= 0) {
                    DTPropietarios.page(current_page).draw('page');
                    DTPropietarios.$('tr:eq(' + idx_update + ')').toggleClass('row-selected');
                    DTPropietarios.$('tr:eq(' + idx_update + ')').find('.editPropietario').trigger('click');
                }
                $('#divPropietariosTable').css('display', '');
            //}
        } else {
            if ($('#tr_predio_' + $('#id_edit').val()).attr('data-dp') !== undefined) {
                var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-dp'));
                if (objJson.length !== undefined) {
                    setFormData('form-predios-datos-propietarios', objJson[0]);
                }
            }
        }
    });

    $('#cancel_da').off('click').on('click', function() {
        $('#new_da').css('display', '');
        $(this).css('display', 'none');
        $('.info_abonos').css('display', '');
        if (DTAbonos !== null) {
            var data = DTAbonos.rows().data();
            //if (data.length > 1) {
                if (idx_update >= 0) {
                    DTAbonos.page(current_page).draw('page');
                    DTAbonos.$('tr:eq(' + idx_update + ')').toggleClass('row-selected');
                    DTAbonos.$('tr:eq(' + idx_update + ')').find('.editAbono').trigger('click');
                }
                $('#divAbonosTable').css('display', '');
            //}
        } else {
            if ($('#tr_predio_' + $('#id_edit').val()).attr('data-da') !== undefined) {
                var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-da'));
                if (objJson.length !== undefined) {
                    setFormData('form-predios-datos-abonos', objJson[0]);
                }
            }
        }
    });

    DTAbonos = $('#abonosTable').DataTable({
        initComplete: function(settings, json) {
            $('#divAbonosTable').css('display', '');
        },
        "destroy": true,
        "ordering": false,
        //"filter": false,
        "order": [],
        "lengthChange": false,
        "info": false,
        "pageLength": PAGE_LENGTH,
        "select": false,
        "autoWidth": false,
        "language": {
            "url": ROOT_URL + "/theme/plugins/bower_components/datatables/spanish.json"
        },
        'data': [],
        'columns': [{
            data: 'id',
            title: 'id',
            visible: false
        }, {
            data: 'anio_abono',
            title: 'A&ntilde;o',
            defaultContent: ''
        }, {
            data: 'factura_abono',
            title: 'Factura abono',
            defaultContent: ''
        }, {
            //data: 'valor_abono',
            title: 'Valor abono',
            "render": function(data, type, row, meta) {
                return accounting.formatMoney(Number(row.valor_abono), "$ ", 0, ".", ",");
            }
        }, {
            title: 'Acción',
            "render": function(data, type, row, meta) {
                return '<a href="#" data-toggle="tooltip" data-placement="bottom" title="Editar" class="editAbono"> <i class="fa fa-edit"></i> </a>';
            }
        }],
        drawCallback: function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
            $('.editAbono').off('click').on('click', function() {
                var tr = $(this).closest('tr');
                var data = DTAbonos.row(tr).data();
                $('.datohidden').remove();
                setFormData('form-predios-datos-abonos', data);
                $('#new_da').css('display', '');
                $('#cancel_da').css('display', 'none');
                DTAbonos.$('.row-selected').toggleClass('row-selected');
                $(tr).addClass('row-selected');
                idx_update = DTAbonos.row(tr).index();
                var info = DTAbonos.page.info();
                current_page = info.page;
                $('#text_page_abonos').html('Edici&oacute;n<br />P&aacute;gina: ' + (current_page + 1));
                var row = ((idx_update + 1) % PAGE_LENGTH) === 0 ? PAGE_LENGTH : (idx_update + 1) % PAGE_LENGTH;
                $('#text_row_abonos').html('Fila: ' + row);
            });
        },
        columnDefs: [
            { className: 'text-center', "targets": [1, 2, 3, 4] }
            //{ className: 'text-center', "targets": [6] },
            //{ className: 'text-right money', targets: [1, 2] },
            //{ className: 'text-center stock_selected', targets: [4] },
            //, { "visible": false, "targets": [2] }
        ]
    });

    DTPropietarios = $('#propietariosTable').DataTable({
        initComplete: function(settings, json) {
            $('#divPropietariosTable').css('display', '');
        },
        "destroy": true,
        "ordering": false,
        //"filter": false,
        "order": [],
        "lengthChange": false,
        "info": false,
        "pageLength": PAGE_LENGTH,
        "select": false,
        "autoWidth": false,
        "language": {
            "url": ROOT_URL + "/theme/plugins/bower_components/datatables/spanish.json"
        },
        'data': [],
        'columns': [{
            data: 'id',
            title: 'id',
            visible: false
        }, {
            data: 'jerarquia',
            title: 'N&uacute;mero',
            defaultContent: ''
        }, {
            data: 'identificacion',
            title: 'Identificaci&oacute;n',
            defaultContent: ''
        }, {
            data: 'nombre',
            title: 'Nombre'
        }, {
            title: 'Acción',
            "render": function(data, type, row, meta) {
                return '<a href="#" data-toggle="tooltip" data-placement="bottom" title="Editar ' + row.jerarquia + '" class="editPropietario"> <i class="fa fa-edit"></i> </a>' +
                '<a href="#" data-toggle="tooltip" data-placement="bottom" title="Eliminar ' + row.jerarquia + '" class="deletePropietario" style="color: tomato; padding-left: 10px;"> <i class="fa fa-close"></i> </a>';
            }
        }],
        drawCallback: function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
            $('.editPropietario').off('click').on('click', function() {
                is_editando_propietario = true;
                var tr = $(this).closest('tr');
                var data = DTPropietarios.row(tr).data();
                $('.datohidden').remove();
                setFormData('form-predios-datos-propietarios', data);
                $('#form-predios-datos-propietarios').find('#nombre').prop('readonly', false);
                $('#form-predios-datos-propietarios').find('#direccion').prop('readonly', false);
                $('#form-predios-datos-propietarios').find('#correo_electronico').prop('readonly', false);
                identificacion_editando = data.identificacion;
                $('#jerarquia').val(data.jerarquia);
                $('#form-predios-datos-propietarios').find('#span_jerarquia').html(data.jerarquia);
                $('#span_de_jererquia').html(' de ' + DTPropietarios.rows().data().length);

                $('#new_dp').css('display', '');
                $('#cancel_dp').css('display', 'none');
                DTPropietarios.$('.row-selected').toggleClass('row-selected');
                $(tr).addClass('row-selected');
                idx_update = DTPropietarios.row(tr).index();
                var info = DTPropietarios.page.info();
                current_page = info.page;
                $('#text_page_propietarios').html('Edici&oacute;n<br />P&aacute;gina: ' + (current_page + 1));
                var row = ((idx_update + 1) % PAGE_LENGTH) === 0 ? PAGE_LENGTH : (idx_update + 1) % PAGE_LENGTH;
                $('#text_row_propietarios').html('Fila: ' + row);
                $('#div_set_jerarquia').fadeOut(function() {
                    $('#div_jerarquia').fadeIn();
                });
                $('#form-predios-datos-propietarios').find('#identificacion').focus();
            });

            $('.deletePropietario').off('click').on('click', function() {
                $('[data-toggle="tooltip"]').tooltip('destroy');
                var tr = $(this).closest('tr');
                var data = DTPropietarios.row(tr).data();
                swal({
                    title: "Atención",
                    text: '¿Está seguro que desea eliminar el propietario ' + data.jerarquia + ' asociado al predio?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        saveEliminarPropietario(data.id_predio_propietario);
                    }
                    $('[data-toggle="tooltip"]').tooltip();
                });
            });
        },
        columnDefs: [
            { className: 'text-center', "targets": [1, 2, 3, 4] }
            //{ className: 'text-center', "targets": [6] },
            //{ className: 'text-right money', targets: [1, 2] },
            //{ className: 'text-center stock_selected', targets: [4] },
            //, { "visible": false, "targets": [2] }
        ]
    });

    DTAvaluos = $('#avaluosTable').DataTable({
        initComplete: function(settings, json) {
            // $('#divAvaluosTable').css('display', '');
        },
        "destroy": true,
        "ordering": false,
        //"filter": false,
        "order": [],
        "lengthChange": false,
        "info": false,
        "pageLength": 10,
        "select": false,
        "autoWidth": false,
        "language": {
            "url": ROOT_URL + "/theme/plugins/bower_components/datatables/spanish.json"
        },
        'data': [],
        'columns': [{
            data: 'id',
            title: 'id',
            visible: false
        }, {
            data: 'anio',
            title: 'A&ntilde;o',
            defaultContent: ''
        }, {
            data: 'prescrito',
            title: 'Prescrito',
            defaultContent: ''
        }, {
            data: 'exencion',
            title: 'Excento',
            defaultContent: ''
        }, {
            title: 'Avaluo',
            "render": function(data, type, row, meta) {
                return accounting.formatMoney(Number(row.avaluo), "$ ", 2, ".", ",");
            }
        }, {
            data: 'tipo_registro',
            title: 'Tipo',
            defaultContent: ''
        }, {
            data: 'tarifa',
            title: 'Tarifa',
            defaultContent: ''
        }, {
            title: 'Porcentaje',
            "render": function(data, type, row, meta) {
                return accounting.formatMoney(Number(row.porcentaje_tarifa), "", 4, ".", ",");
            }
        }, {
            data: 'banco',
            title: 'Banco',
            defaultContent: ''
        }, {
            data: 'fecha_pago',
            title: 'Fecha pago',
            defaultContent: ''
        }, {
            title: 'Valor',
            "render": function(data, type, row, meta) {
                return accounting.formatMoney(Number(row.valor_pago), "$ ", 2, ".", ",");
            }
        }, {
            data: 'factura_pago',
            title: 'Factura',
            defaultContent: ''
        }, {
            title: 'Valor vigencia',
            "render": function(data, type, row, meta) {
                return accounting.formatMoney(Number(row.valor_vigencia), "$ ", 2, ".", ",");
            }
        }
        ],
        drawCallback: function(settings) {
            // $('.editAbono').off('click').on('click', function() {
            //     var tr = $(this).closest('tr');
            //     var data = DTAbonos.row(tr).data();
            //     $('.datohidden').remove();
            //     setFormData('form-predios-datos-abonos', data);
            //     $('#new_da').css('display', '');
            //     $('#cancel_da').css('display', 'none');
            //     DTAbonos.$('.row-selected').toggleClass('row-selected');
            //     $(tr).addClass('row-selected');
            //     idx_update = DTAbonos.row(tr).index();
            //     var info = DTAbonos.page.info();
            //     current_page = info.page;
            //     $('#text_page_abonos').html('Edici&oacute;n<br />P&aacute;gina: ' + (current_page + 1));
            //     var row = ((idx_update + 1) % PAGE_LENGTH) === 0 ? PAGE_LENGTH : (idx_update + 1) % PAGE_LENGTH;
            //     $('#text_row_abonos').html('Fila: ' + row);
            // });
        },
        columnDefs: [
            { className: 'text-center', "targets": [1, 2, 3, 5, 6, 7, 8, 9, 11] },
            { className: 'text-right money', targets: [4, 10, 12] }
            //, { "visible": false, "targets": [0] }
        ]
    });

    DTEstadoCuenta = $('#estadoCuentaTable').DataTable({
        initComplete: function(settings, json) {
            // $('#divEstadoCuentaTable').css('display', '');
        },
        "destroy": true,
        "ordering": false,
        //"filter": false,
        "order": [],
        "lengthChange": false,
        "info": false,
        "pageLength": 5,
        "select": false,
        "autoWidth": false,
        "language": {
            "url": ROOT_URL + "/theme/plugins/bower_components/datatables/spanish.json"
        },
        'data': [],
        'columns': [
            {
                data: 'id',
                title: 'id',
                visible: false
            }, {
                data: 'vigencia',
                title: 'Vigencia',
                defaultContent: ''
            }, {
                title: 'Avaluo',
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.avaluo), "$ ", 2, ".", ",");
                }
            }, {
                title: 'Impuesto',
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.impuesto), "$ ", 2, ".", ",");
                }
            }, {
                title: 'Intereses',
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.interes_impuesto), "$ ", 2, ".", ",");
                }
            }, {
                title: 'CAR',
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.car), "$ ", 2, ".", ",");
                }
            }, {
                title: 'Interes CAR',
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.interes_car), "$ ", 2, ".", ",");
                }
            }, {
                title: 'Descuento',
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.descuento), "$ ", 2, ".", ",");
                }
            }, {
                title: 'Otros',
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.otros), "$ ", 2, ".", ",");
                }
            }, {
                title: 'Total',
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.total), "$ ", 2, ".", ",");
                }
            }, {
                data: 'acuerdo',
                title: 'AP',
                defaultContent: ''
            }
        ],
        drawCallback: function(settings) {
            // $('.editAbono').off('click').on('click', function() {
            //     var tr = $(this).closest('tr');
            //     var data = DTAbonos.row(tr).data();
            //     $('.datohidden').remove();
            //     setFormData('form-predios-datos-abonos', data);
            //     $('#new_da').css('display', '');
            //     $('#cancel_da').css('display', 'none');
            //     DTAbonos.$('.row-selected').toggleClass('row-selected');
            //     $(tr).addClass('row-selected');
            //     idx_update = DTAbonos.row(tr).index();
            //     var info = DTAbonos.page.info();
            //     current_page = info.page;
            //     $('#text_page_abonos').html('Edici&oacute;n<br />P&aacute;gina: ' + (current_page + 1));
            //     var row = ((idx_update + 1) % PAGE_LENGTH) === 0 ? PAGE_LENGTH : (idx_update + 1) % PAGE_LENGTH;
            //     $('#text_row_abonos').html('Fila: ' + row);
            // });
        },
        columnDefs: [
            { className: 'text-center', "targets": [1, 2, 10] },
            { className: 'text-right money', targets: [3, 4, 5, 6, 7, 8, 9] }
            //, { "visible": false, "targets": [0] }
        ]
    });

    $('#div_jerarquia').off('click').on('click', function() {
        if(DTPropietarios.rows().data().length > 1 && !nuevo_propietario) {
            $('#val_jerarquia').val($('#span_jerarquia').text());
            $('#val_jerarquia').attr('max', DTPropietarios.rows().data().length);
            $('#div_jerarquia').fadeOut(function() {
                $('#div_set_jerarquia').fadeIn(function() {
                    $('#val_jerarquia').focus();
                });
            });

            $('#val_jerarquia').off('keyup').on('keyup', function() {
                if(Number($(this).val() > Number($(this).attr('max'))) || $(this).val().length < $(this).attr('maxlength')) {
                    $('#save_jerarquia').attr('disabled', true);
                }
                else {
                    $('#save_jerarquia').attr('disabled', false);
                }
            });
        }
    });

    $('#save_jerarquia').off('click').on('click', function() {
        if($('#jerarquia').val() !== $('#val_jerarquia').val()) {
            saveJerarquiaPredio($('#id_predio').val(), $('#jerarquia').val(), $('#val_jerarquia').val());
        }
        else {
            $('#div_set_jerarquia').fadeOut(function() {
                $('#div_jerarquia').fadeIn(function() {
                    $('#val_jerarquia').val('');
                });
            });
        }
    });

    $('#identificacion').off('blur').on('blur', function() {
        if(!is_editando_propietario && !propietario_autocompletado && global_propietarios.length > 0) {
            autocompletePropietario($(this).val(), true);
        }
    });

    $('#identificacion').off('keyup').on('keyup', function() {
        if(is_editando_propietario) {
            $('#identificacion').trigger('change');
        }
        else if(propietario_autocompletado && identificacion_autocompletada !== $(this).val()) {
            $('#form-predios-datos-propietarios').find('#id_propietario').remove();
            $('#form-predios-datos-propietarios').find('#nombre').val('').prop('readonly', false);
            $('#form-predios-datos-propietarios').find('#direccion').val('').prop('readonly', false);
            $('#form-predios-datos-propietarios').find('#correo_electronico').val('').prop('readonly', false);
            identificacion_autocompletada = '';
            propietario_autocompletado = false;
        }
    });

    $('#identificacion').off('change').on('change', function (evt) {
        if(is_editando_propietario) {
            if ($.trim($('#identificacion').val()).length > 0) {
                clearTimeout($.data(this, 'timer'));
                var wait = setTimeout(function () {
                    var data = DTPropietarios.rows().data().toArray();
                    var existe = data.find(el => el.identificacion === $('#identificacion').val());
                    if(existe && !is_toast) {
                        is_toast = true;
                        $.toast({
                            heading: 'Atención',
                            text: 'La identificación ingresada ya existente en la lista de propietarios actual.',
                            position: 'top-center',
                            loaderBg: '#fff',
                            icon: 'warning',
                            hideAfter: 5000,
                            stack: 6,
                            afterHidden: function () {
                                $('#identificacion').val(identificacion_editando);
                                // $('#save_dp').attr('disabled', true);
                                if (idx_update >= 0) {
                                    DTPropietarios.page(current_page).draw('page');
                                    DTPropietarios.$('tr:eq(' + idx_update + ')').toggleClass('row-selected');
                                    DTPropietarios.$('tr:eq(' + idx_update + ')').find('.editPropietario').trigger('click');
                                }
                                is_toast = false;
                            }
                        });
                    }
                    else if(!existe && !is_toast) {
                        autocompletePropietario($('#identificacion').val(), false);
                    }
                }, 400);//Tiempo de espera despues de presionar una tecla
                $(this).data('timer', wait);
            }
        }
    });

    if($('#print_avaluos').length) {
        $('#print_avaluos').off('click').on('click', function() {
            var btn = $(this);
            $('.btn_pdf').attr('disabled', true);
            startImpresion($(btn).attr('url') + $('#id_edit').val(), 'Iniciando generación de archivo Historial de Pagos de impuesto predial. Espere un momento por favor.', 'warning', false);
        });
    }

    if($('#print_estado_cuenta').length) {
        $('#print_estado_cuenta').off('click').on('click', function() {
            var btn = $(this);
            $('.btn_pdf').attr('disabled', true);
            startImpresion($(btn).attr('url') + $('#id_edit').val(), 'Iniciando generación de archivo Estado de Cuenta de impuesto predial. Espere un momento por favor.', 'warning', false);
        });
    }

    if($('#anio_inicial_acuerdo').length) {
        $('#anio_inicial_acuerdo').off('change').on('change', function() {
            if(Number($('#anio_inicial_acuerdo').val()) > Number($('#anio_final_acuerdo').val())) {
                $('#anio_final_acuerdo').val('');
            } else {
                if($('#anio_inicial_acuerdo').val().length > 0 && $('#anio_final_acuerdo').val().length > 0) {
                    var anios = global_anios.filter(el => parseInt(el.ultimo_anio) >= parseInt($('#anio_inicial_acuerdo').val()) && parseInt(el.ultimo_anio) <= parseInt($('#anio_final_acuerdo').val()));
                    var total_acuerdo = 0;
                    for (var anio in anios) {
                        total_acuerdo += Number(anio.total_calculo);
                    }
                    $('#total_acuerdo').html(accounting.formatMoney(total_acuerdo, "$ ", 2, ".", ", "));
                }
            }
        });
    }

    if($('#anio_final_acuerdo').length) {
        $('#anio_final_acuerdo').off('change').on('change', function() {
            if(Number($('#anio_final_acuerdo').val()) < Number($('#anio_inicial_acuerdo').val())) {
                $('#anio_inicial_acuerdo').val('');
            } else {
                if($('#anio_inicial_acuerdo').val().length > 0 && $('#anio_final_acuerdo').val().length > 0) {
                    var anios = global_anios.filter(el => parseInt(el.ultimo_anio) >= parseInt($('#anio_inicial_acuerdo').val()) && parseInt(el.ultimo_anio) <= parseInt($('#anio_final_acuerdo').val()));
                    var total_acuerdo = 0;
                    for (var anio in anios) {
                        total_acuerdo += Number(anio.total_calculo);
                    }
                    $('#total_acuerdo').html(accounting.formatMoney(total_acuerdo, "$ ", 2, ".", ", "));
                }
            }
        });
    }
});

function saveDatosPredio(form, modal, path, suffix) {
    var jsonObj = $('#' + form).serializeJSON({ useIntKeysAsArrayIndex: true });
    if (jsonObj.id_predio === undefined) {
        jsonObj.id_predio = $('#id_edit').val().length > 0 ? $('#id_edit').val() : $('#id_predio.select2').val();
    }
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/store/' + path,
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if (response.data !== undefined) {
                if (response.data.success) {
                    if (response.obj !== undefined) {
                        var info = null;
                        var last_row = null;
                        if (suffix === 'dp') {
                            if (DTPropietarios !== null) {
                                $('#tr_predio_' + $('#id_edit').val()).attr('data-dp', JSON.stringify(response.obj));
                                DTPropietarios.clear().draw();
                                DTPropietarios.rows.add(response.obj).draw();
                                if (jsonObj.id !== undefined) { // actualizando reg
                                    if (idx_update >= 0) {
                                        DTPropietarios.page(current_page).draw('page');
                                        DTPropietarios.$('tr:eq(' + idx_update + ')').toggleClass('row-selected');
                                    }
                                    if(propietario_autocompletado) {
                                        $('#form-predios-datos-propietarios').find('#id_propietario').remove();
                                        $('#form-predios-datos-propietarios').find('#nombre').prop('readonly', false);
                                        $('#form-predios-datos-propietarios').find('#direccion').prop('readonly', false);
                                        $('#form-predios-datos-propietarios').find('#correo_electronico').prop('readonly', false);
                                        identificacion_autocompletada = '';
                                        propietario_autocompletado = false;
                                        $('#form-predios-datos-propietarios').find('#identificacion').focus();
                                    }
                                } else {
                                    if(propietario_autocompletado) {
                                        $('#form-predios-datos-propietarios').find('#id_propietario').remove();
                                        $('#form-predios-datos-propietarios').find('#nombre').val('').prop('readonly', false);
                                        $('#form-predios-datos-propietarios').find('#direccion').val('').prop('readonly', false);
                                        $('#form-predios-datos-propietarios').find('#correo_electronico').val('').prop('readonly', false);
                                        identificacion_autocompletada = '';
                                        propietario_autocompletado = false;
                                    }
                                    info = DTPropietarios.page.info();
                                    DTPropietarios.page(info.pages - 1).draw('page');
                                    last_row = DTPropietarios.row(':last').index();
                                    DTPropietarios.$('tr:eq(' + last_row + ')').toggleClass('row-selected');
                                    DTPropietarios.$('tr:last').find('.editPropietario').trigger('click');
                                    $('#divPropietariosTable').css('display', '');
                                    $('#new_dp').css('display', '');
                                    $('#cancel_dp').css('display', 'none');
                                    $('.info_propietarios').css('display', '');
                                }
                            }
                            nuevo_propietario = false;
                            is_editando_propietario = true;
                            getPredio($('#id_predio.select2').val(), false, true);
                        } else if (suffix === 'da') {
                            if (DTAbonos !== null) {
                                $('#tr_predio_' + $('#id_edit').val()).attr('data-da', JSON.stringify(response.obj));
                                DTAbonos.clear().draw();
                                DTAbonos.rows.add(response.obj).draw();
                                if (jsonObj.id !== undefined) { // actualizando reg
                                    if (idx_update >= 0) {
                                        DTAbonos.page(current_page).draw('page');
                                        DTAbonos.$('tr:eq(' + idx_update + ')').toggleClass('row-selected');
                                    }
                                } else {
                                    info = DTAbonos.page.info();
                                    DTAbonos.page(info.pages - 1).draw('page');
                                    last_row = DTAbonos.row(':last').index();
                                    DTAbonos.$('tr:eq(' + last_row + ')').toggleClass('row-selected');
                                    DTAbonos.$('tr:last').find('.editAbono').trigger('click');
                                    $('#divAbonosTable').css('display', '');
                                    $('#new_da').css('display', '');
                                    $('#cancel_da').css('display', 'none');
                                    $('.info_abonos').css('display', '');
                                }
                            }
                        } else if (suffix === 'dap') {
                            if (jsonObj.anulado_acuerdo === undefined) {
                                global_acuerdo_pago = response.obj;
                                setDataAcuerdoPagoModalForm();
                                disable_form_elements('#form-predios-datos-acuerdos-pago', true);
                                $('#save_dap').attr('disabled', true);
                                $('#save_dap').css('display', 'none');
                                $('#anular_dap').attr('disabled', false);
                                $('#anular_dap').css('display', '');
                                $('.download_factura_row').attr('disabled', true);
                            } else {
                                global_acuerdo_pago = null;
                                disable_form_elements('#form-predios-datos-acuerdos-pago', false);
                                $('#modal-datos-acuerdo-pago').modal('hide');
                                $('.download_factura_row').attr('disabled', false);
                                $('#abono_inicial_acuerdo').off();

                                var element = AutoNumeric.getAutoNumericElement('#abono_inicial_acuerdo');
                                element.formUnformat();
                                new AutoNumeric('#abono_inicial_acuerdo', {
                                    emptyInputBehavior: "zero",
                                    minimumValue: "0",
                                    modifyValueOnWheel: false,
                                    unformatOnSubmit: true
                                });
                            }

                        } else {
                            $('#tr_predio_' + $('#id_edit').val()).attr('data-' + suffix, JSON.stringify(response.obj));
                        }
                    }
                }

                swal({
                    title: "Atención",
                    text: response.data.message,
                    type: "warning",
                    // confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: true
                });
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
}

function getJsonPrediosDatos() {
    $.ajax({
        type: 'GET',
        url: '/show/predios_datos',
        data: {
            id_predio: $('#id_edit').val()
        },
        success: function(response) {
            if (response.predio_dato !== undefined && response.predio_dato !== null) {
                $('#tr_predio_' + $('#id_edit').val()).attr('data-db', JSON.stringify(response.predio_dato));
            }

            if (response.predio_propietarios !== undefined && response.predio_propietarios !== null) {
                // if (response.predio_propietario.length > 0) {
                //     $('#tr_predio_' + $('#id_edit').val()).attr('data-dp', JSON.stringify(response.predio_propietarios));
                // }
                $('#tr_predio_' + $('#id_edit').val()).attr('data-dp', JSON.stringify(response.predio_propietarios));
                //if (response.predio_propietarios.length > 1) {
                    if (DTPropietarios !== null) {
                        DTPropietarios.clear().draw();
                        DTPropietarios.rows.add(response.predio_propietarios).draw();
                    }
                //}
            }

            if (response.predio_calculo !== undefined && response.predio_calculo !== null) {
                $('#tr_predio_' + $('#id_edit').val()).attr('data-dc', JSON.stringify(response.predio_calculo));
            }

            ///
            $('.datohidden').remove();
            $('#acuerdo_pago').attr('disabled', false);
            $('#form-predios-datos-pagos')[0].reset();
            clear_form_elements("#form-predios-datos-pagos");
            $('#span_acuerdo_pago').html('NO');
            if (response.predio_pago !== undefined && response.predio_pago !== null) {
                $('#tr_predio_' + $('#id_edit').val()).attr('data-dpa', JSON.stringify(response.predio_pago));
                var objJson = JSON.parse($('#tr_predio_' + $('#id_edit').val()).attr('data-dpa'));
                setFormData('form-predios-datos-pagos', objJson);
            }
            $('#acuerdo_pago').attr('disabled', true);

            // if (response.predio_acuerdo_pago !== undefined && response.predio_acuerdo_pago !== null) {
            //     $('#tr_predio_' + $('#id_edit').val()).attr('data-dap', JSON.stringify(response.predio_acuerdo_pago));
            // }

            if (response.predio_abonos !== undefined && response.predio_abonos !== null) {
                $('#tr_predio_' + $('#id_edit').val()).attr('data-da', JSON.stringify(response.predio_abonos));
                //if (response.predio_abonos.length > 1) {
                    if (DTAbonos !== null) {
                        DTAbonos.clear().draw();
                        DTAbonos.rows.add(response.predio_abonos).draw();
                    }
                //}
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
}

function setFormData(form, jsonObj) {
    $.each(jsonObj, function(i, el) {
        if ($('#' + form).find('#' + i).length > 0) {
            if ($('#' + form).find('#' + i).hasClass('selectpicker') || $('#' + form).find('#' + i).hasClass('selectpicker-noval')) {
                $('#' + form).find('#' + i).selectpicker('val', el);
            } else {
                if (el === '.00') {
                    if ($.inArray(i, arr_autonumeric) >= 0) {
                        AutoNumeric.set('#' + i, 0);
                    } else {
                        $('#' + form).find('#' + i).val('0');
                    }
                } else {
                    if ($.inArray(i, arr_autonumeric) >= 0 || i === 'valor_pago') {
                        if(i === 'valor_pago') {
                            $('#valor_pago').val(accounting.formatMoney(Number(el), "$ ", 2, ",", "."));
                        }
                        else {
                            new AutoNumeric('#' + i, {
                                emptyInputBehavior: "zero",
                                minimumValue: "0",
                                modifyValueOnWheel: false,
                                unformatOnSubmit: true
                            });
                            AutoNumeric.set('#' + i, Number(el));
                        }
                    } else {
                        $('#' + form).find('#' + i).val(el);
                        if ($('#' + form).find('#' + i).is(':checkbox')) {
                            if (Number(el) > 0 || Number(el) === -1) {
                                if (!$('#' + form).find('#' + i).is(':checked')) {
                                    $('#' + form).find('#' + i).trigger('click');
                                }
                            } else {
                                if ($('#' + form).find('#' + i).is(':checked')) {
                                    $('#' + form).find('#' + i).trigger('click');
                                }
                            }
                        }
                    }
                }
            }
        } else {
            var input_hidden = $('<input class="datohidden" id="' + i + '" name="' + i + '" type="hidden" value="' + el + '"  />');
            $('#' + form).prepend(input_hidden);
        }
    });
}

function saveJerarquiaPredio(id_predio, jerarquia_anterior, jerarquia_nueva) {
    $('.modal').css('z-index', 1040);
    $.blockUI({
        message: "Ejecutando operaci&oacute;n. Espere un momento.",
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff',
            zIndex: 9999
        }
    });
    var jsonObj = {};
    jsonObj.id_predio = id_predio;
    jsonObj.jerarquia_anterior = jerarquia_anterior;
    jsonObj.jerarquia_nueva = jerarquia_nueva;
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/store/predios_propietarios_jerarquia',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if (DTPropietarios !== null) {
                $('#tr_predio_' + $('#id_edit').val()).attr('data-dp', JSON.stringify(response.data));
                DTPropietarios.clear().draw();
                DTPropietarios.rows.add(response.data).draw();
                DTPropietarios.page(0).draw('page');
                var first_row = DTPropietarios.row(':first').index();
                DTPropietarios.$('tr:eq(' + first_row + ')').toggleClass('row-selected');
                DTPropietarios.$('tr:first').find('.editPropietario').trigger('click');
            }
            $('#div_set_jerarquia').fadeOut(function() {
                $('#div_jerarquia').fadeIn(function() {
                    $('#val_jerarquia').val('');
                    $.unblockUI();
                    $('.modal').css('z-index', 1041);
                });
            });
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $.unblockUI();
            $('.modal').css('z-index', 1041);
        }
    });
}

function saveEliminarPropietario(id_predio_propietario) {
    $.blockUI({
        message: "Ejecutando operaci&oacute;n. Espere un momento.",
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff',
            zIndex: 9999
        }
    });
    var jsonObj = {};
    jsonObj.id = id_predio_propietario;
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/store/predios_propietarios_delete',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if (DTPropietarios !== null) {
                $('#tr_predio_' + $('#id_edit').val()).attr('data-dp', JSON.stringify(response.data));
                DTPropietarios.clear().draw();
                DTPropietarios.rows.add(response.data).draw();
                DTPropietarios.page(0).draw('page');
                var first_row = DTPropietarios.row(':first').index();
                DTPropietarios.$('tr:eq(' + first_row + ')').toggleClass('row-selected');
                DTPropietarios.$('tr:first').find('.editPropietario').trigger('click');
            }
            getPredio($('#id_predio.select2').val(), false, true);
            $.unblockUI();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $.unblockUI();
        }
    });
}

function autocompletePropietario(identificacion, append_hidden) {
    $('#form-predios-datos-propietarios').find('#id_propietario').remove();
    $.blockUI({
        message: "Verificando identificaci&oacute;n. Espere un momento.",
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff',
            zIndex: 9999
        }
    });
    var jsonObj = {};
    jsonObj.identificacion = identificacion;
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/get_propietario_by_identificacion',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if(response.propietario !== null) {
                is_toast = true;
                $.toast({
                    heading: 'Atención',
                    text: 'Se ha detectado un propietario ya existente en la base de datos del sistema.',
                    position: 'top-center',
                    loaderBg: '#fff',
                    icon: 'warning',
                    hideAfter: 5000,
                    stack: 6,
                    afterHidden: function () {
                        is_toast = false;
                    }
                });
                if(append_hidden) {
                    $('#form-predios-datos-propietarios').prepend('<input type="hidden" id="id_propietario" name="id_propietario" value="' + response.propietario.id + '">');
                } else {
                    if ($('#form-predios-datos-propietarios').find('#id').length > 0) {
                        $('#form-predios-datos-propietarios').find('#id').val(response.propietario.id);
                    } else {
                        $('#form-predios-datos-propietarios').prepend('<input type="hidden" id="id" name="id" value="' + response.propietario.id + '">');
                    }
                }
                $('#form-predios-datos-propietarios').find('#nombre').val(response.propietario.nombre).prop('readonly', false);
                $('#form-predios-datos-propietarios').find('#direccion').val(response.propietario.direccion).prop('readonly', false);
                $('#form-predios-datos-propietarios').find('#correo_electronico').val(response.propietario.correo_electronico).prop('readonly', false);
                identificacion_autocompletada = identificacion;
                propietario_autocompletado = true;
            }
            else {
                $('#form-predios-datos-propietarios').find('#id').remove();
                $('#form-predios-datos-propietarios').find('#nombre').val('').prop('readonly', false);
                $('#form-predios-datos-propietarios').find('#direccion').val('').prop('readonly', false);
                $('#form-predios-datos-propietarios').find('#correo_electronico').val('').prop('readonly', false);
                identificacion_autocompletada = '';
                propietario_autocompletado = false;
            }
            $.unblockUI();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $.unblockUI();
        }
    });
}

function checkResponsableAcuerdo() {
    var elem = $('#responsable_propietario_acuerdo');
    if ($(elem).val() === '0') {
        if (!global_acuerdo_pago || global_acuerdo_pago.responsable_propietario_acuerdo !== $(elem).val()) {
            $('#identificacion_acuerdo').val('');
            $('#nombre_acuerdo').val('');
            $('#direccion_acuerdo').val('');
            $('#telefono_acuerdo').val('');
        } else {
            $('#identificacion_acuerdo').val(global_acuerdo_pago.identificacion_acuerdo);
            $('#nombre_acuerdo').val(global_acuerdo_pago.nombre_acuerdo);
            $('#direccion_acuerdo').val(global_acuerdo_pago.direccion_acuerdo);
            $('#telefono_acuerdo').val(global_acuerdo_pago.telefono_acuerdo);
        }
        $('#identificacion_acuerdo').attr('readonly', false);
        $('#nombre_acuerdo').attr('readonly', false);
        $('#direccion_acuerdo').attr('readonly', false);
    } else {
        if (global_acuerdo_pago) {
            $('#identificacion_acuerdo').val(global_acuerdo_pago.identificacion_acuerdo);
            $('#nombre_acuerdo').val(global_acuerdo_pago.nombre_acuerdo);
            $('#direccion_acuerdo').val(global_acuerdo_pago.direccion_acuerdo);
            $('#telefono_acuerdo').val(global_acuerdo_pago.telefono_acuerdo);
        } else {
            $('#identificacion_acuerdo').val(global_propietario.identificacion);
            $('#nombre_acuerdo').val(global_propietario.nombre);
            $('#direccion_acuerdo').val(global_propietario.direccion);
        }
        $('#identificacion_acuerdo').attr('readonly', true);
        $('#nombre_acuerdo').attr('readonly', true);
        $('#direccion_acuerdo').attr('readonly', true);
    }
}

function getPrediosNoCalculados(id_predio_inicial) {
    $.blockUI({
        message: "Obteniendo lista de predios. Espere un momento.",
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff',
            zIndex: 9999
        },
        overlayCSS:  {
            zIndex: 1100
        },
    });
    var jsonObj = {};
    if (Number(id_predio_inicial) !== 0) {
        jsonObj.id_predio_inicial = id_predio_inicial;
    } else {
        $('#div_id_predio_final').fadeOut();
    }

    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/get_predios_no_calculados',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if(response.predios !== null) {
                if (id_predio_inicial === 0) {
                    $('#span_total_predios').html(response.predios.length);
                    $.each(response.predios, function(i, el){
                        $('#id_predio_inicial').append('<option value="' + el.id + '">' + el.codigo_predio + ' - ' + el.direccion + '</option>');
                    });
                    $('#id_predio_inicial').selectpicker("refresh");
                    $.unblockUI();
                } else {
                    $('#span_disponibles_predios').html(response.predios.length);
                    $.each(response.predios, function(i, el){
                        $('#id_predio_final').append('<option value="' + el.id + '">' + el.codigo_predio + ' - ' + el.direccion + '</option>');
                    });
                    $('#id_predio_final').selectpicker("refresh");
                    $('#div_id_predio_final').fadeIn(function() {
                        $('#div_resumen_batch').fadeIn(function() {
                            $('#span_predio_inicial').html($('#id_predio_inicial option:selected').html());
                            $('#li_predio_inicial').fadeIn();
                        });
                        $.unblockUI();
                    });
                }
            } else {
                $.unblockUI();
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $.unblockUI();
        }
    });
}

function ejecutarCalculoBatch(id_predio_inicial, id_predio_final, anio) {
    $.blockUI({
        message: "Ejecutando operaci&oacute;n solicitada. Espere un momento.",
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff',
            zIndex: 9999
        },
        overlayCSS:  {
            zIndex: 1100
        },
    });
    var jsonObj = {};
    jsonObj.id_predio_inicial = id_predio_inicial;
    jsonObj.id_predio_final = id_predio_final;
    jsonObj.anio = anio;
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/ejecutar_calculo_batch',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if(response.data !== null) {
                if (response.data?.id ) {
                    $('#td_por_calcular').html(response.data?.por_calcular);
                    $('#td_procesados').html(0);
                    $('#td_restantes').html(response.data?.por_calcular);
                    $('.batch_element').css('display', 'none');
                    $('.batch_message').css('display', '');
                }
            }
            $('#btn_ejecutar_calculo_batch').attr('disabled', false);
            $('#modal-batch').modal('hide');
            $.unblockUI();

        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $.unblockUI();
        }
    });
}
