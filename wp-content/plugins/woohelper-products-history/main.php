<?php

/*
Plugin Name: Woo Helper Products History
Pugin URI:
Description: create restore point for products
Version: 0.0.0.1
*/
add_action( 'init', 'script_enqueuer' );

function script_enqueuer() {

   // Register the JS file with a unique handle, file location, and an array of dependencies
 //  wp_register_script( "liker_script", plugin_dir_url(__FILE__).'liker_script.js', array('jquery') );

   // localize the script to your domain name, so that you can reference the url to admin-ajax.php file easily
 //  wp_localize_script( 'liker_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

   // enqueue jQuery library and the script you registered above
   wp_enqueue_script( 'jquery' );
 //  wp_enqueue_script( 'liker_script' );

}

function woo_helper_products_history_admin_menu() {
 add_submenu_page(  'edit.php?post_type=product',
 'Woo Helper Products History',
 'Products History',
 'manage_options',
 'woo_helper_products_history',
 'woo_helper_products_history_admin_page');

 $woo_helper_exports_orders_nonce = wp_create_nonce( 'woo-helper-products-history-nonce' );

}
add_action( 'admin_menu', 'woo_helper_products_history_admin_menu' );

function woo_helper_products_history_admin_page(){
	?>
	<style>
		.restore_point_table{

			width:95%;


		}

		.restore_point_column{
			float:left;
			width:19%;
			min-height:60px;
			text-align: center;
			border: black 1px solid;
		}
	</style>

	<div><h1>Products History<h1></div>
	<div class="restore_point_table">
		<div class=restore_point_row>
		<div class=restore_point_column>
					Id
			</div>
			<div class=restore_point_column>
					Date
			</div>
			<div class=restore_point_column>
					Product Name
			</div>
			<div class=restore_point_column>
					<a href="#">restore to this point</a>
			</div>
			<div class=restore_point_column>
					<a href="#">duplicate to check history</a>
			</div>
	</div>

	<script type="text/javascript">

	function send(id,restore){
		alert("button was press");
	jQuery.ajax({
        type:'post',
		dataType: 'json',
		url: ajaxurl,
        data: {
            action: 'my_action_name',
			data: id,
			restore: restore
        },
		success:function(response){
			alert(response);
		}

    });

	}
</script>

	<?php
	global $wpdb;

	$result = $wpdb->get_results("SELECT * FROM products_restore_points",'ARRAY_A');
	echo "<div></div>";


	for($i = 0; $i < count($result); ++$i){
		?>

		<div class=restore_point_row>
		<div class=restore_point_column>
				    <?php echo $result[$i]['id']; ?>
			</div>
			<div class=restore_point_column>
					 <?php echo $result[$i]['time']; ?>
			</div>
			<div class=restore_point_column>
					<?php echo $result[$i]['PRODUCT_NAME']; ?>
			</div>
			<div class=restore_point_column>
					<button onclick="send(<?php echo $result[$i]['id']; ?>, true);"  value ="<?php echo $result[$i]['id']; ?>">restore to this point</button>
			</div>
			<div class=restore_point_column>
					<button onclick="send(<?php echo $result[$i]['id']; ?>, false);" value ="<?php echo $result[$i]['id']; ?>">duplicate to check history</button>
			</div>
	</div>

	<?php

		
	}

	$id = 19;
	
	$sql = $wpdb->prepare("SELECT * FROM `products_data` WHERE products_restore_points_id = %s AND parent_id = 0",$id);
	$result = $wpdb->get_results($sql,'ARRAY_A');
	echo '<br/>';
	//echo $result[0]['regular_price'];
	//echo json_encode($result);
	echo '<br/>';


	$product = wc_get_product(17);

	$children = $product->get_children();
	echo json_encode($children);
	//echo json_encode($children);
	
	
	foreach($children as $child_id){
		echo 'test';
		$p = wc_get_product($child_id);
		echo '<br/>'.$p->get_price();
	}

}

