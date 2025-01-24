<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Parser.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/ORM.php';
require_once __DIR__ . '/Helpers/helperFunctions.php';

// Ensure an operation argument is provided
if ($argc < 2) {
    echo "Error: No operation specified.\n";
    showHelp();
    exit;
}

$operation = $argv[1];
try {
    $db = new Database();
    $orm = new ORM($db->getConnection());
    $parser = new Parser(__DIR__ . '/../dump_data.xml');

    if ($operation === 'insert') {
        echo "Inserting data into the database...\n";

        $data = $parser->parseXML();
        $orm->insert('products', $data['product']);

        foreach ($data['details'] as $detail) {
            $orm->insert('product_details', $detail);
        }

        $orm->insert('product_headers', $data['product_headers']);

        foreach ($data['product_features'] as $feature) {
            $orm->insert('product_features', $feature);
        }

        echo "Data successfully inserted.\n";
    }

    elseif ($operation === 'update') {
        echo "Updating data in the database...\n";

        $data = $parser->parseXML();
        $updateProductData = $data['product'];
        $updateDetailsData = $data['details'];
        $updateProductHeadersData = $data['product_headers'];

        $product_id = $updateProductData['product_id'] ?? '';

        if (empty($product_id)) {
            echo "Error: No product_id found in the XML data. Cannot perform update.\n";
            exit;
        }

        // Update the product by product_id
        $orm->update('products', $updateProductData, ['product_id' => $product_id]);

        // Update details data by skuId
        foreach ($updateDetailsData as $detail) {
            $sku_id = $detail['sku_id'];
            if (empty($sku_id)) {
                echo "No skuID found for this details data. Skipping this row.\n";
                continue;
            }
            $orm->update('product_details', $detail, ['sku_id' => $sku_id]);
        }

        // Update Product Headers by product_id
        $orm->update('product_headers', $updateProductHeadersData, ['product_id' => $product_id]);

        // Refresh product features: delete existing features for the product and insert the updated ones
        // Reason: The XML only provides the feature names without unique identifiers, so we replace all features to ensure accuracy
        $orm->delete('product_features', ['product_id' => $product_id]);

        foreach ($data['product_features'] as $feature) {
            $orm->insert('product_features', $feature);
        }

        echo "Data successfully updated.\n";
    }

    elseif ($operation === 'delete') {
        echo "Deleting data from the database...\n";

        $data = $parser->parseXML();
        $product_id = $data['product']['product_id'] ?? '';

        // Ensure a condition is provided for delete
        if (empty($product_id)) {
            echo "Error: No product_id found for deletion.\n";
            exit;
        }

        // Delete details data
        $orm->delete('product_details', ['product_id' => $product_id]);

        // Delete product headers data
        $orm->delete('product_headers', ['product_id' => $product_id]);

        // Delete product features data
        $orm->delete('product_features', ['product_id' => $product_id]);

        // Delete products data
        $orm->delete('products', ['product_id' => $product_id]);

        echo "Data successfully deleted.\n";
    }

    else {
        echo "Error: Invalid operation '$operation'.\n";
        showHelp();
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}