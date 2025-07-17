<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class ReferenceApi extends Module
{
    public function __construct()
    {
        $this->name = 'referenceapi';
        $this->tab = 'administration';
        $this->version = '1.0.2';
        $this->author = 'Kim Ybanez';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.24');
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product Reference API');
        $this->description = $this->l('Provides a JSON API to retrieve product data by reference number.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        return parent::install() &&
               Configuration::updateValue('REFERENCE_API_KEY', Tools::passwdGen(16)) &&
               $this->installTab();
    }

    public function uninstall()
    {
        return parent::uninstall() &&
               Configuration::deleteByName('REFERENCE_API_KEY') &&
               $this->uninstallTab();
    }

    private function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminReferenceApi';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Reference API';
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminTools');
        $tab->module = $this->name;
        return $tab->add();
    }

    private function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminReferenceApi');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitReferenceApi')) {
            $api_key = Tools::getValue('REFERENCE_API_KEY');
            if (!empty($api_key) && Validate::isGenericName($api_key)) {
                Configuration::updateValue('REFERENCE_API_KEY', $api_key);
                $output .= $this->displayConfirmation($this->l('Settings updated successfully.'));
            } else {
                $output .= $this->displayError($this->l('Invalid API key.'));
            }
        }

        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('API Key'),
                        'name' => 'REFERENCE_API_KEY',
                        'required' => true,
                        'desc' => $this->l('Enter the API key for securing the API endpoint.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submitReferenceApi';
        $helper->fields_value['REFERENCE_API_KEY'] = Configuration::get('REFERENCE_API_KEY');

        return $helper->generateForm(array($fields_form));
    }
}