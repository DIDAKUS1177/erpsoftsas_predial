var DTNotas = null;
var PAGE_LENGTH = 5;
var MAX_FILE_SIZE = 10;
var ROOT_URL = window.location.protocol + "//" + window.location.host;

$(document).ready(function() {
    DTNotas = $("#notasTable").DataTable({
        initComplete: function(settings, json) {
            //$("#notasTable").css("display", "");
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
                data: "codigo_predio",
                title: "C&oacute;digo predio",
            },
            {
                data: "factura_pago",
                title: "Factura"
            },
            {
                data: "anio",
                title: "A&ntilde;o",
            },
            {
                title: "Resoluci&oacute;n",
                render: function (data, type, row, meta) {
                    if (row.file_name) {
                        return '<a data-toggle="tooltip" title="Descargar ' + row.file_name + '" href="/downloadFileResolucion/' + row.file_name + '" style="color: red;"><i style="color: red;" class="fa fa-file-pdf-o"></i></a>';
                    } else {
                        return '&nbsp;';
                    }
                },
            },
            {
                title: "Usuario creaci&oacute;n",
                render: function (data, type, row, meta) {
                    return `${row.nombres} ${row.apellidos}`;
                },
            },
            {
                data: "created_at",
                title: "Fecha creaci&oacute;n",
            },
            {
                title: "Acción",
                render: function (data, type, row, meta) {
                    return '<a href="javascript:void(0)" url="/generate_factura_pdf/" data-toggle="tooltip" data-placement="top" title="Imprimir factura" class="imprimir print_factura" style="color: red; padding-left: 15px; padding-right: 15px;"> <i style="color: red;" class="fa fa-print"></i> </a>' +
                    '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Eliminar nota" class="deleteNota" style="padding-left: 15px; padding-right: 15px;"> <i class="fa fa-trash text-danger"></i> </a>';
                },
            },
        ],
        drawCallback: function(settings) {
            $(".deleteNota")
                .off("click")
                .on("click", function () {
                    var tr = $(this).closest("tr");
                    var data = DTNotas.row(tr).data();
                    swal({
                        title: "Atención",
                        text: '¿Está seguro que desea eliminar la información de la nota a factura?',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si",
                        cancelButtonText: "No",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            saveEliminarNota(data.id);
                        }
                        $('[data-toggle="tooltip"]').tooltip();
                    });
                });

            $(".nota-row")
                .off("click")
                .on("click", function () {
                    var tr = $(this).closest("tr");
                    var data = DTNotas.row(tr).data();
                    // console.log('📌 - notas.js:69 - data:', data);
                    $('#tbody_notas').empty();
                    // $('#txt_factura_pago').html(data.factura_pago);
                    // $('#txt_anio').html(data.anio);

                    $('#p_codigo_predio').html(data.codigo_predio);
                    $('#p_factura_pago').html(data.factura_pago);
                    $('#p_anio').html(data.anio);
                    $('#p_usuario').html(data.nombres + ' ' + data.apellidos);
                    $('#p_fecha_creacion').html(data.created_at);
                    $('#p_resolucion').html(data.file_name ? `<a href="/downloadFileResolucion/${data.file_name}" style="color: red;"><i style="color: red;" class="fa fa-file-pdf-o"></i></a>` : 'No disponible');

                    //for each key in data generate a tr and append to tbody_notas table
                    var conceptos_titulos = {
                        "valor_concepto1": "Impuesto predial",
                        "valor_concepto2": "Intereses predial",
                        "valor_concepto3": "Impuesto CAR",
                        "valor_concepto4": "Intereses CAR",
                        "valor_concepto13": "Descuento predial",
                        "valor_concepto14": "Sobretasa predial",
                        "valor_concepto15": "Descuento CAR",
                        "valor_concepto16": "Sobretasa bomberil",
                        "valor_concepto17": "Saldos a favor y devoluciones",
                        "valor_concepto18": "Alumbrado p&uacute;blico",
                        "total_calculo": "Total factura",
                    };

                    for (const key of Object.keys(conceptos_titulos)) {
                        var diferentes = data[`prev_${key}`] !== data[key];
                        var color_anterior = diferentes ? '#f1d7d7' : '#eee';
                        var color_nuevo = diferentes ? '#e8f1d7' : '#eee';
                        var val_anterior = $('<div><div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-group" style="margin-bottom: 0px;">' +
                            '<label for="prev_'+ key + '">Valor anterior</label>' +
                            '<div class="input-group">' +
                            '    <div class="input-group-addon" style="background-color: ' + color_anterior + '">$</div>' +
                            '    <input id="prev_'+ key + '" type="text" class="form-control" style="background-color: ' + color_anterior + '" readonly="readonly" value="' + data[`prev_${key}`] + '"> </div>' +
                        '</div></div></div>');
                        var val_nuevo = $('<div><div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><div class="form-group" style="margin-bottom: 0px;">' +
                            '<label for="'+ key + '">Valor nuevo</label>' +
                            '<div class="input-group">' +
                            '    <div class="input-group-addon" style="background-color: ' + color_nuevo + '">$</div>' +
                            '    <input id="'+ key + '" type="text" class="form-control" style="background-color: ' + color_nuevo + '" readonly="readonly" value="' + data[key] + '"> </div>' +
                        '</div></div></div>');
                        $('#tbody_notas').append(`<tr><td>${conceptos_titulos[key]}</td><td><div class="row">${$(val_anterior).html()}${$(val_nuevo).html()}</div></td></tr>`);
                    }

                    $('#modal-ver-nota').modal('show');
                });

            $('.print_factura').off("click").on("click", function() {
                var tr = $(this).closest("tr");
                var data = DTNotas.row(tr).data();
                // console.log('📌 - notas.js:69 - data:', data);
                var btn = $(this);
                startImpresion($(btn).attr('url') + data.id_predio + '/0/' + data.anio + '/-/1/-1/0', 'Generación de factura informativa de impuesto predial. Espere un momento por favor.', 'success', '');
            });
        },
        columnDefs: [
            { className: "text-center nota-row", targets: [1, 2, 3, 5, 6] },
            { className: "text-center nota-noclick-row", targets: [4, 7] },
            //{ className: 'text-center', "targets": [6] },
            //{ className: 'text-right money', targets: [1, 2] },
            //{ className: 'text-center stock_selected', targets: [4] },
            //, { "visible": false, "targets": [2] }
        ],
    });

    // if($('#print_notas').length) {
    //     $('#print_notas').off('click').on('click', function() {
    //         $('#modal-impresion').modal({ backdrop: 'static', keyboard: false }, 'show');
    //     });
    // }

    if($('#print_notas').length) {
        $('#print_notas').off('click').on('click', function() {
            var validatorInforme = $("#form-impresion-informe").validate({
                rules: {
                    fecha_min_notas: "required",
                    fecha_max_notas: "required",
                },
                messages: {
                    fecha_min_notas: "Fecha m&iacute;nima requerida",
                    fecha_max_notas: "Fecha m&aacute;xima requerida",
                }
            });
            $('#modal-impresion').modal({ backdrop: 'static', keyboard: false }, 'show');
            // $('#modal-impresion').off('shown.bs.modal').on('shown.bs.modal', function() {
            // });

            $('#modal-impresion').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                $('#form-impresion-informe')[0].reset();
                clear_form_elements("#form-impresion-informe");
                validatorInforme.resetForm();
                $('#fecha_min_notas').removeClass('error');
                $('#fecha_max_notas').removeClass('error');
            });
        });

        $('#fecha_min_notas').on('pick.datepicker', function (e) {
            $('#fecha_min_notas').removeClass('error');
        });
        $('#fecha_max_notas').on('pick.datepicker', function (e) {
            $('#fecha_max_notas').removeClass('error');
            if (e.date !== null) {
                if ($('#fecha_max_notas').datepicker('getDate') < $('#fecha_min_notas').datepicker('getDate')) {
                    $('#fecha_min_notas').datepicker('setDate', '');
                    $('#fecha_min_notas').val('');
                }
            }
        });
    }

    // if($('#generar_informe_notas').length) {
    //     $('#generar_informe_notas').off('click').on('click', function() {
    //         var btn = $(this);
    //         var form = $("#form-impresion-informe");
    //         if (form.valid()) {
    //             $('.btn_pdf').attr('disabled', true);
    //             startImpresion($(btn).attr('url') + $('#fecha_min_prescripcion').val() + '/' + $('#fecha_max_prescripcion').val(), 'Iniciando generación de listado de notas. Espere un momento por favor.', 'warning', 'modal-impresion');
    //         }
    //     });
    // }

    if ($('#btn_descargar_excel_notas')) {
        $('#btn_descargar_excel_notas').off('click').on('click', function() {
            var btn = $(this);
            var form = $("#form-impresion-informe");
            if (form.valid()) {
                $('.btn_excel').attr('disabled', true);
                var tipo = $(btn).attr('tipo');
                var fecha_inicial = $('#fecha_min_notas').val();
                var fecha_final = $('#fecha_max_notas').val();

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
                    a.download = `reporte-${tipo}-a-facturas-${fecha_inicial}_${fecha_final}.xlsx`;
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
});

function getJsonNotas() {
    $.blockUI({ message: null });
    $.ajax({
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        url: "/list/notas",
        // data: jsonObj,
        success: function(response) {
            if (response.notas !== undefined && response.notas !== null) {
                if (response.notas.length > 0) {
                    if (DTNotas !== null) {
                        DTNotas.clear().draw();
                        DTNotas.rows.add(response.notas).draw();
                    }
                } else {
                    DTNotas.clear().draw();
                }
            } else {
                DTNotas.clear().draw();
            }
            $.unblockUI();
        },
        error: function(xhr) {
            $.unblockUI();
            console.log(xhr.responseText);
        },
    });
}

function saveEliminarNota(id_nota) {
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
    jsonObj.id = id_nota;
    $.ajax({
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        url: '/store/notas_delete',
        data: {
            form: JSON.stringify(jsonObj)
        },
        success: function(response) {
            if(!response.error) {
                if (response.notas !== undefined && response.notas !== null) {
                    if (response.notas.length > 0) {
                        if (DTNotas !== null) {
                            DTNotas.clear().draw();
                            DTNotas.rows.add(response.notas).draw();
                        }
                    } else {
                        DTNotas.clear().draw();
                    }
                } else {
                    DTNotas.clear().draw();
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

function cleanNotas() {
    $('#load_resolucion').css('display', 'none');
    $('#select_factura').css('display', 'none');
    $('#select_anios').css('display', 'none');
    $('#select_anio').css('display', 'none');
    $('#valores_factura').css('display', 'none');
    $('#numero_factura').empty();
    $('#numero_factura').selectpicker('refresh');
    $('#ultimo_anio').empty();
    $('#ultimo_anio').selectpicker('refresh');
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
    $('#btn_save_create').attr('disabled', true);
}
