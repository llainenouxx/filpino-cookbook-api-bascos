# Filipino Cookbook API

## API Description

The **Filipino Cookbook API** is a robust and secured web service that provides programmatic access to a curated collection of Filipino food recipes. It offers detailed information including ingredients, preparation instructions, and geographical origins for various traditional dishes.

*   **Purpose of the API:** To serve as a centralized, digital repository of Filipino culinary heritage, enabling developers to easily integrate authentic recipe data into their applications.
*   **Type of Information Provided:** The API provides structured data in JSON format, encompassing dish names, categories (e.g., Main Dish, Soup, Dessert), origins (e.g., Bicol Region, Bacolod), step-by-step instructions, and comprehensive ingredient lists.
*   **Intended Users:** Developers, students, culinary researchers, and food enthusiasts who wish to build or enhance platforms related to Filipino cuisine.
*   **Main Functions of the API:**
    *   **Retrieve All Recipes:** Access the full list of available Filipino dishes.
    *   **Specific Dish Lookup:** Fetch detailed data for a single dish using its unique ID.
    *   **Search Capability:** Find dishes by name using keyword matching.
    *   **Metadata Access:** Retrieve lists of all food categories and ingredients available in the database.
    *   **Data Contribution:** Add new recipes to the database (restricted to authenticated users).
*   **Technologies Used:** Built with **PHP** and the **Slim Framework**, utilizing a **MySQL** relational database for persistent storage and **Composer** for dependency management.

---

## Features

*   **Token-Based Security:** Protects sensitive operations and ensures authorized access to the API's core functionalities.
*   **Relational Data Structure:** Efficiently links dishes to their respective categories, origins, and multiple ingredients.
*   **JSON-Formatted Responses:** Ensures compatibility with a wide range of frontend frameworks and client-side applications.
*   **Search Optimization:** Includes a flexible search endpoint for improved user experience.
*   **Transaction-Safe Writes:** Uses database transactions when adding new recipes to maintain data integrity.

---

## Technology Used

*   **PHP:** The primary server-side scripting language.
*   **Slim Framework (v4):** Used for efficient routing and middleware management.
*   **MySQL:** The database engine for storing culinary data.
*   **Composer:** For managing PHP libraries and PSR-4 autoloading.
*   **JSON:** The standard format for data exchange.
*   **Apache:** The web server used for hosting the application.
*   **XAMPP:** The local development environment for testing.
*   **Thunder Client:** Recommended tool for API testing and documentation.
*   **Git & GitHub:** For version control and repository management.

---

## Installation Instruction

1.  **Clone the Repository:**
    ```bash
    git clone [Your Repository Link]
    cd filipino-cookbook-api
    ```

2.  **Install Dependencies:**
    Ensure you have Composer installed, then run:
    ```bash
    composer install
    ```

3.  **Deploy to Web Server:**
    *   Move the project folder to your server's root (e.g., `C:\xampp\htdocs\`).
    *   Configure the server to point to the `public/` directory.

---

## Database Setup

1.  **Create the Database:**
    Create a new MySQL database named `filipino_cookbook_api`.

2.  **Import Data:**
    Import the provided `filipino_cookbook_api.sql` file into your database to set up the tables and seed them with initial data.

3.  **Connection Configuration:**
    The connection settings are located in `public/index.php`. Default settings:
    *   **Host:** `localhost`
    *   **DB Name:** `filipino_cookbook_api`
    *   **User:** `root`
    *   **Password:** `(empty)`

---

## Base URL

The base URL for all API requests is:
`http://localhost/filipino-cookbook-api/public`

---

## Authentication Instructions

The API uses **Bearer Token Authentication**. To access the `/api` routes, include the following header in your requests:

*   **Header Name:** `Authorization`
*   **Value Format:** `Bearer [Your_Token]`
*   **Default Token:** `Bearer dmmmsu-cookbook-token-2026`

---

## Endpoint Documentation

| Method | Endpoint | Description | Auth Required |
| :--- | :--- | :--- | :--- |
| **GET** | `/` | Welcome message and API info | No |
| **GET** | `/api/foods` | Retrieve all food recipes | Yes |
| **GET** | `/api/foods/{id}` | Get details of a specific food by ID | Yes |
| **GET** | `/api/foods/search/{name}` | Search for foods by name | Yes |
| **GET** | `/api/categories` | List all food categories | Yes |
| **GET** | `/api/ingredients` | List all ingredients | Yes |
| **POST** | `/api/foods` | Add a new food recipe | Yes |

### Sample POST Request Body (`/api/foods`)
```json
{
    "food_name": "New Dish",
    "category_id": 4,
    "origin_id": 4,
    "instructions": "Step by step guide...",
    "ingredient_ids": [1, 2, 3]
}
```

---

## HTTP Status Codes

The API returns standard HTTP status codes to indicate the success or failure of a request:

| Status Code | Description |
| :--- | :--- |
| `200 OK` | The request was successful. |
| `201 Created` | A new resource (e.g., a food recipe) was successfully created. |
| `400 Bad Request` | The request was malformed or missing required parameters. |
| `401 Unauthorized` | Authentication failed or no token was provided. |
| `404 Not Found` | The requested resource (e.g., food ID) does not exist. |
| `500 Internal Server Error` | An unexpected error occurred on the server. |

---

## Developer Information

*   **Student Name:** [Enter Your Name]
*   **Course and Section:** [Enter Course & Section]
*   **GitHub Username:** [Enter GitHub Username]
*   **Repository Link:** [Enter Repository Link]
*   **Date Completed:** August 2, 2026
