# Stadium Booking API

A simple, well-structured API for booking pitches in stadiums, built with Laravel.

## Core Features

- List available booking slots for a pitch by date and duration (60 or 90 mins).
- Book a pitch with robust overbooking and race-condition prevention.
- Built with a clean Service-Repository architecture.

## Setup Instructions

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/MohammedEl-Saeed/SafasoftTask.git
    cd SafasoftTask
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    ```

3.  **Environment Configuration**
    - Copy the example environment file:
      ```bash
      cp .env.example .env
      ```
    - Generate an application key:
      ```bash
      php artisan key:generate
      ```
    - Configure your database connection variables (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in the `.env` file.

4.  **Database Migration & Seeding**
    - Run the migrations to create the database tables and populate them with sample data:
      ```bash
      php artisan migrate --seed
      ```

5.  **Run the Application**
    - Start the local development server:
      ```bash
      php artisan serve
      ```
    - The API will be available at `http://127.0.0.1:8000/ui/slots`.
    - A simple UI for testing is available at the root URL (`/`).

## API Endpoints

### 1. List Available Slots

- **Endpoint:** `GET /api/pitches/{pitch}/slots`
- **Description:** Retrieves a list of all available time slots for a specific pitch on a given date.
- **Query Parameters:**
  - `date` (string, required): The date to check for availability. Format: `YYYY-MM-DD`.
  - `duration` (integer, required): The desired slot duration in minutes. Must be `60` or `90`.
- **Example Request:**
  `GET /api/pitches/{pitch}/slots?date=2025-10-28&duration=90`
- **Success Response (200 OK):**
  ```json
  {
      "data": [
          {
              "start_time": "2025-10-28 09:00:00",
              "end_time": "2025-10-28 10:30:00"
          },
          {
              "start_time": "2025-10-28 10:30:00",
              "end_time": "2025-10-28 12:00:00"
          }
      ]
  }