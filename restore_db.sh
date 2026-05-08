#!/bin/bash
echo "Waiting for SQL Server to be ready..."

# Active loop: retry every 5 seconds until SQL Server accepts connections
for i in $(seq 1 24); do
    /opt/mssql-tools18/bin/sqlcmd -S localhost -U sa -P 'SecretPassword123!' -C -Q "SELECT 1" > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo "SQL Server is ready! Restoring database..."
        /opt/mssql-tools18/bin/sqlcmd -S localhost -U sa -P 'SecretPassword123!' -C -i /var/backups/restore_db.sql
        echo "Restore script execution finished."
        exit 0
    fi
    echo "Still waiting... attempt $i/24 (5s interval)"
    sleep 5
done

echo "ERROR: SQL Server did not become ready in time."
exit 1
