<?php
/*
Plugin Name: Intellum E-Learning Plugin
Plugin URI: http://www.intellum.com
Version: v0.1.1
Author: <a href="http://www.intellum.com">Intellum</a>
Description: Add specific questions to your blog post in the sidebar.

Copyright 2010  Intellum  (email : james DOT charlesworth [a t ] g m ail DOT com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributded in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/



if (!class_exists("IntellumElearning")) {
    
	class IntellumElearning {
                              
                var $adminOptionsName = "IntellumElearningAdminOptions";
                var $meta_box;
		function IntellumElearning() { //constructor
                   global $IntellumElearning_db_version;
                   $IntellumElearning_db_version = "1.0";
                   
                    $prefix = 'dbt_';

                        $this->meta_box = array(
                            'id' => 'elearning-meta-box',
                            'title' => 'E-Learning Critical Thinking',
                            'pages' => array('post', 'page'),
                            'context' => 'normal',
                            'priority' => 'high',
                            'fields' => array(
                                    array(
                                            'name' => 'Title',
                                            'desc' => 'Enter the title',
                                            'id' => $prefix . 'text',
                                            'type' => 'text',
                                            'std' => ''
                                    ),
                                    array(
                                            'name' => 'Message',
                                            'desc' => 'Add your message here',
                                            'id' => $prefix . 'textarea',
                                            'type' => 'textarea',
                                            'std' => ''
                                    ),
                                    array(
                                            'name' => 'Type',
                                            'id' => $prefix . 'select',
                                            'type' => 'select',
                                            'options' => array('Question', 'Tip', 'Reminder', 'Note')
                                    )
                                /*
                                    array(
                                            'name' => 'Radio',
                                            'id' => $prefix . 'radio',
                                            'type' => 'radio',
                                            'options' => array(
                                                    array('name' => 'Name 1', 'value' => 'Value 1'),
                                                    array('name' => 'Name 2', 'value' => 'Value 2')
                                            )
                                    ),
                                    array(
                                            'name' => 'Checkbox',
                                            'id' => $prefix . 'checkbox',
                                            'type' => 'checkbox'
                                    )*/
                            )
                    );


		}

                function init() {
                    $this->getAdminOptions();
                

               
                    
                }

              

                function getAdminOptions() {
                    $IntellumElearningAdminOptions = array('intellum_link' => 'on');

                    $IntellumElearningOptions = get_option($this->adminOptionsName);
                    if (!empty($IntellumElearningOptions)) {
                        foreach ($IntellumElearningOptions as $key => $option)
                            $IntellumElearningAdminOptions[$key] = $option;
                    }
                    update_option($this->adminOptionsName, $IntellumElearningAdminOptions);



                    return $IntellumElearningAdminOptions;


                }

                function printAdminPage() {
                    global $wpdb;

                    $IntellumElearningOptions = $this->getAdminOptions();

                    if (isset($_POST['update_ElearningSettings'])) {

                 
                            $IntellumElearningOptions['intellum_link'] = $_POST['intellum_link'];
                  

                      


                        update_option($this->adminOptionsName, $IntellumElearningOptions);

                       ?>
                        <div class="updated"><p><strong><?php _e("Settings Updated.", "IntellumElearning");?></strong></p></div>

                     <?php
                    }

                    ?>
                        <div class=wrap>
                        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                        <h2>Intellum E-Learning </h2>

                        <label for="intellum_link">Link to Intellum for creating such a cool plugin!</label>
                        <input type="checkbox" name="intellum_link" <?php if ($IntellumElearningOptions['intellum_link']=='on') echo 'CHECKED'; ?> />

                        


                        <div class="submit">
                        <input type="submit" name="update_ElearningSettings" value="<?php _e('Update Settings', 'IntellumElearning') ?>" /></div>
                        </form>


                         </div><?php



                }

                function addHeaderCode() {
                           global $user_ID;

                     //$user_info = get_userdata($user_ID);

                     echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/e-learning-critical-thinking/css/style.css" />' . "\n";

                    if (function_exists('wp_enqueue_script')) {
                        wp_enqueue_script('IntellumElearning_a', get_bloginfo('wpurl') . '/wp-content/plugins/e-learning-critical-thinking/js/jquery-ui-1.8.2.custom.min.js', array('jquery'), '0.1');
                        wp_enqueue_script('IntellumElearning_b', get_bloginfo('wpurl') . '/wp-content/plugins/e-learning-critical-thinking/js/IntellumElearning.php', array('jquery'), '0.1');

                    }

                    $IntellumElearningOptions = $this->getAdminOptions();





                }

                function addAdminHead() {
                      print '<link rel="stylesheet" type="text/css" href="'.get_option('siteurl').'/wp-content/plugins/e-learning-critical-thinking/css/admin.css" />';
  
                  
                }

                function addAdminInit() {

                      if (function_exists('wp_enqueue_script')) {
                        wp_enqueue_script('IntellumElearning_admin', get_bloginfo('wpurl') . '/wp-content/plugins/e-learning-critical-thinking/js/admin.js', array('jquery'), '0.1');


                    }

                }

                function addFooterCode() {

                    global $user_ID;
                }

            
                function mytheme_add_box() {
                    
                     foreach($this->meta_box['pages'] as $page) {
                         add_meta_box($this->meta_box['id'], $this->meta_box['title'], array(&$this,'mytheme_show_box'), $page, $this->meta_box['context'], $this->meta_box['priority']);
                     }

                }


                // Callback function to show fields in meta box
                function mytheme_show_box() {
                        global $post;

                        // Use nonce for verification
                        echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
                      
                  

                        $i=0;
                        //print_r($this->meta_box['fields']);

                         $meta_count = get_post_meta($post->ID, 'dbt_text', true);
                                for ($i=0;$i<count($meta_count);$i++) {
                                 echo '<table class="form-table elearning_fieldset">';
                        foreach ($this->meta_box['fields'] as $field) {
                                // get current post meta data
                          //  echo $field['id'];
                            $meta = get_post_meta($post->ID, $field['id'], true);
                          //$count = print_r($meta);
                            

                        
                           

                                echo '<tr>',
                                                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                                                '<td>';
                                switch ($field['type']) {
                                        case 'text':
                                                echo '<input class="elearning_field" type="text" name="', $field['id'], '[]" id="', $field['id'], '" value="', $meta[$i] ? $meta[$i] : $field['std'], '" size="30" style="width:97%" />',
                                                        '<br />', $field['desc'];
                                                break;
                                        case 'textarea':
                                                echo '<textarea class="elearning_field" name="', $field['id'], '[]" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta[$i] ? $meta[$i] : $field['std'], '</textarea>',
                                                        '<br />', $field['desc'];
                                                break;
                                        case 'select':
                                            //print_r($field['options']);
                                                echo '<select class="elearning_field" name="', $field['id'], '[]" id="', $field['id'], '">';
                                                foreach ($field['options'] as $option) {
                                                        echo '<option', $meta[$i] == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                                                }
                                                echo '</select>';
                                                break;
                                        case 'radio':
                                                foreach ($field['options'] as $option) {
                                                        echo '<input class="elearning_field" type="radio" name="', $field['id'], '[]" value="', $option['value'], '"', $meta[$i] == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                                                }
                                                break;
                                        case 'checkbox':
                                                echo '<input class="elearning_field" type="checkbox" name="', $field['id'], '[]" id="', $field['id'], '"', $meta[$i] ? ' checked="checked"' : '', ' />';
                                                break;
                                }
                                echo 	'<td>',
                                        '</tr>
';
                        }
                            echo '
<tr>
    <td colspan="2" align="right"><a class="delete_elearning_item" href="#">Delete</a></td>
</tr>
</table>';
                         }
                          echo '<a href="#" id="new_textbox">New Textbox</a>';

                    
                }

                function mytheme_save_data($post_id) {


                        // verify nonce
                        if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
                                return $post_id;
                        }

                        // check autosave
                        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                                return $post_id;
                        }

                        // check permissions
                        if ('page' == $_POST['post_type']) {
                                if (!current_user_can('edit_page', $post_id)) {
                                        return $post_id;
                                }
                        } elseif (!current_user_can('edit_post', $post_id)) {
                                return $post_id;
                        }

                      $to_delete = array();
                      $i=0;
                        foreach ($this->meta_box['fields'] as $field) {
                           // print_r($field);
                       //     foreach($fields as $field) {
                             

                                $old = get_post_meta($post_id, $field['id'], true);
                                $new = $_POST[$field['id']];
                               // print_r($old);
                            
                              
                                if ($new && $new != $old) {
                                        update_post_meta($post_id, $field['id'], $new);
                                } elseif ('' == $new && $old) {
                                        delete_post_meta($post_id, $field['id'], $old);
                                }
                        
                                 if (($old) && $old[0]=="") {
                                 //echo 'test';
                                 //print_r($old[0]);
                                 //exit;
                                 delete_post_meta($post_id, $field['id'], $old);
                                 //return;
                                }
                            }
                       
                       // }
                }

           
                function example_load_widgets($args) {
                    register_widget( 'Elearning_Widget' );

                }


	}

} //End Class SeoatlOnSiteGa

