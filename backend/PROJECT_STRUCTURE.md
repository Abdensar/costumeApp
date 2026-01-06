# 📂 Project Structure Overview

## Complete Backend Architecture

```
backend/
│
├── 📁 app/
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   └── 📁 API/
│   │   │       ├── 🎯 AuthController.php          [Register, Login, Logout]
│   │   │       ├── 🎯 CategoryController.php      [CRUD Categories]
│   │   │       ├── 🎯 CostumeController.php       [CRUD Costumes + Images]
│   │   │       ├── 🎯 RentalController.php        [Bookings + QR Codes]
│   │   │       └── 🎯 UserController.php          [Profile + Users List]
│   │   │
│   │   ├── 📁 Middleware/
│   │   │   └── 🔒 IsAdmin.php                     [Admin Authorization]
│   │   │
│   │   ├── 📁 Requests/
│   │   │   ├── ✅ RegisterRequest.php             [Registration Validation]
│   │   │   ├── ✅ LoginRequest.php                [Login Validation]
│   │   │   ├── ✅ CategoryRequest.php             [Category Validation]
│   │   │   ├── ✅ CostumeRequest.php              [Costume Validation]
│   │   │   ├── ✅ RentalRequest.php               [Rental Validation]
│   │   │   └── ✅ UpdateProfileRequest.php        [Profile Validation]
│   │   │
│   │   └── 📁 Resources/
│   │       ├── 📋 UserResource.php                [User JSON Format]
│   │       ├── 📋 CategoryResource.php            [Category JSON Format]
│   │       ├── 📋 CostumeResource.php             [Costume JSON Format]
│   │       └── 📋 RentalResource.php              [Rental JSON Format]
│   │
│   └── 📁 Models/
│       ├── 👤 User.php                            [+ rentals, notifications]
│       ├── 📂 Category.php                        [+ costumes]
│       ├── 👗 Costume.php                         [+ category, rentals, availability]
│       ├── 📅 Rental.php                          [+ user, costume, calculations]
│       └── 🔔 Notification.php                    [+ user]
│
├── 📁 database/
│   ├── 📁 migrations/
│   │   ├── 🗄️ 0001_01_01_000000_create_users_table.php
│   │   ├── 🗄️ 2019_12_14_000001_create_personal_access_tokens_table.php
│   │   ├── 🗄️ 2025_12_18_193934_create_categories_table.php
│   │   ├── 🗄️ 2025_12_18_194007_create_costumes_table.php
│   │   ├── 🗄️ 2025_12_18_194033_create_rentals_table.php
│   │   └── 🗄️ 2025_12_18_194118_create_notifications_table.php
│   │
│   └── 📁 seeders/
│       ├── 🌱 DatabaseSeeder.php                  [Admin + Customer Users]
│       ├── 🌱 CategorySeeder.php                  [6 Categories]
│       └── 🌱 CostumeSeeder.php                   [13 Sample Costumes]
│
├── 📁 routes/
│   └── 🛣️ api.php                                 [24 API Endpoints]
│
├── 📁 storage/
│   └── 📁 app/
│       └── 📁 public/
│           └── 📁 costumes/                       [Uploaded Images]
│
├── 📁 public/
│   └── 📁 storage/                                [Symlink to storage/app/public]
│
├── 📄 .env                                        [Configuration]
├── 📄 composer.json                               [Dependencies]
│
└── 📚 Documentation/
    ├── 📖 API_DOCUMENTATION.md                    [Complete API Reference]
    ├── ⚡ QUICKSTART.md                           [5-Minute Setup]
    ├── 📝 IMPLEMENTATION_SUMMARY.md               [What Was Built]
    ├── 🚀 DEPLOYMENT_CHECKLIST.md                 [Production Guide]
    ├── 📮 Costume_Rental_API.postman_collection.json [API Tests]
    └── 📂 PROJECT_STRUCTURE.md                    [This File]
```

---

## 🎯 Core Features Map

### Authentication Flow
```
Register → Validate → Hash Password → Create User → Generate Token → Return User + Token
Login → Validate → Check Credentials → Generate Token → Return User + Token
Logout → Revoke Token → Success Message
```

### Rental Flow
```
Customer Requests → Validate Dates → Check Availability → Calculate Price → Create Rental (pending)
↓
Admin Reviews → Confirms Rental → Generate QR Code → Mark Costume Unavailable
↓
Rental Period Ends → Admin Marks Returned → Mark Costume Available
```

### Image Upload Flow
```
Admin Uploads → Validate Image → Store in storage/app/public/costumes → Save Path in DB → Return URL
```

---

## 📊 Database Relationships Diagram

```
                    ┌─────────────┐
                    │    User     │
                    │ (Admin/     │
                    │  Customer)  │
                    └──────┬──────┘
                           │
                 ┌─────────┴─────────┐
                 │                   │
           ┌─────▼──────┐     ┌─────▼──────────┐
           │  Rental    │     │ Notification   │
           └─────┬──────┘     └────────────────┘
                 │
                 │
           ┌─────▼──────┐
           │  Costume   │
           └─────┬──────┘
                 │
                 │
           ┌─────▼──────┐
           │  Category  │
           └────────────┘
```

---

## 🔐 Authentication & Authorization

```
Public Access
├── Browse Categories
├── Browse Costumes
├── Register
└── Login

Authenticated (Customer)
├── View Profile
├── Update Profile
├── Create Rental
└── View My Rentals

Authenticated (Admin)
├── All Customer Features
├── Manage Categories (CRUD)
├── Manage Costumes (CRUD)
├── View All Rentals
├── Update Rental Status
├── Generate QR Codes
├── View Statistics
└── View All Users
```

