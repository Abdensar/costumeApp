# Costume Rental Android Application

A complete Android mobile application for costume rental management with modern UI/UX, clean architecture, and seamless API integration.

## 📱 Project Overview

This is a customer-facing Android app built with Java that allows users to:
- Browse and search costumes by category, size, and price
- View detailed costume information with images
- Book costumes by selecting rental dates
- View and manage their rental bookings
- View profile information and logout

## 🏗 Architecture

**Pattern**: MVVM (Model-View-ViewModel)
**Language**: Java
**Min SDK**: 24 (Android 7.0)
**Target SDK**: 34 (Android 14)

### Project Structure

```
app/src/main/java/com/example/frontend/
├── MainActivity.java                      # Main activity with bottom navigation
├── activities/
│   ├── LoginActivity.java                # User login
│   ├── RegisterActivity.java             # User registration
│   └── CostumeDetailActivity.java        # Costume details and booking
├── fragments/
│   ├── HomeFragment.java                 # Home screen with categories and costumes
│   ├── BrowseFragment.java               # Browse all costumes
│   ├── MyRentalsFragment.java            # User's rental history
│   └── ProfileFragment.java              # User profile and logout
├── adapters/
│   ├── CostumeAdapter.java               # RecyclerView adapter for costumes
│   ├── CategoryAdapter.java              # RecyclerView adapter for categories
│   └── RentalAdapter.java                # RecyclerView adapter for rentals
├── models/
│   ├── User.java                         # User data model
│   ├── Category.java                     # Category data model
│   ├── Costume.java                      # Costume data model
│   ├── Rental.java                       # Rental data model
│   └── *Request.java & *Response.java    # API request/response models
├── network/
│   ├── ApiService.java                   # Retrofit API interface
│   └── RetrofitClient.java               # Retrofit client setup
└── utils/
    ├── TokenManager.java                 # JWT token management
    ├── DateUtils.java                    # Date formatting utilities
    ├── PriceUtils.java                   # Price formatting utilities
    ├── ValidationUtils.java              # Input validation utilities
    └── Constants.java                    # App constants
```

## 🔧 Technologies & Libraries

### Core Dependencies
- **Material Design 3**: Modern UI components
- **Retrofit 2**: REST API communication
- **OkHttp**: HTTP client with logging
- **Glide**: Image loading and caching
- **RecyclerView**: Efficient list rendering
- **SwipeRefreshLayout**: Pull-to-refresh functionality
- **ZXing**: QR code generation (ready for implementation)

### Key Features Implemented

✅ **Authentication**
- User login with email and password
- User registration with validation
- JWT token storage in SharedPreferences
- Automatic logout on session expiry

✅ **Costume Browsing**
- Grid view of all costumes
- Category filtering (horizontal scroll)
- Image loading with Glide
- Price display per day
- Availability status badges

✅ **Costume Booking**
- Date picker for start/end dates
- Automatic price calculation
- Date validation (start date must be today or future)
- Booking confirmation dialog
- API integration for creating rentals

✅ **My Rentals**
- List view of all user rentals
- Status badges (pending, confirmed, cancelled, returned)
- Rental details with dates and total price
- Pull-to-refresh to update data

✅ **User Profile**
- Display user information (name, email, phone)
- Logout with confirmation dialog
- Token cleanup on logout

## 🚀 Setup Instructions

### 1. Backend Setup

Make sure the Laravel backend is running:

```bash
cd backend
php artisan serve --host=0.0.0.0
```

The backend should be accessible at `http://localhost:8000` or `http://YOUR_IP:8000`

### 2. Configure API Base URL

The app is pre-configured for Android Emulator. Update if needed:

**For Android Emulator** (default):
- Base URL: `http://10.0.2.2:8000/api/`
- Already set in `RetrofitClient.java`

**For Physical Device**:
- Update `RetrofitClient.java`:
```java
private static final String BASE_URL = "http://YOUR_COMPUTER_IP:8000/api/";
```

**For Constants.java**:
- Update `Constants.java`:
```java
public static final String BASE_URL = "http://YOUR_COMPUTER_IP:8000/";
```

### 3. Sync Gradle

Open the project in Android Studio and sync Gradle:
- Click **File** → **Sync Project with Gradle Files**
- Wait for dependencies to download

### 4. Run the App

1. Connect an Android device or start an emulator
2. Click **Run** (green play button) or press `Shift + F10`
3. Select your device/emulator
4. Wait for the app to build and install

## 🎨 Color Scheme

- **Primary**: #6200EE (Purple)
- **Primary Variant**: #3700B3
- **Secondary**: #03DAC6 (Teal)
- **Background**: #FFFFFF
- **Surface**: #F5F5F5
- **Error**: #B00020

