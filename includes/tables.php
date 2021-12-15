<?php
/**
 * activarlo creará la tabla, y al desactivarlo la borrará.
 */
defined('ABSPATH') or die('No no no');

function jnj_activation()
{
    global $wpdb;
    $sql = 'CREATE TABLE '.$wpdb->prefix.'a_new_table ('
        .'col1 DATETIME NOT NULL,'
        .'col2 VARCHAR(256) NOT NULL,'
        .'col3 VARCHAR(64) NOT NULL'
        .');';
    $wpdb->get_results($sql);
}

function jnj_deactivation()
{
    global $wpdb;
    $sql = 'DROP TABLE '.$wpdb->prefix.'a_new_table;';
    $wpdb->get_results($sql);
}

register_activation_hook(__FILE__, 'jnj_activation');
register_deactivation_hook(__FILE__, 'jnj_deactivation');