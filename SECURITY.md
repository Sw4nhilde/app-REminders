# Security Checklist untuk Railway Deployment

## ✅ Pre-Deployment Checklist

### 1. Environment Variables di Railway
Set these in Railway → Variables:

```bash
# Basic
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate with: php artisan key:generate --show>

# Database
DB_CONNECTION=mysql
DATABASE_URL=${MYSQL_URL}

# HTTPS
APP_URL=https://app-reminders-production.up.railway.app

# API Security
API_ALLOWED_ORIGINS=https://app-reminders-production.up.railway.app
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### 2. Database Migration
Jalankan di Railway (via Railway Shell atau deploy command):
```bash
php artisan migrate --force
```

Ini akan create table `personal_access_tokens` untuk Sanctum.

### 3. Cache Configuration
Add to deploy command atau run manually:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. CORS Configuration
✅ Sudah dikonfigurasi untuk read dari `API_ALLOWED_ORIGINS`
- Local dev: uses `*` (allow all)
- Production: set specific domains

### 5. Rate Limiting
✅ Sudah aktif: 60 requests/minute per IP untuk semua `/api/*` routes

### 6. Token Expiration
✅ Token expire setelah 7 hari (604800 minutes)
- User perlu re-login setelah 7 hari
- Old tokens otomatis invalid

---

## 🔒 Security Features

### Authentication
- ✅ Laravel Sanctum token-based auth
- ✅ Bearer token di Authorization header
- ✅ Token stored encrypted di database
- ✅ Password hashing dengan bcrypt

### Authorization
- ✅ User ownership validation di setiap endpoint
- ✅ 403 Forbidden jika akses task orang lain

### Network Security
- ✅ HTTPS enforced di production
- ✅ CORS configured (restrictable via env)
- ✅ Rate limiting active
- ✅ Secure cookies (HttpOnly, SameSite)

### Input Validation
- ✅ Request validation di semua endpoints
- ✅ 422 error untuk invalid input
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Laravel sanitization)

---

## ⚠️ Known Limitations

1. **No API versioning** 
   - Future breaking changes akan affect semua clients
   - Recommendation: prefix with `/api/v1` di masa depan

2. **No request logging**
   - Tidak ada audit trail untuk API calls
   - Recommendation: add middleware untuk log important actions

3. **No IP whitelisting**
   - Semua IP bisa access API (tapi butuh valid token)
   - Recommendation: add IP whitelist untuk admin endpoints

4. **Token revocation manual only**
   - User harus logout manual untuk revoke token
   - Tidak ada "logout all devices" feature

---

## 🧪 Testing Checklist

### Local Testing
```bash
# Start MySQL local atau adjust DB connection
php artisan serve

# Test login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"nim":"YOUR_NIM","password":"YOUR_PASSWORD"}'

# Test with token
curl -X GET http://localhost:8000/api/tasks \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Production Testing (Railway)
```bash
# Test login
curl -X POST https://app-reminders-production.up.railway.app/api/login \
  -H "Content-Type: application/json" \
  -d '{"nim":"YOUR_NIM","password":"YOUR_PASSWORD"}'

# Test HTTPS redirect
curl -I http://app-reminders-production.up.railway.app/api/tasks
# Should redirect to HTTPS

# Test rate limiting
# Run same request 61 times → should get 429 Too Many Requests
```

---

## 🚀 Deploy Command for Railway

Add this to Railway "Build & Deploy" settings:

**Build Command:**
```bash
composer install --optimize-autoloader --no-dev
```

**Start Command:**
```bash
php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## 📞 API Monitoring

Recommended tools:
- **Postman** untuk testing
- **Railway Logs** untuk error monitoring
- **Laravel Telescope** (optional) untuk debugging

---

## 🔄 Regular Maintenance

1. **Token cleanup** (optional cron):
   ```bash
   php artisan sanctum:prune-expired --hours=168
   ```

2. **Log monitoring**:
   Check Railway logs untuk suspicious activity

3. **Security updates**:
   ```bash
   composer update
   ```

---

## ⚡ Quick Fix Commands

### Reset semua tokens (emergency)
```bash
php artisan tinker
>>> DB::table('personal_access_tokens')->truncate();
```

### Check active sessions
```bash
php artisan tinker
>>> \App\Models\User::with('tokens')->get();
```

### Force logout specific user
```bash
php artisan tinker
>>> $user = \App\Models\User::find(1);
>>> $user->tokens()->delete();
```

---

## 📝 Notes

- API sudah production-ready dengan security basic
- Untuk aplikasi dengan traffic tinggi, consider Redis untuk rate limiting
- Untuk sensitive data, consider API key rotation mechanism
- Monitor Railway logs untuk detect abuse patterns
