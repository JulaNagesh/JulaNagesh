<?php

namespace Techouts\MageCustom\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\ResourceConnection;

use Techouts\MageCustom\Model\ImportImageService;

//use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class AddingProduct implements ResolverInterface
{

    protected $_saveproduct;
    //protected $_productCollectionFactory;
    protected $_resourceConnection;
    protected $_importImge;

    public function __construct(ProductFactory $saveproduct, ResourceConnection $resourceConnection, ImportImageService $importImage)
    {
        $this->_saveproduct = $saveproduct;
        //$this->_productCollectionFactory = $productCollectionFactory;
        $this->_resourceConnection = $resourceConnection;
        $this->_importImge = $importImage;
    }
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        $product = $this->_saveproduct->create();

        $attrSetId = $product->setAttributeSetId($args['attribute_id']);

        $connection = $this->_resourceConnection->getConnection();

        $attributeSet = "Select * from eav_attribute_set where attribute_set_id ='" . $args['attribute_id'] . "'";
        $attSet = $connection->fetchAll($attributeSet);
        // print_r($attSet[0]['attribute_set_name']);die;

        if (!empty($attSet)) {
            if ($attSet[0]['attribute_set_name'] == 'Fragrance') {
                $product->setTopNotes($args['top_notes']);
                $product->setHeartNotes($args['heart_notes']);
                $product->setBaseNotes($args['base_notes']);
                $this->_importImge->execute($product, $args['image'], $visible = true, $imageType = ['image', 'small_image', 'thumbnail', 'swatch']);
            }
            if ($attSet[0]['attribute_set_name'] == 'Hair') {
                $product->setL3Code($args['l3_code']);
                $product->setHairType($args['hair_type']);
                $product->setRecommendedForConcerns($args['recommended_for_concerns']);
                $this->_importImge->execute($product, $args['image'], $visible = true, $imageType = ['image', 'small_image', 'thumbnail', 'swatch']);
            }
            if ($attSet[0]['attribute_set_name'] == 'Makeup') {
                $product->setFinish($args['finish']);
                $product->setCoverage($args['coverage']);
                $product->setWhatItDoesBenefits($args['what_it_does_benefits']);
                $product->setSkinTone($args['skin_tone']);
                $product->setSpf($args['spf']);
                $product->setShade($args['shade']);
                $product->setColorFamily($args['color_family']);
                $this->_importImge->execute($product, $args['image'], $visible = true, $imageType = ['image', 'small_image', 'thumbnail', 'swatch']);
            }
            if ($attSet[0]['attribute_set_name'] == 'Mens Grooming') {
                $product->setL3Code($args['l3_code']);
                $product->setBenefits($args['feature_benefits']);
                $product->setSpf($args['spf']);
                $this->_importImge->execute($product, $args['image'], $visible = true, $imageType = ['image', 'small_image', 'thumbnail', 'swatch']);
            }
            if ($attSet[0]['attribute_set_name'] == 'Personal Care') {
                $product->setL3Code($args['l3_code']);
                $product->setBenefits($args['feature_benefits']);
                $product->setRecommendedForConcerns($args['recommended_for_concerns']);
                $product->setSpf($args['spf']);
                $this->_importImge->execute($product, $args['image'], $visible = true, $imageType = ['image', 'small_image', 'thumbnail', 'swatch']);
            }
            if ($attSet[0]['attribute_set_name'] == 'Skin') {

                $product->setL3Code($args['l3_code']);
                $product->setBenefits($args['feature_benefits']);
                $product->setSpf($args['spf']);
                $this->_importImge->execute($product, $args['image'], $visible = true, $imageType = ['image', 'small_image', 'thumbnail', 'swatch']);
            }
        }
        $product->setWebsiteIds(array($args['website_id']));
        //$product->setAttributeSetId($args['attribute_id']);
        $product->setTypeId($args['type_id']); //simple/configurable/virtual
       // $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
       $product->setStatus($args['status']);
        // // print_r(json_decode(json_encode($product->getData()),true));die;
        $product->setName($args['product_name']);
        $product->setSku($args['sku']);
        $product->setBranName($args['brand']);
        // //assign products to categories

        $category_id = $args['categoryId'];
        $cat_id = explode(",", $category_id);
        $product->setCategoryIds($cat_id);
        //$product->setCategoryIds([$args['categoryId']]);

        $product->setVisibility($args['visibility']);
        $product->setPrice($args['price']);
        $product->setNameOfRange($args['name_of_range']);
        $product->setSubCategoryCode($args['sub_category_code']);
        $product->setProType($args['product_type']);
        $product->setInternalIdentificationCodeM($args['iic_mastersku']);
        $product->setOldSkuCode($args['old_sku_code']);
        $product->setEaCode($args['ean_code']);
        $product->setVariantType($args['variant_type']);
        $product->setVariantGrouping($args['variant_grouping']);
        $product->setVariantValue($args['variant_value']);
        $product->setSize($args['size']);
        $product->setPackOf($args['pack_of']);
        $product->setUnitSize($args['unit_size']);
        $product->setSizeUom($args['size_uom']);
        $product->setDimensions($args['dimensions']);
        $product->setDimensionsUom($args['dimensions_uom']);
        $product->setWeight($args['weight']);
        $product->setWeightUom($args['weight_uom']);
        $product->setShelfLife($args['shelf_life']);
        $product->setIngredients($args['ingredients']);
        $product->setIngredientPreference($args['ingredient_preferences']);
        $product->setHowToUse($args['how_to_use']);
        $product->setDisclaimersCautions($args['disclaimers_cautions']);
        $product->setGender($args['gender']);
        $product->setFormulation($args['formulation']);
        $product->setFragranceFamily($args['fragrance_family']);
        //selecting multiple attribute values
        $product->setData('occasion', $args['occasion']);
        // $product->setOccasion($args['occasion']);
        $product->setAge($args['age_group']);
        $product->setLocation($args['location']);
        $product->setTaxClassId($args['tax']);
        $product->setNewsFromDate($args['news_from_date']);
        $product->setNewsToDate($args['news_to_date']);
        $product->setCountryOfManufacture($args['country_of_manufacturer']);
        $product->setAddressOfTheManufacturer($args['address_of_manufacturer']);
        $product->setNameOfTheManufacturer($args['name_of_manufacturer']);
        $product->setSupplierSAddress($args['supplier_s_address']);
        $product->setSupplierSName($args['supplier_s_name']);
        $product->setReturnPeriod($args['return_period']);
        $product->setShortDescription($args['short_description']);
        $product->setMetaTitle($args['meta_tile']);
        $product->setMetaDescription($args['meta_description']);
        $product->setMetaKeyword($args['meta_keyword']);
        $product->setCodAvailable($args['is_cod']);
        $product->setQuantityAndStockStatus(['qty' => $args['qty'], 'is_in_stock' => $args['stock']]);

        if ($product->save()) {
            return [
                "status" => true,
                "message" => "product added succesfully",
            ];
        }
    }
}
