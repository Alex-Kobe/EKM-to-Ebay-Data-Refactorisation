<?php

class ImageFormatter {
   
    // Creating 3 new arrays to store the contents of the EKM file, the contents of the new ebay titles & the content of the refactored data
    private static $new_title_file_contents = [];
    private static $ekm_data_file_contents = [];
    private static $refactored_file_content = [];
    private static $attributes = [
        'counter' => 1,
        'size' => 0,
    ];

    public static function index(){
        // declaring the file path for the new ebay titles file
        $new_titles_path = './Ebay Data/new ebay titles 2.csv';
        
        // opening the new ebay titles
        $file = fopen($new_titles_path, 'r');
        
        // Looping through the new titles and putting it into the new_title_file_contents array
        while (($row = fgetcsv($file)) !== FALSE) {
            if($row[1] != 'ID' && $row[8] != 'Characters'){
                self::$new_title_file_contents[] = $row;
            }
        }
        fclose($file);
        
        // gets all the subdirectories from the src directory
        $csvPaths = glob('./EKM Data/*');

        self::$attributes = [ 'counter' => 1, 'size' => count($csvPaths) + 1];
        
        // loops over the images in the src directory
        foreach($csvPaths as $csvPath){
            self::update_client('Saving EKM CSV Rows');
            // opening the file passed
            $file = fopen($csvPath, 'r');

            // looping through the file and storing the rows in the file_contents array
            while (($row = fgetcsv($file)) !== FALSE) {
                //$row is an array of the csv elements
                if($row[0] != 'Action' && $row[92] != 'Delivery:Standard Shipping EU'){
                    self::$ekm_data_file_contents[] = $row;
                }
            }
            fclose($file);
        }
        
        self::update_new_titles();
        self::refactor_ekm_data();
        self::compare_with_ebay_listings();
        self::check_for_string_length_errors();
        self::save_refactored_file_content();
    }

    private static function update_client($message){
        self::$attributes['counter']++;
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
        echo var_export($message, true) . "\n";
        echo var_export(self::$attributes['counter'] . ' out of ' . self::$attributes['size'] . ' (' . ((self::$attributes['counter'] / self::$attributes['size']) * 100) . '%)', true) . "\n\n";
    }

    private static function update_new_titles(){
        self::$attributes = [ 'counter' => 1, 'size' => count(self::$new_title_file_contents) + 1];

        // check the new ebay titles file to see if the row matches a newly changed title
        // if so, then the $row title will become inherit the newly changed title & will be pushed to the file_contents array
        // if not, the title does not need to be changed, and will be pushed to the file_ contents array
        foreach(self::$new_title_file_contents as $new_title_file_contents_row){
            self::update_client('Updating new title');
            
            $characters = intval($new_title_file_contents_row[5]);
            for ($i=0; $i < count(self::$ekm_data_file_contents); $i++) { 
                if (self::$ekm_data_file_contents[$i][1] == $new_title_file_contents_row[1]) {
                    self::$ekm_data_file_contents[$i][3] = $new_title_file_contents_row[4];

                    // if ids match, then the title will change
                    if ($characters > 80) {
                        self::$ekm_data_file_contents[$i][3] = $new_title_file_contents_row[7];
                        echo var_export(self::$ekm_data_file_contents[$i][3], true) . "\n";
                        echo var_export(self::$ekm_data_file_contents[$i][3], true) . "\n";
                    }
                }
            }
        }
    }

