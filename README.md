# 🚗 RideFlow

A clean, production-ready **ride-booking REST API** and **Admin Panel** built with Laravel. This project demonstrates a complete ride-sharing backend system with geospatial queries, dual-confirmation completion logic, and a modern admin interface.

---

## ✨ Features

| Module | Capabilities |
|--------|-------------|
| **Passenger API** | Request rides, approve drivers, mark rides complete |
| **Driver API** | Update location, find nearby rides (geospatial), request rides, complete rides |
| **Admin Panel** | Monitor all rides with premium glassmorphism UI |
| **Business Logic** | Dual-confirmation: Ride is marked `completed` only when **both** passenger and driver confirm |

---

## 🔄 API Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              RIDEFLOW WORKFLOW                              │
└─────────────────────────────────────────────────────────────────────────────┘

    PASSENGER                         SYSTEM                           DRIVER
        │                                │                                │
        │  1. POST /passenger/rides      │                                │
        │  ─────────────────────────────>│                                │
        │  (pickup & destination coords) │                                │
        │                                │                                │
        │                                │<─────────────────────────────  │
        │                                │  2. GET /driver/rides/nearby   │
        │                                │  (driver sees available rides) │
        │                                │                                │
        │                                │<─────────────────────────────  │
        │                                │  3. POST /driver/rides/{id}/   │
        │                                │     request                    │
        │                                │  (driver requests the ride)    │
        │                                │                                │
        │  4. POST /passenger/rides/     │                                │
        │     {id}/approve-driver        │                                │
        │  ─────────────────────────────>│                                │
        │  (passenger picks a driver)    │                                │
        │                                │                                │
        │                                │         🚗 RIDE IN PROGRESS    │
        │                                │                                │
        │  5. POST /passenger/rides/     │                                │
        │     {id}/complete              │                                │
        │  ─────────────────────────────>│                                │
        │                                │                                │
        │                                │<─────────────────────────────  │
        │                                │  6. POST /driver/rides/{id}/   │
        │                                │     complete                   │
        │                                │                                │
        │                                │                                │
        └────────────────────────────────┼────────────────────────────────┘
                                         │
                                 ✅ RIDE COMPLETED
                           (only after BOTH confirm)
```

---

## 📚 API Reference

### Passenger Endpoints

| Method | Endpoint | Description | Body |
|--------|----------|-------------|------|
| `POST` | `/api/passenger/rides` | Create a new ride request | `passenger_id`, `pickup_lat`, `pickup_lng`, `dest_lat`, `dest_lng` |
| `POST` | `/api/passenger/rides/{id}/approve-driver` | Approve a driver for the ride | `driver_id`, `passenger_id` |
| `POST` | `/api/passenger/rides/{id}/complete` | Mark ride as completed (passenger side) | – |

### Driver Endpoints

| Method | Endpoint | Description | Body/Query |
|--------|----------|-------------|------------|
| `POST` | `/api/driver/location` | Update driver's current location | `driver_id`, `latitude`, `longitude` |
| `GET` | `/api/driver/rides/nearby` | Fetch nearby pending rides | `?latitude=...&longitude=...&radius=...` |
| `POST` | `/api/driver/rides/{id}/request` | Request to accept a ride | `driver_id` |
| `POST` | `/api/driver/rides/{id}/complete` | Mark ride as completed (driver side) | – |

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Database**: SQLite (easily switchable to MySQL/PostgreSQL)
- **API**: RESTful JSON APIs
- **Admin UI**: Blade templates with Tailwind CSS & glassmorphism design
- **Icons**: Lucide Icons
- **Testing**: PHPUnit Feature Tests
- **API Testing**: Postman Collection included

---

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (optional, for asset compilation)

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/yourusername/rideflow.git
cd rideflow

# 2. Install PHP dependencies
composer install

# 3. Environment setup
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create SQLite database
touch database/database.sqlite

# 6. Run migrations & seed sample data
php artisan migrate
php artisan db:seed --class=RideSeeder

# 7. Start the development server
php artisan serve
```

The application will be available at `http://localhost:8000`

---

## 🎛️ Admin Panel

Access the admin dashboard at:
```
http://localhost:8000/admin/rides
```

Features:
- View all rides with status indicators
- Click on any ride to see detailed information
- Premium dark-themed UI with glassmorphism effects
- Real-time status badges (Pending, Accepted, Completed)

---

## 📦 Postman Collection

A complete Postman collection is included for API testing:

```
RideFlow.postman_collection.json
```

Import this file into Postman to test all endpoints with pre-configured requests.

---

## 🧪 Testing

Run the feature tests:

```bash
php artisan test
```

Or run specific test files:

```bash
php artisan test --filter=RideFlowTest
```

---

## 📁 Project Structure

```
rideflow/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   └── RideController.php      # Admin panel controller
│   │   └── Api/
│   │       ├── DriverController.php    # Driver API endpoints
│   │       └── PassengerController.php # Passenger API endpoints
│   └── Models/
│       ├── Ride.php                    # Ride model
│       ├── RideProposal.php            # Driver proposals for rides
│       └── User.php                    # User model (passengers & drivers)
├── database/
│   ├── migrations/                     # Database schema
│   └── seeders/
│       └── RideSeeder.php              # Sample data seeder
├── resources/views/admin/              # Admin panel Blade views
├── routes/
│   ├── api.php                         # API routes
│   └── web.php                         # Web routes (admin panel)
└── tests/Feature/
    └── RideFlowTest.php                # Feature tests
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">
  Built with ❤️ using Laravel
</p>
