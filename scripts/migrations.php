<?php

require_once __DIR__ . '/../src/Database.php';

/**
 * Function to create tables if they don't exist
 */
function createTables($pdo) {
    $createProductsTable = "
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id VARCHAR(50) UNIQUE,
            bleaching_code INT DEFAULT NULL,
            default_language VARCHAR(10) DEFAULT NULL,
            dry_cleaning_code INT DEFAULT NULL,
            drying_code INT DEFAULT NULL,
            fastening_type_code INT DEFAULT NULL,
            ironing_code INT DEFAULT NULL,
            pullout_type_code INT DEFAULT NULL,
            sap_packet VARCHAR(10) DEFAULT NULL,
            waistline_code INT DEFAULT NULL,
            washability_code INT DEFAULT NULL
        );
    ";

    $createDetailsDataTable = "
        CREATE TABLE IF NOT EXISTS product_details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id VARCHAR(50) DEFAULT NULL,
            cedi VARCHAR(10) DEFAULT NULL,
            child_weight_from FLOAT DEFAULT NULL,
            child_weight_to FLOAT DEFAULT NULL,
            color_code VARCHAR(10) DEFAULT NULL,
            color_description VARCHAR(50) DEFAULT NULL,
            country_images BOOLEAN DEFAULT NULL,
            default_sku BOOLEAN DEFAULT NULL,
            preferred_ean VARCHAR(50) DEFAULT NULL,
            sap_assortment_level VARCHAR(10) DEFAULT NULL,
            sap_price FLOAT DEFAULT NULL,
            season VARCHAR(10) DEFAULT NULL,
            show_online_sku BOOLEAN DEFAULT NULL,
            size_code VARCHAR(10) DEFAULT NULL,
            size_description VARCHAR(100) DEFAULT NULL,
            sku_id VARCHAR(50) DEFAULT NULL,
            sku_name VARCHAR(255) DEFAULT NULL,
            state_of_article BOOLEAN DEFAULT NULL,
            um_sap_price VARCHAR(10) DEFAULT NULL,
            volume FLOAT DEFAULT NULL,
            weight FLOAT DEFAULT NULL,
            FOREIGN KEY (product_id) REFERENCES products(product_id)
        );
    ";

    $createProductHeadersTable = "
        CREATE TABLE IF NOT EXISTS product_headers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id VARCHAR(50) NOT NULL,
            bag BOOLEAN DEFAULT NULL,
            bleaching_description TEXT DEFAULT NULL,
            brand VARCHAR(100) DEFAULT NULL,
            brand_code VARCHAR(50) DEFAULT NULL,
            catalog VARCHAR(100) DEFAULT NULL,
            composition TEXT DEFAULT NULL,
            creation_date_in_database DATETIME DEFAULT NULL,
            custom_1 TEXT DEFAULT NULL,
            custom_2 TEXT DEFAULT NULL,
            custom_3 TEXT DEFAULT NULL,
            drink_holder BOOLEAN DEFAULT NULL,
            dry_cleaning_description TEXT DEFAULT NULL,
            drying_description TEXT DEFAULT NULL,
            e_shop_display_name VARCHAR(255) DEFAULT NULL,
            e_shop_long_description TEXT DEFAULT NULL,
            ergonomic_seat BOOLEAN DEFAULT NULL,
            fastening_type_description TEXT DEFAULT NULL,
            fastening_type_textile VARCHAR(255) DEFAULT NULL,
            flat BOOLEAN DEFAULT NULL,
            free_delivery BOOLEAN DEFAULT NULL,
            gender VARCHAR(50) DEFAULT NULL,
            indicator_of_it_has_to_be_assembled BOOLEAN DEFAULT NULL,
            ironing_description TEXT DEFAULT NULL,
            last_date_changed DATETIME DEFAULT NULL,
            last_user_changed VARCHAR(100) DEFAULT NULL,
            product_status VARCHAR(255) DEFAULT NULL,
            pullout_type VARCHAR(255) DEFAULT NULL,
            pullout_type_description TEXT DEFAULT NULL,
            punnet BOOLEAN DEFAULT NULL,
            sap_category_id VARCHAR(50) DEFAULT NULL,
            sap_category_name VARCHAR(100) DEFAULT NULL,
            sap_division_id VARCHAR(50) DEFAULT NULL,
            sap_division_name VARCHAR(100) DEFAULT NULL,
            sap_family_description TEXT DEFAULT NULL,
            sap_family_id VARCHAR(50) DEFAULT NULL,
            sap_family_name VARCHAR(100) DEFAULT NULL,
            sap_macrocategory_id VARCHAR(50) DEFAULT NULL,
            sap_macrocategory_name VARCHAR(100) DEFAULT NULL,
            sap_name VARCHAR(255) DEFAULT NULL,
            sap_universe_id VARCHAR(50) DEFAULT NULL,
            sap_universe_name VARCHAR(100) DEFAULT NULL,
            show_on_line BOOLEAN DEFAULT NULL,
            size_guide VARCHAR(50) DEFAULT NULL,
            source_id VARCHAR(255) DEFAULT NULL,
            user_of_creation VARCHAR(100) DEFAULT NULL,
            waistline_description TEXT DEFAULT NULL,
            washability VARCHAR(255) DEFAULT NULL,
            washability_description TEXT DEFAULT NULL,
            zip_stopper BOOLEAN DEFAULT NULL,
            FOREIGN KEY (product_id) REFERENCES products(product_id)
        );
    ";

    $createProductFeaturesTable = "
        CREATE TABLE IF NOT EXISTS product_features (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id VARCHAR(50) NOT NULL, -- Foreign key to products table
            feature VARCHAR(255) DEFAULT NULL,
            is_missing TINYINT(1) DEFAULT NULL,
            FOREIGN KEY (product_id) REFERENCES products(product_id)
        );
    ";

    try {
        // Execute the table creation SQL commands
        $pdo->exec($createProductsTable);
        $pdo->exec($createDetailsDataTable);
        $pdo->exec($createProductHeadersTable);
        $pdo->exec($createProductFeaturesTable);
        echo "Tables checked and created if necessary.\n";
    } catch (PDOException $e) {
        echo "Error creating tables: " . $e->getMessage() . "\n";
        exit;
    }
}

// Create connection to the database
try {
    $db = new Database();
    $pdo = $db->getConnection();
    createTables($pdo);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}