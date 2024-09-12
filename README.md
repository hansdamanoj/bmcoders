# Mobile App Reels API

## Overview

This project provides a set of APIs to manage video reels in a mobile application. The APIs allow users to upload reels, save their playback progress, and retrieve reel information. The application uses Laravel 10 for the backend, with JWT for authentication.

## Features

- **Upload Reels**: Allows users to upload video reels with optional thumbnails.
- **Save Playback Progress**: Saves the last second played of a reel for each user.
- **Retrieve Playback Progress**: Fetches the last played second of a reel for a user.
- **Get All Reels**: Retrieves a list of all available reels.
- **Get Single Reel**: Retrieves details of a specific reel by its ID.

## Installation

### Prerequisites

- PHP 8.1 or higher
- Composer
- Laravel 10

### Setup

1. **Clone the Repository**

   ```bash
   git clone https://github.com/hansdamanoj/bmcoders.git
   cd your-repo
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Configure Environment**

   Copy the example environment file and update it with your database and JWT settings.

   ```bash
   cp .env.example .env
   ```

   Edit `.env` to match your configuration:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   JWT_SECRET=your_jwt_secret
   ```

4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**

   ```bash
   php artisan migrate
   ```

6. **Seed Sample Data**

   ```bash
   php artisan db:seed
   ```

## API Endpoints

### 1. Upload Reel

- **URL**: `/api/reels/upload`
- **Method**: `POST`
- **Headers**: 
  - `Authorization: Bearer {token}` (JWT token)
- **Form Data**:
  - `title` (string, required): Title of the reel.
  - `video` (file, required): Video file (mp4, mov, avi, wmv).
  - `thumbnail` (file, optional): Thumbnail image (jpeg, png, jpg, gif).

- **Response**:
  ```json
  {
    "message": "Reel uploaded successfully",
    "reel": {
      "id": 1,
      "user_id": 1,
      "title": "Sample Reel",
      "video_path": "path/to/video.mp4",
      "thumbnail_path": "path/to/thumbnail.jpg",
      "created_at": "2024-09-15T12:34:56",
      "updated_at": "2024-09-15T12:34:56"
    }
  }
  ```

### 2. Save Last Second

- **URL**: `/api/reels/progress/save`
- **Method**: `POST`
- **Headers**: 
  - `Authorization: Bearer {token}` (JWT token)
- **Body**:
  - `user_id` (integer, required): ID of the user.
  - `reel_id` (integer, required): ID of the reel.
  - `last_second` (integer, required): Last second played.

- **Response**:
  ```json
  {
    "status": "success",
    "message": "Last played second saved successfully"
  }
  ```

### 3. Get Last Second

- **URL**: `/api/reels/progress/{reel}/{user}`
- **Method**: `GET`
- **Headers**: 
  - `Authorization: Bearer {token}` (JWT token)

- **Response**:
  ```json
  {
    "status": "success",
    "last_second": 123
  }
  ```

### 4. Get All Reels

- **URL**: `/api/reels`
- **Method**: `GET`

- **Response**:
  ```json
  {
    "status": "success",
    "reels": [
      {
        "id": 1,
        "user_id": 1,
        "title": "Sample Reel",
        "video_path": "path/to/video.mp4",
        "thumbnail_path": "path/to/thumbnail.jpg",
        "created_at": "2024-09-15T12:34:56",
        "updated_at": "2024-09-15T12:34:56"
      }
    ]
  }
  ```

### 5. Get Single Reel

- **URL**: `/api/reels/{id}`
- **Method**: `GET`

- **Response**:
  ```json
  {
    "status": "success",
    "reel": {
      "id": 1,
      "user_id": 1,
      "title": "Sample Reel",
      "video_path": "path/to/video.mp4",
      "thumbnail_path": "path/to/thumbnail.jpg",
      "created_at": "2024-09-15T12:34:56",
      "updated_at": "2024-09-15T12:34:56"
    }
  }
  ```

## Testing

You can use tools like Postman or cURL to test the API endpoints. Ensure to include the `Authorization` header with a valid JWT token.

## Contributing

If you would like to contribute to this project, please fork the repository and submit a pull request. Ensure that your code adheres to the coding standards and includes appropriate tests.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.