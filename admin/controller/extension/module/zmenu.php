<?php

/*
 *  zebratratata@gmail.com
 *
 */

class ControllerExtensionModuleZmenu extends Controller {
    private $error = array();
    private $lists = array();

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/zmenu')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->request->post['zmenu_id'] || !isset($this->lists[$this->request->post['zmenu_id']])) {
            $this->error['zmenu_id'] = $this->language->get('error_zmenu_id');
        }

        return !$this->error;
    }

    private function initLists() {
        $this->lists = $this->load->controller('extension/module/zmenulist/getLists');
    }

    public function index() {
        $this->initLists();


        $this->load->model('setting/module');
        $this->load->model('localisation/language');

        $data = array();

        $data += $this->language->load('extension/module/zmenu');
        $module_id =  $this->getModuleId();
        $data['text_help'] = sprintf($this->language->get('text_help'), $module_id, $module_id, $module_id);

        $data['entry_list'] = sprintf($this->language->get('entry_list'), $this->url->link('extension/module/zmenulist', 'user_token=' . $this->session->data['user_token'], true));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_setting_module->addModule('zmenu', $this->request->post);
            } else {
                $this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->cache->delete('zmenu');
            $this->response->redirect($this->getModulesLink());
        }

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

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['zmenu_id'])) {
            $data['error_zmenu_id'] = $this->error['zmenu_id'];
        } else {
            $data['error_zmenu_id'] = '';
        }


        $module_info = null;
        $data['action'] = $this->url->link('extension/module/zmenu', 'user_token=' . $this->session->data['user_token'], true);
        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
            $data['action'] = $this->url->link('extension/module/zmenu', 'user_token=' . $this->session->data['user_token'] . '&module_id='.$this->request->get['module_id'], true);
        }

        $data['status'] = $this->getVar('status', $module_info, 1);
        $data['name'] = $this->getVar('name', $module_info, '');
        $data['names'] = $this->getVar('names', $module_info, array());
        $data['zmenu_id'] = $this->getVar('zmenu_id', $module_info);
        $data['template'] = $this->getVar('template', $module_info);
        $data['menu_type'] = $this->getVar('menu_type', $module_info);

        $data['lists'] = $this->lists;


        $langs = $this->model_localisation_language->getLanguages();
        $default_lang = $this->config->get('config_language_id');

        $data['languages'] = array();
        foreach ($langs as $k => $l) {
            $l['is_default'] = $l['language_id'] == $default_lang ? 1 : 0;
            $data['languages'][] = $l;
        }


        $this->breadCrumbs($data);


        $data['cancel'] = $this->getModulesLink();

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $data['column_left'] = $this->load->controller('common/column_left');

        $this->response->setOutput($this->load->view('extension/module/zmenu/module', $data));
    }

    private function getVar($name, $module_info = array(), $default_value = '') {
        if (isset($this->request->post[$name])) {
            return $this->request->post[$name];
        }
        if ($module_info && isset($module_info[$name])) {
            return $module_info[$name];
        }
        return $default_value;
    }

    private function getZmenuLink($module_id = 0) {
        return $this->url->link('extension/module/zmenu', 'user_token=' . $this->session->data['user_token'] . ($module_id ? '&module_id=' . $module_id : ''), true);
    }

    private function getModulesLink() {
        return $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
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

    private function getModuleId() {
        if(isset($this->request->get['module_id'])) {
            return $this->request->get['module_id'];
        }

        $q = $this->db->query("SHOW TABLE STATUS WHERE `Name` = '".DB_PREFIX."module'");

        return $q->row && isset($q->row['Auto_increment']) ? $q->row['Auto_increment'] : 'module_id';
    }

}

?>