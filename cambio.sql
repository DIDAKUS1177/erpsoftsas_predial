IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='predios_acuerdos_pago_detalle' AND xtype='U')
BEGIN
    CREATE TABLE predios_acuerdos_pago_detalle (
        id INT IDENTITY(1,1) PRIMARY KEY,
        predio_acuerdo_pago_id INT NOT NULL,
        cuota INT NOT NULL,
        fecha_vencimiento DATE NOT NULL,
        valor_cuota DECIMAL(18,2) NOT NULL,
        valor_interes DECIMAL(18,2) DEFAULT 0,
        estado VARCHAR(20) DEFAULT 'pendiente',
        created_at DATETIME DEFAULT GETDATE(),
        updated_at DATETIME DEFAULT GETDATE()
    );
END
