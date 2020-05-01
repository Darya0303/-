<?php

class ControllerExtensionModuleZmenu extends Controller {

    public function getHtml($module_id) {
        $this->load->model('setting/module');
        $module_info = $this->model_setting_module->getModule($module_id);
        $html = '';

        if ($module_info) {
            $html = $this->index($module_info);
        }

        return $html;
    }

    public function getMenuItems($module_id) {
        $this->load->model('setting/module');
        $module_info = $this->model_setting_module->getModule($module_id);
        $items = array();

        if ($module_info) {
            $data = $this->parseMenuItems($module_info);
            if ($data && !empty($data['items'])) {
                $items = $data['items'];
            }
        }

        return $items;
    }

    public function index($setting_module) {
        $this->load->model('setting/setting');

        $menu_data = $this->parseMenuItems($setting_module);

        if (!$menu_data || empty($menu_data['items']) || !$setting_module['status']) {
            return;
        }

        $template = 'extension/module/zmenu';
        $template_search = $setting_module['template'] && strpos($setting_module['template'], ".tpl") === false ? $setting_module['template'] . ".*" : $setting_module['template'];
        if ($setting_module['template'] && (glob(DIR_TEMPLATE . $this->config->get('config_template') . 'template/' . $template_search . '.*') || glob(DIR_TEMPLATE . 'default/template/' . $template_search))) {
            $template = $setting_module['template'];
        }

        $data = $menu_data;

        $data['css_class'] = $setting_module['menu_type'] == 'vertical' ? 'zmenu-v' : 'zmenu-h';

        if (isset($setting_module['css_class']) && $setting_module['css_class']) {
            $data['css_class'] = $setting_module['css_class'];
        }


        $data['heading_title'] = '';
        if ($setting_module['menu_type'] == 'vertical') {
            $data['heading_title'] = $this->getCurrentTitle($setting_module['names']);
        }

        return $this->load->view($template, $data);
    }

    private function parseMenuItems($setting_module) {
        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('zmenu_data');

        $zmenu_id = $setting_module && isset($setting_module['zmenu_id']) ? $setting_module['zmenu_id'] : 0;
        $menu = isset($settings[$zmenu_id]) ? $settings[$zmenu_id] : '';

        if (!$menu || !is_array($menu)) {
            return array();
        }

        $menu_data = json_decode($menu['json'], true);
        $menu['json'] = $menu_data;

        $data = array();
        $data['items'] = array();


        if (isset($setting_module['css_class']) && $setting_module['css_class']) {
            $data['css_class'] = $setting_module['css_class'];
        }

        $data['heading_title'] = '';
        if ($setting_module['menu_type'] == 'vertical') {
            $data['heading_title'] = $this->getCurrentTitle($setting_module['names']);
        }


        $data['menu_html'] = '';
        $template = 'extension/module/zmenu';
        $template_search = $setting_module['template'] && strpos($setting_module['template'], ".tpl") === false ? $setting_module['template'] . ".*" : $setting_module['template'];
        if ($setting_module['template'] && (glob(DIR_TEMPLATE . $this->config->get('config_template') . 'template/' . $template_search . '.*') || glob(DIR_TEMPLATE . 'default/template/' . $template_search))) {
            $template = $setting_module['template'];
        }

        $cache_name = 'zmenu.items.' . $this->session->data['language'] . '.' . (int)$this->config->get('config_store_id') . '.' . $menu['id'] . '.' . $template;
        $cache_data = $this->cache->get($cache_name);
//        $cache_data = '';

        if ($cache_data) {
            foreach ($cache_data['items'] as $it) {
                $data['menu_html'] .= $this->itemToHtml($it);
            }
            $data['items'] = $cache_data['items'];

        } else {
            foreach ($menu_data as $item) {
                $it = $this->getItem($item, $menu);
                if (!$it) {
                    continue;
                }
                $data['items'][] = $it;
                $data['menu_html'] .= $this->itemToHtml($it);
            }
            $this->cache->set($cache_name, array('items' => $data['items']));
        }


        return $data;
    }

    public function getCurrentTitle($titles) {
        $lang_id = $this->config->get('config_language_id');
        return isset($titles[$lang_id]) ? $titles[$lang_id] : '';
    }