    private static function refactor_ekm_data(){
        // adding the first row to the array
        self::$refactored_file_content[] = [
            'type', 'site', 'title', 'enable_category_mapping', 'condition_id',
            'dispatch_time_max', 'listing_duration', 'listing_type', 'private_listing',
            'payment_methods', 'paypal_email_address', 'payment_profile_id',
            'shipping_profile_id', 'returns_profile_id', 'primary_category_id', 
            'store_category1_id', 'compat_name', 'compat_value', 'sku',	'ean', 'brand',
            'mpn', 'quantity', 'min_remnant_set', 'postal_code', 'location', 'country',
            'media_url', 'media_type', 'measurement_unit', 'depth', 'length', 'width',
            'weight_major', 'weight_minor', 'currency', 'start_price', 'sale_price',
            'best_offer_min', 'specific_name', 'specific_value', 'template_id', 'description',
        ];

        self::$attributes = [ 'counter' => 1, 'size' => count(self::$ekm_data_file_contents) + 1];

        foreach(self::$ekm_data_file_contents as $ekm_data_file_contents_row){
            self::update_client('Refactoring EKM Files');

            // getting all the columns that can contain image links
            $ekm_data_file_contents_row_images = [
                $ekm_data_file_contents_row[19], $ekm_data_file_contents_row[20], $ekm_data_file_contents_row[21], $ekm_data_file_contents_row[22],
                $ekm_data_file_contents_row[23], $ekm_data_file_contents_row[24], $ekm_data_file_contents_row[25], $ekm_data_file_contents_row[26]
            ];

            // looping through the columns on the specific row and only using the columns that have image links
            $ekm_data_file_contents_row_images_urls = [];
            foreach($ekm_data_file_contents_row_images as $image){
                $tmp_image = strtolower($image);
                if (str_contains($tmp_image, 'http')){
                    array_push($ekm_data_file_contents_row_images_urls, $image);
                }
            }

            // getting first level catagory
            $ekm_data_file_contents_row_catagory_path_exploded = explode('>', $ekm_data_file_contents_row[2]);
            $first_level_category = $ekm_data_file_contents_row_catagory_path_exploded[1];
            $description_catagory_tag = '';

            // depending on what the first level catagory was equal to, it would change the description_catagory_tag variable
            if(str_contains($first_level_category, 'Road Use') || str_contains($first_level_category, 'Handling Packs') || str_contains($first_level_category, 'Universal Bushes') || str_contains($first_level_category, 'Jack Pads')) $description_catagory_tag = '[road-series]';
            if(str_contains($first_level_category, 'Motorsport')) $description_catagory_tag = '[black-series]';
            if(str_contains($first_level_category, 'Heritage')) $description_catagory_tag = '[heritage]';

            self::$refactored_file_content[] = [
                'Listing', // type
                'UK',  // site
                $ekm_data_file_contents_row[3], // title
                true, // enable_category_mapping
                1000, // condition_id
                10, // dispatch_time_max
                'GTC', // listing_duration
                'FixedPriceItem', // listing_type
                'FALSE', // private_listing
                '', // payment_methods
                '', // paypal_email_address
                012345678991, // payment_profile_id
                012345678991, // shipping_profile_id
                012345678991, // returns_profile_id
                123456, // primary_category_id
                012345678991, // store_category1_id
                '', // compat_name
                '', // compat_value
                $ekm_data_file_contents_row[4], // sku
                '', // ean
                $ekm_data_file_contents_row[7], // brand
                '', // mpn
                1, // quantity
                1, // min_remnant_set
                'BS4 2JP', // postal_code
                'Bristol', // location
                'GB', // country
                implode(',', $ekm_data_file_contents_row_images_urls), // media_url
                'Picture', // media_type
                'Metric', // measurement_unit
                '', // depth
                '', // length
                '', // width
                explode('.', strval($ekm_data_file_contents_row[30]))[0], // weight_major
                explode('.', strval($ekm_data_file_contents_row[30]))[1], // weight_minor
                'GBP', // currency
                $ekm_data_file_contents_row[8], // start_price
                '', // sale_price
                '', // best_offer_min
                '', // specific_name
                '', // specific_value
                '', // template_id
                $ekm_data_file_contents_row[5] . ' ' . $description_catagory_tag, // description
            ];
        }
    }

    private static function compare_with_ebay_listings(){
        // declaring array where ebay listings will be stored 
        $path_to_ebay_listings = 'Ebay Data\listings.csv';

        $duplicate_listings_first_row = fopen('Duplicate Products.csv', 'a');
        fputcsv($duplicate_listings_first_row, [
            'item ID',
            'title of the listing',
            'quntity of sold items',
        ]);
        fclose($duplicate_listings_first_row);

        // opening the file
        $ebay_file = fopen($path_to_ebay_listings, 'r');

        $duplicate_listings = fopen('Duplicate Products.csv', 'a');
        
        // looping through the ebay listings file and storing the rows in the file_contents array
        while (($row = fgetcsv($ebay_file)) !== FALSE) {            
            $ebay_listing_title = $row[4];

            foreach(self::$refactored_file_content as $refactored_file_content_row){
                $refactored_file_content_row_title = $refactored_file_content_row[2];

                // ItemID,Category,StoreCat1ID,StoreCat2ID,Title,Price,Description,MainImage,ExtraImages,ConditionID,ConditionDescription,Stock,SoldStock
                if ($ebay_listing_title == $refactored_file_content_row_title) {
                    fputcsv($duplicate_listings, [
                        $row[0], // 'item ID'
                        $row[4], // 'title of the listing',
                        $row[12], // 'quntity of sold items',
                    ]);
                }
            }
        }
        fclose($ebay_file);
        fclose($duplicate_listings);
    }

    private static function check_for_string_length_errors(){
        self::$attributes = [ 'counter' => 1, 'size' => count(self::$new_title_file_contents) + 1];

        $string_length_errors = fopen('String Length Errors.csv', 'a');
        fputcsv($string_length_errors, [
            'Item ID',
            'Title of the listing',
            'Characters',
        ]);
        
        foreach(self::$ekm_data_file_contents as $file_contents_row){
            self::update_client('Checking for ebay string length errors');
            
            $characters = strlen($file_contents_row[3]);
            if ($characters > 80) {
                fputcsv($string_length_errors, [
                    $file_contents_row[1],
                    $file_contents_row[3],
                    $characters,
                ]);
            }
        }
        fclose($string_length_errors);
    }

    private static function save_refactored_file_content(){
        self::$attributes = [ 'counter' => 1, 'size' => count(self::$refactored_file_content) + 1];

        // this saves each row in the csv to a csv
        foreach(self::$refactored_file_content as $product){
            self::update_client('Saving Refactored File Content');

            $fp = fopen('Refactored EKM Data.csv', 'a');
            fputcsv($fp, $product);
        }
        fclose($fp);
        echo var_export('Finished!', true);
    }
}
ImageFormatter::index();