function duplicate_products($id){
	global $wpdb;

	$productPage = new WC_Admin_Duplicate_Product;

	$sql = $wpdb->prepare("SELECT * FROM `products_data` WHERE products_restore_points_id = %s AND parent_id = 0",$id);
	$result = $wpdb->get_results($sql,'ARRAY_A');


 $product = $productPage->product_duplicate(wc_get_product($result[0]['product_id']));

 $children_products_ids = $product->get_children();
	if(!empty( $children_products_ids)){
 		foreach($children_products_ids as $child_id){
			
			$statement = $wpdb->prepare("SELECT * FROM `products_data` WHERE products_restore_points_id = %s AND product_id = %s",$id, $child_id);
			$get_info = $wpdb->get_results($statement,'ARRAY_A');

			$children = wc_get_product($child_id);
			$children->set_price($get_info[0]['price']);
   			$children->set_regular_price($get_info[0]['regular_price']);
 			$children->save();
 		}
	}

  wp_set_object_terms( $product->get_id(), $result[0]['type'], 'product_type' );

  $image_id = $result[0]['image_id'];
  update_post_meta($product->get_id(),'_thumbnail_id',$image_id);
/*
$gallery_image_ids = json_decode($result[0]['gallery_image_ids']);
$gallery_string_id;
for($i = 0; $i < count($gallery_image_ids); ++$i){
  if($i === 0){
    $gallery_string_id .= $gallery_image_ids[$i];
  }else{
    $gallery_string_id .= ",";
    $gallery_string_id .= $gallery_image_ids[$i];
  }
}
update_post_meta($product->get_id(),'_product_image_gallery',$gallery_string_id);


 $category_ids = json_decode($result[0]['category_ids']);
wp_set_object_terms($product->get_id(),  $category_ids, 'product_cat');

$product_tag_ids = json_decode($result[0]['tag_ids']);
 wp_set_object_terms( $product->get_id(), $product_tag_ids, 'product_tag' );

$product_attributes = json_decode($result[0]['attributes']);
$product_attributes = array("Size");
$product->set_attributes($product_attributes);
$a = array("small","large");
$product->set_attribute();
*/


	$product->set_name($result[0]['product_name']);

	$product->set_slug($result[0]['slug']);


	$product->set_featured($result[0]['featured']);

	$product->set_catalog_visibility($result[0]['catalog_visibility']);


	$product->set_description($result[0]['description']);
	$product->set_short_description($result[0]['short_description']);
	$product->set_sku($result[0]['sku']);
	$product->set_menu_order($result[0]['menu_order']);

	$product->set_price($result[0]['price']);
	$product->set_regular_price($result[0]['regular_price']);

	if($result[0]['sale_price'] !== '0'){
		$product->set_sale_price($result[0]['sale_price']);
	}


	$product->set_date_on_sale_from($result[0]['get_date_on_sale_from']);
	$product->set_date_on_sale_to($result[0]['get_date_on_sale_to']);


	$product->set_tax_status($result[0]['tax_status']);
	$product->set_tax_class($result[0]['tax_class']);

	$product->set_manage_stock($result[0]['manage_stock']);
	$product->set_stock_quantity($result[0]['stock_quantity']);


	$product->set_sold_individually($result[0]['sold_individually']);

	$product->set_purchase_note($result[0]['get_purchase_note']);
	$product->set_shipping_class_id($result[0]['shipping_class_id']);

	$product->set_weight($result[0]['weight']);

	$product->set_length($result[0]['length']);
	$product->set_height($result[0]['height']);
	$product->set_width($result[0]['width']);

  //$product_upsell = json_decode($result[0]['upsell_ids']);
//	$product->set_upsell_ids($product_upsell);

//  $product_cross_sell = json_decode($result[0]['cross_sell_ids']);
//	$product->set_cross_sell_ids($product_cross_sell);

 // $product_default_attributs = json_decode($result[0]['default_attributes']);
  
 

 
  


 
 
 
 $product->save();

 //$product_attributs = json_decode($result[0]['attributs']);
	//$product->set_attributs(plu);

/*

	$product->set_downloads(json_decode($result[0]['download']));
	$product->set_downloads(json_decode($result[0]['downloadable']));
	$product->set_download_limit($result[0]['download_limit']);
*/
  //$product_image = json_decode($result[0]['image_id']);
	//$product->set_image_id($result[0]['image_id']);



}


 function my_ajax_callback_function() {
		$data = $_POST['data'];
		$restore = $_POST['restore'];

		if($restore === "true"){
			echo json_encode("restore");
		}else if($restore === "false"){
			echo json_encode("duplicate");
			duplicate_products($data);
		//	add_post_meta(46, '_product_attributes');
		}


		wp_die();
    }
	add_action( 'wp_ajax_my_action_name', 'my_ajax_callback_function' );    // If called from front end
   add_action( 'wp_ajax_nopriv_my_action_name', 'my_ajax_callback_function' );    // If called from front end