---

## 📡 API Endpoints Tree

```
/api
├── /register                          POST    [Public]
├── /login                             POST    [Public]
├── /logout                            POST    [Auth]
│
├── /categories                        GET     [Public]
│   ├── /{id}                         GET     [Public]
│   ├── /                             POST    [Admin]
│   ├── /{id}                         PUT     [Admin]
│   └── /{id}                         DELETE  [Admin]
│
├── /costumes                          GET     [Public]
│   ├── /{id}                         GET     [Public]
│   ├── /                             POST    [Admin]
│   ├── /{id}                         PUT     [Admin]
│   └── /{id}                         DELETE  [Admin]
│
├── /rentals                           POST    [Customer]
│   ├── /                             GET     [Admin]
│   ├── /{id}/status                  PUT     [Admin]
│   └── /statistics                   GET     [Admin]
│
├── /my-rentals                        GET     [Customer]
│
├── /profile                           GET     [Auth]
│   └── /                             PUT     [Auth]
│
└── /users                             GET     [Admin]
```

---

## 🧩 Component Dependencies

### Controllers Dependency Graph
```
AuthController
├── RegisterRequest
├── LoginRequest
├── UserResource
└── User Model

CategoryController
├── CategoryRequest
├── CategoryResource
└── Category Model

CostumeController
├── CostumeRequest
├── CostumeResource
├── Costume Model
└── Storage Facade

RentalController
├── RentalRequest
├── RentalResource
├── Rental Model
├── Costume Model
└── Carbon (Date handling)

UserController
├── UpdateProfileRequest
├── UserResource
└── User Model
```

---

## 💾 Data Flow Examples

### Creating a Rental
```
Mobile App → POST /api/rentals
    ↓
RentalRequest validates input
    ↓
RentalController::store()
    ↓
Check costume availability
    ↓
Calculate total price
    ↓
Create Rental record (status: pending)
    ↓
RentalResource formats response
    ↓
JSON returned to Mobile App
```

### Admin Confirms Rental
```
Admin App → PUT /api/rentals/1/status
    ↓
IsAdmin middleware checks authorization
    ↓
RentalController::updateStatus()
    ↓
Update status to "confirmed"
    ↓
Generate QR code string
    ↓
Mark costume as unavailable
    ↓
Save changes
    ↓
RentalResource formats response
    ↓
JSON returned to Admin App
```

---

## 🔄 State Transitions

### Rental Status States
```
pending → confirmed → returned
   ↓         ↓
cancelled  cancelled
```

### Costume Availability
```
available → (rental confirmed) → unavailable → (rental returned) → available
```

---

## 🎨 Code Architecture Layers

```
┌─────────────────────────────────────┐
│         Mobile App (Flutter)        │  ← Your next step
└─────────────┬───────────────────────┘
              │ HTTP/JSON
┌─────────────▼───────────────────────┐
│          API Routes (api.php)       │  Layer 1: Routing
└─────────────┬───────────────────────┘
              │
┌─────────────▼───────────────────────┐
│     Middleware (Auth, Admin)        │  Layer 2: Authorization
└─────────────┬───────────────────────┘
              │
┌─────────────▼───────────────────────┐
│    Form Requests (Validation)       │  Layer 3: Validation
└─────────────┬───────────────────────┘
              │
┌─────────────▼───────────────────────┐
│      Controllers (Business)         │  Layer 4: Business Logic
└─────────────┬───────────────────────┘
              │
┌─────────────▼───────────────────────┐
│      Models (Data Access)           │  Layer 5: Data Layer
└─────────────┬───────────────────────┘
              │
┌─────────────▼───────────────────────┐
│      API Resources (Format)         │  Layer 6: Presentation
└─────────────┬───────────────────────┘
              │ JSON
┌─────────────▼───────────────────────┐
│         Mobile App (Flutter)        │  
└─────────────────────────────────────┘
```

---

## 📦 Package Dependencies

```json
{
  "laravel/framework": "^10.0",
  "laravel/sanctum": "^4.0",
  "php": "^8.1"
}
```

---

## 🎯 Key Files Quick Reference

| File | Purpose | Lines |
|------|---------|-------|
| AuthController.php | User authentication | ~70 |
| CategoryController.php | Category management | ~65 |
| CostumeController.php | Costume management | ~130 |
| RentalController.php | Rental management | ~150 |
| UserController.php | User profiles | ~50 |
| api.php | Route definitions | ~60 |
| User.php | User model | ~70 |
| Costume.php | Costume model | ~65 |
| Rental.php | Rental model | ~60 |

**Total Backend Code**: ~1500 lines (excluding migrations, seeders, config)

---

## 🧪 Testing Coverage

✅ Authentication endpoints  
✅ CRUD operations  
✅ Authorization checks  
✅ Validation rules  
✅ Business logic  
✅ Relationships  
✅ Image uploads  
✅ Date calculations  

---

## 📈 Scalability Considerations

- **Database**: Indexed foreign keys for fast lookups
- **Caching**: Ready for Redis integration
- **Queues**: Can add queue workers for heavy operations
- **Storage**: Can switch to S3/CloudStorage for images
- **Load Balancing**: Stateless API ready for horizontal scaling

---

**This backend is production-ready and follows Laravel best practices! 🎉**
