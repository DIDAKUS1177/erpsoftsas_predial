var global_json = null;
var global_json_predio = null;
var global_url_autocomplete_predio = "/autocomplete";
var global_url_autocomplete_solo_codigo_predio = "/autocomplete_check";
var global_url_print = '';
var global_ya_pagado = false;
var global_predio_con_deuda = false;
var global_anio_actual = 0;
var existe_predio = false;
var codigo_predio_buscando = '';
var global_anios_prescripcion_exencion = [];
var global_propietario = null;
var global_propietarios = null;
var global_anios = null;
var global_acuerdo_pago = null;
var global_ultima_factura = null;
var global_plusvalia = 0;
var globalShowBtnAcuerdos = false;
var global_facturado = null;
var arr_autonumeric = ['porcentaje', 'porcentaje_ex', 'minimo_urbano', 'minimo_rural', 'avaluo_inicial', 'avaluo_final', 'tarifa', 'porcentaje_car',
    'area_metros', 'area_construida', 'area_hectareas', 'tarifa_actual', 'avaluo', 'avaluo_presente_anio', 'valor_abono',
    'valor_facturado', 'avaluoigac', 'area', 'valor_paz', 'tasa_diaria', 'tasa_mensual', 'tasa_acuerdo','abono_inicial_acuerdo', 'tarifa_anterior', 'tarifa_nueva', 'valor_concepto1', 'valor_concepto2', 'valor_concepto3', 'valor_concepto4', 'valor_concepto13', 'valor_concepto14', 'valor_concepto15', 'valor_concepto16', 'valor_concepto17', 'valor_concepto18', 'total_calculo', 'porcentaje_inicial_acuerdo'
];
var ROOT_URL = window.location.protocol + "//" + window.location.host;
$(document).ready(function() {

    if ($('#codigo_predio').length > 0) {
        $('.buttonTareas').css('display', 'none');
    }

    if ($('#tab').length > 0) {
        $('#' + $('#tab').val()).trigger('click');
    }

    if ($('#li-section-bar-1').length > 0) {
        $('#li-section-bar-1').bind('click', function() {
            if ($('#tab').val() === 'li-section-bar-2') {
                if ($('#valor_facturado').length > 0) {
                    $('#valor_facturado').attr('readonly', true);
                }
                if ($('#btn_save_info').length > 0) {
                    $('#btn_save_info').attr('disabled', false);
                    $('#numero_recibo').attr('disabled', true);
                }
                if ($('#codigo_predio').length > 0) {
                    $('.buttonTareas').css('display', 'none');
                    $('.span_predio').html('');
                    $('.span_predio').closest('div').parent('div').css('opacity', '0');
                    $('.codigo_15').find('input').val('');
                    $('.codigo_25').find('input').val('');
                    $('.codigo_15_25').find('input').val('');
                    $('.codigo_25').css({
                        'display': 'none'
                    });
                    $('.codigo_15').css({
                        'display': ''
                    });
                    $('.labels_codigo_predio').css('display', 'none');
                }

                if ($("#pagosTable").length > 0) {
                    if (DTPagos !== null) {
                        DTPagos.clear().draw();
                        $("#pagos-filtro-form")[0].reset();
                        clear_form_elements("#pagos-filtro-form");
                    }
                    $('.search_factura').fadeOut();
                    $('.search_general').fadeIn();
                    // $('#chk_search_factura').val('0');
                    // $('#chk_search_factura').prop('checked', false);
                    if ($('#chk_search_factura').is(':checked')) {
                        $('#chk_search_factura').trigger('click');
                    }
                }
                if ($('#fecha_pago_final').length > 0) {
                    $('#fecha_pago_final').datepicker('setStartDate', null);
                    $('#btn_descargar_pagos').fadeOut();
                }
                if ($('#id_banco_final').length > 0) {
                    $.each($('#id_banco_final').find('option'), function(i, el) {
                        $(el).css('display', '');
                    });
                    $('#id_banco_final').attr('disabled', true);
                    $('#id_banco_final').selectpicker('refresh');
                }
                $('.result').empty();
                if ($('#tab').length > 0) {
                    $('#tab').val('li-section-bar-1');
                }
                if ($('#cuotas_acuerdo').length > 0) {
                    $('#cuotas_acuerdo').val('1');
                    // Create a date object from a date string
                    var today = new Date();
                    // Get year, month, and day part from the date
                    var year = today.toLocaleString("default", { year: "numeric" });
                    var month = today.toLocaleString("default", { month: "2-digit" });
                    var day = today.toLocaleString("default", { day: "2-digit" });
                    // Generate yyyy-mm-dd date string
                    var formattedDate = year + "-" + month + "-" + day;
                    $('#fecha_acuerdo').datepicker('setDate', formattedDate);
                    $('#fecha_inicial_acuerdo').datepicker('setDate', formattedDate);
                }
                if($('#total_acuerdo_edit').length > 0) {
                    $('#total_acuerdo_edit').attr('data-total', "");
                }
                if($('#total_acuerdo').length > 0) {
                    $('#total_acuerdo').attr('data-total', "");
                }
                setTimeout(function() {
                    $('#btn_cancel_edit').trigger('click');
                }, 300);

                if ($("#update-form").length > 0) {
                    $("#update-form")[0].reset();
                    $('.text-danger').remove();
                    clear_form_elements("#update-form");
                    var validatorUpdate = $("#update-form").validate();
                    validatorUpdate.resetForm();
                    if ($("#create-form").length > 0) {
                        $("#create-form")[0].reset();
                        clear_form_elements("#create-form");
                        var validatorCreate = $("#create-form").validate();
                        validatorCreate.resetForm();
                    }
                    $.each($(".selectpicker"), function(i, el) {
                        var val = $(el).find('option:selected').val();
                        if (val !== undefined)
                            $(el).val(val).selectpicker("refresh");
                        else
                            $(el).val('default').selectpicker("refresh");
                    });

                    $.each($('.has-success'), function(i, el) {
                        $(el).removeClass('has-success');
                    });
                    $.each($('.has-error'), function(i, el) {
                        $(el).removeClass('has-error');
                    });
                }

                // Limpiar la ultima busqueda realizada en predios
                if($('#id_predio.select2.json').length > 0) {
                    $('#id_predio.select2.json').val(null).trigger('change');
                    $('#id_predio.select2.json').find('option').remove();
                    if ($('#id_predio.select2.json.pagos').length) {
                        if($('#myTable').length > 0) {
                            $('#myTable').find('tbody').empty();
                        }
                    } else if($('#id_predio.select2').hasClass('basico')) {
                        $('#span_prescribe').empty();
                        $('#span_exencion').empty();
                        $('#span_cambio_tarifa').empty();
                    }
                }

                if ($('#interfaz').val() === 'notas') {
                    cleanNotas();
                }
            }
        });
    }

    if ($('#li-section-bar-2').length > 0) {
        $('#li-section-bar-2').bind('click', function() {
            if ($('#tab').val() === 'li-section-bar-1') {
                //$('#btn_cancel_edit').trigger('click');
                $('#id_predio_edit.select2').val(null).trigger("change");
                $('#div_msg_predio_existe').fadeOut('fast');
                $('#div_msg_predio_disponible').fadeOut('fast');
                existe_predio = false;
                codigo_predio_buscando = '';
                $('.result').empty();
                if ($('#tab').length > 0) {
                    $('#tab').val('li-section-bar-2');
                }

                if ($("#create-form").length > 0) {
                    $("#create-form")[0].reset();
                    $('.text-danger').remove();
                    clear_form_elements("#create-form");
                    var validatorCreate = $("#create-form").validate();
                    validatorCreate.resetForm();
                    if ($("#update-form").length > 0) {
                        $("#update-form")[0].reset();
                        clear_form_elements("#update-form");
                        var validatorUpdate = $("#update-form").validate();
                        validatorUpdate.resetForm();
                    }
                    $.each($(".selectpicker"), function(i, el) {
                        var val = $(el).find('option:selected').val();
                        if (val !== undefined)
                            $(el).val(val).selectpicker("refresh");
                        else
                            $(el).val('default').selectpicker("refresh");
                    });
                    $.each($('.has-success'), function(i, el) {
                        $(el).removeClass('has-success');
                    });
                    $.each($('.has-error'), function(i, el) {
                        $(el).removeClass('has-error');
                    });
                }

                // Limpiar la ultima busqueda realizada en predios
                if($('#id_predio.select2.json').length > 0) {
                    if($('#id_predio.select2').hasClass('basico')) {
                        $('#span_prescribe').empty();
                        $('#span_exencion').empty();
                        $('#span_cambio_tarifa').empty();
                    }
                }

                if ($('#interfaz').val() === 'notas') {
                    getJsonNotas();
                } else if ($('#interfaz').val() === 'acuerdos') {
                    getJsonAcuerdos();
                }
            }
        });
    }

    if ($('#btn_save_edit').length > 0) {
        $('#btn_save_edit').off('click').on('click', function() {
            if($('#update-form').valid()) {

                if($('#abono_inicial_acuerdo_edit').length > 0) {
                    if (AutoNumeric.getNumber('#abono_inicial_acuerdo_edit') === 0) {
                        $('#abono_inicial_acuerdo_edit').closest('.form-group').addClass('has-error');
                        $('#abono_inicial_acuerdo_edit').closest('.form-group').removeClass('has-success');
                        $('#abono_inicial_acuerdo_edit').closest('.form-group').find('#abono_inicial_acuerdo_edit-error').html('Abono inicial debe ser mayor a 0.');
                        return;
                    } else {
                        $('#abono_inicial_acuerdo_edit').closest('.form-group').addClass('has-success');
                        $('#abono_inicial_acuerdo_edit').closest('.form-group').removeClass('has-error');
                        $('#abono_inicial_acuerdo_edit').closest('.form-group').find('#abono_inicial_acuerdo_edit-error').remove();
                    }
                }

                checkSaveResolucion($('#update-form'), $('#update-form').attr('desc-to-resolucion-modal'));
            }
        });
    }

    if ($('#btn_save_create').length > 0) {
        $('#btn_save_create').off('click').on('click', function() {
            if($('#create-form').valid()) {
                if($('#tarifa_nueva').length > 0) {
                    if (AutoNumeric.getNumber('#tarifa_nueva') === 0) {
                        return;
                    }
                }

                if($('#abono_inicial_acuerdo').length > 0) {
                    if (AutoNumeric.getNumber('#abono_inicial_acuerdo') === 0) {
                        $('#abono_inicial_acuerdo').closest('.form-group').addClass('has-error');
                        $('#abono_inicial_acuerdo').closest('.form-group').removeClass('has-success');
                        $('#abono_inicial_acuerdo').closest('.form-group').find('#abono_inicial_acuerdo-error').html('Abono inicial debe ser mayor a 0.');
                        return;
                    } else {
                        $('#abono_inicial_acuerdo').closest('.form-group').addClass('has-success');
                        $('#abono_inicial_acuerdo').closest('.form-group').removeClass('has-error');
                        $('#abono_inicial_acuerdo').closest('.form-group').find('#abono_inicial_acuerdo-error').remove();
                    }
                }

                checkSaveResolucion($('#create-form'), $('#create-form').attr('desc-to-resolucion-modal'));
            }
        });
    }

    if ($('#btn_cancel_edit').length > 0) {
        $('#btn_cancel_edit').off('click').on('click', function() {
            $('#div_edit_form').fadeOut(function() {
                if ($('#codigo_predio').length > 0) {
                    $('.buttonTareas').css('display', 'none');
                    $('.codigo_15_edit').find('input').val('');
                    $('.codigo_25_edit').find('input').val('');
                    $('.codigo_15_25_edit').find('input').val('');
                }
                if ($('#div_table').length > 0)
                    $('#div_table').fadeIn();

                if ($('#div_estado_tmp_edit').length > 0) {
                    $('#label_estado_edit').text(' Inactivo');
                    $('#estado_tmp_edit').prop('checked', false);
                }

                if($('#total_acuerdo_edit').length > 0) {
                    $('#total_acuerdo_edit').attr('data-total', "");
                }

                if ($("#create-form").length > 0) {
                    var validatorCreate = $("#create-form").validate();
                    validatorCreate.resetForm();
                    $.each($('.has-success'), function(i, el) {
                        $(el).removeClass('has-success');
                    });
                    $.each($('.has-error'), function(i, el) {
                        $(el).removeClass('has-error');
                    });
                }

                if ($("#update-form").length > 0) {
                    var validatorUpdate = $("#update-form").validate();
                    validatorUpdate.resetForm();
                    $.each($('.has-success'), function(i, el) {
                        $(el).removeClass('has-success');
                    });
                    $.each($('.has-error'), function(i, el) {
                        $(el).removeClass('has-error');
                    });
                }

            });
        });
    }

    setEditRow(true);
    setModifyRow();
    setDeleteRow();

    $('.selectpicker').selectpicker();
    $('.selectpicker-noval').selectpicker();

    $('.selectpicker').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
        if ($("#create-form").length > 0 && $(this).parent('form').attr('id') === 'create-form') {
            $('#create-form').validate().element($(this));
        }
        if ($("#update-form").length > 0 && $(this).parent('form').attr('id') === 'update-form') {
            $('#update-form').validate().element($(this));
        }
    });

    $('.datepicker').datepicker({
        language: 'es-ES',
        format: 'yyyy-mm-dd',
        autoHide: true,
        hide: function() {
            if ($("#create-form").length > 0) {
                if ($('#' + $(this).attr('id') + '-error').length > 0)
                    $('#' + $(this).attr('id') + '-error').remove();

                if ($('#create-form').is(':visible') && !$(this).hasClass('novalidate')) {
                    $('#create-form').validate().element($(this));
                }
            }
            if ($("#update-form").length > 0) {
                if ($('#' + $(this).attr('id') + '-error').length > 0)
                    $('#' + $(this).attr('id') + '-error').remove();

                if ($('#update-form').is(':visible') && !$(this).hasClass('novalidate')) {
                    $('#update-form').validate().element($(this));
                }
            }
        }
    });

    if ($('.datelimite').length > 0) {
        $('.datelimite').datepicker({
            language: 'es-ES',
            format: 'yyyy-mm-dd',
            autoHide: true,
            hide: function() {
                if ($("#create-form").length > 0) {
                    if ($('#' + $(this).attr('id') + '-error').length > 0)
                        $('#' + $(this).attr('id') + '-error').remove();

                    if ($('#create-form').is(':visible') && !$(this).hasClass('novalidate')) {
                        $('#create-form').validate().element($(this));
                    }
                }
                if ($("#update-form").length > 0) {
                    if ($('#' + $(this).attr('id') + '-error').length > 0)
                        $('#' + $(this).attr('id') + '-error').remove();

                    if ($('#update-form').is(':visible') && !$(this).hasClass('novalidate')) {
                        $('#update-form').validate().element($(this));
                    }
                }
            },
            pick: function() {
                $('.text-danger').remove();
            }
        });
    }

    $.each(arr_autonumeric, function(i, el) {
        if ($('#' + el).length > 0) {
            if ($('#' + el).hasClass('porcentaje')) {
                new AutoNumeric('#' + el, {
                    emptyInputBehavior: "zero",
                    maximumValue: "100",
                    minimumValue: "0",
                    modifyValueOnWheel: false,
                    suffixText: "%",
                    unformatOnSubmit: true
                });
            } else if ($('#' + el).hasClass('cien')) {
                new AutoNumeric('#' + el, {
                    emptyInputBehavior: "zero",
                    maximumValue: "100",
                    minimumValue: "0",
                    modifyValueOnWheel: false,
                    // suffixText: "%",
                    unformatOnSubmit: true
                });
            } else if ($('#' + el).hasClass('tasa')) {
                new AutoNumeric('#' + el, {
                    // emptyInputBehavior: "zero",
                    minimumValue: "0",
                    modifyValueOnWheel: false,
                    unformatOnSubmit: true,
                    decimalPlaces: "12"
                });
            } else if ($('#' + el).hasClass('negativos')) {
                new AutoNumeric('#' + el, {
                    emptyInputBehavior: "zero",
                    modifyValueOnWheel: false,
                    unformatOnSubmit: true
                });
            } else {
                new AutoNumeric('#' + el, {
                    emptyInputBehavior: "zero",
                    minimumValue: "0",
                    modifyValueOnWheel: false,
                    unformatOnSubmit: true
                });
            }
        }
        if ($('#' + el + '_edit').length > 0) {
            if ($('#' + el).hasClass('porcentaje')) {
                new AutoNumeric('#' + el + '_edit', {
                    emptyInputBehavior: "zero",
                    maximumValue: "100",
                    minimumValue: "0",
                    modifyValueOnWheel: false,
                    suffixText: "%",
                    unformatOnSubmit: true
                });
            } else if ($('#' + el).hasClass('tasa')) {
                new AutoNumeric('#' + el + '_edit', {
                    // emptyInputBehavior: "zero",
                    minimumValue: "0",
                    modifyValueOnWheel: false,
                    unformatOnSubmit: true,
                    decimalPlaces: "12"
                });
            } else {
                new AutoNumeric('#' + el + '_edit', {
                    emptyInputBehavior: "zero",
                    minimumValue: "0",
                    modifyValueOnWheel: false,
                    unformatOnSubmit: true
                });
            }
        }
    });

    if ($('#aplica_interes').length > 0) {
        $('#aplica_interes').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_aplica_interes').html('SI');
                $(this).val(1);
            } else {
                $('#span_aplica_interes').html('NO');
                $(this).val(0);
            }
        });

        $('#aplica_interes_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_aplica_interes_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_aplica_interes_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#calcular_intereses').length > 0) {
        $('#calcular_intereses').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_calcular_intereses').html('SI');
                $(this).val(1);
            } else {
                $('#span_calcular_intereses').html('NO');
                $(this).val(0);
            }
        });

        $('#calcular_intereses_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_calcular_intereses_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_calcular_intereses_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#estado_acuerdo_edit').length > 0) {
        $('#estado_acuerdo_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_estado_acuerdo_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_estado_acuerdo_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#capital').length > 0) {
        $('#capital').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_capital').html('SI');
                $(this).val(1);
            } else {
                $('#span_capital').html('NO');
                $(this).val(0);
            }
        });

        $('#capital_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_capital_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_capital_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#interes').length > 0) {
        $('#interes').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_interes').html('SI');
                $(this).val(1);
            } else {
                $('#span_interes').html('NO');
                $(this).val(0);
            }
        });

        $('#interes_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_interes_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_interes_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#ind_ley1995').length > 0) {
        $('#ind_ley1995').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_ind_ley1995').html('SI');
                $(this).val(1);
            } else {
                $('#span_ind_ley1995').html('NO');
                $(this).val(0);
            }
        });

        $('#ind_ley1995_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_ind_ley1995_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_ind_ley1995_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#ind_excento_impuesto').length > 0) {
        $('#ind_excento_impuesto').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_ind_excento_impuesto').html('SI');
                $(this).val(1);
            } else {
                $('#span_ind_excento_impuesto').html('NO');
                $(this).val(0);
            }
        });

        $('#ind_excento_impuesto_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_ind_excento_impuesto_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_ind_excento_impuesto_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#ind_plusvalia').length > 0) {
        $('#ind_plusvalia').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_ind_plusvalia').html('SI');
                $(this).val(1);
            } else {
                $('#span_ind_plusvalia').html('NO');
                $(this).val(0);
            }
        });

        $('#ind_plusvalia_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_ind_plusvalia_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_ind_plusvalia_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#ind_invalidez').length > 0) {
        $('#ind_invalidez').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_ind_invalidez').html('SI');
                $(this).val(1);
            } else {
                $('#span_ind_invalidez').html('NO');
                $(this).val(0);
            }
        });

        $('#ind_invalidez_edit').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_ind_invalidez_edit').html('SI');
                $(this).val(1);
            } else {
                $('#span_ind_invalidez_edit').html('NO');
                $(this).val(0);
            }
        });
    }

    if ($('#tipo_factura').length > 0) {
        $('#tipo_factura').off('click').on('click', function() {
            if ($(this).is(':checked')) {
                $('#span_tipo_factura').html('SI');
                $(this).val(1);
                $('#ultimo_anio_facturar').css('height', '70px');
                $('#ultimo_anio_facturar').attr('multiple', true);
                $('#ultimo_anio_facturar').val('');
                $('#ultimo_anio_facturar option:first').css('display', 'none');
                $('#generate_factura_temporal').css('display', '');
                $('#ultimo_anio_facturar').off('change').on('change', function(e) {
                    var selected = $(e.target).val().map(el => Number(el));
                    var newSelected = new Array();
                    var existe_factura = false;
                    for (let i = Math.max(...selected); i >= Math.min(...selected); i--) {
                        newSelected.push(i);
                        var global_anio = global_anios.find(el => parseInt(el.ultimo_anio) === parseInt(i));
                        if (selected.length > 1 && global_anio && global_anio.factura_pago !== null) {
                            existe_factura = true;
                        }
                    }
                    if (existe_factura) {
                        newSelected = [Math.max(...selected)];
                    }
                    $('#ultimo_anio_facturar').val(newSelected);
                    if (newSelected.length === 1) {
                        var anio = newSelected[0];
                        var global_anio = global_anios.find(el => parseInt(el.ultimo_anio) === parseInt(anio));
                        if (global_anio && global_anio.factura_pago !== null) {
                            $('#generate_factura_temporal').css('display', 'none');
                            alert_anio_facturado(global_anio.factura_pago, global_anio.fecha_emision, existe_factura);
                        } else {
                            $('#generate_factura_temporal').css('display', '');
                        }
                    } else {
                        $('#generate_factura_temporal').css('display', '');
                    }
                });
            } else {
                $('#span_tipo_factura').html('NO');
                $(this).val(0);
                $('#ultimo_anio_facturar').css('height', '');
                $('#ultimo_anio_facturar').removeAttr('multiple');
                $('#ultimo_anio_facturar option:first').css('display', '');
                $('#ultimo_anio_facturar').val('');
                $('#generate_factura_temporal').css('display', '');
                $('#ultimo_anio_facturar').off('change').on('change', function() {
                    var anio = $(this).val();
                    var global_anio = global_anios.find(el => parseInt(el.ultimo_anio) === parseInt(anio));
                    if (global_anio && global_anio.factura_pago !== null) {
                        $('#generate_factura_temporal').css('display', 'none');
                        alert_anio_facturado(global_anio.factura_pago, global_anio.fecha_emision, false);
                    } else {
                        $('#generate_factura_temporal').css('display', '');
                    }
                });
            }
        });
    }

    if ($("#create-form").length > 0) {
        $('#create-form').validate().settings.ignore = '';
        $('#create-form').validate().settings.errorPlacement = function(error, element) {
            if (element.hasClass('selectpicker')) {
                element.next().after(error);
            } else if (element.hasClass('withadon')) {
                element.parent().after(error);
            } else {
                error.insertAfter(element);
            }
        };
    }

    if ($("#pagos-filtro-form").length > 0) {
        $('#pagos-filtro-form').validate().settings.ignore = '';
        $('#pagos-filtro-form').validate().settings.errorPlacement = function(error, element) {
            if (element.hasClass('selectpicker')) {
                element.next().after(error);
            } else if (element.hasClass('withadon')) {
                element.parent().after(error);
            } else {
                error.insertAfter(element);
            }
        };
    }

    if ($("#form-predios-prescripcion").length > 0) {
        $('#form-predios-prescripcion').validate().settings.ignore = '';
        $('#form-predios-prescripcion').validate().settings.errorPlacement = function(error, element) {
            if (element.hasClass('selectpicker')) {
                element.next().after(error);
            } else if (element.hasClass('withadon')) {
                element.parent().after(error);
            } else {
                error.insertAfter(element);
            }
        };
    }

    if ($("#update-form").length > 0) {
        $('#update-form').validate().settings.ignore = '';
        $('#update-form').validate().settings.errorPlacement = function(error, element) {
            if (element.hasClass('selectpicker')) {
                element.next().after(error);
            } else if (element.hasClass('withadon')) {
                element.parent().after(error);
            } else {
                error.insertAfter(element);
            }
        };
    }

    if ($('.nofocus').length > 0) {
        $('.nofocus').off('focus').on('focus', function() {
            $(this).blur();
        });
    }

    $('.buttonTareas a').bind('click', function(evt) {
        evt.preventDefault();
    });

    // if($('#exencion_desde').length > 0) {
    //     $('#exencion_desde').off('keyup blur change').on('keyup blur change', function() {
    //         if($(this).val().length === 4) {
    //             if($('#exencion_hasta').val().length === 4) {
    //                 var validatorCreate = $("#create-form").validate();
    //                 validatorCreate.resetForm();
    //                 $.each($('.has-success'), function(i, el) {
    //                     $(el).removeClass('has-success');
    //                 });
    //                 $.each($('.has-error'), function(i, el) {
    //                     $(el).removeClass('has-error');
    //                 });
    //                 $('#create-form').validate().element($('#exencion_hasta'));
    //             }
    //         }
    //     });
    //     $('#exencion_hasta').off('keyup blur change').on('keyup blur change', function() {
    //         if($(this).val().length === 4) {
    //             if($('#exencion_desde').val().length === 4) {
    //                 $('#create-form').validate().element($('#exencion_desde'));
    //                 $('#exencion_desde').trigger('blur');
    //             }
    //         }
    //     });
    //     $('#exencion_desde_edit').off('keyup blur change').on('keyup blur change', function() {
    //         if($(this).val().length === 4) {
    //             if($('#exencion_hasta_edit').val().length === 4) {
    //                 var validatorUpdate = $("#update-form").validate();
    //                 validatorUpdate.resetForm();
    //                 $.each($('.has-success'), function(i, el) {
    //                     $(el).removeClass('has-success');
    //                 });
    //                 $.each($('.has-error'), function(i, el) {
    //                     $(el).removeClass('has-error');
    //                 });
    //                 $('#update-form').validate().element($('#exencion_hasta_edit'));
    //             }
    //         }
    //     });
    //     $('#exencion_hasta_edit').off('keyup blur change').on('keyup blur change', function() {
    //         if($(this).val().length === 4) {
    //             if($('#exencion_desde_edit').val().length === 4) {
    //                 $('#update-form').validate().element($('#exencion_desde_edit'));
    //                 $('#exencion_desde_edit').trigger('blur');
    //             }
    //         }
    //     });
    // }

    if($('#id_predio.select2').length > 0) {
        $('#id_predio.select2').select2({
            language: "es",
            placeholder: "Buscar...",
            allowClear: true,
            minimumInputLength: 3,
            ajax: {
                url: global_url_autocomplete_predio,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        $('#id_predio.select2').on('select2:select', function (e) {
            if ($('#id_predio.select2').closest('form').length > 0 && !$('#id_predio.select2').hasClass('json')) {
                if ($('#id_predio.select2').closest('form').attr('id') === 'create-form') {
                    $('#create-form').validate().element($('#id_predio.select2'));
                }
            }
            else {
                if($('#id_predio.select2').hasClass('json')) {
                    if($('#id_predio.select2').hasClass('pagos')) {
                        getPredio($('#id_predio.select2').val(), true, false);
                    } else if($('#id_predio.select2').hasClass('basico')) {
                        getPredioOtros($('#id_predio.select2').val(), true, $('#interfaz').val());
                    }

                    if ($('#id_predio.select2').find('option').length > 1) {
                        $('#id_predio.select2').find('option:eq(0)').remove();
                    }
                }
            }
        });

        $('#id_predio.select2').on('select2:clear', function (e) {
            global_json_predio = null;
            $('#div_edit_predio').fadeOut();
            if ($('#id_predio.select2').closest('form').length > 0 && !$('#id_predio.select2').hasClass('json')) {
                if ($('#id_predio.select2').closest('form').attr('id') === 'create-form') {
                    $('#create-form').validate().element($('#id_predio.select2'));
                }
            }
            else {
                if($('#id_predio.select2').hasClass('json')) {
                    $('.predio_row').remove();
                    if($('#interfaz').val() === 'notas') {
                        cleanNotas();
                    } else if($('#interfaz').val() === 'acuerdos') {
                        cleanAcuerdos();
                    }
                }
            }
        });
    }

    if($('#id_predio_edit.select2').length > 0) {
        $('#id_predio_edit.select2').select2({
            language: "es",
            placeholder: "Buscar...",
            allowClear: true,
            maximumInputLength: 25,
            minimumInputLength: 3,
            ajax: {
                url: global_url_autocomplete_solo_codigo_predio,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;
                    if(data.items.length === 0) {
                        $('#div_msg_predio_disponible').fadeIn('fast');
                        $('#id_predio_edit.select2').val(null).trigger("change");
                        $('#div_msg_predio_existe').fadeOut('fast');
                        existe_predio = false;
                        codigo_predio_buscando = $('.select2-search__field').val();
                    }
                    else {
                        $('#div_msg_predio_disponible').fadeOut('fast');
                        codigo_predio_buscando = '';
                    }
                    return {
                        results: data.items,
                        pagination: {
                        more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });


        $('#id_predio_edit.select2').on('select2:open', function (e) {
            $('.select2-search__field').attr('maxlength', 25);
            $('#div_msg_predio_disponible').fadeOut('fast');
            existe_predio = true;
        });

        $('#id_predio_edit.select2').on('select2:close', function (e) {
            if($('#id_predio_edit').find('option').length === 0 || !existe_predio) {
                if(codigo_predio_buscando.length > 0) {
                    $('#div_msg_predio_disponible').fadeOut('fast');
                    $('#codigo_predio').val(codigo_predio_buscando);
                    $('#codigo_predio').trigger('keyup');
                    $('#id_predio_edit.select2').val(null).trigger("change");
                    $('#id_predio_edit').empty();
                    existe_predio = false;
                    codigo_predio_buscando = '';
                    setTimeout(function() {
                        $('#codigo_predio').focus();
                    }, 200);
                }
                else {
                    clearFormCreatePredio();
                }
            }
        });

        $('#id_predio_edit.select2').on('select2:select', function (e) {
            $('#div_msg_predio_existe').fadeIn('fast');
            $('#div_msg_predio_disponible').fadeOut('fast');
            codigo_predio_buscando = '';
            clearFormCreatePredio();
        });

        $('#id_predio_edit.select2').on('select2:clear', function (e) {
            $('#div_msg_predio_existe').fadeOut('fast');
            $('#div_msg_predio_disponible').fadeOut('fast');
            codigo_predio_buscando = '';
        });
    }

    $('#generate_factura_definitiva').off('click').on('click', function() {
        $('.btn_pdf').attr('disabled', true);
        if(checkAnioImpresion()) {
            var anios = '';
            if (typeof $('#ultimo_anio_facturar').val() === 'object' && $('#ultimo_anio_facturar').val().length > 1) {
                var arrayAnios = $('#ultimo_anio_facturar').val().map(el => Number(el));
                anios = `${Math.min(...arrayAnios)},${Math.max(...arrayAnios)}`;
            } else if (typeof $('#ultimo_anio_facturar').val() === 'object' && $('#ultimo_anio_facturar').val().length === 1) {
                anios = $('#ultimo_anio_facturar').val()[0];
            } else {
                anios = $('#ultimo_anio_facturar').val();
            }
            var max_fecha = $('#fecha_max_pago').val().length > 0 ? $('#fecha_max_pago').val() : '-';
            var propietario_facturar = $('#propietario_facturar').val();
            var vigencias = $('#tipo_factura').val() || 0;
            startImpresion(global_url_print + '/0/' + anios + '/' + max_fecha + '/0/' + propietario_facturar + '/' + vigencias, 'Iniciando generación factura definitiva de impuesto predial. Espere un momento por favor.', 'warning', 'modal-impresion-factura', undefined);
        }
    });

    $('#generate_factura_temporal').off('click').on('click', function() {
        $('.btn_pdf').attr('disabled', true);
        if(checkAnioImpresion()) {
            var anios = '';
            if (typeof $('#ultimo_anio_facturar').val() === 'object' && $('#ultimo_anio_facturar').val().length > 1) {
                var arrayAnios = $('#ultimo_anio_facturar').val().map(el => Number(el));
                anios = `${Math.min(...arrayAnios)},${Math.max(...arrayAnios)}`;
            } else if (typeof $('#ultimo_anio_facturar').val() === 'object' && $('#ultimo_anio_facturar').val().length === 1) {
                anios = $('#ultimo_anio_facturar').val()[0];
            } else {
                anios = $('#ultimo_anio_facturar').val();
            }
            var max_fecha = $('#fecha_max_pago').val().length > 0 ? $('#fecha_max_pago').val() : '-';
            var propietario_facturar = $('#propietario_facturar').val();
            var vigencias = $('#tipo_factura').val() || 0;
            startImpresion(global_url_print + '/1/' + anios + '/' + max_fecha + '/0/' + propietario_facturar + '/' + vigencias, 'Iniciando generación factura vista previa de impuesto predial. Espere un momento por favor.', 'warning', 'modal-impresion-factura', undefined);
        }
    });

    $('#generate_paz').off('click').on('click', function() {
        $('.btn_pdf').attr('disabled', true);
        if(checkFormPaz()) {
            startImpresion(global_url_print + '/' + $('#destino_paz').val() + '/' + $('#fecha_paz').val() + '/' + $('#valor_paz').val(), 'Iniciando generación de documento de paz y salvo. Espere un momento por favor.', 'warning', 'modal-impresion-paz', undefined);
        }
    });

    $('#btn_modificar_anios').off('click').on('click', function() {
        var uncheckedValues = [];
        $('.anio_factura:not(:checked)').each(function(i, el) {
            uncheckedValues.push($(el).val()); // Push the value of each unchecked checkbox into the array
        });
        if (uncheckedValues.length > 0){
            saveAniosFactura(uncheckedValues);
        }
    });

    if ($('#div_estado_tmp').length > 0) {
        $('#estado_tmp').off('click').on('click', function() {
            $('#div_estado_tmp').trigger('click');
        });
        $('#label_estado_tmp').off('click').on('click', function() {
            $('#div_estado_tmp').trigger('click');
        });
        $('#div_estado_tmp').off('click').on('click', function() {
            if (!$('#estado_tmp').is(':checked')) {
                $('#estado').val('A');
                $('#label_estado_tmp').text(' Activo');
                $('#estado_tmp').prop('checked', true);
            } else {
                $('#estado').val('I');
                $('#label_estado_tmp').text(' Inactivo');
                $('#estado_tmp').prop('checked', false);
            }
        });
    }

    if ($('#div_estado_tmp_edit').length > 0) {
        $('#estado_tmp_edit').off('click').on('click', function() {
            $('#div_estado_tmp_edit').trigger('click');
        });
        $('#label_estado_tmp_edit').off('click').on('click', function() {
            $('#div_estado_tmp_edit').trigger('click');
        });
        $('#div_estado_tmp_edit').off('click').on('click', function() {
            if (!$('#estado_tmp_edit').is(':checked')) {
                $('#estado_edit').val('A');
                $('#label_estado_tmp_edit').text(' Activo');
                $('#estado_tmp_edit').prop('checked', true);
            } else {
                $('#estado_edit').val('I');
                $('#label_estado_tmp_edit').text(' Inactivo');
                $('#estado_tmp_edit').prop('checked', false);
            }
        });
    }

    if($('#print_prescripciones').length) {
        $('#print_prescripciones').off('click').on('click', function() {
            var validatorInforme = $("#form-impresion-informe").validate({
                rules: {
                    fecha_min_prescripcion: "required",
                    fecha_max_prescripcion: "required",
                },
                messages: {
                    fecha_min_prescripcion: "Fecha m&iacute;nima requerida",
                    fecha_max_prescripcion: "Fecha m&aacute;xima requerida",
                }
            });
            $('#modal-impresion').modal({ backdrop: 'static', keyboard: false }, 'show');
            // $('#modal-impresion').off('shown.bs.modal').on('shown.bs.modal', function() {
            // });

            $('#modal-impresion').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                $('#form-impresion-informe')[0].reset();
                clear_form_elements("#form-impresion-informe");
                validatorInforme.resetForm();
                $('#fecha_min_prescripcion').removeClass('error');
                $('#fecha_max_prescripcion').removeClass('error');
            });
        });

        $('#fecha_min_prescripcion').on('pick.datepicker', function (e) {
            $('#fecha_min_prescripcion').removeClass('error');
        });
        $('#fecha_max_prescripcion').on('pick.datepicker', function (e) {
            $('#fecha_max_prescripcion').removeClass('error');
            if (e.date !== null) {
                if ($('#fecha_max_prescripcion').datepicker('getDate') < $('#fecha_min_prescripcion').datepicker('getDate')) {
                    $('#fecha_min_prescripcion').datepicker('setDate', '');
                    $('#fecha_min_prescripcion').val('');
                }
            }
        });
    }

    if($('#generar_informe_prescripciones').length) {
        $('#generar_informe_prescripciones').off('click').on('click', function() {
            var btn = $(this);
            var form = $("#form-impresion-informe");
            if (form.valid()) {
                $('.btn_pdf').attr('disabled', true);
                startImpresion($(btn).attr('url') + $('#fecha_min_prescripcion').val() + '/' + $('#fecha_max_prescripcion').val(), 'Iniciando generación de listado de prescripciones. Espere un momento por favor.', 'warning', 'modal-impresion');
            }
        });
    }

    if($('#print_exenciones').length) {
        $('#print_exenciones').off('click').on('click', function() {
            var validatorInforme = $("#form-impresion-informe").validate({
                rules: {
                    fecha_min_exencion: "required",
                    fecha_max_exencion: "required",
                },
                messages: {
                    fecha_min_exencion: "Fecha m&iacute;nima requerida",
                    fecha_max_exencion: "Fecha m&aacute;xima requerida",
                }
            });
            $('#modal-impresion').modal({ backdrop: 'static', keyboard: false }, 'show');
            // $('#modal-impresion').off('shown.bs.modal').on('shown.bs.modal', function() {
            // });

            $('#modal-impresion').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                $('#form-impresion-informe')[0].reset();
                clear_form_elements("#form-impresion-informe");
                validatorInforme.resetForm();
                $('#fecha_min_exencion').removeClass('error');
                $('#fecha_max_exencion').removeClass('error');
            });
        });

        $('#fecha_min_exencion').on('pick.datepicker', function (e) {
            $('#fecha_min_exencion').removeClass('error');
        });
        $('#fecha_max_exencion').on('pick.datepicker', function (e) {
            $('#fecha_max_exencion').removeClass('error');
            if (e.date !== null) {
                if ($('#fecha_max_exencion').datepicker('getDate') < $('#fecha_min_exencion').datepicker('getDate')) {
                    $('#fecha_min_exencion').datepicker('setDate', '');
                    $('#fecha_min_exencion').val('');
                }
            }
        });
    }

    if($('#generar_informe_exenciones').length) {
        $('#generar_informe_exenciones').off('click').on('click', function() {
            var btn = $(this);
            var form = $("#form-impresion-informe");
            if (form.valid()) {
                $('.btn_pdf').attr('disabled', true);
                startImpresion($(btn).attr('url') + $('#fecha_min_exencion').val() + '/' + $('#fecha_max_exencion').val(), 'Iniciando generación de listado de exenciones. Espere un momento por favor.', 'warning', 'modal-impresion');
            }
        });
    }

    if ($('#btn_descargar_excel_prescripciones_exenciones')) {
        $('#btn_descargar_excel_prescripciones_exenciones').off('click').on('click', function() {
            var btn = $(this);
            var form = $("#form-impresion-informe");
            if (form.valid()) {
                $('.btn_excel').attr('disabled', true);
                var tipo = $(btn).attr('tipo');
                var fecha_inicial = tipo === 'prescripciones' ? $('#fecha_min_prescripcion').val() : $('#fecha_min_exencion').val();
                var fecha_final = tipo === 'prescripciones' ? $('#fecha_max_prescripcion').val() : $('#fecha_max_exencion').val();

                $.blockUI({
                    message: `Generando archivo EXCEL con reporte de ${tipo} desde ${fecha_inicial} hasta ${fecha_final}.<br />Espere un momento.`,
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
                fetch(`/export-excel-${tipo}/${fecha_inicial}/${fecha_final}`)
                .then(resp => resp.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    // the filename you want
                    a.download = `reporte-${tipo}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    $.unblockUI();
                    $('.btn_excel').attr('disabled', false);
                })
                .catch((err) => {
                    $.unblockUI();
                    $('.btn_excel').attr('disabled', false);
                    console.log(err);
                });
            }
        });
    }

    if ($('.excel_avaluos_estado')) {
        $('.excel_avaluos_estado').off('click').on('click', function() {
            var btn = $(this);
            $('.btn_excel').attr('disabled', true);
            var tipo = $(btn).attr('tipo');

            $.blockUI({
                message: `Generando archivo EXCEL de ${tipo.replace(/_/g, ' ')}.<br />Espere un momento.`,
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
            fetch(`/export-excel-${tipo.replace(/_/g, '-')}/${$('#id_edit').val()}`)
            .then(resp => resp.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                // the filename you want
                a.download = `reporte-${tipo.replace(/_/g, '-')}.xlsx`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                $.unblockUI();
                $('.btn_excel').attr('disabled', false);
            })
            .catch((err) => {
                $.unblockUI();
                $('.btn_excel').attr('disabled', false);
                console.log(err);
            });
        });
    }

});

function clearFormCreatePredio() {
    $("#create-form")[0].reset();
    $('.text-danger').remove();
    clear_form_elements("#create-form");
    var validatorCreate = $("#create-form").validate();
    validatorCreate.resetForm();
    $.each($('.has-success'), function(i, el) {
        $(el).removeClass('has-success');
    });
    $.each($('.has-error'), function(i, el) {
        $(el).removeClass('has-error');
    });
    $('#codigo_predio').trigger('keyup');
}

function setEditRow(initDataTable) {
    if ($('.edit_row').length > 0) {
        $('.edit_row').off('click').on('click', function() {
            var row = $(this).parent('tr');
            if (!$(row).hasClass('disabled')) {
                var jsonObj = JSON.parse($(row).attr('json-data'));
                global_json = jsonObj;
                // if(global_json.prescrito === undefined || Number(global_json.prescrito) < 1) {
                $('.result').empty();
                if ($(row).hasClass('descuento_row')) {
                    $('#porcentaje_edit').prop('readonly', false);
                    $('#fecha_inicio_edit').prop('readonly', true);
                    $('#fecha_fin_edit').prop('readonly', true);
                    $('#fecha_fin_edit').datepicker('destroy');

                    if (moment($('#fecha_oculta').val()) <= moment(jsonObj.fecha_fin) && moment($('#fecha_oculta').val()) > moment(jsonObj.fecha_inicio)) {
                        $('#porcentaje_edit').prop('readonly', true);
                        $('#fecha_fin_edit').prop('readonly', false);
                        $('#fecha_fin_edit').datepicker({
                            language: 'es-ES',
                            format: 'yyyy-mm-dd',
                            startDate: moment($('#fecha_oculta').val()), // Or '02/14/2014'
                            hide: function() {
                                if ($("#create-form").length > 0) {
                                    if ($('#' + $(this).attr('id') + '-error').length > 0)
                                        $('#' + $(this).attr('id') + '-error').remove();

                                    $('#create-form').validate().element($(this));
                                }
                                if ($("#update-form").length > 0) {
                                    if ($('#' + $(this).attr('id') + '-error').length > 0)
                                        $('#' + $(this).attr('id') + '-error').remove();

                                    $('#update-form').validate().element($(this));
                                }
                            },
                            pick: function() {
                                $('.text-danger').remove();
                            }
                        });
                    }
                }

                setData(jsonObj);
                // }
            }
        });

        if(initDataTable) {
            $('#myTable').DataTable({
                "destroy": true,
                "ordering": false,
                "order": [],
                "lengthChange": false,
                "info": false,
                "pageLength": 5,
                "select": false,
                "autoWidth": false,
                "language": {
                    "url": ROOT_URL + "/theme/plugins/bower_components/datatables/spanish.json"
                }
            });
        }
    }
}

function setModifyRow() {
    if ($('.modify_row').length > 0) {
        $('.modify_row').off('click').on('click', function(evt) {
            $(this).closest('tr').find('td:eq(1)').trigger('click');
        });
    }
}

function setDeleteRow() {
    if ($('.delete_row').length > 0) {
        $('.delete_row').off('click').on('click', function(evt) {
            var btn = $(this);
            var msg = "¿Está seguro/a que desea eliminar el registro?";
            if ($(btn).is('[msg]')) {
                msg = $(btn).attr('msg');
            }
            swal({
                title: "Atención",
                text: msg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    $('#input_delete').val($(btn).attr('ide'));
                    if ($(btn).attr('req_res') !== undefined) {
                        $('#txt_operacion_resolucion').html('<span class="text-danger">Operaci&oacute;n:&nbsp;</span>Anulaci&oacuten de predio');
                        global_form_to_send = 'form_delete';
                        $('#modal-resolucion').modal('show');
                    } else {
                        $('#form_delete').first().submit();
                    }
                }
            });
        });
    }
}

function setDownloadFacturaRow() {
    if ($('.download_factura_row').length > 0) {
        $('.download_factura_row').off('click').on('click', function(evt) {
            var btn = $(this);
            if (global_ultima_factura && global_ultima_factura['factura_pago'] && !global_ya_pagado && global_ultima_factura['pagado'] !== '-1') {
                swal({
                    title: "Atención",
                    text: `La solicitud de generación de factura ya fue realizada previamente.\n\nFactura No. ${global_ultima_factura['factura_pago']}\nGenerada el ${new Date(global_ultima_factura['fecha_emision']).toLocaleString()}\n\n¿Desea volver a generarla?`,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        downloadFacturaRowProcesar(btn);
                    }
                });
            } else {
                downloadFacturaRowProcesar(btn);
            }
        });
    }
}

function downloadFacturaRowProcesar(btn) {
    var url_download = $(btn).attr('url');
    var id_predio = $(btn).attr('ide');
    global_url_print = url_download;
    $(btn).attr('disabled', true);
    $('[data-toggle="tooltip"]').tooltip('destroy');
    if(!global_ya_pagado) {
        $('#modal-impresion-factura').modal({ backdrop: 'static', keyboard: false }, 'show');
        $('#modal-impresion-factura').off('hidden.bs.modal').on('hidden.bs.modal', function() {
            $(btn).attr('disabled', false);
            $('#form-predios-impresion-factura')[0].reset();
            $('#tipo_factura').prop('checked', false);
            $('#tipo_factura').val(0);
            $('#span_tipo_factura').html('NO');
            $('#ultimo_anio_facturar').css('height', '');
            $('#ultimo_anio_facturar').removeAttr('multiple');
            $('#ultimo_anio_facturar option:first').css('display', '');
            $('#ultimo_anio_facturar').off('change');
            $('#ultimo_anio_facturar').val('');
            $('#generate_factura_temporal').css('display', '');
            $('#div_anios_factura').css('display', 'none');
            $('#lista_anios_factura').empty();
            $('#btn_modificar_anios').css('display', 'none');
            $('#div_fecha_max_pago').css('display', '');
            setTimeout(function() {
                getPredio(id_predio, false, false);
            }, 2000);
        });
        $('#modal-impresion-factura').off('show.bs.modal').on('show.bs.modal', function() {
            if (moment($('#fecha_actual').val()) > moment($('#max_fecha_descuentos').val())) {
                var today = new Date();
                var year = today.toLocaleString("default", { year: "numeric" });
                var month = today.toLocaleString("default", { month: "2-digit" });
                var day = today.toLocaleString("default", { day: "2-digit" });
                if (month === '11') {
                    if (day === '01') day = '1';
                }
                // Generate yyyy-mm-dd date string
                var formattedDate = year + "-" + month + "-" + day;

                $('#fecha_max_pago').datepicker('setDate', formattedDate);
                // $('#fecha_max_pago').datepicker('setDate', $('#fecha_actual').val());
            } else {
                $('#fecha_max_pago').datepicker('clearDates');
            }
        });

        $('#modal-impresion-factura').off('shown.bs.modal').on('shown.bs.modal', function() {
            $('[data-toggle="tooltip"]').tooltip();
            $('#ultimo_anio_facturar').off('change').on('change', function() {
                $('#div_anios_factura').css('display', 'none');
                $('#lista_anios_factura').empty();
                $('#btn_modificar_anios').css('display', 'none');
                $('#div_fecha_max_pago').css('display', '');
                var anio = $(this).val();
                if (anio.length > 0) {
                    var global_anio = global_anios.find(el => parseInt(el.ultimo_anio) === parseInt(anio));
                    if (global_anio && global_anio.factura_pago !== null) {
                        global_facturado = global_anio;
                        $('#generate_factura_temporal').css('display', 'none');
                        alert_anio_facturado(global_anio.factura_pago, global_anio.fecha_emision, false);
                        var lista = $('#ultimo_anio_facturar').find('option[value="'+ anio +'"]').attr('lista').split(',');
                        if (lista.length > 0 && lista[0] !== '') {
                            $('#div_anios_factura').fadeIn();
                            $.each($('#ultimo_anio_facturar').find('option[value="'+ anio +'"]').attr('lista').split(','), function(i, el) {
                                var html_anio = $('<div id="div_anio_factura_' + el + '" class="col-lg-4 col-md-4 col-sm-6 col-xs-12">' +
                                    '<div class="form-group" style="margin: 0px;">' +
                                    ' <label for="anio_factura" class="control-label" style="display: block; margin: 0px;">' +
                                    '  <input class="anio_factura" type="checkbox" id="anio_factura_'+ el +'" name="anio_factura_'+ el +'" value="' + el + '" checked="checked">&nbsp;' + el +
                                    ' </label>' +
                                    '</div>' +
                                '</div>');
                                $('#lista_anios_factura').append(html_anio);
                            });

                            var html_anio = $('<div id="div_anio_factura_todos" class="col-lg-8 col-md-8 col-sm-12 col-xs-12">' +
                                '<div class="form-group" style="margin: 0px;">' +
                                ' <label for="anio_factura_todos" class="control-label" style="display: block; margin: 0px;">' +
                                '  <input class="anio_factura_todos" type="checkbox" id="anio_factura_todos" name="anio_factura_todos" value="ALL" checked="checked">&nbsp;<span id="span_marcar_desmarcar">Deseleccionar</span> todos' +
                                ' </label>' +
                                '</div>' +
                            '</div>');
                            $('#lista_anios_factura').append(html_anio);

                            $('#anio_factura_todos').off('change').on('change', function(e) {
                                checkUncheckAnios(e, global_anio);
                            });

                            $('.anio_factura').off('change').on('change', function(e) {
                                checkAnio(e, global_anio);
                            });
                        }
                    } else {
                        $('#generate_factura_temporal').css('display', '');
                    }
                } else {
                    $('.btn_ver_factura').fadeIn('fast');
                }
            });
        });
    }
    else {
        var max_fecha = $('#fecha_max_pago').val().length > 0 ? $('#fecha_max_pago').val() : '-';
        var propietario_facturar = $('#propietario_facturar').val();
        startImpresion(global_url_print + '/0/' + global_anio_actual + '/' + max_fecha + '/0/' + propietario_facturar + '/0', 'Pago ya registrado. Generación de factura informativa de impuesto predial. Espere un momento por favor.', 'success', '', undefined);
        $(btn).attr('disabled', false);
        $('[data-toggle="tooltip"]').tooltip();
    }
}

function checkAnio(e, global_anio) {
    var control = $(e.target);
    var checkedValues = [];
    var uncheckedValues = [];
    $('.anio_factura:checked').each(function() {
        checkedValues.push(Number($(control).val())); // Push the value of each checked checkbox into the array
    });

    var hasMissing = false; // hasMissingValue(checkedValues) || !checkedValues.includes(global_anio_actual);

    $('.anio_factura:not(:checked)').each(function() {
        uncheckedValues.push($(control).val()); // Push the value of each unchecked checkbox into the array
    });

    if (checkedValues.length === 0) {
        $('#span_marcar_desmarcar').html('Seleccionar');

    } else {
        $('#span_marcar_desmarcar').html('Deseleccionar');
    }

    if (uncheckedValues.length === 0) {
        $('#span_marcar_desmarcar').html('Deseleccionar');
        $('#anio_factura_todos').prop('checked', true);
        if (global_anio && global_anio.factura_pago !== null) {
            $('#generate_factura_definitiva').css('display', '');
        } else {
            $('.btn_ver_factura').css('display', '');
        }
        $('#div_fecha_max_pago').css('display', '');
    } else {
        $('#span_marcar_desmarcar').html('Seleccionar');
        $('#anio_factura_todos').prop('checked', false);

        if (global_anio && global_anio.factura_pago !== null) {
            $('#generate_factura_definitiva').css('display', 'none');
        } else {
            $('.btn_ver_factura').css('display', 'none');
        }
        $('#div_fecha_max_pago').css('display', 'none');
    }

    if (!hasMissing && uncheckedValues.length > 0 && checkedValues.length > 0) {
        $('#btn_modificar_anios').css('display', '');
    } else {
        $('#btn_modificar_anios').css('display', 'none');
    }
}

function checkUncheckAnios(e, global_anio) {
    var control = $(e.target);
    var checked = $(control).is(':checked');
    $('.anio_factura').off();
    $('.anio_factura').prop('checked', checked);
    if (checked) {
        $('#span_marcar_desmarcar').html('Deseleccionar');
        if (global_anio && global_anio.factura_pago !== null) {
            $('#generate_factura_definitiva').css('display', '');
        } else {
            $('.btn_ver_factura').css('display', '');
        }
        $('#div_fecha_max_pago').css('display', '');
        $('#btn_modificar_anios').css('display', 'none');

    } else {
        $('#span_marcar_desmarcar').html('Seleccionar');
        if (global_anio && global_anio.factura_pago !== null) {
            $('#generate_factura_definitiva').css('display', 'none');
        } else {
            $('.btn_ver_factura').css('display', 'none');
        }
        $('#div_fecha_max_pago').css('display', 'none');
    }

    $('.anio_factura').off('change').on('change', function(e) {
        checkAnio(e, global_anio);
    });
}

function hasMissingValue(numbers) {
    // Sort the array in ascending order
    const sorted = numbers.sort((a, b) => a - b);

    // Check for missing values
    for (let i = 1; i < sorted.length; i++) {
      if (sorted[i] - sorted[i-1] > 1) {
        return true; // Missing value found
      }
    }

    return false; // No missing values
}

// Función removida: setDownloadFacturaGreenRow() - el botón verde fue eliminado

function setDownloadPazRow() {
    if ($('.download_paz_row').length > 0) {
        $('.download_paz_row').off('click').on('click', function(evt) {
            var btn = $(this);
            $(btn).attr('disabled', true);

            var url_download = $(btn).attr('url');
            var plusv = $(btn).attr('plusv');
            global_plusvalia = Number(plusv);
            global_url_print = url_download;

            // Create a date object from a date string
            var today = new Date();
            //var tomorrow = new Date();
            //tomorrow.setDate(today.getDate()+1);
            // Get year, month, and day part from the date
            var year = today.toLocaleString("default", { year: "numeric" });
            var month = today.toLocaleString("default", { month: "2-digit" });
            var day = today.toLocaleString("default", { day: "2-digit" });
            // Generate yyyy-mm-dd date string
            var formattedDate = year + "-" + month + "-" + day;
            var formattedEndDate = year + "-" + 12 + "-" + 31;
            $('#fecha_paz').datepicker('setDate', formattedEndDate);
            $('#fecha_paz').datepicker('setStartDate', formattedDate);
            $('#fecha_paz').datepicker('setEndDate', formattedEndDate);
            $('[data-toggle="tooltip"]').tooltip('destroy');
            $('#modal-impresion-paz').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                $(btn).attr('disabled', false);
                $('#form-predios-impresion-paz')[0].reset();
                global_plusvalia = 0;
                $('#div_message_plusvalia').fadeOut();
            });
            $('#modal-impresion-paz').off('shown.bs.modal').on('shown.bs.modal', function() {
                $('#destino_paz').focus();
                $('[data-toggle="tooltip"]').tooltip();
            });
            if (global_plusvalia !== 0) {
                $('#div_message_plusvalia').fadeIn('fast');
                swal({
                    title: "Atención",
                    text: 'El predio seleccionado presenta un indicador de plusvalia activo. Por favor, no olvide realizar el cobro pertinente.',
                    type: "warning",
                    // confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $('#modal-impresion-paz').modal({ backdrop: 'static', keyboard: false }, 'show');
                    }
                });
            } else {
                $('#modal-impresion-paz').modal({ backdrop: 'static', keyboard: false }, 'show');
            }
        });
    }
}

function startImpresion(url_download, message_toast, type_icon, modal, callback) {
    if(url_download.length > 0) {
        $.toast({
            heading: 'Atención',
            text: message_toast,
            position: 'top-right',
            loaderBg: '#fff',
            icon: type_icon,
            hideAfter: 4000,
            stack: 6,
            afterHidden: function () {
                if(modal.length > 0) {
                    // $('.btn_pdf').attr('disabled', false);
                    $('#' + modal).modal('hide');
                }
                $('.btn_pdf').attr('disabled', false);
            }
        });
        setTimeout(function() {
            if ($('#iframe_reporte').length > 0) {
                $('#iframe_reporte').remove();
            }
            var iframe = $('<iframe id="iframe_reporte" style="display:none;"></iframe>');
            iframe.attr('src', url_download);
            $('body').append(iframe);
            if (callback !== undefined) {
                setTimeout(function() {
                    callback();
                }, 1000);
            }
        }, 1000);
    }
    else {
        swal({
            title: "Atención",
            text: "Se necesita establecer una url para generación de PDF.",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true,
            closeOnCancel: true
        });
        $('.btn_pdf').attr('disabled', false);
    }
}

function getPredio(id_predio, showBlock, getData) {
    if (showBlock) {
        $.blockUI({
            message: "Ejecutando b&uacute;squeda de predio. Espere un momento.",
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
    }
    global_ya_pagado = false;
    global_anio_actual = 0;
    global_predio_con_deuda = false;
    global_anios_prescripcion_exencion = [];
    $('#div_fecha_pago_factura').css('display', 'none');
    $('#identificacion_acuerdo').attr('readonly', false);
    $('#nombre_acuerdo').attr('readonly', false);
    $('#direccion_acuerdo').attr('readonly', false);
    $('.result').empty();
    var jsonObj = {};
    jsonObj.id_predio = id_predio;
    jsonObj.calcular = 1;
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/get_predio',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            $('#myTable').find('tbody').empty();
            if (Object.keys(response.predio).length > 0) {
                var opcion = JSON.parse($('#opcion').val());
                var predio = response.predio;
                var anios = response.anios;
                global_acuerdo_pago = response.acuerdo_pago;
                global_propietario = response.propietario;
                global_propietarios = response.propietarios;
                global_anios = response.anios;
                global_ultima_factura = response.ultimo_anio;
                exento_pago = false;
                if (response.anios_prescripcion.length > 0) {
                    global_anios_prescripcion_exencion = response.anios_prescripcion.map(el => el.ultimo_anio);
                } else {
                    global_anios_prescripcion_exencion = [];
                }
                if (Number(response.ultimo_anio.exencion) != 0) {
                    if (Number(response.ultimo_anio.exencion_porcentaje) === 100) {
                        exento_pago = true;
                    }
                }
                global_anio_actual = Number(response.anio_actual);
                var classBtn = 'btn-info';
                var classBtnCalculo = 'fa-cogs';
                var colorBtnCalculo = 'success';
                var disabledBtnEdita = '';
                var disabledBtnPrescribe = '';
                var disabledBtnCalculo = '';
                var disabledBtnPaz = '';
                var disabledBtnElimina = '';
                var tr = $('<tr style="cursor: pointer;" id="tr_predio_' + predio.id + '" json-data=\'' + JSON.stringify(predio) + '\' class="predio_row"></tr>');
                var td_1 = $('<td class="cell_center">' + predio.codigo_predio + '&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-copy copy_predio" data-codigo="' + predio.codigo_predio + '" data-toggle="tooltip" data-placement="top" title="Copiar al portapapeles"></i></td>');
                // if(Number(predio.prescrito) > 0) {
                //     td_1.append('&nbsp;&nbsp;<span data-toggle="tooltip" data-placement="bottom" title="Prescrito hasta ' + predio.prescribe_desde + '" style="color: #f41a0f;"><i class="fa fa-info-circle"></i></span>');
                //     classBtn = 'btn-default';
                //     disabledBtnPrescribe = 'disabled="disabled"';
                //     disabledBtnCalculo = 'disabled="disabled"';
                //     disabledBtnPaz = 'disabled="disabled"';
                //     disabledBtnEdita = 'disabled="disabled"';
                //     disabledBtnElimina = 'disabled="disabled"';
                // }
                if (global_anios_prescripcion_exencion.length === 0) {
                    disabledBtnPrescribe = 'disabled="disabled"';
                }

                if(global_acuerdo_pago) {
                    $.toast({
                        heading: 'Atención',
                        text: 'El predio seleccionado posee un ACUERDO DE PAGO activo.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'warning',
                        hideAfter: 5000,
                        stack: 6
                    });
                }

                // if(anios.filter(a => a.factura_pago === null).length > 0) {
                if (anios.length > 0 || (anios.length === 1 && Number(predio.ultimo_anio_pago) !== Number(anios[0].ultimo_anio))) {
                    disabledBtnPaz = 'disabled="disabled"';
                    $('#div_propietario_facturar').css('display', '');
                    if (anios.length === 1) {
                        $('#div_vigencias_impresion').css('display', 'none');
                    }
                }
                else if(anios.length === 0) {
                    global_ya_pagado = true;
                    classBtnCalculo = 'fa-file-pdf-o';
                    colorBtnCalculo = 'success btn-outline';
                    $('#div_propietario_facturar').css('display', 'none');
                }

                var anios_sin_acuerdo = [];
                if (global_acuerdo_pago) {
                    var inicio = parseInt(global_acuerdo_pago.anio_inicial_acuerdo);
                    var fin = parseInt(global_acuerdo_pago.anio_final_acuerdo);
                    anios.forEach(function(a) {
                        var anio_val = parseInt(a.ultimo_anio);
                        if (anio_val < inicio || anio_val > fin) {
                            anios_sin_acuerdo.push(a.ultimo_anio);
                        }
                    });
                }

                var td_2 = $('<td class="edit_row cell_center">' + predio.direccion + '</td>');
                var td_3 = $('<td class="edit_row cell_center">' + predio.propietarios + '</td>');
                var td_4 = $('<td class="cell_center"></td>');

                var htmlBotones = '<button type="button" data-toggle="tooltip" data-placement="bottom" title="Editar predio" ide="' + predio.id + '" class="modify_row btn btn-instagram" req_res="' + opcion.resolucion_edita + '" ' + disabledBtnEdita + '><i class="fa fa-pencil-square"></i></button>' +
                                  '&nbsp;&nbsp;' +
                                //   '<button type="button" data-toggle="tooltip" data-placement="bottom" title="Prescribir predio" ide="' + predio.id + '" class="prescribe_row btn ' + classBtn + '" ' + disabledBtnPrescribe + '><i class="fa fa-clock-o"></i></button>' +
                                //   '&nbsp;&nbsp;' +
                                  (!exento_pago ?
                                    '<button id="btn_calculo_predio_' + predio.id + '" type="button" data-toggle="tooltip" data-placement="bottom" title="Generar factura" ide="' + predio.id + '" class="download_factura_row btn btn-'+ colorBtnCalculo +'" url="/generate_factura_pdf/' + predio.id + '" msg="¿Está seguro/a que desea ejecutar el cálculo?" ' + disabledBtnCalculo + '><i id="i_calculo_predio_' + predio.id + '" class="fa ' + classBtnCalculo + '"></i></button>' + '&nbsp;&nbsp;'
                                    : '') +
                                  '<button type="button" data-toggle="tooltip" data-placement="bottom" title="Generar paz y salvo" ide="' + predio.id + '" class="download_paz_row btn btn-warning" url="/generate_paz_pdf/' + predio.id + '" plusv="' + predio.ind_plusvalia + '" msg="¿Está seguro/a que desea generar el paz y salvo?" ' + disabledBtnPaz + '><i class="fa fa-trophy"></i></button>' +
                                  '&nbsp;&nbsp;' +
                                //   '<button type="button" data-toggle="tooltip" data-placement="bottom" title="Ver aval&uacute;os" ide="' + predio.id + '" class="avaluos_row btn btn-default"><i class="fa fa-home"></i></button>' +
                                //   '&nbsp;&nbsp;' +
                                  (globalShowBtnAcuerdos && !global_ya_pagado && (anios.length > 0 || global_acuerdo_pago) ? ('<button type="button" data-toggle="tooltip" data-placement="bottom" title="Acuerdo de pago" ide="' + predio.id + '" class="acuerdos_row btn btn-default"><i class="fa fa-child"></i></button>' +
                                  '&nbsp;&nbsp;') : '' ) +
                                  '<button type="button" data-toggle="tooltip" data-placement="bottom" title="Eliminar predio" ide="' + predio.id + '" class="delete_row btn btn-danger" req_res="' + opcion.resolucion_elimina + '" msg="¿Está seguro/a que desea anular el predio?" ' + disabledBtnElimina + '><i class="fa fa-trash-o"></i></button>';

                td_4.html(htmlBotones);
                tr.append(td_1).append(td_2).append(td_3).append(td_4);
                $('#myTable').find('tbody').append(tr);

                $('#propietario_facturar').empty();
                $('#ultimo_anio_facturar').empty();
                $('#anio_inicial_acuerdo').empty();
                $('#anio_final_acuerdo').empty();
                $('#ultimo_anio_facturar').append('<option value="">Seleccione a&ntilde;o...</option>');
                $('#anio_inicial_acuerdo').append('<option value="">Seleccione</option>');
                $('#anio_final_acuerdo').append('<option value="">Seleccione</option>');
                $.each(global_propietarios, function(i, el) {
                    if (el.jerarquia === '001') {
                        $('#propietario_facturar').append('<option selected value="' + el.id + '">' + el.jerarquia + ' - ' + el.nombre + '</option>');
                    } else {
                        $('#propietario_facturar').append('<option value="' + el.id + '">' + el.jerarquia + ' - ' + el.nombre + '</option>');
                    }
                });

                $.each(anios, function(i, el) {
                    var anio = global_anios.find(a => a.ultimo_anio === el.ultimo_anio);
                    var factura = anio.factura_pago || '';
                    $('#ultimo_anio_facturar').append('<option value="' + el.ultimo_anio + '" lista="' + (anio?.lista_anios ? anio.lista_anios.toString() : '') + '">' + el.ultimo_anio + (factura.length > 0 ? `&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Factura No. ${factura}` : '') + '</option>');
                    $('#anio_inicial_acuerdo').append('<option value="' + el.ultimo_anio + '">' + el.ultimo_anio + '</option>');
                    $('#anio_final_acuerdo').append('<option value="' + el.ultimo_anio + '">' + el.ultimo_anio + '</option>');
                });
                if(anios.length > 1) {
                    global_predio_con_deuda = true;
                    $('#div_fecha_pago_factura').css('display', '');
                    var today = new Date();
                    var tomorrow = new Date();
                    tomorrow.setDate(today.getDate()+1);
                    var year = tomorrow.toLocaleString("default", { year: "numeric" });
                    var month = tomorrow.toLocaleString("default", { month: "2-digit" });
                    var day = tomorrow.toLocaleString("default", { day: "2-digit" });
                    // Generate yyyy-mm-dd date string
                    var formattedDate = year + "-" + month + "-" + day;
                    var formattedEndDate = year + "-" + 12 + "-" + 31;
                    $('#fecha_pago_factura').datepicker('setDate', formattedDate);
                    $('#fecha_pago_factura').datepicker('setStartDate', formattedDate);
                    $('#fecha_pago_factura').datepicker('setEndDate', formattedEndDate);
                }
                setTimeout(function() {
                    setEditRow(false);
                    setModifyRow();
                    setDeleteRow();
                    setDownloadFacturaRow();
                    // setDownloadFacturaGreenRow() - removido
                    setDownloadPazRow();
                    // setPrescribeRow();
                    setCopyPredio();
                    // setAvaluosRow();
                    if (!global_ya_pagado) {
                        setAcuerdoPagoRow();
                    }
                    $('.tips').powerTip({
                        placement: 's' // north-east tooltip position
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                    $('#div_edit_predio').fadeIn(function() {
                        $.unblockUI();
                    });
                    if (getData) {
                        getJsonPrediosDatos();
                    }
                }, 500);
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

function getPredioOtros(id_predio, showBlock, interfaz) {
    if (showBlock) {
        $.blockUI({
            message: "Ejecutando b&uacute;squeda de predio. Espere un momento.",
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
    }
    global_ya_pagado = false;
    // global_anio_actual = 0;
    // global_predio_con_deuda = false;
    global_anios_prescripcion_exencion = [];
    $('.result').empty();
    var jsonObj = {};
    jsonObj.id_predio = id_predio;
    if (interfaz === 'notas') {
        jsonObj.notas = 1;
        $('#select_anios').css('display', 'none');
        $('#select_anio').css('display', 'none');
        $('#ultimo_anio').empty();
        $('#ultimo_anio').selectpicker('refresh');
        var input_predio = $('<input class="res-validate" type="hidden" id="id_predio" name="id_predio" value="' + id_predio + '">');
        $('#create-form').prepend(input_predio);
    } else if (interfaz === 'acuerdos') {
        jsonObj.acuerdos = 1;
        var input_predio = $('<input class="res-validate" type="hidden" id="id_predio_acuerdo" name="id_predio_acuerdo" value="' + id_predio + '">');
        $('#create-form').prepend(input_predio);
    }
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/get_predio',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if (interfaz === 'prescribe' || interfaz === 'exencion') {
                if ($('#create-form').length) {
                    var validatorCreate = $("#create-form").validate();
                    validatorCreate.resetForm();
                    $.each($('.has-success'), function(i, el) {
                        $(el).removeClass('has-success');
                    });
                    $.each($('.has-error'), function(i, el) {
                        $(el).removeClass('has-error');
                    });
                }
            }
            if (Object.keys(response.predio).length > 0) {
                if (interfaz === 'prescribe' || interfaz === 'exencion') {
                    if (response.anios_prescripcion.length > 0 || response.anios_exencion.length > 0) {
                        if (interfaz === 'prescribe') {
                            global_anios_prescripcion_exencion = response.anios_prescripcion.map(el => el.ultimo_anio);
                        } else {
                            global_anios_prescripcion_exencion = response.anios_exencion.map(el => el.ultimo_anio);
                        }
                    } else {
                        global_anios_prescripcion_exencion = [];
                    }

                    if (global_anios_prescripcion_exencion.length > 0) {
                        if ($('#prescribe_hasta').length || $('#exencion_hasta').length) {
                            var control = interfaz;
                            var control_label = interfaz === 'prescribe' ? 'prescripci&oacute;n' : 'exenci&oacute;n';
                            $('#' + control + '_desde').empty();
                            $('#' + control + '_hasta').empty();
                            $.each(global_anios_prescripcion_exencion, function(i, el) {
                                $('#' + control + '_desde').append('<option value="' + el + '">' + el + '</option>');
                                $('#' + control + '_hasta').append('<option value="' + el + '">' + el + '</option>');
                            });
                            $('#' + control + '_desde').selectpicker('refresh');
                            $('#' + control + '_hasta').selectpicker('refresh');
                            // if (interfaz === 'prescribe') {
                            //     $('#' + control + '_desde').val(global_anios_prescripcion_exencion[0]);
                            // }
                            $('#' + control + '_hasta').focus();
                            $('#btn_save_create').fadeIn('fast');
                            $('#span_' + control).html('');

                            $('#' + control + '_desde').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                                $('#' + control + '_hasta').find('option').css('display', '');
                                $.each($('#' + control + '_hasta').find('option'), function(i, el) {
                                    if ($(el).val().length > 0 && Number($(el).val()) < Number($('#' + control + '_desde').val())) {
                                        $(el).css('display', 'none');
                                    }
                                });
                                $('#' + control + '_hasta').val('');
                                $('#' + control + '_hasta').selectpicker('refresh');
                            });

                            $('#' + control + '_hasta').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                                if (interfaz !== 'prescribe') {
                                //     $('#span_' + control).html(`A&ntilde;os a tener en cuenta en la ${control_label}: <b>${Number($('#' + control + '_hasta').selectpicker('val')) - Number($('#' + control + '_desde').val()) + 1}</b>`);
                                // } else {
                                    // if ($('#' + control + '_hasta').find('option').length - 1 === 1) {
                                    $('#span_' + control).html(`A&ntilde;o a tener en cuenta en la ${control_label}: <b>${$('#' + control + '_hasta').selectpicker('val')}</b>`);
                                    $('#btn_save_create').fadeIn('fast');
                                    // } else {
                                    //     $('#btn_save_create').fadeOut('fast');
                                    //     $('#span_' + control).html(`<b style="color: tomato;">El predio seleccionado tiene deuda vigente. Operación de exenci&oacute;n no permitida.</b>`);
                                    // }
                                }
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
                            });
                        }
                    } else {
                        $('#btn_save_create').fadeIn('fast');
                        var control = interfaz;
                        $('#span_' + control).html('');
                        $('#' + control + '_desde').empty();
                        $('#' + control + '_hasta').empty();
                        $('#' + control + '_desde').selectpicker('refresh');
                        $('#' + control + '_hasta').selectpicker('refresh');
                    }
                } else if (interfaz === 'cambio_tarifa') {
                    AutoNumeric.set('#tarifa_anterior', Number(response.predio.tarifa_actual));
                } else if (interfaz === 'notas') {
                    var facturas_pendientes = response.facturas_pendientes;
                    $('#numero_factura').empty();
                    $('#valores_factura').fadeOut(function() {
                        AutoNumeric.set('#valor_concepto1', 0);
                        AutoNumeric.set('#valor_concepto2', 0);
                        AutoNumeric.set('#valor_concepto3', 0);
                        AutoNumeric.set('#valor_concepto4', 0);
                        AutoNumeric.set('#valor_concepto13', 0);
                        AutoNumeric.set('#valor_concepto14', 0);
                        AutoNumeric.set('#valor_concepto15', 0);
                        AutoNumeric.set('#valor_concepto16', 0);
                        AutoNumeric.set('#valor_concepto17', 0);
                        AutoNumeric.set('#valor_concepto18', 0);
                        AutoNumeric.set('#total_calculo', 0);
                    });
                    if (facturas_pendientes.length > 0) {
                        $.each(facturas_pendientes, function(i, el) {
                            $('#numero_factura').append('<option value="' + el.factura_pago + '">' + el.factura_pago + '</option>');
                        });
                        $('#select_factura').fadeIn();
                        // $('#load_resolucion').fadeIn();
                        $('#numero_factura').off('changed.bs.select').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                            getPredioPago(id_predio, $('#numero_factura').selectpicker('val'), 0);
                        });
                    } else {
                        $('#select_factura').fadeOut();
                        $('#load_resolucion').fadeOut();
                    }
                    $('#numero_factura').selectpicker('refresh');
                } else if (interfaz === 'acuerdos') {
                    // var predio = response.predio;
                    var anios = response.anios;
                    global_anios = response.anios;
                    global_acuerdo_pago = response.acuerdo_pago;

                    if(anios.length === 0) {
                        global_ya_pagado = true;
                    }

                    if (
                        global_ya_pagado || (anios.length === 1 && response.anio_actual === Number(anios[0].ultimo_anio) && anios[0].factura_pago !== null)
                    ) {
                        // $('#no_acuerdo').html('<b>ACUERDO DE PAGO NO PERMITIDO</b>');
                        // $('#no_acuerdo').fadeIn();
                        swal({
                            title: "Atención",
                            text: "El predio seleccionado no tiene permitido la configuración de un acuerdo de pago.",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Aceptar",
                            // cancelButtonText: "Cancelar",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        });
                        $('#id_predio.select2').empty().trigger('change');
                    } else if (global_acuerdo_pago) {
                        // $('#ya_acuerdo').html('<b>EL PREDIO SELECCIONADO YA POSEE UN ACUERDO DE PAGO CONFIGURADO</b>');
                        // $('#ya_acuerdo').fadeIn();
                        swal({
                            title: "Atención",
                            text: "El predio seleccionado ya posee un acuerdo de pago configurado.",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Aceptar",
                            // cancelButtonText: "Cancelar",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        });
                        $('#id_predio.select2').empty().trigger('change');
                    } else {
                        $('#anio_inicial_acuerdo').empty();
                        $('#anio_final_acuerdo').empty();
                        $('#anio_inicial_acuerdo').append('<option value="">Seleccione</option>');
                        $('#anio_final_acuerdo').append('<option value="">Seleccione</option>');
                        $.each(anios, function(i, el) {
                            $('#anio_inicial_acuerdo').append('<option value="' + el.ultimo_anio + '">' + el.ultimo_anio + '</option>');
                            $('#anio_final_acuerdo').append('<option value="' + el.ultimo_anio + '">' + el.ultimo_anio + '</option>');
                        });
                        $('#load_resolucion').fadeIn();
                        $('#valores_acuerdo').fadeIn();
                    }
                }

                $.unblockUI();
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

function getAvaluosPredio(id_predio, btn) {
    var jsonObj = {};
    jsonObj.id_predio = id_predio;
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/avaluos_predio',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if(btn !== undefined) {
                $(btn).attr('disabled', false);
            }
            if (response.predio.length > 0) {
                if (DTAvaluos !== null) {
                    DTAvaluos.clear().draw();
                    DTAvaluos.rows.add(response.predio).draw();
                }
            }
        },
        error: function(xhr) {
            if(btn !== undefined) {
                $(btn).attr('disabled', false);
            }
            console.log(xhr.responseText);
        }
    });
}

function getEstadoCuentaPredio(id_predio, btn) {
    var jsonObj = {};
    jsonObj.id_predio = id_predio;
    $('#print_estado_cuenta').css('display', 'none');
    $('#excel_estado_cuenta').css('display', 'none');
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/estado_cuenta_predio',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if(btn !== undefined) {
                $(btn).attr('disabled', false);
            }
            if (response.predio.length > 0) {
                $('#print_estado_cuenta').css('display', '');
                $('#excel_estado_cuenta').css('display', '');
                if (DTEstadoCuenta !== null) {
                    DTEstadoCuenta.clear().draw();
                    DTEstadoCuenta.rows.add(response.predio).draw();
                }
            }
        },
        error: function(xhr) {
            if(btn !== undefined) {
                $(btn).attr('disabled', false);
            }
            console.log(xhr.responseText);
        }
    });
}

// function setPrescribeRow() {
//     if ($('.prescribe_row').length > 0) {
//         $('#span_prescribir').html('&nbsp;');
//         $('#prescribe_desde_modal').val('');
//         $('#prescribe_hasta_modal').empty();
//         $.each(global_anios_prescripcion_exencion, function(i, el) {
//             $('#prescribe_hasta_modal').append('<option value="' + el + '">' + el + '</option>');
//         });
//         $('#prescribe_hasta_modal').selectpicker('refresh');

//         $('#prescribe_hasta_modal').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
//             $('#span_prescribir').html(`A&ntilde;os a prescribir: <b>${Number($('#prescribe_hasta_modal').selectpicker('val')) - Number($('#prescribe_desde_modal').val()) + 1}</b>`);
//             var validatorPrescripciones = $("#form-predios-prescripcion").validate();
//             validatorPrescripciones.resetForm();
//             $.each($('.has-success'), function(i, el) {
//                 $(el).removeClass('has-success');
//             });
//             $.each($('.has-error'), function(i, el) {
//                 $(el).removeClass('has-error');
//             });
//         });

//         $('.prescribe_row').off('click').on('click', function(evt) {
//             var btn = $(this);
//             $('#input_prescribe').val($(btn).attr('ide'));
//             $('#txt_operacion_resolucion').html('<span class="text-danger">Operaci&oacute;n:&nbsp;</span>Prescripci&oacute;n de predio');
//             global_form_to_send = 'form_prescribe';
//             $('#modal-prescripciones').modal({ backdrop: 'static', keyboard: false }, 'show');
//         });

//         $('#modal-prescripciones').off('hidden.bs.modal').on('hidden.bs.modal', function() {
//             $('#span_prescribir').html('&nbsp;');
//             $('#prescribe_desde_modal').val('');
//             $('#prescribe_hasta_modal').selectpicker('val', '')
//         });
//         $('#modal-prescripciones').off('shown.bs.modal').on('shown.bs.modal', function() {
//             $('#form-predios-prescripcion')[0].reset();
//             clear_form_elements("#form-predios-prescripcion");
//             validatorPrescripciones.resetForm();
//             $('#prescribe_desde_modal').val(global_anios_prescripcion_exencion[0]);
//             $('#prescribe_hasta_modal').focus();
//         });

//         $('#save_prescripcion').off('click').on('click', function() {
//             if ($('#prescribe_hasta_modal').selectpicker('val').length > 0) {
//                 var input_prescribe_desde = $('<input id="prescribe_desde" name="prescribe_desde" type="hidden" value="' + $('#prescribe_desde_modal').val() + '"  />');
//                 var input_prescribe_hasta = $('<input id="prescribe_hasta" name="prescribe_hasta" type="hidden" value="' + $('#prescribe_hasta_modal').val() + '"  />');

//                 if ($('#prescribe_hasta').length > 0) {
//                     $('#prescribe_desde').val($('#prescribe_desde_modal').val());
//                     input_prescribe_desde = $('#prescribe_desde');

//                     $('#prescribe_hasta').val($('#prescribe_hasta_modal').val());
//                     input_prescribe_hasta = $('#prescribe_hasta');
//                 }

//                 $('#' + global_form_to_send).prepend(input_prescribe_desde);
//                 $('#' + global_form_to_send).prepend(input_prescribe_hasta);
//                 $('#modal-resolucion').modal('show');
//                 $('#modal-prescripciones').modal('hide');
//             } else {
//                 validatorPrescripciones.showErrors({
//                     prescribe_hasta_modal: "A&ntilde;o prescripci&oacute;n requerido."
//                 });
//             }
//         });

//         var validatorPrescripciones = $("#form-predios-prescripcion").validate({
//             rules: {
//                 prescribe_hasta_modal: "required"
//             },
//             messages: {
//                 prescribe_hasta_modal: "A&ntilde;o prescripci&oacute;n requerido."
//             }
//         });
//     }
// }

function setCopyPredio() {
    if ($('.copy_predio').length > 0) {
        $('.copy_predio').off('click').on('click', function(evt) {
            navigator.clipboard.writeText($(this).data('codigo'));
            $.toast({
                heading: 'Atención',
                text: 'Código de predio copiado al portapapeles. Use Ctrl + V para pegar.',
                position: 'top-center',
                loaderBg: '#fff',
                icon: 'info',
                hideAfter: 4000,
                stack: 6
            });
        });
    }
}

// function setAvaluosRow() {
//     if ($('.avaluos_row').length > 0) {

//         $('.avaluos_row').off('click').on('click', function(evt) {
//             var btn = $(this);
//             $(btn).attr('disabled', true);
//             getAvaluosPredio($(btn).attr('ide'), btn);
//             $('#modal-avaluo').modal({ backdrop: 'static', keyboard: false }, 'show');
//             $('[data-toggle="tooltip"]').tooltip('destroy');
//         });

//         $('#modal-avaluo').off('hidden.bs.modal').on('hidden.bs.modal', function() {
//             $('[data-toggle="tooltip"]').tooltip();
//         });
//         // $('#modal-avaluo').off('shown.bs.modal').on('shown.bs.modal', function() {
//         // });
//     }
// }

function setAcuerdoPagoRow() {
    if ($('.acuerdos_row').length > 0) {

        $('.acuerdos_row').off('click').on('click', function(evt) {
            setDataAcuerdoPagoModalForm();
            $('#modal-datos-acuerdo-pago').modal({ backdrop: 'static', keyboard: false }, 'show');
            $('[data-toggle="tooltip"]').tooltip('destroy');
        });

        $('#modal-datos-acuerdo-pago').off('hidden.bs.modal').on('hidden.bs.modal', function() {
            $('[data-toggle="tooltip"]').tooltip();
            $('.datohidden').remove();
            $('#form-predios-datos-acuerdos-pago')[0].reset();
            clear_form_elements("#form-predios-datos-acuerdos-pago");
            validatorAcuerdosPago.resetForm();
            $('#anio_inicial_acuerdo').removeClass('error');
            $('#anio_final_acuerdo').removeClass('error');
            $('#total_acuerdo').html(accounting.formatMoney(0, "$ ", 2, ".", ", "));
            $('#div_ver_detalle_ap').fadeOut();
        });

        var validatorAcuerdosPago = $("#form-predios-datos-acuerdos-pago").validate({
            rules: {
                numero_resolucion_acuerdo: "required",
                anio_inicial_acuerdo: "required",
                anio_final_acuerdo: "required",
                cuotas_acuerdo: "required",
                dia_pago_acuerdo: "required",
                abono_inicial_acuerdo: "required",
                identificacion_acuerdo: "required",
                nombre_acuerdo: "required",
                telefono_acuerdo: "required",
                direccion_acuerdo: "required",
                fecha_acuerdo: "required"

            },
            messages: {
                numero_resolucion_acuerdo: "N&uacute;mero requerido",
                anio_inicial_acuerdo: "A&ntilde;o requerido",
                anio_final_acuerdo: "A&ntilde;o requerido",
                cuotas_acuerdo: "Cuotas requerido",
                dia_pago_acuerdo: "D&iacute;a requerido",
                abono_inicial_acuerdo: "Abono requerido",
                identificacion_acuerdo: "Identificaci&oacute;n requerido",
                nombre_acuerdo: "Nombre requerido",
                telefono_acuerdo: "Tel&eacute;fono requerido",
                direccion_acuerdo: "Direcci&oacute;n requerido",
                fecha_acuerdo: "Fecha acuerdo requerido"
            }
        });
    }
}

function formatRepo (repo) {
    if (repo.loading) {
      return repo.text;
    }

    var $container = $(
      "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAhCAYAAABX5MJvAAABg2lDQ1BJQ0MgcHJvZmlsZQAAKJF9kT1Iw0AcxV9TpSJVBztIcchQO1kQFXGUKhbBQmkrtOpgcukXNGlIUlwcBdeCgx+LVQcXZ10dXAVB8APE0clJ0UVK/F9SaBHjwXE/3t173L0DhGaVqWbPBKBqlpFOxMVcflUMvELAIIAwohIz9WRmMQvP8XUPH1/vYjzL+9yfY0ApmAzwicRzTDcs4g3imU1L57xPHGJlSSE+Jx436ILEj1yXXX7jXHJY4JkhI5ueJw4Ri6UulruYlQ2VeJo4oqga5Qs5lxXOW5zVap2178lfGCxoKxmu0xxFAktIIgURMuqooAoLMVo1UkykaT/u4Q87/hS5ZHJVwMixgBpUSI4f/A9+d2sWpybdpGAc6H2x7Y8xILALtBq2/X1s260TwP8MXGkdf60JzH6S3uhokSNgaBu4uO5o8h5wuQOMPOmSITmSn6ZQLALvZ/RNeWD4Fuhfc3tr7+P0AchSV8s3wMEhEC1R9rrHu/u6e/v3TLu/H1bmcpz7VyktAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH5gIOADEga+VRFgAAABl0RVh0Q29tbWVudABDcmVhdGVkIHdpdGggR0lNUFeBDhcAAAVlSURBVFjD7ZdrTBRXFMf/d2YfsyzsLrCgwrIujwUWRKAiCKVa0wZNCI0JNjEpsYlNtNLSSlIrak1NY+hDjEapqabWxsSmH2pitFYxaQEfgLSNUCMPAQsliALL7uxzdtmd2w9mDcpLUJp+8My3c+4985t7zn/ODOGdAuZihGHwXukHSUNDI9WRkdr3vz56uJOKIkaGh3H//pB8sj1yucxnTDT6J+SaC8TJE8fQeKN1dW/fwBm34AlVcHKLYXF0cW5Oeq0ITvnd96cdDGHo+D0UFBlLTSXV1Yd+eDIfM1uA3t4+NDS2bOrq6atxC55QAHALntCunr6ahsaWTXa7nRJCAPL4RQghU+WUzAagvb2d7Nv3ZWX/wIMKSh97UPh8fmlnd++JMZ8vJdWUcLSto6d0fDwiXNMUHb2oedLSPm05qo8cVdTWXT01NGJZH/DJpBJndNSCgwP3HpR7x3zKgF8brukcMVuTxu9Xq5Snfj5/5u3Jcj9VOXZW7F5w6fJvteMBghTcYGx+zo67cbHrYvNzdgQpuMFA7EmAmWxGiLKy8tSbrbebeJsjJ+DTqIJb9avzq+q9Y1W3BGFJvXesSr86v0qjCm6di9KmhFArOWzdWramvaO7wekSDI9qqw29oMjLuXjOyh8YEkUOUhmGRJE7Z+UPKPJyLkZoQy88Fwi1ksO69SWbO+/cveDxjqkCfkOs7pg/K5Ov4fkKgVIQXTywogBEFw+BUtTwfIU/K5NfHKs79mROlmWD1UpuUgh25+5PHnM0Nd5gRyzOqv6B+5V+kTIAIJGwYuKS5L1dUVHLm53OIgAgxnTQxUaAECAsEkTGAaMP0OvxpHFarTNZpTxpNVtWiSIlACB4PKbMZctVMdHRv+pidHRKdRw+XB1UW3ft9IjZui7g4+QyhyEzbVcrx5V1ejxGsCxISjZoWMREqY0Og7Y1A34/kuTyrnRBONJ781al4PEGj1PO2VdXvvzWh9vKXBMgtm+vWNTW3nXeZncuCwQVnLw/7pXcr6643XsH/P5wIlcAaTmgStXUr3OnDbh1A9TjRjTLmlcpFHt7rjZ+7BY8MYE1qhDlnykmY9H+/V8MPoIoL/8oo72j+5zTJcSM0/UfC17J/anWavvMLIoyqEJBUpeDyriZ54pXAL39O2CzQMsw3pXqkD3D15re5G3OrMAaZRDXb0pOeOPgwaoWtu+fwcK2jp5fBI/30flGRoSdVeRm37xs5ffYKZWQiCggNRuQyp6u3VkJEKkDcTvhctrYvz3e1xMTE35UWK1DTpc7GQDGxnzq4ZHRksbr1/9idfqEHbzNsSKwPzZeX20xJcvqbLbNPoCQGCOocSnAzHLMMAygXQQiUvh4M+kWhLxIfUyLDmKd1cJnA4AoijKpROJmigrXlGrUIfVSicQXZ9Bt6TUY+pocjg0AQJIyQONMDxUwp3lPQONMIEkZD5XncGzojTX0xRl0W6QSiU+jDqkvKlxTypRsLPFmpqcWx8fpCwrWFh73USolrAQkPQ90oR7Pw+hCPUh6HggrgY9SacHaouPxcfqCzPTU4pKNJd4JAyzv0Dc722KMlTQoBM/biMuOlP6uXQ3b3v18+lGemgXqE6dMtELCYm14CEAnuwtwyWxHk88/+YkEhQCpWc/2PQEAK1VB+DQ/bcq4q7YFTaP2WeWcNYTd78cA7wDBxGaloLD7/bMu06whegQvLvXcmzY+7xAZSgXeeSlxyvgdvgWXPbMrB4P/gb2AmHNP1Nlc2HOlddr4vEM0+/xoHra96In/piekNh7qaWbHs5pUwswMEXb6W4hm67xBhIVrgOLXpofQaFQCwxDLfEGoVCETfn7/BQXmN3HMiPeEAAAAAElFTkSuQmCC' /></div>" +
        //"<div class='select2-result-repository__avatar'>" + repo.text + "</div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'></div>" +
          "<div class='select2-result-repository__description'></div>" +
           "<div class='select2-result-repository__statistics'>" +
             "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> </div>" +
        //     "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> </div>" +
        //     "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> </div>" +
           "</div>" +
        "</div>" +
      "</div>"
    );

    $container.find(".select2-result-repository__title").text(repo.text);
    $container.find(".select2-result-repository__description").text(repo.codigo_predio);
    $container.find(".select2-result-repository__forks").append(repo.propietarios);

    //$container.find(".select2-result-repository__title").text(repo.full_name);
    // $container.find(".select2-result-repository__description").text(repo.description);
    // $container.find(".select2-result-repository__forks").append(repo.forks_count + " Forks");
    // $container.find(".select2-result-repository__stargazers").append(repo.stargazers_count + " Stars");
    // $container.find(".select2-result-repository__watchers").append(repo.watchers_count + " Watchers");

    return $container;
}

function formatRepoSelection (repo) {
    global_json_predio = repo;
    return repo.codigo_predio || repo.text;
}

function setData(jsonObj) {
    // console.log('📌 - controlsite.js - setData - jsonObj:', jsonObj);
    $('#div_table').fadeOut(function() {
        $.each(jsonObj, function(i, el) {
            if ($('#' + i + '_edit').length > 0) {
                if (!$('#' + i + '_edit').is('[value]') && !$('#' + i + '_edit').hasClass('selectpicker')) {
                    if ($('#' + i + '_edit').hasClass('accounting')) {
                        $('#' + i + '_edit').html(accounting.formatMoney(el, "$ ", 2, ".", ", "));
                    } else {
                        $('#' + i + '_edit').html(el);
                    }
                } else {

                    if ($('#' + i + '_edit').hasClass('selectpicker')) {
                        $('#' + i + '_edit').selectpicker('val', el);
                        $('#' + i + '_edit').attr('prev-val', el);
                    } else {
                        if (el === '.00') {
                            if ($.inArray(i, arr_autonumeric) >= 0) {
                                AutoNumeric.set('#' + i + '_edit', 0);
                            } else {
                                $('#' + i + '_edit').val('0');
                            }
                            $('#' + i + '_edit').attr('prev-val', '0');
                        } else {
                            if ($.inArray(i, arr_autonumeric) >= 0) {
                                AutoNumeric.set('#' + i + '_edit', Number(el));
                                $('#' + i + '_edit').attr('prev-val', Number(el));
                            } else {
                                $('#' + i + '_edit').val(el);
                                $('#' + i + '_edit').attr('prev-val', el);
                                if ($('#' + i + '_edit').is(':checkbox')) {
                                    if (Number(el) > 0 || Number(el) === -1) {
                                        if (!$('#' + i + '_edit').is(':checked')) {
                                            $('#' + i + '_edit').trigger('click');
                                        }
                                    } else {
                                        if ($('#' + i + '_edit').is(':checked')) {
                                            $('#' + i + '_edit').trigger('click');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if ($('#h1_' + i + '_edit').length > 0) {
                    $('#h1_' + i + '_edit').text(el);
                }
                if ($('#' + i + '_edit_confirmation').length > 0) {
                    $('#' + i + '_edit_confirmation').val(el);
                }
            }
        });

        if ($('#div_estado_tmp_edit').length > 0) {
            if ($('#estado_edit').val() === 'A') {
                $('#div_estado_tmp_edit').trigger('click');
            }
        }

        $('.selectpicker').selectpicker('refresh');
        $('#div_edit_form').fadeIn();

        if ($('#codigo_predio').length > 0) {
            // if(jsonObj.prescrito > 0) {
            //     $('#span_prescribe_desde').html(jsonObj.prescribe_desde);
            //     $('#span_prescribe_desde').parent().fadeIn();
            // }
            // else {
            //     $('#span_prescribe_desde').empty();
            //     $('#span_prescribe_desde').parent().fadeOut();
            // }
            addButtonsPredios();
        }

    });
}

function clear_form_elements(ele) {
    $(ele).find(':input').each(function() {
        switch (this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }

        if ($('#' + $(this).attr('id') + '_edit').is('[prev-val]')) {
            $('#' + $(this).attr('id') + '_edit').removeAttr('prev-val');
        }
    });

    if($(ele).find('.select2').length > 0) {
        $(ele).find('.select2').val(null).trigger('change');
        $(ele).find('.select2').find('option').remove();
    }

    $.each(arr_autonumeric, function(i, el) {
        if ($(ele).find('#' + el).length > 0) {
            AutoNumeric.set('#' + el, 0);
        }
        if ($(ele).find('#' + el + '_edit').length > 0) {
            AutoNumeric.set('#' + el + '_edit', 0);
            if ($(ele).find('#' + el + '_edit').is('[prev-val]')) {
                $(ele).find('#' + el + '_edit').removeAttr('prev-val');
            }
        }
    });

    $.each($(ele).find('.selectpicker'), function(i, el) {
        $(el).selectpicker('val', '');
        $(el).selectpicker('refresh');
        if ($('#' + $(el).attr('id') + '_edit').is('[prev-val]')) {
            $('#' + $(el).attr('id') + '_edit').removeAttr('prev-val');
        }
    });
    $.each($(ele).find('.selectpicker-noval'), function(i, el) {
        $(el).selectpicker('val', '');
        $(el).selectpicker('refresh');
    });
}

function calcEditLabels() {
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
}

function addButtonsPredios() {
    $('.buttonTareas').css('display', '');
    calcEditLabels();
    getJsonPrediosDatos();
}

function checkSaveResolucion(form, desc_operacion) {
    var show_resoluciones_modal = false;
    if ($('.resolucion_validate_form_level-' + $(form).attr('id')).length > 0) { // Validacion a nivel de formulario
        $.each($('.res-validate'), function(i, el) {
            if (!$(el).is("div")) {
                if (!$(el).hasClass('selectpicker')) {
                    if ($.inArray($(el).attr('id').replace('_edit', ''), arr_autonumeric) >= 0) {
                        if (Number($(el).attr('prev-val')) !== AutoNumeric.getNumber('#' + $(el).attr('id'))) {
                            show_resoluciones_modal = true;
                            return false;
                        }
                    } else if ($(el).attr('prev-val') !== $(el).val()) {
                        show_resoluciones_modal = true;
                        return false;
                    }
                } else {
                    if ($(el).attr('prev-val') !== $(el).selectpicker('val')) {
                        show_resoluciones_modal = true;
                        return false;
                    }
                }
            }
        });
    }
    else if ($('.resolucion_validate_field_level-' + $(form).attr('id')).length > 0) { // Validacion a nivel de campos individuales
        $.each($('.resolucion_validate_field_level-' + $(form).attr('id')), function(i, el) {
            if ($('#' + $(el).attr('field')).attr('prev-val') !== $('#' + $(el).attr('field')).val()) {
                show_resoluciones_modal = true;
                return false;
            }
        });
    }

    if (show_resoluciones_modal) {
        if($(form).attr('id').includes('create')) {
            $('#txt_operacion_resolucion').html('<span class="text-danger">Operaci&oacute;n:&nbsp;</span>Creaci&oacuten de ' + desc_operacion);
        }
        else {
            $('#txt_operacion_resolucion').html('<span class="text-danger">Operaci&oacute;n:&nbsp;</span>Actualizaci&oacuten de ' + desc_operacion);
        }
        global_form_to_send = $(form).attr('id');
        $('#modal-resolucion').modal('show');
    } else {
        if ($('.resolucion_validate_field_level-' + $(form).attr('id')).length === 0 && $('.resolucion_validate_form_level-' + $(form).attr('id')).length === 0) {
            $('#' + $(form).attr('id')).first().submit();
        }
    }
}

function checkAnioImpresion() {
    if((typeof $('#ultimo_anio_facturar').val() === 'string' && ($('#ultimo_anio_facturar').val() === '' || $('#ultimo_anio_facturar').val().length < 4)) || (typeof $('#ultimo_anio_facturar').val() === 'object' && $('#ultimo_anio_facturar').val().length === 0)) {
        swal({
            title: "Atención",
            text: "Se necesita establecer el último año que se desea facturar.",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true,
            closeOnCancel: true
        });
        $('.btn_pdf').attr('disabled', false);
        return false;
    }
    else {
        return true;
    }
}

function checkFormPaz() {
    if($('#destino_paz').val() === '' || $('#fecha_paz').val() === '' || $('#valor_paz').val() === '') {
        swal({
            title: "Atención",
            text: "Es necesario establecer toda la información requerida.",
            type: "error",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true,
            closeOnCancel: true
        });
        $('.btn_pdf').attr('disabled', false);
        return false;
    }
    else {
        return true;
    }
}

function setDataAcuerdoPagoModalForm() {
    if (global_acuerdo_pago) {
        setFormData('form-predios-datos-acuerdos-pago', global_acuerdo_pago);
        if (Number(global_acuerdo_pago.responsable_propietario_acuerdo) > 0) {
            $('#identificacion_acuerdo').attr('readonly', true);
            $('#nombre_acuerdo').attr('readonly', true);
            $('#direccion_acuerdo').attr('readonly', true);
        }
        disable_form_elements('#form-predios-datos-acuerdos-pago', true);
        $('#save_dap').attr('disabled', true);
        $('#save_dap').css('display', 'none');
        $('#anular_dap').attr('disabled', false);
        $('#anular_dap').css('display', '');
        $('#total_acuerdo').html(accounting.formatMoney(global_acuerdo_pago.total_acuerdo, "$ ", 2, ".", ", "));
        $('#div_ver_detalle_ap').fadeIn();
    } else {
        disable_form_elements('#form-predios-datos-acuerdos-pago', false);
        $('#save_dap').attr('disabled', false);
        $('#save_dap').css('display', '');
        $('#anular_dap').attr('disabled', true);
        $('#anular_dap').css('display', 'none');
        $('#total_acuerdo').html(accounting.formatMoney(0, "$ ", 2, ".", ", "));
        $('#div_ver_detalle_ap').fadeOut();
        AutoNumeric.set('#abono_inicial_acuerdo', 0);
        if (global_propietario) {
            $('#identificacion_acuerdo').val(global_propietario.identificacion);
            $('#nombre_acuerdo').val(global_propietario.nombre);
            $('#direccion_acuerdo').val(global_propietario.direccion);
            $('#identificacion_acuerdo').attr('readonly', true);
            $('#nombre_acuerdo').attr('readonly', true);
            $('#direccion_acuerdo').attr('readonly', true);
            $('#responsable_propietario_acuerdo').val(1);
            $('#responsable_propietario_acuerdo').prop('checked', true);
        }
    }
}

function disable_form_elements(ele, status) {
    $(ele).find(':input').each(function() {
        switch (this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
            case 'checkbox':
            case 'radio':
                this.disabled = status;
        }

        // if ($('#' + $(this).attr('id') + '_edit').is('[prev-val]')) {
        //     $('#' + $(this).attr('id') + '_edit').removeAttr('prev-val');
        // }
    });

    // if($(ele).find('.select2').length > 0) {
    //     $(ele).find('.select2').val(null).trigger('change');
    //     $(ele).find('.select2').find('option').remove();
    // }

    // $.each(arr_autonumeric, function(i, el) {
    //     if ($(ele).find('#' + el).length > 0) {
    //         AutoNumeric.set('#' + el, 0);
    //     }
    //     if ($(ele).find('#' + el + '_edit').length > 0) {
    //         AutoNumeric.set('#' + el + '_edit', 0);
    //         if ($(ele).find('#' + el + '_edit').is('[prev-val]')) {
    //             $(ele).find('#' + el + '_edit').removeAttr('prev-val');
    //         }
    //     }
    // });

    // $.each($(ele).find('.selectpicker'), function(i, el) {
    //     $(el).selectpicker('val', '');
    //     $(el).selectpicker('refresh');
    //     if ($('#' + $(el).attr('id') + '_edit').is('[prev-val]')) {
    //         $('#' + $(el).attr('id') + '_edit').removeAttr('prev-val');
    //     }
    // });
    // $.each($(ele).find('.selectpicker-noval'), function(i, el) {
    //     $(el).selectpicker('val', '');
    //     $(el).selectpicker('refresh');
    // });
}

function alert_anio_facturado(factura_numero, fecha_emision, multiple) {
    var complemento = '';
    if (multiple) {
        complemento = '\n\nSolicitud de generación de factura por rango de vigencias no permitida.';
    }
    swal({
        title: "Atención",
        text: `La solicitud de generación de factura ya fue realizada previamente.\n\nFactura No. ${factura_numero}${complemento}\nGenerada el ${new Date(fecha_emision).toLocaleString()}.`,
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Aceptar",
        // cancelButtonText: "No",
        closeOnConfirm: true,
        // closeOnCancel: true
    });
}

function saveAniosFactura(uncheckedAnios) {
    var msg = `¿Está seguro/a que desea modificar los años asociados a la factura ${global_facturado.factura_pago}?`;
    swal({
        title: "Atención",
        text: msg,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function(confirm) {
        if (confirm) {
            var jsonObj = {};
            jsonObj.id_predio = Number(global_json_predio.id_predio);
            jsonObj.lista_anios = uncheckedAnios.toString();
            jsonObj.factura = global_facturado.factura_pago;
            $.ajax({
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: 'json',
                url: '/update_anios_factura',
                data: {
                    form: JSON.stringify(jsonObj)
                },
                success: function(response) {
                    if (response.data !== undefined) {
                        swal({
                            title: "Atención",
                            text: response.data.message,
                            type: "warning",
                            // confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: true
                        }, function(isConfirm) {
                            if (isConfirm) {
                                if (response.data.success) {
                                    $('#generate_factura_definitiva').css('display', '');
                                    $('#generate_factura_temporal').css('display', '');
                                    $('#div_anios_factura').css('display', 'none');
                                    $('#lista_anios_factura').empty();
                                    $('#btn_modificar_anios').css('display', 'none');
                                    getPredio($('#id_predio.select2').val(), false, false);
                                    $('#modal-impresion-factura').modal('hide');
                                }
                            }
                        });
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    });
}

function getPredioPago(id_predio, numero_factura, ultimo_anio) {
    $.blockUI({
        message: "Ejecutando b&uacute;squeda de informaci&oacute;n. Espere un momento.",
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
    jsonObj.id_predio = id_predio;
    jsonObj.numero_factura = numero_factura;

    if ($('#create-form').find('#factura_pago').length > 0) {
        $('#factura_pago').val(numero_factura);
    } else {
        var input_factura_pago = $('<input class="res-validate" type="hidden" id="factura_pago" name="factura_pago" value="' + numero_factura + '">');
        $('#create-form').prepend(input_factura_pago);
    }

    if (ultimo_anio > 0) {
        jsonObj.ultimo_anio = ultimo_anio;
        if ($('#create-form').find('#ultimo_anio').length > 0) {
            $('#ultimo_anio').val(ultimo_anio);
        } else {
            var input_ultimo_anio = $('<input class="res-validate" type="hidden" id="ultimo_anio" name="ultimo_anio" value="' + ultimo_anio + '">');
            $('#create-form').prepend(input_ultimo_anio);
        }
    }
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: ultimo_anio === 0 ? '/get_factura' : '/get_factura_anio',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if (response.data !== undefined) {
                if (Object.keys(response.data).length > 0) {
                    // console.log('📌 - controlsite.js:2795 - getPredioPago - response:', response.data);
                    $('#valor_concepto1').attr('prev', Number(response.data.valor_concepto1));
                    $('#valor_concepto2').attr('prev', Number(response.data.valor_concepto2));
                    $('#valor_concepto3').attr('prev', Number(response.data.valor_concepto3));
                    $('#valor_concepto4').attr('prev', Number(response.data.valor_concepto4));
                    $('#valor_concepto13').attr('prev', Number(response.data.valor_concepto13));
                    $('#valor_concepto14').attr('prev', Number(response.data.valor_concepto14));
                    $('#valor_concepto15').attr('prev', Number(response.data.valor_concepto15));
                    $('#valor_concepto16').attr('prev', Number(response.data.valor_concepto16));
                    $('#valor_concepto17').attr('prev', Number(response.data.valor_concepto17));
                    $('#valor_concepto18').attr('prev', Number(response.data.valor_concepto18));
                    AutoNumeric.set('#valor_concepto1', Number(response.data.valor_concepto1));
                    AutoNumeric.set('#valor_concepto2', Number(response.data.valor_concepto2));
                    AutoNumeric.set('#valor_concepto3', Number(response.data.valor_concepto3));
                    AutoNumeric.set('#valor_concepto4', Number(response.data.valor_concepto4));
                    AutoNumeric.set('#valor_concepto13', Number(response.data.valor_concepto13));
                    AutoNumeric.set('#valor_concepto14', Number(response.data.valor_concepto14));
                    AutoNumeric.set('#valor_concepto15', Number(response.data.valor_concepto15));
                    AutoNumeric.set('#valor_concepto16', Number(response.data.valor_concepto16));
                    AutoNumeric.set('#valor_concepto17', Number(response.data.valor_concepto17));
                    AutoNumeric.set('#valor_concepto18', Number(response.data.valor_concepto18));
                    AutoNumeric.set('#total_calculo', Number(response.data.total_calculo));
                    $('#valores_factura').fadeIn();
                    $('.conceptos').bind('change', function() {
                        var changed = false;
                        var total = 0;
                        $.each($('.conceptos'), function(i, el) {
                            if (Number($(el).attr('prev')) !== AutoNumeric.getNumber('#' + $(el).attr('id'))) {
                                changed = true;
                            }
                            total += AutoNumeric.getNumber('#' + $(el).attr('id'));
                        });
                        AutoNumeric.set('#total_calculo', total);

                        if (changed) {
                            $('#load_resolucion').fadeIn();
                            $('#btn_save_create').attr('disabled', false);
                        } else {
                            $('#load_resolucion').fadeOut();
                            $('#btn_save_create').attr('disabled', true);
                        }
                    });
                    $('#p_ultimo_anio').html(response.data.ultimo_anio);
                    $('#select_anio').fadeIn();
                    $.unblockUI();
                } else {
                    $.unblockUI();
                }
            } else if (response.anios !== undefined) {
                // console.log('📌 - controlsite.js:2869 - getPredioPago - response.anios:', response.anios);
                if (Object.keys(response.anios).length > 0) {
                    $('#ultimo_anio').empty();
                    $.each(response.anios, function(i, el) {
                        $('#ultimo_anio').append('<option value="' + el.ultimo_anio + '">' + el.ultimo_anio + '</option>');
                    });
                    $('#ultimo_anio').selectpicker('refresh');
                    $('#select_anios').fadeIn();
                    $('#ultimo_anio').off('changed.bs.select').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                        getPredioPago(id_predio, $('#numero_factura').selectpicker('val'), parseInt($('#ultimo_anio').selectpicker('val')));
                        $('#load_resolucion').fadeOut();
                        $('#btn_save_create').attr('disabled', true);
                    });
                }

                $.unblockUI();
            } else {
                $.unblockUI();
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
}
