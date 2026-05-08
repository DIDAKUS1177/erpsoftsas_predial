var DTPagos = null;
var DTLogs = null;
var PAGE_LENGTH = 5;
var MAX_FILE_SIZE = 10;
var global_filtroform_to_send = "";
var bar = $('.bar.one');
var percent = $('.porciento.one');
var ROOT_URL = window.location.protocol + "//" + window.location.host;
var visualizando_factura = false;
var visualizando_factura_edit = false;
var selected_banco_factura = 'default';

var validatorFiltro = $("#pagos-filtro-form").validate({
    rules: {
        fecha_pago_inicial: "required",
        id_banco_inicial: {
            required: true,
            minlength: 1
        },
        fecha_pago_final: "required",
        id_banco_final: {
            required: true,
            minlength: 1
        },
    },
    messages: {
        fecha_pago_inicial: "Fecha inicial requerida",
        id_banco_inicial: "Banco inicial requerido",
        fecha_pago_final: "Fecha final requerida",
        id_banco_final: "Banco final requerido",
    },
});

$(document).ready(function() {
    $('.search_general').fadeIn();
    $('.search_factura').fadeOut();

    $('#numero_recibo').bind('keyup', function() {
        if($.trim($(this).val()).length === 9) {
            getInfoPago();
        }
        else {
            $('#btn_save_info').attr('disabled', false);
        }
    });

    $('#numero_recibo_search').bind('keyup', function() {
        if($.trim($(this).val()).length === 9 && !visualizando_factura) {
            $('#btn_buscar_recibo').attr('disabled', false);
            $('#btn_buscar_recibo').attr('class', 'btn btn-info');
            $('#btn_buscar_recibo').trigger('click');
            visualizando_factura = true;
        }
        else if($.trim($(this).val()).length < 9) {
            $('#btn_buscar_recibo').attr('disabled', true);
            $('#btn_buscar_recibo').attr('class', 'btn btn-default');
            $('.info_factura').fadeOut();
            $('#div_descargar_factura').fadeOut();
            visualizando_factura = false;
        }
    });

    $('#numero_factura_search').bind('keyup', function() {
        if($.trim($(this).val()).length === 9 && !visualizando_factura_edit) {
            $('#btn_buscar_factura').attr('disabled', false);
            $('#btn_buscar_factura').attr('class', 'btn btn-info');
            // $('#btn_buscar_factura').trigger('click');
            visualizando_factura_edit = true;
        }
        else if($.trim($(this).val()).length < 9) {
            $('#btn_buscar_factura').attr('disabled', true);
            $('#btn_buscar_factura').attr('class', 'btn btn-default');
            // $('.info_factura').fadeOut();
            // $('#div_descargar_factura').fadeOut();
            visualizando_factura_edit = false;
        }
    });

    $('#upload-asobancaria').off('click').on('click', function() {
        var btn = $(this);
        $(btn).attr('disabled', true);
        $('#modal-carga-archivo-asobancaria').modal({ backdrop: 'static', keyboard: false }, 'show');
        $('#btn_cargar_archivo_asobancaria').attr('disabled', true);

        $('#modal-carga-archivo-asobancaria').on('hidden.bs.modal', function() {
            $('#load-form')[0].reset();
            $('#upload-asobancaria').attr('disabled', false);
            reset_bar(false);
        });

    });

    $('#search-pago').off('click').on('click', function() {
        var btn = $(this);
        $(btn).attr('disabled', true);
        $('#modal-search-recibo').modal({ backdrop: 'static', keyboard: false }, 'show');
        // $('#btn_cargar_archivo_asobancaria').attr('disabled', true);

        $('#modal-search-recibo').on('hidden.bs.modal', function() {
            $('#numero_recibo_search').val('');
            $('#search-pago').attr('disabled', false);
            $('#btn_buscar_recibo').attr('disabled', true);
            $('#btn_buscar_recibo').attr('class', 'btn btn-default');
            $('.info_factura').fadeOut();
            $('#div_descargar_factura').fadeOut();
        });

        $('#modal-search-recibo').on('show.bs.modal', function() {
            $('#numero_recibo_search').val('');
        });

        $('#modal-search-recibo').on('shown.bs.modal', function() {
            $('#numero_recibo_search').focus();
        });

    });

    $("#btn_buscar_recibo").off("click").on("click", function() {
        if ($('#numero_recibo_search').val().length > 0) {
            getJsonRecibo();
        }
    });

    $("#btn_buscar_pagos").off("click").on("click", function() {
            var form = $("#pagos-filtro-form");
            if (form.valid()) {
                var input_fecha_pago_inicial = $(
                    '<input class="datohidden" id="fecha_pago_inicial" name="fecha_pago_inicial" type="hidden" value="' +
                    $("#fecha_pago_inicial").val() +
                    '"  />'
                );
                $("#" + global_filtroform_to_send).prepend(
                    input_fecha_pago_inicial
                );

                var input_fecha_pago_final = $(
                    '<input class="datohidden" id="fecha_pago_final" name="fecha_pago_final" type="hidden" value="' +
                    $("#fecha_pago_final").val() +
                    '"  />'
                );
                $("#" + global_filtroform_to_send).prepend(
                    input_fecha_pago_final
                );

                var input_banco_pago_inicial = $(
                    '<input class="datohidden" id="banco_pago_inicial" name="banco_pago_inicial" type="hidden" value="' +
                    $("#id_banco_inicial").val() +
                    '"  />'
                );
                $("#" + global_filtroform_to_send).prepend(
                    input_banco_pago_inicial
                );

                var input_banco_pago_final = $(
                    '<input class="datohidden" id="banco_pago_final" name="banco_pago_final" type="hidden" value="' +
                    $("#id_banco_final").val() +
                    '"  />'
                );
                $("#" + global_filtroform_to_send).prepend(
                    input_banco_pago_final
                );

                $("#" + global_filtroform_to_send)
                    .first()
                    .submit();
                getJsonPagos();
            }

    });

    $("#btn_buscar_factura").off("click").on("click", function() {
        getJsonPagos();
    });

    DTPagos = $("#pagosTable").DataTable({
        initComplete: function(settings, json) {
            //$("#pagosTable").css("display", "");
        },
        destroy: true,
        ordering: false,
        //"filter": false,
        order: [],
        lengthChange: false,
        info: true,
        pageLength: PAGE_LENGTH,
        select: false,
        autoWidth: false,
        language: {
            url: ROOT_URL +
                "/theme/plugins/bower_components/datatables/spanish.json",
        },
        data: [],
        columns: [{
                data: "id",
                title: "id",
                visible: false,
            },
            {
                // data: "numero_recibo",
                title: "N&uacute;mero factura",
                render: function (data, type, row, meta) {
                    return row.numero_recibo + '&nbsp;&nbsp;&nbsp;<a href="#" data-toggle="tooltip" data-placement="top" title="Cargado ' + (row.origen === 'M' ? 'manualmente' : 'mediante archivo') + '"> <i class="' + (row.origen === 'M' ? 'ti-hand-stop text-success' : 'ti-upload text-info') + '"></i> </a>';
                },
            },
            {
                title: "Valor facturado",
                "render": function(data, type, row, meta) {
                    return accounting.formatMoney(Number(row.valor_facturado), "$ ", 0, ".", ",");
                }
            },
            {
                data: "anio_pago",
                title: "A&ntilde;o pago",
            },
            {
                data: "fecha_pago",
                title: "Fecha pago",
            },
            {
                data: "banco",
                title: "Banco",
            },
            // {
            //     title: "Origen",
            //     render: function (data, type, row, meta) {
            //         return '<a href="#" data-toggle="tooltip" data-placement="top" title="Carga ' + (row.origen === 'M' ? 'manual' : 'archivo') + '"> <i class="' + (row.origen === 'M' ? 'ti-hand-stop text-success' : 'ti-cloud-up text-info') + '"></i> </a>';
            //     },
            // },
            {
                title: "Acción",
                render: function (data, type, row, meta) {
                    if (row.origen === 'M') {
                        if ($('#chk_search_factura').is(':checked')) {
                            return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Editar pago" class="editarPago"> <i class="fa fa-edit text-info"></i> </a>';
                        } else {
                            return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Eliminar registro" class="deletePago"> <i class="fa fa-trash text-danger"></i> </a>';
                        }
                    } else {
                        return '<i class="fa fa-trash text-muted"></i>';
                    }
                },
            },
        ],
        drawCallback: function(settings) {
            $(".deletePago")
                .off("click")
                .on("click", function () {
                    var tr = $(this).closest("tr");
                    var data = DTPagos.row(tr).data();
                    swal({
                        title: "Atención",
                        text: '¿Está seguro que desea eliminar la información del pago?',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si",
                        cancelButtonText: "No",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            saveEliminarPago(data.id);
                        }
                        $('[data-toggle="tooltip"]').tooltip();
                    });
                });

            $(".editarPago")
                .off("click")
                .on("click", function (e) {
                    e.preventDefault();
                    var tr = $(this).closest("tr");
                    var data = DTPagos.row(tr).data();
                    $('#id_pago_edit').val(data.id);
                    $('#numero_recibo_edit').val(data.numero_recibo);
                    $('#id_banco_factura_edit').val(data.id_banco_factura).selectpicker("refresh");
                    $('#fecha_pago_edit').datepicker('setDate', moment(data.fecha_pago).toDate());
                    $('#fecha_pago_edit').attr('default', data.fecha_pago);
                    $('#modal-editar-pago').modal({ backdrop: 'static', keyboard: false }, 'show');
                });
        },
        columnDefs: [
            { className: "text-center", targets: [1, 2, 3, 4, 5, 6] },
            //{ className: 'text-center', "targets": [6] },
            //{ className: 'text-right money', targets: [1, 2] },
            //{ className: 'text-center stock_selected', targets: [4] },
            //, { "visible": false, "targets": [2] }
        ],
    });

    DTLogs = $("#logsTable").DataTable({
        initComplete: function(settings, json) {
            // $("#div_logs").fadeIn();
        },
        destroy: true,
        ordering: false,
        filter: false,
        order: [],
        lengthChange: false,
        info: true,
        pageLength: PAGE_LENGTH,
        select: false,
        autoWidth: false,
        paging: false,
        language: {
            url: ROOT_URL +
                "/theme/plugins/bower_components/datatables/spanish.json",
        },
        data: [],
        columns: [{
                data: "id",
                title: "id",
                visible: false,
            },
            {
                data: "usuario",
                title: "Usuario carga",
                defaultContent: "",
            },
            {
                data: "total_guardados",
                title: "Total guardados",
                defaultContent: "",
            },
            {
                data: "total_existentes",
                title: "Total guardados previamente",
                defaultContent: "",
            },
            {
                data: "total_fallidos",
                title: "Total fallidos",
                defaultContent: "",
            },
            {
                data: "control_archivo_registros",
                title: "Total control archivo",
                defaultContent: "",
            },
            {
                title: "Acción",
                render: function (data, type, row, meta) {
                    return '<a href="#" data-toggle="tooltip" data-placement="top" title="Ver todo" class="verLog"> <i class="fa fa-eye text-info"></i> </a>';
                },
            },
        ],
        drawCallback: function(settings) {
            $(".verLog")
                .off("click")
                .on("click", function (e) {
                    e.preventDefault();
                    var tr = $(this).closest("tr");
                    var data = DTLogs.row(tr).data();
                    $('#div_show_log').html('<b>Usuario carga:</b> ' + data.usuario +
                                            '<br /><b>Archivo:</b> ' + data.name + ' <span class="text-success">(type:&nbsp;'+ data.type +', size:&nbsp;' + (Number(data.size)/1024).toFixed(2) + 'Kb)</span>' +
                                            '<br /><b>Fecha recaudos:</b> ' + data.fecha_pago +
                                            '<br /><b>Fecha carga:</b> ' + data.created_at.substring(0, 19) +
                                            '<br /><b>Generado por:</b> ' + data.banco +
                                            '<br />' + data.descripcion);
                    $('#modal-log').modal({ backdrop: 'static', keyboard: false }, 'show');
                });
        },
        columnDefs: [
            { className: "text-center", targets: [2, 3, 4, 5, 6] },
            //{ className: 'text-center', "targets": [6] },
            //{ className: 'text-right money', targets: [1, 2] },
            //{ className: 'text-center stock_selected', targets: [4] },
            //, { "visible": false, "targets": [2] }
        ],
    });

    $('#btn_cargar_archivo_asobancaria').off('click').on('click', function() {
        $('#load-form').submit();
    });

    $('#file').bind('change', function() {
        reset_bar(false);
        // clearImageLoader();
        var filesize = this.files[0].size; // On older browsers this can return NULL.
        var filesizeMB = (filesize / (1024 * 1024)).toFixed(2);

        if (filesizeMB <= MAX_FILE_SIZE) {
            // Allow the form to be submitted here.
            $('#btn_cargar_archivo_asobancaria').attr('disabled', false);
            $('#error_fileupload').fadeOut();
        } else {
            // Don't allow submission of the form here.
            $('#btn_cargar_archivo_asobancaria').attr('disabled', true);
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
            $('.btnasobancaria').attr('disabled', true);
            reset_bar(true);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function(xhr) {
            $.unblockUI();
            $('.btnasobancaria').attr('disabled', false);
        },
        success: function(response, statusText, xhr, $form) {
            if (response.message !== undefined) {
                $('#current_filename').html(response.message);
                if(!response.error) {
                    percent.css('color', '#2eb52a');
                    percent.html('Archivo cargado con &eacute;xito');
                }
                else {
                    percent.css('color', 'tomato');
                    percent.html('Error al cargar el archivo');
                }
            }
        }
    });

    $('#fecha_pago').bind('change', function() {
        if(!moment($('#fecha_pago').val(), 'YYYY-MM-DD', true).isValid()) {
            $('#numero_recibo').attr('disabled', true);
            $('#numero_recibo').val('');
            $('#fecha_pago').datepicker('reset');
            $('#create-form')[0].reset();
            $('#id_banco_archivo').val('default').selectpicker("refresh");
        }
    });

    $('#fecha_pago_edit').bind('change', function() {
        $('#btn_guardar_editar_pago').attr('disabled', !(moment($('#fecha_pago_edit').val(), 'YYYY-MM-DD', true).isValid()));
    });

    $('#fecha_pago_edit').bind('blur', function() {
        if(!moment($('#fecha_pago_edit').val(), 'YYYY-MM-DD', true).isValid()) {
            $('#fecha_pago_edit').datepicker('setDate', $('#fecha_pago_edit').attr('default'));
            $('#btn_guardar_editar_pago').attr('disabled', false);
        }
    });

    $('#fecha_pago').on('pick.datepicker', function (e) {
        $('#btn_save_info').attr('disabled', false);
        $('#fecha_pago').datepicker('hide');
        $('#numero_recibo').attr('disabled', false);
        $('#numero_recibo').focus();
        $('#id_banco_archivo').val('default').selectpicker("refresh");
        var validatorCreate = $("#create-form").validate();
        validatorCreate.resetForm();
        $.each($('.has-error'), function(i, el) {
            $(el).removeClass('has-error');
        });
    });

    $('#fecha_pago_inicial').on('pick.datepicker', function (e) {
        if (e.date !== null) {
            $('#fecha_pago_final').datepicker('setStartDate', moment($('#fecha_pago_inicial').datepicker('getDate').toISOString().slice(0, 10)).toDate());
            if ($('#fecha_pago_final').datepicker('getDate') < $('#fecha_pago_inicial').datepicker('getDate')) {
                $('#fecha_pago_final').datepicker('setDate', '');
                $('#fecha_pago_final').val('');
            }
        }
    });

    $('#id_banco_inicial').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
        $('#id_banco_final').attr('disabled', false);
        $.each($('#id_banco_final').find('option'), function(i, el) {
            $(el).css('display', '');
        });
        $.each($('#id_banco_final').find('option'), function(i, el) {
            if (parseInt($(el).attr('value')) < parseInt($('#id_banco_inicial').selectpicker('val'))) {
                $(el).css('display', 'none');
            }
        });
        $('#id_banco_final').selectpicker('refresh');
        if ($('#id_banco_final').selectpicker('val') !== '') {
            if (parseInt($('#id_banco_final').selectpicker('val')) < parseInt($('#id_banco_inicial').selectpicker('val'))) {
                $('#id_banco_final').selectpicker('val', '');
                $('#id_banco_final').selectpicker('refresh');
            }
        }
    });

    $('#id_banco_final').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
        if ($('#id_banco_inicial').selectpicker('val') !== '') {
            if (parseInt($('#id_banco_final').selectpicker('val')) < parseInt($('#id_banco_inicial').selectpicker('val'))) {
                $('#id_banco_inicial').selectpicker('val', '');
                $('#id_banco_inicial').selectpicker('refresh');
            }
        }
    });

    if ($('#print_factura').length > 0) {
        $('#print_factura').off("click").on("click", function() {
            var btn = $(this);
            startImpresion($(btn).attr('url') + $('#info_id_predio').val() + '/0/' + $('#info_anio').val() + '/-/1/-1/0', 'Generación de factura informativa de impuesto predial. Espere un momento por favor.', 'success', '');
        });
    }

    $('#id_banco_factura').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        selected_banco_factura = $('#id_banco_factura').selectpicker('val');
    });

    $('#btn_descargar_pagos').off('click').on('click', function() {
        var fecha_pago_inicial = $('#fecha_pago_inicial').val();
        var fecha_pago_final = $('#fecha_pago_final').val();
        var id_banco_inicial = $('#id_banco_inicial').selectpicker('val');
        var id_banco_final = $('#id_banco_final').selectpicker('val');

        $.blockUI({
            message: `Generando archivo EXCEL con reporte de pagos desde ${fecha_pago_inicial} hasta ${fecha_pago_final}.<br />Espere un momento.`,
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
        fetch(`/export-pagos/${fecha_pago_inicial}/${fecha_pago_final}/${id_banco_inicial}/${id_banco_final}`)
        .then(resp => resp.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            // the filename you want
            a.download = `reporte-pagos.xlsx`;
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

    $('#btn_guardar_editar_pago').off('click').on('click', function() {
        var id_pago = $('#id_pago_edit').val();
        var fecha_pago = $('#fecha_pago_edit').val();
        var id_banco_factura = $('#id_banco_factura_edit').selectpicker('val');
        swal({
            title: "Atención",
            text: '¿Está seguro que desea editar la información del pago?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                saveEditarPago(id_pago, fecha_pago, id_banco_factura);
            }
            $('[data-toggle="tooltip"]').tooltip();
        });
    });

    $('.js-switch').each(function () {
        new Switchery($(this)[0], $(this).data());
    });

    $('#chk_search_factura').off('change').on('change', function() {
        DTPagos.clear().draw();
        if ($(this).is(':checked')) {
            $('.search_general').fadeOut(function() {
                $('.search_factura').fadeIn();
                setTimeout(function() {
                    $('#pagos-filtro-form')[0].reset();
                    validatorFiltro.resetForm();
                    $('#id_banco_inicial').val('default').selectpicker("refresh");
                    $('#id_banco_final').attr('disabled', true);
                    $('#id_banco_final').val('default').selectpicker("refresh");
                    $('#btn_descargar_pagos').fadeOut();
                }, 1500);
            });
        } else {
            $('.search_factura').fadeOut(function() {
                $('.search_general').fadeIn(function() {
                    setTimeout(function() {
                        $("#numero_factura_search").val('');
                        $('#btn_buscar_factura').attr('disabled', true);
                        $('#btn_buscar_factura').attr('class', 'btn btn-default');
                    }, 1500);
                });
            });
        }
    });
});

function getJsonPagos() {
    var type_search = $('#chk_search_factura').is(':checked') ? 'factura' : 'general';
    var jsonObj = {};
    if (type_search === 'factura') {
        jsonObj = {
            numero_recibo: $("#numero_factura_search").val(),
        }
    } else {
        jsonObj = {
            fecha_pago_inicial: $("#fecha_pago_inicial").val(),
            id_banco_factura_inicial: $("#id_banco_inicial").selectpicker("val"),
            fecha_pago_final: $("#fecha_pago_final").val(),
            id_banco_factura_final: $("#id_banco_final").selectpicker("val"),
        }
    }

    $.blockUI({ message: null });
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        url: "/list/pagos_fecha",
        data: jsonObj,
        success: function(response) {
            if (response.pagos !== undefined && response.pagos !== null) {
                if (response.pagos.length > 0) {
                    if (DTPagos !== null) {
                        DTPagos.clear().draw();
                        DTPagos.rows.add(response.pagos).draw();
                        if (type_search === 'general') {
                            $('#btn_descargar_pagos').fadeIn();
                        }
                    }
                } else {
                    DTPagos.clear().draw();
                }
            } else {
                DTPagos.clear().draw();
            }
            if (response.logs !== undefined && response.logs !== null) {
                if (response.logs.length > 0) {
                    if (DTLogs !== null) {
                        DTLogs.clear().draw();
                        DTLogs.rows.add(response.logs).draw();
                        $("#div_logs").fadeIn();
                    }
                } else {
                    DTLogs.clear().draw();
                    $("#div_logs").fadeOut();
                }
            } else {
                DTLogs.clear().draw();
                $("#div_logs").fadeOut();
            }
            $.unblockUI();
        },
        error: function(xhr) {
            $.unblockUI();
            console.log(xhr.responseText);
        },
    });
}

