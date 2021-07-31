<?php

/**
 * Fired during plugin activation
 *
 * @link       https://bivek.ca
 * @since      1.0.0
 *
 * @package    Yapt
 * @subpackage Yapt/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Yapt
 * @subpackage Yapt/includes
 * @author     bvk <bivek_j@yahoo.com>
 */
class Yapt_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) {
            // check if it is a network activation - if so, run the activation function for each blog id
            $old_blog = $wpdb->blogid;
            // Get all blog ids
            $blogids = $wpdb->get_col("SELECT `blog_id` FROM $wpdb->blogs");
            foreach ($blogids as $blog_id) {
                switch_to_blog($blog_id);
                // create tables
                (new Yapt_Activator)->create_tables();
            }
            switch_to_blog($old_blog);
        } else {
            // create tables
            (new Yapt_Activator)->create_tables();
        }
    }

    /**
     * Create tables when activating plugin
     */
    private function create_tables()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        global $wpdb;
        // yapt_pricing_tables
        $table_name = $wpdb->prefix . 'yapt_pricing_tables';
        $charset_collate = $wpdb->get_charset_collate();
        if ($wpdb->get_var("show tables like '{$table_name}'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
             `id` INT(11) NOT NULL AUTO_INCREMENT,
             `pt_title` VARCHAR(255) NOT NULL,
             `template_id` INT(11) NOT NULL,
			 `created_at` DATETIME NOT NULL,
			 `updated_at` DATETIME NOT NULL,
			  PRIMARY KEY id (id)
		)$charset_collate;";
            dbDelta($sql);
        }

        // yapt_templates
        $table_name = $wpdb->prefix . 'yapt_templates';
        $charset_collate = $wpdb->get_charset_collate();
        if ($wpdb->get_var("show tables like '{$table_name}'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
             `id` INT(11) NOT NULL AUTO_INCREMENT,
             `template_name` VARCHAR(255) NOT NULL,
             `style` VARCHAR(255) NOT NULL,
             `html` VARCHAR(255) NOT NULL,
			 `created_at` DATETIME NOT NULL,
			 `updated_at` DATETIME NOT NULL,
			  PRIMARY KEY id (id)
		)$charset_collate;";
            dbDelta($sql);
        }

        // yapt_columns
        $table_name = $wpdb->prefix . 'yapt_columns';
        $charset_collate = $wpdb->get_charset_collate();
        if ($wpdb->get_var("show tables like '{$table_name}'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
             `id` INT(11) NOT NULL AUTO_INCREMENT,
             `column_title` VARCHAR(255) NOT NULL,
             `table_id` VARCHAR(255) NOT NULL,
             `price_text` VARCHAR(255) NOT NULL,
             `ctoa_btn_text` VARCHAR(255) NOT NULL,/** ctoa => call to action */
             `ctoa_btn_link` VARCHAR(255) NOT NULL,
			 `created_at` DATETIME NOT NULL,
			 `updated_at` DATETIME NOT NULL,
			  PRIMARY KEY id (id)
		)$charset_collate;";
            dbDelta($sql);
        }

        // yapt_features
        $table_name = $wpdb->prefix . 'yapt_features';
        $charset_collate = $wpdb->get_charset_collate();
        if ($wpdb->get_var("show tables like '{$table_name}'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
             `id` INT(11) NOT NULL AUTO_INCREMENT,
             `column_id` INT(11) NOT NULL,
             `feature_text` VARCHAR(255) NOT NULL,
             `is_set` ENUM('0', '1') NOT NULL DEFAULT '1',
			 `created_at` DATETIME NOT NULL,
			 `updated_at` DATETIME NOT NULL,
			  PRIMARY KEY id (id)
		)$charset_collate;";
            dbDelta($sql);
        }
    }
}