# ReminderApps REST API Documentation

## Base URL
- Local: `http://localhost:8000/api`
- Production: `https://app-reminders-production.up.railway.app/api`

## Authentication
API menggunakan **Laravel Sanctum** token-based authentication.

### 1. Login (Get Token)
```http
POST /api/login
Content-Type: application/json

{
  "nim": "123456",
  "password": "secret"
}
```

**Response (200 OK):**
```json
{
  "message": "Login successful",
  "token": "1|abcdefghijklmnopqrstuvwxyz...",
  "user": {
    "id": 1,
    "nim": "123456",
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

**Gunakan token ini di header untuk semua request berikutnya:**
```http
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...
```

---

## Endpoints

### 2. Get User Info
```http
GET /api/user
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "user": {
    "id": 1,
    "nim": "123456",
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

---

### 3. Get All Tasks
```http
GET /api/tasks
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Belajar Laravel",
      "description": "Complete tutorial chapter 5",
      "completed": false,
      "created_at": "2025-12-24T10:30:00Z",
      "updated_at": "2025-12-24T10:30:00Z"
    },
    {
      "id": 2,
      "title": "Bikin API",
      "description": "RESTful API dengan Sanctum",
      "completed": true,
      "created_at": "2025-12-24T11:00:00Z",
      "updated_at": "2025-12-24T12:00:00Z"
    }
  ],
  "total": 2
}
```

---

### 4. Get Single Task
```http
GET /api/tasks/{id}
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "data": {
    "id": 1,
    "title": "Belajar Laravel",
    "description": "Complete tutorial chapter 5",
    "completed": false,
    "created_at": "2025-12-24T10:30:00Z",
    "updated_at": "2025-12-24T10:30:00Z"
  }
}
```

**Response (403 Forbidden):**
```json
{
  "message": "Unauthorized"
}
```

---

### 5. Create Task
```http
POST /api/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Tugas baru",
  "description": "Deskripsi tugas (optional)"
}
```

**Response (201 Created):**
```json
{
  "message": "Task created successfully",
  "data": {
    "id": 3,
    "title": "Tugas baru",
    "description": "Deskripsi tugas",
    "completed": false,
    "created_at": "2025-12-24T13:00:00Z",
    "updated_at": "2025-12-24T13:00:00Z"
  }
}
```

---

### 6. Update Task
```http
PUT /api/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated title",
  "description": "Updated description",
  "completed": true
}
```

**Response (200 OK):**
```json
{
  "message": "Task updated successfully",
  "data": {
    "id": 1,
    "title": "Updated title",
    "description": "Updated description",
    "completed": true,
    "created_at": "2025-12-24T10:30:00Z",
    "updated_at": "2025-12-24T14:00:00Z"
  }
}
```

---

### 7. Toggle Task Completion
```http
POST /api/tasks/{id}/toggle
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "message": "Task toggled successfully",
  "data": {
    "id": 1,
    "title": "Belajar Laravel",
    "description": "Complete tutorial chapter 5",
    "completed": true,
    "created_at": "2025-12-24T10:30:00Z",
    "updated_at": "2025-12-24T14:30:00Z"
  }
}
```

---

### 8. Delete Task
```http
DELETE /api/tasks/{id}
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "message": "Task deleted successfully"
}
```

---

### 9. Logout (Revoke Token)
```http
POST /api/logout
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "message": "Logged out successfully"
}
```

---

## Error Responses

### 401 Unauthorized (Token invalid/missing)
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden (Access denied)
```json
{
  "message": "Unauthorized"
}
```

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\Task]"
}
```

### 422 Validation Error
```json
{
  "message": "The title field is required.",
  "errors": {
    "title": [
      "The title field is required."
    ]
  }
}
```

---

## Testing dengan cURL

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"nim":"123456","password":"secret"}'
```

### Get Tasks
```bash
curl -X GET http://localhost:8000/api/tasks \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Create Task
```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"title":"New Task","description":"Task description"}'
```

### Toggle Task
```bash
curl -X POST http://localhost:8000/api/tasks/1/toggle \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Delete Task
```bash
curl -X DELETE http://localhost:8000/api/tasks/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Testing dengan Postman

1. Import collection atau buat manual:
   - Base URL: `http://localhost:8000/api`
   - Add environment variable: `token`

2. Login dulu untuk dapat token
3. Set token di Authorization tab → Type: Bearer Token
4. Test semua endpoint

---

## CORS Configuration

API sudah dikonfigurasi untuk accept requests dari semua origin (`*`). 

Untuk production, sebaiknya set specific origins di `config/cors.php`:
```php
'allowed_origins' => [
    'https://yourdomain.com',
    'https://mobile-app.com'
],
```

---

## Rate Limiting

Default Laravel rate limit: **60 requests per minute** per IP address.

Untuk customize, edit `app/Http/Kernel.php` atau `bootstrap/app.php`.
