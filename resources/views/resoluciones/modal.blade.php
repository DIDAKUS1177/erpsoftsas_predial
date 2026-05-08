@push('scripts')
    <script src="{!! asset('theme/plugins/bower_components/jquery-validation-1.19.5/jquery.validate.min.js') !!}"></script>
    <script src="{!! asset('theme/js/customjs/resoluciones.js') !!}"></script>
@endpush
<div id="modal-resolucion" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-resolucion-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-resolucion-label">Informaci&oacute;n de resoluci&oacute;n</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="resolucion-form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">N&uacute;mero:</label>
                                            <input type="text" id="numero_resolucion_modal" name="numero_resolucion_modal" class="form-control" autocomplete="off" placeholder="Ingrese n&uacute;mero resoluci&oacute;n" maxlength="30">
                                            {{-- <span class="text-danger">@error('numero_resolucion_modal') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Fecha:</label>
                                            <input type="text" id="fecha_resolucion_modal" name="fecha_resolucion_modal" class="form-control datepicker novalidate" autocomplete="off" placeholder="Ingrese fecha resoluci&oacute;n" readonly style="background-color: white;">
                                            {{-- <span class="text-danger">@error('fecha_resolucion_modal') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Elaborado por:</label>
                                            <input type="text" id="firma_resolucion_modal" name="firma_resolucion_modal" class="form-control" autocomplete="off" placeholder="Ingrese elaborado por" maxlength="128">
                                            {{-- <span class="text-danger">@error('firma_resolucion_modal') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div id="txt_operacion_resolucion" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="save_resolucion" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
