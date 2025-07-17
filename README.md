# Product Reference API Module for PrestaShop

## Description
The **Product Reference API** module enables retrieval of product details from a PrestaShop store via a secure JSON API. It provides product information such as ID, reference, name, price, currency, and short description, accessible through a custom API endpoint. Designed for PrestaShop 1.6.24.

- **Module Name**: referenceapi
- **Version**: 1.0.3
- **API Endpoint**: `https://yourshop.com/api/referenceapi/?reference=PRODUCT_REF&api_key=YOUR_API_KEY`

## Features
- Fetches product data (ID, reference, name, price, currency, short description) in JSON format.
- Secures API access with a configurable API key.
- Includes a backoffice configuration page to set the API key.
- Supports custom URL (`/api/referenceapi/`) via `.htaccess` rewrite rules.

## Installation
1. **Download**:
   - Clone this repository or download the `referenceapi.zip` file.

2. **Upload and Install**:
   - In the PrestaShop backoffice, navigate to **Modules > Add new module**.
   - Upload `referenceapi.zip` and click **Install**.

3. **Verify File Structure**:
   - Ensure the module is installed in `/var/www/yourshop/public_html/modules/referenceapi/` with the following structure:
     ```
     referenceapi/
     ├── referenceapi.php
     ├── config.xml
     ├── api.php
     └── views/
         └── templates/
             └── admin/
                 └── configure.tpl
     ```

4. **Set Permissions**:
   ```bash
   sudo chown -R www-data:www-data /var/www/yourshop/public_html/modules/referenceapi
   sudo chmod -R 755 /var/www/yourshop/public_html/modules/referenceapi
   ```

## Configuration
1. **Set API Key**:
   - Go to **Modules > Product Reference API > Configure** in the backoffice.
   - Enter a secure API key (e.g., `fz6IeW4arS8cZAJJ`) or use the auto-generated key.
   - Click **Save**.

2. **Enable Friendly URLs**:
   - Navigate to **Preferences > SEO & URLs** in the backoffice.
   - Ensure **Friendly URL** is enabled.

3. **Add `.htaccess` Rewrite Rule**:
   - Edit `/var/www/yourshop/public_html/.htaccess`.
   - Add the following within the `<IfModule mod_rewrite.c>` block:
     ```plaintext
     RewriteRule ^api/referenceapi/$ modules/referenceapi/api.php [L]
     ```
   - Set permissions:
     ```bash
     sudo chown www-data:www-data /var/www/yourshop/public_html/.htaccess
     sudo chmod 644 /var/www/yourshop/public_html/.htaccess
     ```

4. **Enable `mod_rewrite`**:
   ```bash
   sudo a2enmod rewrite
   sudo service apache2 restart
   ```

## Usage
- **API Endpoint**:
  ```
  https://yourshop.com/api/referenceapi/?reference=PRODUCT_REF&api_key=YOUR_API_KEY
  ```
  - `PRODUCT_REF`: The product reference (e.g., `41000011`).
  - `YOUR_API_KEY`: The API key set in the backoffice (e.g., `fz6IeW4arS8cZAJJ`).

- **Example Request**:
  ```
  https://sal-tech.com/api/referenceapi/?reference=41000011&api_key=fz6IeW4arS8cZAJJ
  ```

- **Example Response**:
  ```json
  {
      "id_product": 17,
      "reference": "41000011",
      "name": "Product Name",
      "price": "6990.00",
      "currency": "USD",
      "description_short": "Product description."
  }
  ```

- **Error Responses**:
  - Invalid or missing API key:
    ```json
    {"error": "Invalid or missing API key"}
    ```
  - Missing reference:
    ```json
    {"error": "Reference number is required"}
    ```
  - Product not found:
    ```json
    {"error": "Product not found for reference: 41000011"}
    ```

## Requirements
- PrestaShop 1.6 to 1.6.24
- Apache with `mod_rewrite` enabled
- PHP 5.3 or higher
- MySQL database with `ps_` prefix (or as configured)

## License
This module is released under the [MIT License](LICENSE).