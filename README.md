# XML to Database

A PHP script to dump_data.xml XML data and perform `insert`, `update`, and `delete` operations on a MySQL database.

## Database Schema
![Database Schema](db-schema.png)


## Setup

1. **Install Dependencies**
   Run the following command to install required dependencies:
   ```
   composer install
   ```

2. **Environment Configuration**

    Create a .env file in the project root:
    ```
    DB_HOST=localhost
    DB_PORT=3306
    DB_NAME=your_database_name
    DB_USER=your_database_user
    DB_PASSWORD=your_database_password
    ```
   
3. **Database Setup Script**
   
   Create database tables if not already created
   ```
   php scripts/migrations.php     
   ```
   
4. **Usage**
    
    Insert

    ```
    php src/main.php insert
    ```
   
    Update
    ```
    php src/main.php update
    ```
   
    Delete
    ```
    php src/main.php delete
    ```


**Notes**

- The project uses the ```vlucas/phpdotenv``` package for loading environment variables.