    public function getItem($item, $menu) {
        $this->load->model('catalog/information');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');

        $childs = array();
        $lang_id = $this->config->get('config_language_id');

        $item = (array)$item;


        $href = $item['href'];
        $html = '';
        $title = isset($item['titles'][$lang_id]) ? $item['titles'][$lang_id] : '';

        $css_class = '';
        $icon = '';
        if (isset($item['data']['image'])) {
            $icon = $item['data']['image'];
        }

        if (isset($item['data']['css_class']) && $item['data']['css_class']) {
            $css_class = $item['data']['css_class'];
        }

        switch ($item['type']) {
            case 'information':
                if ($item['data']['show_default_title'] == 1) {

                    $information = $this->model_catalog_information->getInformation($item['data']['information_id']);
                    if (!$information) {
                        return false;
                    }

                    $title = $information['title'];
                }

                $href = $this->url->link('information/information', 'information_id=' . $item['data']['information_id']);
                break;

            case 'category':
                $category = null;
                if ($item['data']['show_default_title'] == 1 || isset($item['data']['use_default_image']) && $item['data']['use_default_image']) {
                    $category = $this->model_catalog_category->getCategory($item['data']['category_id']);
                }

                $category_path = $this->getPathByCategory($item['data']['category_id']);
                if ($category_path) {
                    $href = $this->url->link('product/category', 'path=' . $category_path);
                } else {
                    $category_path = $item['data']['category_id'];
                    $href = $this->url->link('product/category', 'path=' . $item['data']['category_id']);
                }

                if ($item['data']['show_default_title'] == 1) {
                    if (!$category) {
                        return false;
                    }
                    $title = $category['name'];
                }

                if (isset($item['data']['show_all_subcategories']) && $item['data']['show_all_subcategories']) {
                    $childs = $this->getSubcats($item['data']['category_id'], $category_path, 10);

                } else if ($item['data']['show_subcategories']) {
                    $subcats = $this->model_catalog_category->getCategories($item['data']['category_id']);
                    foreach ($subcats as $cat) {
                        $childs[] = array(
                            'icon' => '',
                            'css_class' => '',
                            'html' => '',
                            'title' => $cat['name'],
                            'href' => $this->url->link('product/category', 'path=' . $category_path . '_' . $cat['category_id'])
                        );
                    }

                }

                if (isset($item['data']['use_default_image']) && $item['data']['use_default_image'] && $category['image']) {
                    $icon = $category['image'];
                }

                break;

            case 'product':
                $product = null;
                if ($item['data']['show_default_title'] == 1 || isset($item['data']['use_default_image']) && $item['data']['use_default_image']) {
                    $product = $this->model_catalog_product->getProduct($item['data']['product_id']);
                }

                $category_path = $this->getPathByProduct($item['data']['product_id']);
                if ($category_path) {
                    $href = $this->url->link('product/product', 'path=' . $category_path . '&product_id=' . $item['data']['product_id']);
                } else {
                    $href = $this->url->link('product/product', 'product_id=' . $item['data']['product_id']);
                }

                if ($item['data']['show_default_title'] == 1) {

                    if (!$product) {
                        return false;
                    }
                    $title = $product['name'];
                }

                if (isset($item['data']['use_default_image']) && $item['data']['use_default_image'] && $product['image']) {
                    $icon = $product['image'];
                }


                break;

            case 'manufacturer':
                $manufacturer = null;

                if ($item['data']['show_default_title'] == 1 || isset($item['data']['use_default_image']) && $item['data']['use_default_image']) {
                    $manufacturer = $this->model_catalog_manufacturer->getManufacturer($item['data']['manufacturer_id']);
                }

                if ($item['data']['show_default_title'] == 1) {

                    if (!$manufacturer) {
                        return false;
                    }

                    $title = $manufacturer['name'];
                }

                $href = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $item['data']['manufacturer_id']);
                if (isset($item['data']['use_default_image']) && $item['data']['use_default_image'] && $manufacturer['image']) {
                    $icon = $manufacturer['image'];
                }
                break;

            case 'html':
                $href = '';
                $html = isset($item['data']['html']) && isset($item['data']['html'][$lang_id]) ? $item['data']['html'][$lang_id] : '';
                break;
        }

        foreach ($item['items'] as $subitem) {
            $childs[] = $this->getItem($subitem, $menu);
        }


