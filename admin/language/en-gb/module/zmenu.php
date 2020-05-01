<?php
// Heading
$_['heading_title']       = '<b>ZMenu</b>';
$_['heading_title2']      = 'ZMenu';

// Text
$_['text_module']         = 'Modules';
$_['text_success']        = 'Success: You have modified ZMenu!';
$_['text_content_top']    = 'Content Top';
$_['text_content_bottom'] = 'Content Bottom';
$_['text_column_left']    = 'Column Left';
$_['text_column_right']   = 'Column Right';
$_['button_save_and_stay'] = 'Save and stay';
$_['button_add_menu']      = 'Add menu';
$_['text_name']            = 'Name';
$_['button_edit']          = 'Edit';
$_['button_clear_cache']   = 'Clear cache';
$_['text_link']            = 'Link';
$_['text_custom_link']     = 'Custom link';
$_['text_information']     = 'Information';
$_['text_informations']    = 'Informations';
$_['text_category']        = 'Category';
$_['text_categories']      = 'Categories';
$_['text_product']         = 'Product';
$_['text_product_help']    = 'Product name (autocomplete)';
$_['button_add']           = 'Add';
$_['text_show_subcategories']  = 'Show subcategories (first level)';
$_['text_title']           = 'Title';
$_['text_link_title']      = 'Title link';
$_['text_show_default_title'] = 'Show default title';
$_['text_all_pages']       = 'ZMenu All';
$_['text_menu_type']       = 'Menu type';
$_['text_menu_horizontal'] = 'Horizontal';
$_['text_menu_vertical']   = 'Vertical';
$_['text_custom_css']      = 'Your css class for menu';
$_['button_add_module']    = 'Add module';
$_['text_category_help']   = 'Category name (autocomplete)';
$_['text_main_menu_position']   = 'Main menu';
$_['text_show_all_subcategories']  = 'Show all subcategories';
$_['text_manufacturer']  = 'Manufacturer';
$_['text_icons']  = 'Icons';
$_['text_icons_size']  = 'Icon size Width x Height';
$_['text_css_class']  = 'Css class';
$_['text_image']  = 'Image';
$_['text_use_default_image']  = 'Use default icon for category/product/manufacturer';
$_['text_edit']        = 'Edit Zmenu Module';
$_['text_template_info']   = 'You can use your own template with module data items for render menu.';
$_['text_help']            = 'Help info<ul>
<li>
Need to add menu list (module zmenu list) and than use this module to display it in site
</li>
<li>Module used cache, need clear cache in module zmenu list, or save this module to clear all cache</li>
<li>
   Variables in custom template: $items(array of all menu items), $heading_title, $menu_html (rendered default menu without main &#60ul&#62; tags)<br/>
   if the path to the custom template is invalid, then the default template will be used
</li>
<li>To display menu in custom controller (or in all pages in header controller) use this code
<pre>
//in controller file
$data[\'menu_html\'] = $this->load->controller(\'extension/module/zmenu/getHtml\', %s);

// in template file
<&#63;php echo $menu_html; ?>
</pre>
Where "%s" is module_id from browser query string
</li>
<li>
To get array of items menu use this code:
<pre>
$data[\'items\'] = $this->load->controller(\'extension/module/zmenu/getMenuItems\', %s);
</pre>
</li>
<li>
    module help zebratratata@gmail.com or skype dedhater
</li>
';


// Entry
$_['entry_name']          = 'Name:';
$_['entry_module_name']   = 'Module name:';
$_['entry_layout']        = 'Layout:';
$_['entry_position']      = 'Position:';
$_['entry_status']        = 'Status:';
$_['entry_sort_order']    = 'Sort Order:';
$_['entry_action']        = 'Action:';
$_['entry_list']          = 'Zmenu list:<br><a href="%s">Create new list</a>';
$_['entry_template']      = 'Custom template path';

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify ZMenu!';
$_['error_name']       = 'Module Name must be between 3 and 64 characters!';
$_['error_zmenu_id']      = 'Need to create and choose a ZMenu list';

?>