function getInfoPago() {
    // $('#valor_facturado').attr('readonly', true);
    $.blockUI({ message: null });
    $('#btn_save_info').attr('disabled', false);
    var jsonObj = {};
    jsonObj.factura_pago = $('#numero_recibo').val();
    jsonObj.fecha_pago = $('#fecha_pago').val();
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        url: '/get_info_pago',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if (response.length > 0) {
                var prev_valor = response[0].valor_pago;
                $('#id_predio').val(response[0].id_predio);
                $('#codigo_predio').val(response[0].codigo_predio);

                if (prev_valor === '.00'|| prev_valor === null) {
                    prev_valor = '0.00';
                }

                if(Number(prev_valor) > 0) {
                    AutoNumeric.set('#valor_facturado', Number(prev_valor));
                }

                if(response[0].fecha_pago !== null) {
                    $('#fecha_factura').val(response[0].fecha_pago.substr(0, 10));
                }
                else {
                    $('#fecha_factura').val('');
                }

                if(response[0].ultimo_anio !== null) {
                    $('#anio_pago').val(response[0].ultimo_anio);
                }
                else {
                    $('#anio_pago').val('');
                }

                if(response[0].codigo_barras !== null) {
                    $('#codigo_barras').val(response[0].codigo_barras);
                }
                else {
                    $('#codigo_barras').val('');
                }

                if(response[0].id_banco_archivo !== null) {
                    $('#id_banco_archivo').val(response[0].id_banco_archivo).selectpicker("refresh");
                }
                else {
                    $('#id_banco_archivo').val('default').selectpicker("refresh");
                }

                if(response[0].paquete_archivo !== null) {
                    $('#paquete_archivo').val(response[0].paquete_archivo);
                }
                else {
                    $('#paquete_archivo').val('');
                }

                $('#id_banco_factura').val(selected_banco_factura).selectpicker("refresh");

                if(Number(response[0].pagado) < 0) {
                    $('#btn_save_info').attr('disabled', true);
                    swal({
                        title: "Atención",
                        text: `El número de factura ${jsonObj.factura_pago} ya posee un pago registrado. Verifique su consulta`,
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: true
                    });
                }
            } else {
                swal({
                    title: "Atención",
                    text: `El número de factura ${jsonObj.factura_pago} no fue encontrado en el sistema. Verifique su consulta`,
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: true
                });
                $('#id_predio').val('');
                $('#codigo_predio').val('');
                AutoNumeric.set('#valor_facturado', 0);
                $('#fecha_factura').val('');
                $('#anio_pago').val('');
                $('#codigo_barras').val('');
                $('#id_banco_archivo').val('default').selectpicker("refresh");
                $('#paquete_archivo').val('');
            }
            $.unblockUI();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $.unblockUI();
        },
    });
}