        return array(
            'title' => $title,
            'childs' => $childs,
            'html' => $html,
            'href' => $this->replaceHref($href),
            'icon' => $icon ? $this->getIconPath($icon, $menu) : '',
            'css_class' => $css_class
        );
    }

    public function getIconPath($icon, $menu) {
        if ($menu['icons'] && $menu['icons_width'] && $menu['icons_height']) {
            return $this->model_tool_image->resize($icon, $menu['icons_width'], $menu['icons_height']);
        }
        return '';
    }

    public function replaceHref($href) {
        return str_replace('&amp;', '&', $href);
    }

    public function getSubcats($category_id, $category_path, $max_level = 0) {
        $subcats = $this->model_catalog_category->getCategories($category_id);
        $items = array();
        foreach ($subcats as $cat) {
            $items[] = array(
                'title' => $cat['name'],
                'href' => $this->replaceHref($this->url->link('product/category', 'path=' . $category_path . '_' . $cat['category_id'])),
                'childs' => $max_level >= 0 ? $this->getSubcats($cat['category_id'], $category_path . '_' . $cat['category_id'], $max_level - 1) : array(),
                'css_class' => '',
                'icon' => '',
                'html' => ''
            );

        }

        return $items;
    }

    public function itemToHtml($item) {
        $li_css = $icon = '';

        if ($item['css_class']) {
            $li_css = 'class="' . $item['css_class'] . '"';
        }

        if (isset($item['icon']) && $item['icon']) {
            $icon = '<span class="icon"><img src="' . $item['icon'] . '" alt="' . $item['title'] . '" /></span>';
        }


        $html = '<li ' . $li_css . '>' . "\r\n";
        $hasChilds = isset($item['childs']) && count($item['childs']);
        $css_class = array();

        if ($hasChilds) {
            $css_class[] = "haschild";
        }

        if ($this->isCurrentUrl($item['href'])) {
            $css_class[] = 'selected';
        }


        $css_class = implode(" ", $css_class);

        if ($item['html']) {
            $html .= $item['html'];
        } else {
            $html .= '<a href="' . $item['href'] . '" class="' . $css_class . '">' . $icon . '<span class="z-title">' . $item['title'] . '</span></a>' . "\r\n";
        }

        if ($hasChilds) {
            $html .= '<ul>' . "\r\n";
            foreach ($item['childs'] as $child) {
                $html .= $this->itemToHtml($child);
            }
            $html .= '</ul>' . "\r\n";
        }

        $html .= '</li> ' . "\r\n";
        return $html;
    }

    public function isCurrentUrl($url) {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http';
        $current_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . urldecode($_SERVER['REQUEST_URI']);
        $current_url2 = $scheme . '://' . $_SERVER['HTTP_HOST'] . urldecode($this->replaceHref($_SERVER['REQUEST_URI']));

        $url2 = $this->replaceHref($url);
        $r = false;

        if ($current_url == $url || $current_url == $url2 || $current_url2 == $url || $current_url2 == $url2) {
            $r = true;

        } else if ($_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_URI'] == $url) {
            $r = true;
        }

        return $r;
    }


    /* getting functions from seo_pro */

    public function getPathByProduct($product_id) {
        $product_id = (int)$product_id;
        if ($product_id < 1)
            return false;

        static $path = null;
        if (!is_array($path)) {
            $path = $this->cache->get('zmenu.product.seopath');
            if (!is_array($path))
                $path = array();
        }

        $path = array();

        if (!isset($path[$product_id])) {
            $query = $this->db->query("SELECT ptc.category_id FROM " . DB_PREFIX . "product_to_category ptc LEFT JOIN " . DB_PREFIX . "category_path cp ON(cp.category_id = ptc.category_id) WHERE ptc.product_id = '" . $product_id . "' ORDER BY cp.level DESC LIMIT 1");

            $path[$product_id] = $this->getPathByCategory($query->num_rows ? (int)$query->row['category_id'] : 0);

            $this->cache->set('zmenu.product.seopath', $path);
        }

        return $path[$product_id];
    }

    public function getPathByCategory($category_id) {
        $category_id = (int)$category_id;
        if ($category_id < 1)
            return false;

        static $path = null;
        if (!is_array($path)) {
            $path = $this->cache->get('zmenu.category.seopath');
            if (!is_array($path))
                $path = array();
        }

        if (!isset($path[$category_id])) {
            $max_level = 10;

            $sql = "SELECT CONCAT_WS('_'";
            for ($i = $max_level - 1; $i >= 0; --$i) {
                $sql .= ",t$i.category_id";
            }
            $sql .= ") AS path FROM " . DB_PREFIX . "category t0";
            for ($i = 1; $i < $max_level; ++$i) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i - 1) . ".parent_id)";
            }
            $sql .= " WHERE t0.category_id = '" . $category_id . "'";

            $query = $this->db->query($sql);

            $path[$category_id] = $query->num_rows ? $query->row['path'] : false;

            $this->cache->set('zmenu.category.seopath', $path);
        }

        return $path[$category_id];
    }

}


?>