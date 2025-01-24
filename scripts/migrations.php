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
        CREATE TABLE IF NOT EXISTS details_data (
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

    $createHeaderDataTable = "
            CREATE TABLE header_data (
            id INT AUTO_INCREMENT PRIMARY KEY,
            productID VARCHAR(50) NOT NULL,
            bag BOOLEAN DEFAULT NULL,
            bleachingDescription TEXT DEFAULT NULL,
            brand VARCHAR(100) DEFAULT NULL,
            brandCode VARCHAR(50) DEFAULT NULL,
            catalog VARCHAR(100) DEFAULT NULL,
            composition TEXT DEFAULT NULL,
            creationDateInDatabase DATETIME DEFAULT NULL,
            custom1 TEXT DEFAULT NULL,
            custom2 TEXT DEFAULT NULL,
            custom3 TEXT DEFAULT NULL,
            drinkHolder BOOLEAN DEFAULT NULL,
            dryCleaningDescription TEXT DEFAULT NULL,
            dryingDescription TEXT DEFAULT NULL,
            EShopDisplayName TEXT DEFAULT NULL,
            EShopLongDescription TEXT DEFAULT NULL,
            ergonomicSeat BOOLEAN DEFAULT NULL,
            fasteningTypeDescription TEXT DEFAULT NULL,
            fasteningTypeTextile TEXT DEFAULT NULL,
            flat BOOLEAN DEFAULT NULL,
            freeDelivery BOOLEAN DEFAULT NULL,
            gender VARCHAR(50) DEFAULT NULL,
            indicatorOfItHasToBeAssembled BOOLEAN DEFAULT NULL,
            ironingDescription TEXT DEFAULT NULL,
            lastDateChanged DATETIME DEFAULT NULL,
            lastUserChanged VARCHAR(100) DEFAULT NULL,
            productFeatures TEXT DEFAULT NULL,
            productMissingFeatures TEXT DEFAULT NULL,
            productStatus TEXT DEFAULT NULL,
            pulloutType TEXT DEFAULT NULL,
            pulloutTypeDescription TEXT DEFAULT NULL,
            punnet BOOLEAN DEFAULT NULL,
            sapCategoryID VARCHAR(50) DEFAULT NULL,
            sapCategoryName VARCHAR(100) DEFAULT NULL,
            sapDivisionID VARCHAR(50) DEFAULT NULL,
            sapDivisionName VARCHAR(100) DEFAULT NULL,
            sapFamilyDescription TEXT DEFAULT NULL,
            sapFamilyID VARCHAR(50) DEFAULT NULL,
            sapFamilyName VARCHAR(100) DEFAULT NULL,
            sapMacrocategoryID VARCHAR(50) DEFAULT NULL,
            sapMacrocategoryName VARCHAR(100) DEFAULT NULL,
            sapName VARCHAR(255) DEFAULT NULL,
            sapUniverseID VARCHAR(50) DEFAULT NULL,
            sapUniverseName VARCHAR(100) DEFAULT NULL,
            showOnLine BOOLEAN DEFAULT NULL,
            sizeGuide VARCHAR(50) DEFAULT NULL,
            sourceID TEXT DEFAULT NULL,
            userOfCreation VARCHAR(100) DEFAULT NULL,
            waistlineDescription TEXT DEFAULT NULL,
            washability TEXT DEFAULT NULL,
            washabilityDescription TEXT DEFAULT NULL,
            zipStopper BOOLEAN DEFAULT NULL,
            FOREIGN KEY (productID) REFERENCES products(productID)
        );
    ";

    $createProductFeaturesTable = "
        CREATE TABLE product_features (
            id INT AUTO_INCREMENT PRIMARY KEY,
            productID VARCHAR(50) NOT NULL, -- Foreign key to products table
            feature VARCHAR(255) DEFAULT NULL,
            is_missing TINYINT(1) DEFAULT NULL,
            FOREIGN KEY (productID) REFERENCES products(productID)
        );
    ";

    try {
        // Execute the table creation SQL commands
        $pdo->exec($createProductsTable);
        $pdo->exec($createDetailsDataTable);
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