function stringToDate(str_date) {
    let return_date = '';
    if(str_date.length === 8) {
        return_date = `${str_date.substr(0, 4)}-${str_date.substr(4, 2)}-${str_date.substr(6)}`;
    }
    else {
        const date = new Date();
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        return_date = `${year}-${month}-${day}`;
    }
    return return_date;
}

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
    $('#btn_cargar_archivo_asobancaria').attr('disabled', before);
    $('#btn_cargar_archivo_asobancaria').css('display', '');
    $('#current_filename').html('');
}

function saveEliminarPago(id_pago) {
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
    jsonObj.id = id_pago;
    jsonObj.fecha_pago = $("#fecha_pago_inicial").val();
    jsonObj.id_banco_factura = $("#id_banco").selectpicker("val");
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/store/pagos_delete',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if(!response.error) {
                if (response.pagos !== undefined && response.pagos !== null) {
                    if (response.pagos.length > 0) {
                        if (DTPagos !== null) {
                            DTPagos.clear().draw();
                            DTPagos.rows.add(response.pagos).draw();
                        }
                    } else {
                        DTPagos.clear().draw();
                    }
                } else {
                    DTPagos.clear().draw();
                }
            }

            swal({
                title: "Atención",
                text: response.message,
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Aceptar",
                closeOnConfirm: true
            });

            $.unblockUI();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $.unblockUI();
        }
    });
}

