# Quick Start Guide - Costume Rental API

## ⚡ Get Started in 5 Minutes

### Step 1: Configure Database Password

Open `.env` and update your MySQL password:

```env
DB_PASSWORD=your_mysql_password
```

### Step 2: Create Database

```bash
mysql -u root -p -e "CREATE DATABASE costume_rental;"
```

### Step 3: Run Setup Commands

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

### Step 4: Test the API

**Login as Admin:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@costumeapp.com","password":"password123"}'
```

**Browse Costumes:**
```bash
curl http://localhost:8000/api/costumes
```

---

## 🧪 Quick Test Scenarios

### Scenario 1: Customer Registration & Booking

1. **Register a new customer:**
```json
POST /api/register
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

2. **Login and get token:**
```json
POST /api/login
{
  "email": "jane@example.com",
  "password": "password123"
}
```

3. **Browse available costumes:**
```
GET /api/costumes?available=1
```

4. **Book a costume:**
```json
POST /api/rentals
Headers: Authorization: Bearer {token}
{
  "costume_id": 1,
  "start_date": "2025-12-25",
  "end_date": "2025-12-27"
}
```

5. **View your rentals:**
```
GET /api/my-rentals
Headers: Authorization: Bearer {token}
```

### Scenario 2: Admin Management

1. **Login as admin:**
```json
POST /api/login
{
  "email": "admin@costumeapp.com",
  "password": "password123"
}
```

2. **View all rentals:**
```
GET /api/rentals
Headers: Authorization: Bearer {admin_token}
```

3. **Confirm a rental (generates QR code):**
```json
PUT /api/rentals/1/status
Headers: Authorization: Bearer {admin_token}
{
  "status": "confirmed"
}
```

4. **Add a new costume:**
```
POST /api/costumes
Headers: Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

name: "New Superhero Costume"
description: "Amazing costume"
category_id: 1
size: "L"
price_per_day: 25.00
images[]: [file.jpg]
```

5. **View statistics:**
```
GET /api/rentals/statistics
Headers: Authorization: Bearer {admin_token}
```

---

## 📝 Default Test Accounts

| Role     | Email                    | Password      |
|----------|--------------------------|---------------|
| Admin    | admin@costumeapp.com     | password123   |
| Customer | customer@costumeapp.com  | password123   |

---

## 🎯 Common API Endpoints

| Method | Endpoint                  | Auth | Role     | Description           |
|--------|---------------------------|------|----------|-----------------------|
| POST   | /api/register             | No   | -        | Register new user     |
| POST   | /api/login                | No   | -        | Login                 |
| GET    | /api/categories           | No   | -        | Browse categories     |
| GET    | /api/costumes             | No   | -        | Browse costumes       |
| POST   | /api/rentals              | Yes  | Customer | Book costume          |
| GET    | /api/my-rentals           | Yes  | Customer | View my rentals       |
| GET    | /api/rentals              | Yes  | Admin    | View all rentals      |
| PUT    | /api/rentals/{id}/status  | Yes  | Admin    | Update rental status  |
| POST   | /api/costumes             | Yes  | Admin    | Add costume           |
| GET    | /api/users                | Yes  | Admin    | View all users        |

---

## 🔍 Troubleshooting

**Problem:** Can't connect to database  
**Fix:** Check MySQL is running and `.env` credentials are correct

**Problem:** 401 Unauthorized  
**Fix:** Include `Authorization: Bearer {token}` header

**Problem:** 403 Forbidden  
**Fix:** Endpoint requires admin role, login with admin account

**Problem:** 422 Validation Error  
**Fix:** Check request body matches the required format

---

## 📚 Full Documentation

See [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for complete API reference.

---

## ✅ Verification Checklist

- [ ] Database created
- [ ] Migrations run successfully
- [ ] Seeders completed
- [ ] Can login as admin
- [ ] Can login as customer
- [ ] Can browse costumes
- [ ] Customer can create rental
- [ ] Admin can confirm rental
- [ ] QR code generated on confirmation

---

**Ready to build your mobile app? The backend is all set! 🚀**
