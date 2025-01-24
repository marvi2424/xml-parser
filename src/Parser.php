<?php

/**
 *
 */
class Parser {
    /**
     * @var string
     */
    private string $filePath;

    /**
     * @param $filePath
     * @throws Exception
     */
    public function __construct($filePath) {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }
        $this->filePath = $filePath;
    }

    /**
     *
     * Parse products XML to associative arrays
     *
     * @return array
     */
    public function parseXML(): array
    {
        $xml = simplexml_load_file($this->filePath);

        // Parse product Data
        $product = [
            'product_id' => (string)$xml->productID,
            'bleaching_code' => (int)$xml->bleachingCode,
            'default_language' => (string)$xml->defaultLanguage,
            'dry_cleaning_code' => (int)$xml->dryCleaningCode,
            'drying_code' => (int)$xml->dryingCode,
            'fastening_type_code' => (int)$xml->fasteningTypeCode,
            'ironing_code' => (int)$xml->ironingCode,
            'pullout_type_code' => (int)$xml->pulloutTypeCode,
            'sap_packet' => (string)$xml->sapPacket,
            'waistline_code' => (int)$xml->waistlineCode,
            'washability_code' => (int)$xml->washabilityCode,
        ];

        // Parse product details data
        $details = [];
        foreach ($xml->definitions->detailsData as $detailsData) {
            $details[] = [
                'product_id' => (string)$xml->productID,
                'cedi' => (string)$detailsData->cedi,
                'child_weight_from' => (float)$detailsData->childWeightFrom,
                'child_weight_to' => (float)$detailsData->childWeightTo,
                'color_code' => (string)$detailsData->color_code,
                'color_description' => (string)$detailsData->color_description,
                'country_images' => (int)($detailsData->countryImages === 'true'),
                'default_sku' => (int)($detailsData->defaultSku === 'true'),
                'preferred_ean' => (string)$detailsData->preferredEan,
                'sap_assortment_level' => (string)$detailsData->sapAssortmentLevel,
                'sap_price' => (float)$detailsData->sapPrice,
                'season' => (int)$detailsData->season,
                'show_online_sku' => (int)($detailsData->showOnLineSku === 'true'),
                'size_code' => (string)$detailsData->size_code,
                'size_description' => (string)$detailsData->size_description,
                'sku_id' => (string)$detailsData->skuID,
                'sku_name' => (string)$detailsData->skuName,
                'state_of_article' => (int)($detailsData->stateOfArticle === 'true'),
                'um_sap_price' => (string)$detailsData->umSAPprice,
                'volume' => (float)$detailsData->volume,
                'weight' => (float)$detailsData->weight,
            ];
        }

        // Parse product headers Data
        $product_headers = [
            'product_id' => (string)$xml->productID, // Link to the parent productID
            'bag' => $xml->definitions->headerData->bag ? 1 : 0,
            'bleaching_description' => (string)$xml->definitions->headerData->bleachingDescription,
            'brand' => (string)$xml->definitions->headerData->brand,
            'brand_code' => (string)$xml->definitions->headerData->brandCode,
            'catalog' => (string)$xml->definitions->headerData->catalog,
            'composition' => (string)$xml->definitions->headerData->composition,
            'creation_date_in_database' => (string)$xml->definitions->headerData->creationDateInDatabase,
            'custom_1' => (string)$xml->definitions->headerData->custom1,
            'custom_2' => (string)$xml->definitions->headerData->custom2,
            'custom_3' => (string)$xml->definitions->headerData->custom3,
            'drink_holder' => $xml->definitions->headerData->drinkHolder ? 1 : 0,
            'dry_cleaning_description' => (string)$xml->definitions->headerData->dryCleaningDescription,
            'drying_description' => (string)$xml->definitions->headerData->dryingDescription,
            'e_shop_display_name' => (string)$xml->definitions->headerData->EShopDisplayName,
            'e_shop_long_description' => (string)$xml->definitions->headerData->EShopLongDescription,
            'ergonomic_seat' => $xml->definitions->headerData->ergonomicSeat ? 1 : 0,
            'fastening_type_description' => (string)$xml->definitions->headerData->fasteningTypeDescription,
            'fastening_type_textile' => (string)$xml->definitions->headerData->fasteningTypeTextile,
            'flat' => $xml->definitions->headerData->flat ? 1 : 0,
            'free_delivery' => $xml->definitions->headerData->freeDelivery ? 1 : 0,
            'gender' => (string)$xml->definitions->headerData->gender,
            'indicator_of_it_has_to_be_assembled' => $xml->definitions->headerData->indicatorOfItHasToBeAssembled ? 1 : 0,
            'ironing_description' => (string)$xml->definitions->headerData->ironingDescription,
            'last_date_changed' => (string)$xml->definitions->headerData->lastDateChanged,
            'last_user_changed' => (string)$xml->definitions->headerData->lastUserChanged,
            'product_status' => (string)$xml->definitions->headerData->productStatus,
            'pullout_type' => (string)$xml->definitions->headerData->pulloutType,
            'pullout_type_description' => (string)$xml->definitions->headerData->pulloutTypeDescription,
            'punnet' => $xml->definitions->headerData->punnet ? 1 : 0,
            'sap_category_id' => (string)$xml->definitions->headerData->sapCategoryID,
            'sap_category_name' => (string)$xml->definitions->headerData->sapCategoryName,
            'sap_division_id' => (string)$xml->definitions->headerData->sapDivisionID,
            'sap_division_name' => (string)$xml->definitions->headerData->sapDivisionName,
            'sap_family_description' => (string)$xml->definitions->headerData->sapFamilyDescription,
            'sap_family_id' => (string)$xml->definitions->headerData->sapFamilyID,
            'sap_family_name' => (string)$xml->definitions->headerData->sapFamilyName,
            'sap_macrocategory_id' => (string)$xml->definitions->headerData->sapMacrocategoryID,
            'sap_macrocategory_name' => (string)$xml->definitions->headerData->sapMacrocategoryName,
            'sap_name' => (string)$xml->definitions->headerData->sapName,
            'sap_universe_id' => (string)$xml->definitions->headerData->sapUniverseID,
            'sap_universe_name' => (string)$xml->definitions->headerData->sapUniverseName,
            'show_on_line' => $xml->definitions->headerData->showOnLine ? 1 : 0,
            'size_guide' => (string)$xml->definitions->headerData->sizeGuide,
            'source_id' => (string)$xml->definitions->headerData->sourceID,
            'user_of_creation' => (string)$xml->definitions->headerData->userOfCreation,
            'waistline_description' => (string)$xml->definitions->headerData->waistlineDescription,
            'washability' => (string)$xml->definitions->headerData->washability,
            'washability_description' => (string)$xml->definitions->headerData->washabilityDescription,
            'zip_stopper' => $xml->definitions->headerData->zipStopper ? 1 : 0,
        ];

        // Parse product features data
        // NOTE: missing and present product features will be stored in the same table and will be differentiated by
        // is_missing column
        $products_features = [];

        foreach ($xml->definitions->headerData->productFeatures as $productFeature){
            $products_features[] = [
                'product_id' => (string)$xml->productID,
                'feature' => (string)$productFeature,
                'is_missing' => 0
            ];
        }

        foreach ($xml->definitions->headerData->productMissingFeatures as $productFeature){
            $products_features[] = [
                'product_id' => (string)$xml->productID,
                'feature' => (string)$productFeature,
                'is_missing' => 1
            ];
        }


        return [
            'product' => $product,
            'details' => $details,
            'product_headers' => $product_headers,
            'product_features' => $products_features
        ];
    }
}