function getJsonRecibo() {
    $('.modal').css('z-index', 1040);
    $.blockUI({
        message: null,
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
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        url: "/get_info_recibo",
        data: {
            numero_recibo: $("#numero_recibo_search").val()
        },
        success: function(response) {
            $.unblockUI();
            $('.modal').css('z-index', 1041);
            $('#form-info-factura')[0].reset();
            if (response.length > 0) {
                $('#info_id_predio').val(response[0].id_predio);
                $('#info_numero').val(response[0].numero);
                $('#info_anio').val(response[0].anio);
                $('#info_avaluo').val(accounting.formatMoney(Number(response[0].valor_avaluo), "", 2, ",", "."));
                $('#info_fecha_emision').val(response[0].fecha_emision ? response[0].fecha_emision.substring(0, 19) : 'INDEFINIDA');
                $('#info_fecha_vencimiento').val(response[0].fecha_vencimiento ? response[0].fecha_vencimiento.substring(0, 10) : 'INDEFINIDA');
                $('#info_anulado').val(response[0].anulado);
                $('#info_pagado').val(response[0].pagado);
                $('#info_fecha_pago').val(response[0].fecha_pago.substring(0, 10));
                $('#info_valor_factura').val(accounting.formatMoney(Number(response[0].valor_factura), "", 2, ",", "."));
                $('#info_banco').val(response[0].banco);
                $('.info_factura').fadeIn();
                $('#div_descargar_factura').fadeIn();
            } else {
                swal({
                    title: "Atención",
                    text: `El número de factura ${$("#numero_recibo_search").val()} no posee un pago registrado o no existe una factura asociada a este número. Verifique su consulta`,
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: true
                });
            }
        },
        error: function(xhr) {
            $.unblockUI();
            $('.modal').css('z-index', 1041);
            console.log(xhr.responseText);
        },
    });
}

function saveEditarPago(id_pago, fecha_pago, id_banco_factura) {
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
    jsonObj.id = id_pago;
    jsonObj.fecha_pago = fecha_pago;
    jsonObj.id_banco_factura = id_banco_factura;
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/store/pagos_edit',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if(!response.error) {
                if (response.pagos !== undefined && response.pagos !== null) {
                    if (response.pagos.length > 0) {
                        if (DTPagos !== null) {
                            DTPagos.clear().draw();
                            DTPagos.rows.add(response.pagos).draw();
                        }
                    } else {
                        DTPagos.clear().draw();
                    }
                } else {
                    DTPagos.clear().draw();
                }
                $('#modal-editar-pago').modal('hide');
            }

            swal({
                title: "Atención",
                text: response.message,
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Aceptar",
                closeOnConfirm: true
            });

            $.unblockUI();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $.unblockUI();
        }
    });
}
