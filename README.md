# **Secure PHP** 🛡️  
A PHP application to handle authentication operations securely.

---

## **Technologies Used** 🧑‍💻  
- **PHP (Native)**: Backend API with JSON data exchange.  
- **MySQL**: Database management system.  
- **Composer Dependencies**:  
  - **JWT Authentication**: [`firebase/php-jwt`](https://github.com/firebase/php-jwt)  
  - **Environment Variables**: [`vlucas/phpdotenv`](https://github.com/vlucas/phpdotenv)  
  - **UUID Generation**: [`ramsey/uuid`](https://github.com/ramsey/uuid)  

---

## **Installation & Setup** ⚙️  

### **1️⃣ Clone the Repository**:  
Run the following commands:  
    ```bash
git clone https://github.com/Dismas-Ospaltic/SecurePhpEndpoints.git

---

cd SecurePhpEndpoints
###  **2️⃣ Install Dependencies with Composer**:
Run the following command in your project root:
        ```bash
composer require firebase/php-jwt vlucas/phpdotenv ramsey/uuid

### **3️⃣Configure Environment Variables**:
Create a .env file in the project root and add your database credentials:

    DB_HOST=localhost
    DB_NAME=auth_system
    DB_USER=root
    DB_PASS=databasepassword
    SECRET_KEY=your_secret_key
    JWT_ISSUER=your_app
    JWT_AUDIENCE=your_audience
    ACCESS_TOKEN_EXPIRY=3600
    REFRESH_TOKEN_EXPIRY=604800
## **4️⃣ Set Up the Database**:
Import the provided SQL schema:
     ```bash
        mysql -u root -p secure_php < database/schema.sql
## **5️⃣ Run the Project**
Use PHP's built-in server to serve your API:
       ```bash
      php -S localhost:8000 -t public

---
API Testing 🔥
Test your API endpoints using:

Postman 🟠

Insomnia 🌙

Bruno 🟣

---
Endpoints 📜
Here are some of the main API endpoints available in the project:

Register User
Method: POST

Endpoint: /api/register

Request:

json
{
  "email": "user@example.com",
  "password": "password123"
}
Response:
201 Created if successful.

Login User
Method: POST

Endpoint: /api/login

Request:

json
{
  "email": "user@example.com",
  "password": "password123"
}
Response:
Returns JWT access token and refresh token.

Get User Profile
Method: GET

Endpoint: /api/profile

Headers:

Authorization: Bearer <access_token>
Response:
User profile data including id, email, created_at, etc.

Refresh Access Token
Method: POST

Endpoint: /api/refresh

Request:

json
{
  "refresh_token": "<refresh_token>"
}
Response:
Returns a new access token.

Logout User
Method: POST

Endpoint: /api/logout

Request:

json
{
  "refresh_token": "<refresh_token>",
    "refresh_token": "<access_token>"
}
Response:
Returns a success message upon logging out.

---
License 📜
This project is open-source and available under the MIT License.

---
Contributing 🤝
We welcome contributions to this project! Please fork the repository, make your changes, and create a pull request.

---
Contact 📧
For any questions or suggestions, feel free to reach out to Dismas-Ospaltic.
Phone: +254742354784
email: kizaidismas@gmail.com