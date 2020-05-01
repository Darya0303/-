<?php

/*
 *  zebratratata@gmail.com
 *
 */

class ControllerExtensionModuleZmenuList extends Controller {
    private $error = array();
    private $furl; // front url
    private $config_name = 'zmenu_data';
    private $lists = array();

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/zmenulist')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }


        return !$this->error;
    }

    public function index() {
        $this->initLists();
        $data = array();

        $data += $this->language->load('extension/module/zmenulist');

        $this->document->setTitle($this->language->get('heading_title2'));

        $data['heading_title'] = $this->language->get('heading_title');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $this->breadCrumbs($data);

        $data['items'] = array();


        foreach ($this->lists as $sett) {
            $data['items'][] = array(
                'id' => $sett['id'],
                'name' => $sett['name'],
                'copy_href' => $this->url->link('extension/module/zmenulist/copy', 'id=' . $sett['id'] . '&user_token=' . $this->session->data['user_token'], true),
                'edit_href' => $this->url->link('extension/module/zmenulist/edit', 'id=' . $sett['id'] . '&user_token=' . $this->session->data['user_token'], true),
                'remove_href' => $this->url->link('extension/module/zmenulist/remove', 'id=' . $sett['id'] . '&user_token=' . $this->session->data['user_token'], true)
            );
        }


        $data['action'] = $this->url->link('extension/module/zmenu', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->getModulesLink();
        $data['add_href'] = $this->url->link('extension/module/zmenulist/edit', 'user_token=' . $this->session->data['user_token'], true);
        $data['clear_cache_href'] = $this->url->link('extension/module/zmenulist/clearcache', 'user_token=' . $this->session->data['user_token'], true);
        $data['zmenu_href'] = $this->url->link('extension/module/zmenu', 'user_token=' . $this->session->data['user_token'], true);


        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $data['column_left'] = $this->load->controller('common/column_left');

        $this->response->setOutput($this->load->view('extension/module/zmenu/lists', $data));
    }

    private function getZmenuLink($list_id = 0) {
        return $this->url->link('extension/module/zmenulist', 'user_token=' . $this->session->data['user_token'] . ($list_id ? '&list_id=' . $list_id : ''), true);
    }

    private function getModulesLink() {
        return $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
    }

    private function initLists() {
        $this->load->model('setting/setting');

        $this->lists = $this->model_setting_setting->getSetting($this->config_name);
        if (!$this->lists) {
            $this->lists = array();
        }


        $fix_id = array();
        foreach ($this->lists as $k => $list) {
            $fix_id[$list['id']] = $list;
        }

        if (count($fix_id) > 1 && function_exists('uksort')) {
            uksort($fix_id, array('ControllerExtensionModuleZmenuList', 'sortList'));
        }

        $this->lists = $fix_id;
    }

    private function sortList($prev_id, $next_id) {
        $prev_id = str_replace($this->config_name_data . '_', '', $prev_id);
        $next_id = str_replace($this->config_name_data . '_', '', $next_id);
        return $prev_id < $next_id;
    }

    private function _redirect($url) {
        $this->response->redirect($url);
    }

    public function clearCache() {
        $this->language->load('module/zmenulist');
        $this->cache->delete('zmenu');
        $list_id = isset($this->request->get['list_id']) ? $this->request->get['list_id'] : 0;
        $this->session->data['success'] = $this->language->get('text_success_cache');
        $this->_redirect($this->getZmenuLink($list_id));
    }

    public function edit() {
        $this->initLists();
        $this->saveList();
        $this->showListForm();
    }

    public function getLists() {
        $this->initLists();
        return $this->lists;
    }

    private function _generateId() {
        if (count($this->lists) == 0) {
            return $this->config_name . '_0';
        }
        $max = 0;
        foreach ($this->lists as $k => $value) {
            $k = str_replace($this->config_name . '_', '', $k);
            $k = (int)$k;
            if ($k > $max) {
                $max = $k;
            }
        }
        return $this->config_name . '_' . ($max + 1);
    }


    private function saveList() {
        $this->language->load('module/zmenulist');

        if ($this->request->server['REQUEST_METHOD'] != 'POST' || !$this->validate()) {
            return;
//            $this->_redirect($this->getZmenuLink());
        }




        $this->initLists();

        $id = $this->request->post['id'];
        if (!$id) {
            $id = $this->_generateId();
        }

        $json = $this->request->post['json'];

        $this->lists[$id] = array(
            'id' => $id,
            'name' => $this->request->post['name'],
            'json' => htmlspecialchars_decode($json, ENT_COMPAT),
            'icons' => $this->request->post['icons'] ? 1 : 0,
            'icons_width' => (int)$this->request->post['icons_width'],
            'icons_height' => (int)$this->request->post['icons_height']
        );


        $this->cache->delete('zmenu');
        $this->model_setting_setting->editSetting($this->config_name, $this->lists);
        $this->_redirect($this->getZmenuLink());
    }


    public function showListForm() {
        $this->load->model('setting/extension');
        $this->load->model('catalog/information');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('localisation/language');
        $this->load->model('tool/image');

        $this->document->addStyle('view/template/extension/module/zmenu/scripts/form.css?2');
        $this->document->addScript('view/template/extension/module/zmenu/scripts/jquery.mjs.nestedsortable.js?1');
        $this->document->addScript('view/template/extension/module/zmenu/scripts/jquery.tmpl.min.js');
        $this->document->addScript('view/template/extension/module/zmenu/scripts/menu.js?21.09');

        $data = array();

        $data += $this->language->load('module/zmenulist');

        $this->document->setTitle($this->language->get('heading_title2'));


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $this->breadCrumbs($data);





        $data['action'] = $this->url->link('extension/module/zmenulist/edit', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('extension/module/zmenulist', 'user_token=' . $this->session->data['user_token'], true);
        $module_info = null;
        if (isset($this->request->get['id']) && isset($this->lists[$this->request->get['id']])) {
            $module_info = $this->lists[$this->request->get['id']];
            $data['action'] = $this->url->link('extension/module/zmenulist/edit', 'user_token=' . $this->session->data['user_token'] . '&id='.$this->request->get['id'], true);
        }


        $data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        $this->furl = new Url("/", "/");

        $item = array(
            'name' => '',
            'id' => 0,
            'json' => ''
        );

        if ($module_info) {
            $item = $module_info;

            if ($item['json']) {
                $item['json'] = json_decode(html_entity_decode($item['json'], ENT_QUOTES, 'UTF-8'), true);
            }


            if ($item && $item['json'] && is_array($item['json'])) {
                foreach ($item['json'] as &$o) {

                    if (isset($o['data']) && isset($o['data']['image'])) {
                        if ($o['data']['image']) {
                            $o['data']['thumb'] = $this->model_tool_image->resize($o['data']['image'], 100, 100);
                        } else {
                            $o['data']['thumb'] = $data['no_image'];
                        }
                    }

                }

                $item['json'] = json_encode($item['json']);
            }
        }


        $update_item = array(
            'icons' => 0,
            'icons_width' => 16,
            'icons_height' => 16
        );

        foreach ($update_item as $k => $v) {
            if (!isset($item[$k])) {
                $item[$k] = $v;
            }
        }


        $langs = $this->model_localisation_language->getLanguages();
        $default_lang = $this->config->get('config_language_id');

        $data['languages'] = array();
        foreach ($langs as $k => $l) {
            $l['is_default'] = $l['language_id'] == $default_lang ? 1 : 0;
            $data['languages'][] = $l;
        }


        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');


        $this->language->load('catalog/category');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_image_manager'] = $this->language->get('text_image_manager');
        $data['text_browse'] = $this->language->get('text_browse');
        $data['text_clear'] = $this->language->get('text_clear');
        $data['text_image'] = $this->language->get('text_image');

        $data['user_token'] = $this->session->data['user_token'];


        $information_data = array(
            'sort' => 'id',
            'order' => 'DESC'
        );

        $categories = $this->model_catalog_category->getCategories(array('sort' => 'name'));
        $informations = $this->model_catalog_information->getInformations($information_data);
        $manufacturers = $this->model_catalog_manufacturer->getManufacturers(array('sort' => 'sort_order'));


        $data['informations'] = array();
        $data['categories'] = array();
        $data['manufacturers'] = array();


        foreach ($informations as $information) {
            $data['informations'][] = array(
                'information_id' => $information['information_id'],
                'title' => $information['title'],
                'href' => $this->furl->link('information/information', 'information_id=' . $information['information_id'], true),
                'titles' => $this->model_catalog_information->getInformationDescriptions($information['information_id'])
            );
        }


        foreach ($categories as $category) {
            $cat = $this->model_catalog_category->getCategory($category['category_id']);
            $data['categories'][] = array(
                'category_id' => $category['category_id'],
                'category_path' => $category['name'],
                'name' => isset($cat['name']) ? $cat['name'] : $category['name'],
                'href' => $this->furl->link('product/category', 'path=' . $category['category_id'], true),
                'titles' => $this->model_catalog_category->getCategoryDescriptions($category['category_id'])
            );
        }

        foreach ($manufacturers as $manufacturer) {
            $titles = array();

            foreach ($langs as $k => $l) {
                $titles[$l['language_id']] = array(
                    'title' => $manufacturer['name']
                );
            }

            $data['manufacturers'][] = array(
                'manufacturer_id' => $manufacturer['manufacturer_id'],
                'category_path' => $manufacturer['name'],
                'name' => $manufacturer['name'],
                'href' => $this->furl->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'], true),
                'titles' => $titles
            );

        }


        $data['item'] = $item;

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $data['column_left'] = $this->load->controller('common/column_left');

        $this->response->setOutput($this->load->view('extension/module/zmenu/list', $data));
    }


    private function breadCrumbs(&$data) {
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->getModulesLink(),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->getZmenuLink(),
            'separator' => ' :: '
        );
    }


    public function install() {

    }

    public function uninstall() {

    }

    public function category_autocomplete() {
        $furl = new Url("/", "/");
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/category');


            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start' => 0,
                'limit' => 20
            );

            $results = $this->model_catalog_category->getCategories($filter_data);

            foreach ($results as $result) {
                $titles = $this->model_catalog_category->getCategoryDescriptions($result['category_id']);


                $json[] = array(
                    'category_id' => $result['category_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'titles' => $titles,
                    'href' => $furl->link('product/category', 'path=' . $result['category_id'], true)
                );
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function product_autocomplete() {
        $furl = new Url("/", "/");
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
            $this->load->model('catalog/product');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 20;
            }

            $data = array(
                'filter_name' => $filter_name,
                'filter_model' => $filter_model,
                'start' => 0,
                'limit' => $limit
            );

            $results = $this->model_catalog_product->getProducts($data);

            foreach ($results as $result) {
                $titles = $this->model_catalog_product->getProductDescriptions($result['product_id']);


                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model' => $result['model'],
                    'price' => $result['price'],
                    'titles' => $titles,
                    'href' => $furl->link('product/product', 'path=' . $result['product_id'], true)
                );
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function copy() {
        $this->initLists();
        $id = isset($this->request->get['id']) ? $this->request->get['id'] : 0;

        if (!$id || !isset($this->lists[$id]) || !$this->validate()) {
            $this->_redirect($this->getZmenuLink());
        }

        $new_id = $this->_generateId();
        $clone = $this->lists[$id];
        $clone['name'] .= ' clone';

        $clone['id'] = $new_id;
        $this->lists[$new_id] = $clone;
        $this->model_setting_setting->editSetting($this->config_name, $this->lists);


        $this->_redirect($this->getZmenuLink());
    }

    public function remove() {
        $this->initLists();
        $id = isset($this->request->get['id']) ? $this->request->get['id'] : 0;

        if(!$this->validate()) {
            $this->_redirect($this->getZmenuLink());
        }

        if (isset($this->lists[$id])) {
            unset($this->lists[$id]);
            $this->model_setting_setting->editSetting($this->config_name, $this->lists);
        }


        $this->_redirect($this->getZmenuLink());
    }
}

?>