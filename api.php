<?php
require_once(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../init.php');

header('Content-Type: application/json');

$api_key = Tools::getValue('api_key');
$reference = Tools::getValue('reference');
$stored_api_key = Configuration::get('REFERENCE_API_KEY');

$response = array();

if (empty($api_key) || $api_key !== $stored_api_key) {
    http_response_code(401);
    $response = array('error' => 'Invalid or missing API key');
    echo json_encode($response);
    exit;
}

if (empty($reference)) {
    http_response_code(400);
    $response = array('error' => 'Reference number is required');
    echo json_encode($response);
    exit;
}

$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
$default_currency = Configuration::get('PS_CURRENCY_DEFAULT');

$product = Db::getInstance()->getRow(
    'SELECT p.id_product, p.reference, p.price, pl.name, pl.description_short
     FROM ' . _DB_PREFIX_ . 'product p
     LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = ' . $default_lang . ')
     WHERE p.reference = \'' . pSQL($reference) . '\''
);

if ($product) {
    // Fetch the default currency's ISO code
    $currency = Db::getInstance()->getValue(
        'SELECT iso_code FROM ' . _DB_PREFIX_ . 'currency WHERE id_currency = ' . (int)$default_currency
    );
    
    $response = array(
        'id_product' => $product['id_product'],
        'reference' => $product['reference'],
        'name' => $product['name'] ? $product['name'] : 'N/A',
        'price' => number_format($product['price'], 2, '.', ''),
        'currency' => $currency ? $currency : 'USD', // Fallback to USD if currency not found
        'description_short' => $product['description_short'] ? $product['description_short'] : 'N/A'
    );
    http_response_code(200);
} else {
    http_response_code(404);
    $response = array('error' => 'Product not found for reference: ' . $reference);
}

echo json_encode($response);
exit;