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
            $orm->insert('details_data', $detail);
        }

        $orm->insert('products_headers', $data['products_headers']);

        echo "Data successfully inserted.\n";
    }

    elseif ($operation === 'update') {
        echo "Updating data in the database...\n";

        $data = $parser->parseXML();
        $updateProductData = $data['product'];
        $updateDetailsData = $data['details'];

        $product_id = $updateProductData['product_id'] ?? '';

        if (empty($product_id)) {
            echo "Error: No product_id found in the XML data. Cannot perform update.\n";
            exit;
        }

        // Perform the update for the product using ORM, based on the condition (e.g., product_id=12345)
        $orm->update('products', $updateProductData, ['product_id' => $product_id]);

        // Perform the update for details data (if needed), using the same condition
        foreach ($updateDetailsData as $detail) {
            $skuId = $detail['skuID'];
            if (empty($skuId)) {
                echo "No skuID found for this details data. Skipping this row.\n";
                continue;
            }
            $orm->update('details_data', $detail, ['sku_id' => $skuId]);
        }

        $orm->update('products_headers', $data['products_headers'], ['product_id' => $product_id]);

        echo "Data successfully updated.\n";
    }

    elseif ($operation === 'delete') {
        echo "Deleting data from the database...\n";

        $data = $parser->parseXML();
        $updateProductData = $data['product'];

        // Ensure a condition is provided for delete
        if (!isset($argv[2])) {
            echo "Error: No condition specified for delete.\n";
            showHelp();
            exit;
        }

        // Prepare for condition and data arguments
        $condition = $argv[2];

        // Split the condition into field and value
        list($conditionField, $conditionValue) = explode('=', $condition);

        // Delete details data
        $orm->delete('details_data', [$conditionField => $conditionValue]);

        // Delete products data
        $orm->delete('products', [$conditionField => $conditionValue]);

        // Delete products data
        $orm->delete('products_headers', [$conditionField => $conditionValue]);

        echo "Data successfully deleted.\n";
    }

    else {
        echo "Error: Invalid operation '$operation'.\n";
        showHelp();
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}