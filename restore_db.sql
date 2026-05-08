DECLARE @DataName NVARCHAR(255), @LogName NVARCHAR(255);

-- Create a temporary table to hold the results of RESTORE FILELISTONLY
CREATE TABLE #FileList (
    LogicalName NVARCHAR(128), PhysicalName NVARCHAR(260), Type CHAR(1), FileGroupName NVARCHAR(128),
    Size NUMERIC(20,0), MaxSize NUMERIC(20,0), FileID BIGINT, CreateLSN NUMERIC(25,0), DropLSN NUMERIC(25,0),
    UniqueID UNIQUEIDENTIFIER, ReadOnlyLSN NUMERIC(25,0), ReadWriteLSN NUMERIC(25,0), BackupSizeInBytes BIGINT,
    SourceBlockSize INT, FileGroupID INT, LogGroupGUID UNIQUEIDENTIFIER, DifferentialBaseLSN NUMERIC(25,0),
    DifferentialBaseGUID UNIQUEIDENTIFIER, IsReadOnly BIT, IsPresent BIT, TDEThumbprint VARBINARY(32), SnapshotUrl NVARCHAR(360)
);

-- Execute RESTORE FILELISTONLY into the temporary table
INSERT INTO #FileList EXEC('RESTORE FILELISTONLY FROM DISK=''/var/backups/erpsoft_guateque.bak''');

-- Retrieve logical names
SELECT @DataName = LogicalName FROM #FileList WHERE Type = 'D';
SELECT @LogName = LogicalName FROM #FileList WHERE Type = 'L';

-- Build the dynamic RESTORE command
DECLARE @RestoreCmd NVARCHAR(MAX) = 'RESTORE DATABASE erpsofts_guateque FROM DISK=''/var/backups/erpsoft_guateque.bak'' WITH REPLACE, ' +
    'MOVE ''' + @DataName + ''' TO ''/var/opt/mssql/data/erpsofts_guateque.mdf'', ' +
    'MOVE ''' + @LogName + ''' TO ''/var/opt/mssql/data/erpsofts_guateque_log.ldf''';

-- Execute the RESTORE
PRINT 'Executing: ' + @RestoreCmd;
EXEC(@RestoreCmd);
PRINT 'Database restored successfully.';
