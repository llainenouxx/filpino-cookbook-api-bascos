# Filipino Cookbook API

The Filipino Cookbook API is a RESTful API designed to provide programmatic access to a collection of Filipino food recipes. It allows users to retrieve information about various Filipino dishes, including their ingredients, categories, origins, and cooking instructions. The API also supports searching for dishes by name and adding new food entries.

## API Description

This API serves as a digital repository for Filipino culinary information. It is built using PHP with the Slim Framework and utilizes a MySQL database to store food, category, origin, and ingredient data. The API is secured with a simple token-based authentication mechanism for its core functionalities.

## Intended Users

This API is intended for:
*   **Developers** who want to integrate Filipino recipe data into their applications (e.g., mobile apps, websites).
*   **Food bloggers or content creators** looking for structured data on Filipino cuisine.
*   **Students or researchers** studying culinary arts or database management.

## Main Functions

The API provides the following core functionalities:
*   Retrieve a list of all Filipino food dishes.
*   Fetch detailed information for a specific food dish by its ID.
*   Search for food dishes by name.
*   List all available food categories.
*   List all available ingredients.
*   Add new food entries to the cookbook.

## Features

*   **Comprehensive Data**: Provides detailed information on food names, categories, origins, instructions, and ingredients.
*   **Search Capability**: Allows users to search for dishes by partial or full names.
*   **Structured Data**: Returns data in JSON format, making it easy to consume by various applications.
*   **Extensible**: Designed to be easily extended with more food items, categories, and origins.
*   **Authentication**: Secures API endpoints with a token-based authentication system.

## Technologies Used

*   **PHP**: The primary programming language.
*   **Slim Framework**: A micro-framework for building RESTful APIs in PHP.
*   **MySQL**: The relational database management system used for data storage.
*   **Composer**: Dependency manager for PHP.

## Installation Instructions

To set up the Filipino Cookbook API on your local machine, follow these steps:

1.  **Clone the repository**:
    ```bash
    git clone <repository_url>
    cd filipino-cookbook-api
    ```
    *(Note: Replace `<repository_url>` with the actual repository URL if available.)*

2.  **Install PHP dependencies**:
    Navigate to the project root directory and run Composer to install the required libraries:
    ```bash
    composer install
    ```

3.  **Configure your web server**:
    Point your web server (e.g., Apache, Nginx) document root to the `public/` directory within the project. Ensure that URL rewriting is enabled to allow Slim to handle routing correctly.

## Database Setup

1.  **Create a MySQL database**:
    Create a new database named `filipino_cookbook_api`.

2.  **Import the SQL schema and data**:
    Import the provided `filipino_cookbook_api.sql` file into your newly created database. This file contains the table structures and initial data.
    ```bash
    mysql -u your_username -p filipino_cookbook_api < filipino_cookbook_api.sql
    ```
    *(Note: Replace `your_username` with your MySQL username. You will be prompted for your password.)*

3.  **Update database connection details (if necessary)**:
    The database connection details are hardcoded in `public/index.php` within the `getDB()` function. If your MySQL setup uses different credentials, update the `$host`, `$db`, `$user`, and `$pass` variables accordingly.
    ```php
    function getDB() {
        $host = 'localhost';
        $db   = 'filipino_cookbook_api';
        $user = 'root'; // Your MySQL username
        $pass = '';     // Your MySQL password
        // ... other connection details
    }
    ```

## Base URL

The base URL for the API endpoints is configured as `/filipino-cookbook-api/public/api`. For example, if your application is hosted at `http://localhost`, the full base URL for the API would be:

`http://localhost/filipino-cookbook-api/public/api`

## Authentication

Access to the `/api` endpoints requires token-based authentication. A `Bearer` token must be included in the `Authorization` header of your requests.

**Expected Token**:
`Bearer dmmmsu-cookbook-token-2026`

**Example Request Header**:
`Authorization: Bearer dmmmsu-cookbook-token-2026`

Requests to the root path (`/`) do not require authentication.

## Endpoints

All API endpoints are prefixed with `/api` and require authentication unless otherwise specified.

| Method | Endpoint | Description | Authentication Required |
|---|---|---|---|
| `GET` | `/` | Welcome message and API usage note. | No |
| `GET` | `/api/foods` | Retrieve a list of all food dishes with their categories, origins, and ingredients. | Yes |
| `GET` | `/api/foods/{id}` | Retrieve detailed information for a specific food dish by its `food_id`. | Yes |
| `GET` | `/api/foods/search/{name}` | Search for food dishes by `name` (case-insensitive, partial match). | Yes |
| `GET` | `/api/categories` | Retrieve a list of all food categories. | Yes |
| `GET` | `/api/ingredients` | Retrieve a list of all ingredients. | Yes |
| `POST` | `/api/foods` | Add a new food dish. Requires `food_name`, `category_id`, `origin_id`, `instructions`, and optionally `ingredient_ids` (array). | Yes |

