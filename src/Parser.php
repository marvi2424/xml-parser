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
     * @return array
     */
    public function parseXML(): array
    {
        $xml = simplexml_load_file($this->filePath);

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

        $products_headers = [
            'product_id' => (string)$xml->productID, // Link to the parent productID
            'bag' => filter_var((string)$xml->definitions->headerData->bag, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'bleaching_description' => (string)$xml->definitions->headerData->bleachingDescription,
            'brand' => (string)$xml->definitions->headerData->brand,
            'brand_code' => (string)$xml->definitions->headerData->brandCode,
            'catalog' => (string)$xml->definitions->headerData->catalog,
            'composition' => (string)$xml->definitions->headerData->composition,
            'creation_date_in_database' => (string)$xml->definitions->headerData->creationDateInDatabase,
            'custom1' => (string)$xml->definitions->headerData->custom1,
            'custom2' => (string)$xml->definitions->headerData->custom2,
            'custom3' => (string)$xml->definitions->headerData->custom3,
            'drink_holder' => filter_var((string)$xml->definitions->headerData->drinkHolder, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'dry_cleaning_description' => (string)$xml->definitions->headerData->dryCleaningDescription,
            'drying_description' => (string)$xml->definitions->headerData->dryingDescription,
            'eshop_display_name' => (string)$xml->definitions->headerData->EShopDisplayName,
            'eshop_long_description' => (string)$xml->definitions->headerData->EShopLongDescription,
            'ergonomic_seat' => filter_var((string)$xml->definitions->headerData->ergonomicSeat, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'fastening_type_description' => (string)$xml->definitions->headerData->fasteningTypeDescription,
            'fastening_type_textile' => (string)$xml->definitions->headerData->fasteningTypeTextile,
            'flat' => filter_var((string)$xml->definitions->headerData->flat, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'free_delivery' => filter_var((string)$xml->definitions->headerData->freeDelivery, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'gender' => (string)$xml->definitions->headerData->gender,
            'indicator_of_it_has_to_be_assembled' => filter_var((string)$xml->definitions->headerData->indicatorOfItHasToBeAssembled, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'ironing_description' => (string)$xml->definitions->headerData->ironingDescription,
            'last_date_changed' => (string)$xml->definitions->headerData->lastDateChanged,
            'last_user_changed' => (string)$xml->definitions->headerData->lastUserChanged,
            'product_status' => (string)$xml->definitions->headerData->productStatus,
            'pullout_type' => (string)$xml->definitions->headerData->pulloutType,
            'pullout_type_description' => (string)$xml->definitions->headerData->pulloutTypeDescription,
            'punnet' => filter_var((string)$xml->definitions->headerData->punnet, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
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
            'show_on_line' => filter_var((string)$xml->definitions->headerData->showOnLine, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'size_guide' => (string)$xml->definitions->headerData->sizeGuide,
            'source_id' => (string)$xml->definitions->headerData->sourceID,
            'user_of_creation' => (string)$xml->definitions->headerData->userOfCreation,
            'waistline_description' => (string)$xml->definitions->headerData->waistlineDescription,
            'washability' => (string)$xml->definitions->headerData->washability,
            'washability_description' => (string)$xml->definitions->headerData->washabilityDescription,
            'zip_stopper' => filter_var((string)$xml->definitions->headerData->zipStopper, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        ];

        $products_features = [];
        foreach ($xml->definitions->headerData->productFeatures as $productFeature){
            $products_features[] = [
                'product_id' => (string)$xml->productID,
                'feature' => $productFeature,
                'is_missing' => 0
            ];
        }

        foreach ($xml->definitions->headerData->productMissingFeatures as $productFeature){
            $products_features[] = [
                'product_id' => (string)$xml->productID,
                'feature' => $productFeature,
                'is_missing' => 1
            ];
        }


        return [
            'product' => $product,
            'details' => $details,
            'products_headers' => $products_headers,
            'product_features' => $products_features
        ];
    }
}