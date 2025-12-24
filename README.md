# ReminderApps – Deployment Notes

## Railway MySQL Configuration
- **Single URL**: Set `DATABASE_URL=${MYSQL_URL}` and `DB_CONNECTION=mysql`.
- **Individual fields**: Set `DB_HOST=${MYSQLHOST}`, `DB_PORT=${MYSQLPORT}`, `DB_DATABASE=${MYSQLDATABASE}`, `DB_USERNAME=${MYSQLUSER}`, `DB_PASSWORD=${MYSQLPASSWORD}`.
- **Networking**: Use `mysql.railway.internal` only when your service and DB are in the same project/environment with Private Networking enabled. Otherwise use the Public endpoint from Railway’s Connect tab.

## Laravel Caches on Deploy
- Run `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache` after variables change.

## DB Connectivity Check
- Run `php artisan db:ping` on Railway to confirm the app can reach the database and which host/port it’s using.

## Troubleshooting "Connection refused"
- **Mismatch host**: Internal host used without Private Networking → switch to Public endpoint.
- **Port/security**: Ensure port `3306` and credentials match Railway.
- **Config cache**: Clear or rebuild caches to pick up updated variables.