### Status Colors
- **Pending**: #FFA726 (Orange)
- **Confirmed**: #66BB6A (Green)
- **Cancelled**: #EF5350 (Red)
- **Returned**: #42A5F5 (Blue)

## 📝 Default Test Credentials

Use these credentials from the backend seeder:

**Customer Account**:
- Email: `customer@costumeapp.com`
- Password: `password123`

**Owner Account** (for testing API):
- Email: `owner@costumeapp.com`
- Password: `password123`

## 🔑 Key Implementation Details

### Authentication Flow

1. App starts at `LoginActivity` (defined as launcher in manifest)
2. User enters credentials
3. API call to `/api/login` with email and password
4. On success:
   - Token and user info saved to SharedPreferences
   - Navigate to `MainActivity` with fragments
5. On subsequent launches:
   - `MainActivity` checks if token exists
   - If yes, show home screen
   - If no, redirect to login

### Token Management

```java
// Save token after login
TokenManager.saveToken(context, token);
TokenManager.saveUserInfo(context, id, name, email, phone);

// Get auth header for API calls
String authHeader = TokenManager.getAuthHeader(context);
// Returns: "Bearer YOUR_TOKEN"

// Check if logged in
boolean isLoggedIn = TokenManager.isLoggedIn(context);

// Clear token on logout
TokenManager.clearToken(context);
```

### API Calls Example

```java
// Get all costumes
Call<List<Costume>> call = RetrofitClient.getApiService()
    .getCostumes(null, null, null, null, null);

// Get costumes by category
Call<List<Costume>> call = RetrofitClient.getApiService()
    .getCostumes(categoryId, null, null, null, null);

// Get user's rentals (requires auth)
String token = TokenManager.getAuthHeader(context);
Call<List<Rental>> call = RetrofitClient.getApiService()
    .getMyRentals(token);

// Create rental booking (requires auth)
String token = TokenManager.getAuthHeader(context);
RentalRequest request = new RentalRequest(costumeId, startDate, endDate);
Call<Rental> call = RetrofitClient.getApiService()
    .createRental(token, request);
```

### Date Formatting

```java
// Format API date (YYYY-MM-DD) to display format (MMM dd, yyyy)
String displayDate = DateUtils.formatDate("2025-01-15");
// Returns: "Jan 15, 2025"

// Calculate days between dates
int days = DateUtils.calculateDays("2025-01-15", "2025-01-18");
// Returns: 4 (inclusive)

// Get current date in API format
String today = DateUtils.getCurrentDate();
// Returns: "2025-12-18"
```

### Price Formatting

```java
// Format price with currency
String price = PriceUtils.formatPriceWithCurrency(15.00);
// Returns: "$15.00"

// Calculate total price
double total = PriceUtils.calculateTotalPrice(15.00, 4);
// Returns: 60.0
```

## 🎯 Implemented Screens

### 1. Login Screen (`LoginActivity`)
- Email and password input fields
- Password visibility toggle
- Form validation
- Loading indicator during API call
- Link to register screen

### 2. Register Screen (`RegisterActivity`)
- Name, email, phone, password, confirm password fields
- Input validation
- Loading indicator
- Link back to login screen

### 3. Home Screen (`HomeFragment`)
- Welcome message with user name
- Search bar (UI ready)
- Horizontal categories list
- Grid of featured costumes (2 columns)
- Pull-to-refresh

### 4. Browse Screen (`BrowseFragment`)
- Grid of all costumes
- Pull-to-refresh
- Click to view details

### 5. Costume Detail Screen (`CostumeDetailActivity`)
- Large costume image
- Name, category, size, price per day
- Availability status
- Description
- Date pickers for start and end dates
- Automatic total price calculation
- Book now button with confirmation dialog

### 6. My Rentals Screen (`MyRentalsFragment`)
- List of all user rentals
- Status badges with colors
- Rental dates and total price
- Pull-to-refresh

### 7. Profile Screen (`ProfileFragment`)
- User avatar/icon
- User information (name, email, phone)
- Logout button with confirmation

## 🔄 Data Flow Example: Booking a Costume

1. User browses costumes in `HomeFragment`
2. User clicks a costume card
3. `CostumeDetailActivity` opens with costume ID
4. Activity loads costume details from API
5. User selects start date using DatePicker
6. User selects end date (must be after start date)
7. App calculates total: `days × price_per_day`
8. User clicks "Book Now"
9. Confirmation dialog shows booking summary
10. User confirms
11. API call to `/api/rentals` with:
    - `costume_id`
    - `start_date` (YYYY-MM-DD)
    - `end_date` (YYYY-MM-DD)
    - Auth header with Bearer token