//Initialize the admin panel
if (!function_exists("IntellumElearning_ap")) {
    function IntellumElearning_ap() {
        global $IntellumElearning_plugin;
        if (!isset($IntellumElearning_plugin)) {
            return;
        }
        if (function_exists('add_options_page')) {
            add_options_page('Intellum Elearning', 'Intellum Elearning', 9, basename(__FILE__), array(&$IntellumElearning_plugin, 'printAdminPage'));
        }

    }
}



if (class_exists("IntellumElearning")) {
	$IntellumElearning_plugin = new IntellumElearning();
}



//Actions and Filters
if (isset($IntellumElearning_plugin)) {
	//Actions
    	add_action('wp_head', array(&$IntellumElearning_plugin, 'addHeaderCode'), 1);
        add_action('admin_head', array(&$IntellumElearning_plugin, 'addAdminHead'), 1);
        add_action('admin_init', array(&$IntellumElearning_plugin, 'addAdminInit'), 1);
	add_action('wp_footer', array(&$IntellumElearning_plugin, 'addFooterCode'), 1);
        add_action('admin_menu', 'IntellumElearning_ap');
        add_action('IntellumElearning/IntellumElearning.php', array(&$IntellumElearning_plugin, 'init'));
        add_action('activate_IntellumElearning/IntellumElearning.php', array(&$IntellumElearning_plugin, 'IntellumElearning_install'));


        add_action('admin_menu', array(&$IntellumElearning_plugin, 'mytheme_add_box'),1);
        add_action('save_post', array(&$IntellumElearning_plugin, 'mytheme_save_data'),1);

        include("php/Elearning_Widget.php");
        add_action("widgets_init", array(&$IntellumElearning_plugin,'example_load_widgets'),1);





}

