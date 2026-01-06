# Costume Rental Backend - API Documentation

## ✅ Database Status

**Total Costumes:** 15 formal suits (costumes)
**All costumes include:**
- French names and descriptions
- Real images from Unsplash
- Size variants (S, M, L, XL)
- Price per day (39.99€ - 75.00€)
- Brand information

## 📋 Costume List (15 items)

1. **Costume Slim-Fit Noir** - Maison Élégance (49.99€/day)
2. **Costume Bleu Marine** - Atelier Marine (44.00€/day)
3. **Costume Gris Chiné** - TailorPro (39.99€/day)
4. **Costume Coupe Moderne** - Moderne (55.00€/day)
5. **Costume Mariage Classique** - Cérémonie (65.00€/day)
6. **Costume Noir 3 pièces** - Prestige (72.00€/day)
7. **Costume Business Slim** - OfficeWear (45.00€/day)
8. **Costume Cérémonie Bleu Clair** - Été (50.00€/day)
9. **Costume Tweed Vintage** - Vintage Atelier (55.00€/day)
10. **Costume Noir Cintré** - Couturier (48.00€/day)
11. **Costume Beige Élégant** - Soleil (42.00€/day)
12. **Costume Anthracite** - UrbanTailor (46.00€/day)
13. **Costume Gris Clair** - LightLine (40.00€/day)
14. **Costume 3 pièces Bleu Nuit** - Nocturne (75.00€/day)
15. **Costume Marron Chocolat** - Automne (52.00€/day)

## 🚀 API Endpoints for Mobile App

### Base URL
```
http://127.0.0.1:8000/api
```

### 1. Get All Costumes
**Endpoint:** `GET /api/costumes`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Costume Slim-Fit Noir",
      "slug": "costume-slimfit-noir",
      "description": "Un costume est un ensemble de vêtements coordonnés...",
      "brand": "Maison Élégance",
      "price_per_day": 49.99,
      "featured_image_url": "https://images.unsplash.com/photo-1520975914124-2a4f9a3f91f1?w=1200&q=80",
      "is_active": true,
      "available": true,
      "category": {
        "id": 1,
        "name": "General"
      },
      "sizes": [
        {
          "id": 1,
          "size_label": "M",
          "quantity_available": 3
        },
        {
          "id": 2,
          "size_label": "L",
          "quantity_available": 2
        }
      ]
    }
  ]
}
```

### 2. Get Single Costume
**Endpoint:** `GET /api/costumes/{id}`

**Example:** `GET /api/costumes/1`

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Costume Slim-Fit Noir",
    "slug": "costume-slimfit-noir",
    "description": "Un costume est un ensemble de vêtements coordonnés, généralement composé d'une veste et d'un pantalon...",
    "brand": "Maison Élégance",
    "price_per_day": 49.99,
    "featured_image_url": "https://images.unsplash.com/photo-1520975914124-2a4f9a3f91f1?w=1200&q=80",
    "is_active": true,
    "available": true,
    "category": {
      "id": 1,
      "name": "General"
    }
  }
}
```

### 3. Get Costume Images
**Endpoint:** `GET /api/costumes/{id}/images`

**Example:** `GET /api/costumes/1/images`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "costume_id": 1,
      "image_url": "https://images.unsplash.com/photo-1520975914124-2a4f9a3f91f1?w=1200&q=80",
      "position": 0
    }
  ]
}
```

### 4. Get Costume Sizes
**Endpoint:** `GET /api/costumes/{id}/sizes`

**Example:** `GET /api/costumes/1/sizes`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "costume_id": 1,
      "size_label": "M",
      "quantity_available": 3
    },
    {
      "id": 2,
      "costume_id": 1,
      "size_label": "L",
      "quantity_available": 2
    }
  ]
}
```

### 5. User Registration
**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "+212600000000"
}
```

**Response:**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+212600000000",
    "role": "customer"
  },
  "token": "1|abcdef123456..."
}
```

### 6. User Login
**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "customer"
  },
  "token": "2|ghijkl789012..."
}
```

### 7. Create Rental (Authenticated)
**Endpoint:** `POST /api/rentals`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "costume_id": 1,
  "size_id": 1,
  "start_date": "2025-01-15",
  "end_date": "2025-01-17"
}
```

**Response:**
```json
{
  "message": "Rental created successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "costume_id": 1,
    "size_id": 1,
    "start_date": "2025-01-15",
    "end_date": "2025-01-17",
    "price_total": 99.98,
    "status": "pending",
    "qr_code": "RENTAL-1-ABC123"
  }
}
```

### 8. Get User Rentals (Authenticated)
**Endpoint:** `GET /api/rentals`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "costume": {
        "id": 1,
        "name": "Costume Slim-Fit Noir",
        "featured_image_url": "https://..."
      },
      "start_date": "2025-01-15",
      "end_date": "2025-01-17",
      "price_total": 99.98,
      "status": "pending",
      "qr_code": "RENTAL-1-ABC123"
    }
  ]
}
```

## 📱 Mobile App Integration Examples

### Flutter/Dart Example
```dart
// Get all costumes
Future<List<Costume>> getCostumes() async {
  final response = await http.get(
    Uri.parse('http://127.0.0.1:8000/api/costumes'),
  );
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    return (data['data'] as List)
        .map((item) => Costume.fromJson(item))
        .toList();
  }
  throw Exception('Failed to load costumes');
}

// Create rental
Future<Rental> createRental(int costumeId, int sizeId, String startDate, String endDate, String token) async {
  final response = await http.post(
    Uri.parse('http://127.0.0.1:8000/api/rentals'),
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer $token',
    },
    body: json.encode({
      'costume_id': costumeId,
      'size_id': sizeId,
      'start_date': startDate,
      'end_date': endDate,
    }),
  );
  
  if (response.statusCode == 201) {
    final data = json.decode(response.body);
    return Rental.fromJson(data['data']);
  }
  throw Exception('Failed to create rental');
}
```

### React Native Example
```javascript
// Get all costumes
const getCostumes = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/costumes');
    const data = await response.json();
    return data.data;
  } catch (error) {
    console.error('Error fetching costumes:', error);
  }
};

// Login user
const login = async (email, password) => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email, password }),
    });
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Login error:', error);
  }
};
```

## 🔧 Start the Server

To start the Laravel development server:

```bash
cd "D:\emsi\programmation mobile\costumeapp\backend"
php artisan serve
```

Server will be available at: `http://127.0.0.1:8000`

## 📝 Notes

- All costumes are formal suits (veste + pantalon + gilet optionnel)
- Images are hosted on Unsplash (external URLs)
- Prices are in EUR per day
- Authentication uses Laravel Sanctum (token-based)
- All endpoints return JSON responses
- Client-side only (no admin features needed)

## 🎯 Next Steps for Mobile App

1. **Display Costumes List**: Use `GET /api/costumes` to show all 15 costumes
2. **Show Costume Details**: Use `GET /api/costumes/{id}` for detail page
3. **User Authentication**: Implement register/login flow
4. **Create Rentals**: Allow users to rent costumes with date selection
5. **View Rental History**: Show user's past and current rentals