### Endpoint Details

#### `GET /api/foods`

*   **Description**: Returns an array of all food dishes, each including `food_id`, `food_name`, `category_name`, `origin_name`, `instructions`, and an array of `ingredients`.
*   **Example Response**:
    ```json
    [
        {
            "food_id": "1",
            "food_name": "Adobo",
            "category_name": "Main Dish",
            "origin_name": "Philippines",
            "instructions": "Marinate the meat with soy sauce, vinegar, garlic, bay leaves, and peppercorn. Simmer until the meat becomes tender and the sauce is reduced.",
            "ingredients": [
                "Chicken or pork",
                "Soy sauce",
                "Vinegar",
                "Garlic",
                "Bay leaves",
                "Peppercorn",
                "Cooking oil"
            ]
        }
    ]
    ```

#### `GET /api/foods/{id}`

*   **Description**: Returns a single food dish object matching the provided `food_id`.
*   **Parameters**:
    *   `id` (path parameter): The unique identifier of the food dish.
*   **Example Response**:
    ```json
    {
        "food_id": "1",
        "food_name": "Adobo",
        "category_name": "Main Dish",
        "origin_name": "Philippines",
        "instructions": "Marinate the meat with soy sauce, vinegar, garlic, bay leaves, and peppercorn. Simmer until the meat becomes tender and the sauce is reduced.",
        "ingredients": [
            "Chicken or pork",
            "Soy sauce",
            "Vinegar",
            "Garlic",
            "Bay leaves",
            "Peppercorn",
            "Cooking oil"
        ]
    }
    ```
*   **Error Response (404 Not Found)**:
    ```json
    {
        "status": "error",
        "message": "Food not found"
    }
    ```

#### `GET /api/foods/search/{name}`

*   **Description**: Returns an array of food dishes whose names contain the provided `name` string (case-insensitive).
*   **Parameters**:
    *   `name` (path parameter): The search string for the food name.
*   **Example Response**:
    ```json
    [
        {
            "food_id": "1",
            "food_name": "Adobo",
            "category_name": "Main Dish",
            "origin_name": "Philippines",
            "instructions": "Marinate the meat with soy sauce, vinegar, garlic, bay leaves, and peppercorn. Simmer until the meat becomes tender and the sauce is reduced.",
            "ingredients": [
                "Chicken or pork",
                "Soy sauce",
                "Vinegar",
                "Garlic",
                "Bay leaves",
                "Peppercorn",
                "Cooking oil"
            ]
        }
    ]
    ```

#### `GET /api/categories`

*   **Description**: Returns an array of all food categories.
*   **Example Response**:
    ```json
    [
        {
            "category_id": "1",
            "category_name": "Appetizer"
        },
        {
            "category_id": "2",
            "category_name": "Dessert"
        }
    ]
    ```

#### `GET /api/ingredients`

*   **Description**: Returns an array of all ingredients.
*   **Example Response**:
    ```json
    [
        {
            "ingredient_id": "1",
            "ingredient_name": "Annatto oil"
        },
        {
            "ingredient_id": "2",
            "ingredient_name": "Bagoong"
        }
    ]
    ```

#### `POST /api/foods`

*   **Description**: Adds a new food dish to the database. Requires a JSON request body.
*   **Request Body**:
    ```json
    {
        "food_name": "New Dish Name",
        "category_id": 1, 
        "origin_id": 4,
        "instructions": "Detailed cooking instructions.",
        "ingredient_ids": [1, 2, 3] // Optional array of existing ingredient IDs
    }
    ```
*   **Example Success Response (201 Created)**:
    ```json
    {
        "status": "success",
        "message": "Food added successfully."
    }
    ```
*   **Error Response (500 Internal Server Error)**:
    ```json
    {
        "status": "error",
        "message": "Failed to add food: <error_details>"
    }
    ```

## HTTP Status Codes

The API returns standard HTTP status codes to indicate the success or failure of a request.

| Status Code | Meaning |
|---|---|
| `200` | OK |
| `201` | Created |
| `400` | Bad Request |
| `401` | Unauthorized |
| `404` | Not Found |
| `500` | Internal Server Error |

## Developer Information
*    Name: Bascos, Adellaine Nicole D.
*    Course & Section: BSInfoTech 4-A
*    Github Username: llainenouxx
*    Repository: https://github.com/llainenouxx/filpino-cookbook-api-bascos
*    Date Completed: August 2, 2026
