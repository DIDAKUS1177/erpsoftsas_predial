<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Validación de certificado PAZ Y SALVO">
    <meta name="author" content="ERPSoft Predial">
    <link rel="icon" type="image/png" sizes="16x16" href="{!! asset('theme/plugins/images/favicon.png') !!}">
    <title>{{ $title }} - Validación PAZ Y SALVO</title>

    <!-- Bootstrap Core CSS -->
    <link href="{!! asset('theme/bootstrap/dist/css/bootstrap.min.css') !!}" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{!! asset('theme/css/animate.css') !!}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{!! asset('theme/css/style.css') !!}" rel="stylesheet">
    <!-- color CSS -->
    <link href="{!! asset('theme/css/colors/green.css') !!}" id="theme" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{!! asset('theme/css/customcss/style.css') !!}" rel="stylesheet">

    <style>
        body {
            background: url('{!! asset('theme/plugins/images/predial-login.jpg') !!}') no-repeat center center / cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.5);
            z-index: -1;
        }
        .validation-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .validation-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
        }
        .status-header {
            padding: 2rem;
            text-align: center;
            color: white;
        }
        .status-valid {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .status-invalid {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
        }
        .status-already_used {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: #212529 !important;
        }
        .status-expired {
            background: linear-gradient(135deg, #6c757d, #495057);
        }
        .status-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }
        .certificate-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            flex: 1;
        }
        .info-value {
            color: #212529;
            text-align: right;
            flex: 1;
        }
        .municipality-footer {
            background: #495057;
            color: white;
            text-align: center;
            padding: 1rem;
        }
        @media (max-width: 768px) {
            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .info-value {
                text-align: left;
                margin-top: 0.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>

    <div class="validation-container">
        <div class="validation-card">
            <div class="status-header status-{{ $status }}">
                @if($status === 'valid')
                    <span class="status-icon">✓</span>
                    <h2>Certificado Válido</h2>
                @elseif($status === 'already_used')
                    <span class="status-icon">⚠</span>
                    <h2>Ya Validado</h2>
                @elseif($status === 'expired')
                    <span class="status-icon">⏰</span>
                    <h2>Expirado</h2>
                @else
                    <span class="status-icon">✗</span>
                    <h2>Inválido</h2>
                @endif
                <p class="mb-0 lead">{{ $message }}</p>
            </div>

            <div class="p-4">
                @if(isset($validation_data))
                    <div class="certificate-info">
                        <h5 class="mb-3">
                            <i class="fa fa-file-text-o"></i>
                            Información del Certificado
                        </h5>

                        <div class="info-row">
                            <span class="info-label">Certificado No.:</span>
                            <span class="info-value"><strong>{{ $validation_data['certificado_numero'] }}</strong></span>
                        </div>

                        @if(isset($validation_data['propietario_principal']))
                        <div class="info-row">
                            <span class="info-label">Propietario Principal:</span>
                            <span class="info-value">{{ $validation_data['propietario_principal'] }}</span>
                        </div>
                        @endif

                        @if(isset($validation_data['fecha_expedicion']))
                        <div class="info-row">
                            <span class="info-label">Fecha de Expedición:</span>
                            <span class="info-value">{{ $validation_data['fecha_expedicion'] }}</span>
                        </div>
                        @endif

                        @if(isset($validation_data['fecha_validez']))
                        <div class="info-row">
                            <span class="info-label">Válido hasta:</span>
                            <span class="info-value">{{ $validation_data['fecha_validez'] }}</span>
                        </div>
                        @endif

                        @if(isset($validation_data['validated_at']))
                        <div class="info-row">
                            <span class="info-label">Fecha de Validación:</span>
                            <span class="info-value">{{ $validation_data['validated_at'] }}</span>
                        </div>
                        @endif

                        @if(isset($validation_data['validation_count']) && isset($validation_data['max_validations']))
                        <div class="info-row">
                            <span class="info-label">Validaciones Realizadas:</span>
                            <span class="info-value"><strong>{{ $validation_data['validation_count'] }} de {{ $validation_data['max_validations'] }}</strong></span>
                        </div>
                        @endif

                        @if(isset($validation_data['remaining_validations']))
                        <div class="info-row">
                            <span class="info-label">Validaciones Restantes:</span>
                            <span class="info-value">
                                @if($validation_data['remaining_validations'] > 0)
                                    <span class="text-success"><strong>{{ $validation_data['remaining_validations'] }}</strong></span>
                                @else
                                    <span class="text-danger"><strong>0 - Límite alcanzado</strong></span>
                                @endif
                            </span>
                        </div>
                        @endif

                        @if(isset($validation_data['predio']))
                        <div class="info-row">
                            <span class="info-label">Código Catastral:</span>
                            <span class="info-value">{{ $validation_data['predio']->codigo_predio }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Dirección:</span>
                            <span class="info-value">{{ $validation_data['predio']->direccion }}</span>
                        </div>
                        @endif
                    </div>
                @endif

                <div class="alert alert-info mt-4">
                    <i class="fa fa-info-circle"></i>
                    <strong>Importante:</strong>
                    @if($status === 'valid')
                        Este certificado ha sido validado exitosamente.
                        @if(isset($validation_data['remaining_validations']) && $validation_data['remaining_validations'] > 0)
                            Puede validar este código QR {{ $validation_data['remaining_validations'] }} vez(es) más.
                        @else
                            Este código QR ha alcanzado su límite máximo de validaciones.
                        @endif
                    @elseif($status === 'already_used')
                        Este código QR ha alcanzado el límite máximo de validaciones permitidas. Para nuevas validaciones, solicite un certificado actualizado.
                    @elseif($status === 'expired')
                        Este certificado ha expirado. Los códigos QR son válidos hasta el 31 de diciembre del año de expedición.
                    @else
                        Verifique que el código QR sea correcto o contacte a la oficina de tesorería.
                    @endif
                </div>

                <div class="alert alert-warning mt-3">
                    <i class="fa fa-shield"></i>
                    <strong>Política de Seguridad:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Cada código QR es válido hasta el 31 de diciembre del año de expedición</li>
                        <li>Se permite un número limitado de validaciones por código QR (por defecto: 5 validaciones)</li>
                        <li>Esta medida previene el uso fraudulento de certificados copiados</li>
                        <li>Una vez alcanzado el límite máximo, solicite un certificado actualizado</li>
                        <li>Cada validación se registra con fecha, hora e IP para auditoría</li>
                    </ul>
                </div>

                <div class="text-center mt-4" style="margin-bottom: 20px;">
                    <button onclick="window.print()" class="btn btn-info m-r-10">
                        <i class="fa fa-print"></i> Imprimir
                    </button>
                    {{-- <button onclick="window.history.back()" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Cerrar
                    </button> --}}
                </div>
            </div>

            <div class="municipality-footer">
                <strong>TESORERÍA MUNICIPAL DE PAIPA</strong><br>
                <small>tesoreria@paipa-boyaca.gov.co | Carrera 22 # 25 - 14</small><br>
                <small>Sistema de Validación de PAZ Y SALVO</small>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{!! asset('theme/plugins/bower_components/jquery/dist/jquery.min.js') !!}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{!! asset('theme/bootstrap/dist/js/bootstrap.min.js') !!}"></script>

    <script>
        $(document).ready(function() {
            $(function() {
                $(".preloader").fadeOut();
            });
        });
    </script>
</body>
</html>