class WooHelperPluginMaintenances
{
    public function __construct(){
         register_activation_hook( __FILE__, array($this, 'plugin_activated' ));
         register_deactivation_hook( __FILE__, array($this, 'plugin_deactivated' ));
    }

    public function plugin_activated(){
         // This will run when the plu
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE PRODUCTS_RESTORE_POINTS( id INT(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRODUCT_NAME VARCHAR(255) NOT NULL,
			PRODUCT_PARENT_POST_ID INT(9) NOT NULL,
			PRIMARY KEY (id)
			) $charset_collate;


	CREATE TABLE PRODUCTS_DATA(
Id INT(9) NOT NULL AUTO_INCREMENT,
products_restore_points_id INT(9),
product_id INT(9) NOT NULL,
type VARCHAR(255) NOT NULL,
product_name VARCHAR(255),
slug VARCHAR(255),
date_created DATETIME,
date_save DATETIME NOT NULL,
featured BOOLEAN DEFAULT false,
catalog_visibility VARCHAR(255),
description LONGTEXT,
short_description LONGTEXT,
sku VARCHAR(255),
menu_order INT(9),
virtual BOOLEAN DEFAULT false,
price DECIMAL,
regular_price DECIMAL,
sale_price DECIMAL,
get_date_on_sale_from VARCHAR(255),
get_date_on_sale_to VARCHAR(255),
tax_status TEXT,
tax_class TEXT,
manage_stock BOOLEAN DEFAULT FALSE,
stock_quantity INT,
stock_status TEXT,
backorders TEXT,
sold_individually BOOLEAN DEFAULT FALSE,
purchase_note LONGTEXT,
shipping_class_id TEXT,
weight DECIMAL,
length DECIMAL,
width DECIMAL,
height DECIMAL,
dimensions VARCHAR(255),
upsell_ids LONGTEXT,
cross_sell_ids LONGTEXT,
parent_id INT NOT NULL,
children VARCHAR(255),
attributes LONGTEXT,
default_attributes LONGTEXT,
categories TEXT,
category_ids TEXT,
tag_ids TEXT,
downloads TEXT,
download_expiry TEXT,
downloadable TEXT,
download_limit TEXT,
image_id TEXT,
image TEXT,
gallery_image_ids TEXT,
PRIMARY KEY(Id))$charset_collate;

ALTER TABLE products_data
ADD FOREIGN KEY(products_restore_points_id) REFERENCES products_restore_points(id)$charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
    }

    public function plugin_deactivated(){
         // This will run when the plugin is deactivated, use to delete the database
    }
}


function run_one_save($post_id){

if( isset($_POST['action_performed']) ){
      //Prevent running the action twice
      return;
    }
global $wpdb;




	$current_date = date('Y-m-d h:i:s');
	$current_product = wc_get_product($post_id);


		$table_name = 'PRODUCTS_RESTORE_POINTS';
		$wpdb->insert($table_name, array(
			'time' => $current_date,
			'PRODUCT_NAME' => $current_product->get_name(),
			'PRODUCT_PARENT_POST_ID' => $current_product->get_id()
		));

    $restore_point_key = $wpdb->get_var(
    $wpdb->prepare(
    "SELECT id FROM products_restore_points WHERE PRODUCT_PARENT_POST_ID = %s AND time = %s",
     $post_id,$current_date));

	$current_product_children_ids = $current_product->get_children();


    $product = wc_get_product($post_id);

    $table_name = 'products_data';
    $wpdb->insert($table_name, array(
  'products_restore_points_id' => $restore_point_key,
  'product_id' => $product->get_id(),
  'type' => $product->get_type(),
  'product_name' => $product->get_name(),
  'slug' => $product->get_slug(),
  'date_created' => date($product->get_date_created()),
  'date_save' => date($product->get_date_created()),
  'featured' => $product->get_featured(),
  'catalog_visibility' => $product->get_catalog_visibility(),
  'description' => $product->get_description(),
  'short_description' => $product->get_short_description(),
  'sku' => $product->get_sku(),
  'menu_order' => $product->get_menu_order(),
  'virtual' => $product->get_virtual(),
  'price' => $product->get_price(),
  'regular_price' => $product->get_regular_price(),
  'sale_price' => $product->get_sale_price(),
  'get_date_on_sale_from' => date($product->get_date_on_sale_from()),
  'get_date_on_sale_to' => date($product->get_date_on_sale_to()),
  'tax_status' => $product->get_tax_status(),
  'tax_class' => $product->get_tax_class(),
  'manage_stock' => $product->get_manage_stock(),
  'stock_quantity' => $product->get_stock_quantity(),
  'stock_status' => $product->get_stock_status(),
  'backorders' => $product->get_backorders(),
  'sold_individually' => $product->get_sold_individually(),
  'purchase_note' => $product->get_purchase_note(),
  'shipping_class_id' => $product->get_shipping_class_id(),
  'weight' => $product->get_weight(),
  'length' => $product->get_length(),
  'height' => $product->get_height(),
  'width' => $product->get_width(),
  'upsell_ids' => json_encode($product->get_upsell_ids()),
  'cross_sell_ids' => json_encode($product->get_cross_sell_ids()),
  'parent_id' => $product->get_parent_id(),
  'children' => $product->get_children(),
  'attributes' => json_encode($product->get_attributes()),
  'default_attributes' => json_encode($product->get_default_attributes()),
  'categories' =>  wc_get_product_category_list($post_id),
  'category_ids' => json_encode($product->get_category_ids()),
  'tag_ids' => json_encode($product->get_tag_ids()),
  'downloads' => json_encode($product->get_downloads()),
  'download_expiry' => $product->get_download_expiry(),
  'downloadable' => json_encode($product->get_downloadable()),
  'download_limit' => $product->get_download_limit(),
  'image_id' => $product->get_image_id(),
  'image' => $product->get_image(),
  'gallery_image_ids'=> json_encode($product->get_gallery_image_ids())

  ));



		if(!empty($current_product_children_ids)){


			for($i = 0; $i < sizeof($current_product_children_ids); ++$i){
				$product = wc_get_product($current_product_children_ids[$i]);

				$table_name = 'products_data';
				$wpdb->insert($table_name, array(
			'products_restore_points_id' => $restore_point_key,
			'product_id' => $product->get_id(),
			'type' => $product->get_type(),
			'product_name' => $product->get_name(),
			'slug' => $product->get_slug(),
			'date_created' => date($product->get_date_created()),
			'date_save' => date($product->get_date_created()),
			'featured' => $product->get_featured(),
			'catalog_visibility' => $product->get_catalog_visibility(),
			'description' => $product->get_description(),
			'short_description' => $product->get_short_description(),
			'sku' => $product->get_sku(),
			'menu_order' => $product->get_menu_order(),
			'virtual' => $product->get_virtual(),
			'price' => $product->get_price(),
			'regular_price' => $product->get_regular_price(),
			'sale_price' => $product->get_sale_price(),
			'get_date_on_sale_from' => date($product->get_date_on_sale_from()),
			'get_date_on_sale_to' => date($product->get_date_on_sale_to()),
			'tax_status' => $product->get_tax_status(),
			'tax_class' => $product->get_tax_class(),
			'manage_stock' => $product->get_manage_stock(),
			'stock_quantity' => $product->get_stock_quantity(),
			'stock_status' => $product->get_stock_status(),
			'backorders' => $product->get_backorders(),
			'sold_individually' => $product->get_sold_individually(),
			'purchase_note' => $product->get_purchase_note(),
			'shipping_class_id' => $product->get_shipping_class_id(),
			'weight' => $product->get_weight(),
			'length' => $product->get_length(),
			'height' => $product->get_height(),
			'width' => $product->get_width(),
			'upsell_ids' => json_encode($product->get_upsell_ids()),
			'cross_sell_ids' => json_encode($product->get_cross_sell_ids()),
			'parent_id' => $product->get_parent_id(),
			'children' => $product->get_children(),
			'attributes' => json_encode($product->get_attributes()),
			'default_attributes' => json_encode($product->get_default_attributes()),
			'categories' =>  wc_get_product_category_list($post_id),
			'category_ids' => json_encode($product->get_category_ids()),
			'tag_ids' => json_encode($product->get_tag_ids()),
			'downloads' => json_encode($product->get_downloads()),
			'download_expiry' => $product->get_download_expiry(),
			'downloadable' => json_encode($product->get_downloadable()),
			'download_limit' => $product->get_download_limit(),
			'image_id' => $product->get_image_id(),
			'image' => $product->get_image(),
			'gallery_image_ids'=> json_encode($product->get_gallery_image_ids())


		));

			}
		}

	$_POST['action_performed'] = true;

}

add_action('woocommerce_update_product','run_one_save');

new  WooHelperPluginMaintenances();

?>