12. On success:
    - Show success toast
    - Close activity
    - User returns to previous screen
13. User navigates to "My Rentals" to see new booking

## 🐛 Troubleshooting

### Cannot connect to backend API

**Problem**: Network error or connection refused

**Solutions**:
1. Make sure backend server is running: `php artisan serve --host=0.0.0.0`
2. Check if `usesCleartextTraffic="true"` is in AndroidManifest.xml
3. For emulator: Use `http://10.0.2.2:8000/api/`
4. For physical device: Use your computer's IP address
5. Make sure device/emulator and computer are on same network

### Images not loading

**Problem**: Costume images show placeholder

**Solutions**:
1. Ensure backend storage is publicly accessible
2. Check image URLs in API response
3. Update `Constants.STORAGE_URL` to match your backend
4. Run `php artisan storage:link` in backend

### Session expired error

**Problem**: 401 Unauthorized on API calls

**Solutions**:
1. Token might be expired or invalid
2. Logout and login again
3. Check if token is being sent: Look at Logcat for API requests

### App crashes on login

**Problem**: NullPointerException or NetworkOnMainThreadException

**Solutions**:
1. Check Logcat for detailed error
2. Ensure all required fields are in API response
3. Verify API response structure matches model classes

## 📊 API Endpoints Used

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout

### Categories
- `GET /api/categories` - Get all categories

### Costumes
- `GET /api/costumes` - Get all costumes (with optional filters)
- `GET /api/costumes/{id}` - Get specific costume details

### Rentals
- `POST /api/rentals` - Create new rental booking
- `GET /api/my-rentals` - Get user's rental history

### Profile
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update user profile (UI not implemented yet)

## 🎓 Next Steps / Future Enhancements

### Features to Implement
- [ ] Search functionality in home screen
- [ ] Filter costumes by size, price range
- [ ] Sort costumes (price, name, date)
- [ ] QR code display for confirmed rentals
- [ ] Edit profile screen
- [ ] Change password functionality
- [ ] Rental detail screen with QR code
- [ ] Cancel rental functionality
- [ ] Image slider for multiple costume images
- [ ] Favorite/wishlist costumes
- [ ] Push notifications for rental status changes
- [ ] Dark mode support
- [ ] Offline caching with Room database
- [ ] Rating and review system
- [ ] Payment integration

### Code Improvements
- [ ] Implement ViewModels for fragments
- [ ] Use LiveData for reactive updates
- [ ] Add data binding
- [ ] Implement proper error handling with sealed classes
- [ ] Add unit tests
- [ ] Add UI tests with Espresso
- [ ] Optimize image loading with custom Glide config
- [ ] Add shimmer loading effect
- [ ] Implement pagination for costume list
- [ ] Add input field animations

## 📱 App Screenshots

(Screenshots will be here once you run the app)

## 🤝 Contributing

This is a complete implementation based on the requirements. To extend:

1. Add new activities in `activities/` package
2. Add new fragments in `fragments/` package
3. Create corresponding layout files in `res/layout/`
4. Register activities in `AndroidManifest.xml`
5. Update `ApiService.java` for new API endpoints
6. Create new model classes in `models/` package

## 📄 License

This project is for educational purposes.

## 👨‍💻 Developer Notes

### Important Files to Know
- **RetrofitClient.java**: Change BASE_URL here for different environments
- **TokenManager.java**: All authentication token management
- **Constants.java**: App-wide constants (URLs, status codes, etc.)
- **MainActivity.java**: Entry point after login, manages fragments
- **AndroidManifest.xml**: All activities must be registered here

### Testing Tips
1. Use Logcat to see API requests and responses
2. Check OkHttp logging for detailed network calls
3. Use Android Studio's Network Profiler
4. Test on both emulator and physical device
5. Test with different network conditions (slow, offline)

### Production Checklist
- [ ] Update BASE_URL to production server
- [ ] Remove or reduce OkHttp logging
- [ ] Add ProGuard rules for Retrofit and Glide
- [ ] Enable code minification in build.gradle
- [ ] Test on various Android versions
- [ ] Test on different screen sizes
- [ ] Add proper app icon
- [ ] Add splash screen
- [ ] Implement proper error tracking (Crashlytics)
- [ ] Add analytics (Firebase Analytics)

---

**Ready to run!** 🚀

The application is fully functional and ready to test. Make sure your Laravel backend is running, then build and run the app in Android Studio.

For support or questions, check the code comments or review the implementation files.

