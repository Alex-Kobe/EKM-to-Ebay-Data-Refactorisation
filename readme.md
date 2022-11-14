# EKM to Ebay Data Refactorisation

This is a php based script for refactoring product information that comes from EKM. The purpose of this is refactor product information from EKM into a format that Ebay will accept via their api. This script is multi-purposeful & below, I will show the diffarent functionality this script has.

### Update New Titles
Since eBay format their database diffarently to EKM, you may find that product titles/names do not conform with eBays database rules. This feature allows for updating the titles that come in from a product. 

### Refactor EKM Data
This function simply refactor the EKM data into a format that ebays api will accept.

### Compare With Ebay Listings
The idea of this application was that you may already have an ebay store and simply want to update the products on your ebay store with the products on your EKM store. This function compares your ebay store lisings, with your EKM store listings, and creates a file named "Duplicate Products.csv" which will display the eBay Product ID, Product Name & Quntity Of Sold Items".

### Check For String Length Errors
This function is allows you to further check if the the product titles you have used are within the 80 character eBay database limit. This will create a file named "String Length Errors.csv" that shows the products that do not conform to eBays standards.

### Save Refactored File Content
This creates a new file named "Refactored EKM Data.csv" that will store the data that the eBay api will take to import the new products into eBay.


## Prerequisites 

- php 8: To use this script, you will need to have php 8. Some functions on this script where released in php 8, but the code can be changed to suit whatever php version you use.
- php.ini: I have not had issues with running this script, but my php.ini file has been modified. You may have to modify your php.ini file to allow for higher memory allowance, etc... 


## Installation

You can download this script onto your computer but you will need to relavent files to allow the application to function.


## Usage

### Running the file

To run the file you will need to run the following command into your teminal. I use Git Bash to run this command

```
php index.php
```

### Disabling Functions
The mejority of my functions are controlled via these functions below. If you do not require any of these functions during your usage of the application, comment the functions that you do not requrie

```
self::update_new_titles();
self::refactor_ekm_data();
self::compare_with_ebay_listings();
self::check_for_string_length_errors();
self::save_refactored_file_content();
```

### New eBay Titles.csv
The new titles file that I based my code off is not formatted in a convienent way to replicate my solution. If you require this feature, you will need to rewite this part of my code to allow for a more standardised format. An example of a csv row format that would be suitable for this usage could be.

| productID | oldTitle | characters | newTitle | characters |
|-----------|----------|------------|----------|------------|

### eBay Listings.csv
To check for duplicate listings, you will need your eBay stores listings. Below is the row names that will help you format the file. These rows are strict and will have to be ordered in the order it is displayed below.

| Rows                                                  |
|-------------------------------------------------------|
| ItemID                                                |
| Category                                              |
| StoreCat1ID                                           |
| StoreCat2ID                                           |
| Title                                                 |
| Price                                                 |
| Description                                           |
| MainImage                                             |
| ExtraImages                                           |
| ConditionID                                           |
| ConditionDescription                                  |
| Stock                                                 |
| SoldStock                                             |

### EKM Data/*.csv
You will need one or more files containing your product data. The files have to in the form of a .csv file format & below is the row names that will help you format the file. These rows are strict and will have to be ordered in the order it is displayed below.

| Rows                                                |
|-------------------------------------------------------|
| ID                                                    |
| Action                                                |
| ID                                                    |
| CategoryPath                                          |
| Name                                                  |
| Code                                                  |
| Description                                           |
| ProductSummary                                        |
| Brand                                                 |
| Price                                                 |
| RRP                                                   |
| Image1                                                |
| Image2                                                |
| Image3                                                |
| Image4                                                |
| Image5                                                |
| Image6                                                |
| Image7                                                |
| Image8                                                |
| Image1Address                                         |
| Image2Address                                         |
| Image3Address                                         |
| Image4Address                                         |
| Image5Address                                         |
| Image6Address                                         |
| Image7Address                                         |
| Image8Address                                         |
| MetaTitle                                             |
| MetaDescription                                       |
| MetaKeywords                                          |
| Stock                                                 |
| Weight                                                |
| TaxRateID                                             |
| Condition                                             |
| SpecialOffer                                          |
| OrderLocation                                         |
| OrderNote                                             |
| Hidden                                                |
| CategoryManagement                                    |
| CategoryManagementOrder                               |
| RelatedProducts                                       |
| OptionName                                            |
| OptionSize                                            |
| OptionType                                            |
| OptionValidation                                      |
| OptionItemName                                        |
| OptionItemPriceExtra                                  |
| OptionItemOrder                                       |
| OptionVariantOrder                                    |
| VariantNames                                          |
| VariantTypes                                          |
| VariantCategoryPage                                   |
| VariantChoiceName                                     |
| VariantItem1                                          |
| VariantItem1Data                                      |
| VariantItem2                                          |
| VariantItem2Data                                      |
| VariantItem3                                          |
| VariantItem3Data                                      |
| VariantItem4                                          |
| VariantItem4Data                                      |
| VariantItem5                                          |
| VariantItem5Data                                      |
| VariantDefault                                        |
| WebAddress                                            |
| CanBeAddedToCart                                      |
| PromoStickers                                         |
| CostPrice                                             |
| TaxRateName                                           |
| OptionPlaceHolder                                     |
| Attribute:BUSHSIZE                                    |
| Attribute:DIAGRAMPOSITION                             |
| Attribute:EAN                                         |
| Attribute:MAKE                                        |
| Attribute:MANUFACTURERPARTNUMBER                      |
| Attribute:MODEL                                       |
| Attribute:MPN                                         |
| Attribute:OEM                                         |
| Attribute:OEM1                                        |
| Attribute:OEM2                                        |
| Attribute:OEM3                                        |
| Attribute:PARTNUMBER                                  |
| Attribute:PRODUCTNOTES                                |
| Attribute:QUANTITYSUPPLIED                            |
| Attribute:RRP                                         |
| Attribute:SERIES                                      |
| Attribute:SHORTDESCRIPTION                            |
| Attribute:SKU","Attribute:VARIANTS                    |
| Attribute:VARIANTS1                                   |
| Delivery:Digital Delivery                             |
| Delivery:Standard Delivery                            |
| Delivery:Standard International Delivery (Royal Mail) |
| Delivery:Standard Shipping EU                         |