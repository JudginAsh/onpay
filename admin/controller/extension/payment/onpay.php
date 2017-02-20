<?php
class ControllerExtensionPaymentOnpay extends Controller
{
  private $error = array();

  public function index()
  {
    $this->load->language('extension/payment/onpay');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
    {
      $this->model_setting_setting->editSetting('onpay', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $data['heading_title'] = $this->language->get('heading_title');

    $data['text_edit'] = $this->language->get('text_edit');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');

    $data['entry_url_api'] = $this->language->get('entry_url_api');
    $data['entry_your_site'] = $this->language->get('entry_your_site');
    $data['entry_login'] = $this->language->get('entry_login');
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_security'] = $this->language->get('entry_security');
    $data['entry_ln_ru'] = $this->language->get('entry_ln_ru');
    $data['entry_ln_en'] = $this->language->get('entry_ln_en');
    $data['entry_ln_zh'] = $this->language->get('entry_ln_zh');
    $data['entry_ln'] = $this->language->get('entry_ln');
    $data['entry_yes'] = $this->language->get('entry_yes');
    $data['entry_no'] = $this->language->get('entry_no');
    $data['entry_convert'] = $this->language->get('entry_convert');
    $data['entry_price_final'] = $this->language->get('entry_price_final');
    $data['entry_form_id'] = $this->language->get('entry_form_id');
    
    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_payment'),
      'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/payment/onpay', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $data['action'] = $this->url->link('extension/payment/onpay', 'token=' . $this->session->data['token'], 'SSL');

    $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

    if (isset($this->request->post['onpay_login']))
    {
      $data['onpay_login'] = $this->request->post['onpay_login'];
    }
    else
    {
      $data['onpay_login'] = $this->config->get('onpay_login');
    }

    if (isset($this->request->post['onpay_security']))
    {
      $data['onpay_security'] = $this->request->post['onpay_security'];
    }
    else
    {
      $data['onpay_security'] = $this->config->get('onpay_security');
    }
    
    if (isset($this->request->post['onpay_ln']))
    {
      $data['onpay_ln'] = $this->request->post['onpay_ln'];
    }
    else
    {
      $data['onpay_ln'] = $this->config->get('onpay_ln');
    }
    
    if (isset($this->request->post['onpay_convert']))
    {
      $data['onpay_convert'] = $this->request->post['onpay_convert'];
    }
    else
    {
      $data['onpay_convert'] = $this->config->get('onpay_convert');
    }
    
    if (isset($this->request->post['onpay_price_final']))
    {
      $data['onpay_price_final'] = $this->request->post['onpay_price_final'];
    }
    else
    {
      $data['onpay_price_final'] = $this->config->get('onpay_price_final');
    }

    if (isset($this->request->post['onpay_order_status_id']))
    {
      $data['onpay_order_status_id'] = $this->request->post['onpay_order_status_id'];
    }
    else
    {
      $data['onpay_order_status_id'] = $this->config->get('onpay_order_status_id');
    }

    if (isset($this->request->post['onpay_form_id']))
    {
      $data['onpay_form_id'] = $this->request->post['onpay_form_id'];
    }
    else
    {
      $data['onpay_form_id'] = $this->config->get('onpay_form_id');
    }

    $this->load->model('localisation/order_status');

    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    if (isset($this->request->post['onpay_status']))
    {
      $data['onpay_status'] = $this->request->post['onpay_status'];
    }
    else
    {
      $data['onpay_status'] = $this->config->get('onpay_status');
    }

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/payment/onpay.tpl', $data));
  }

  protected function validate()
  {
    if (!$this->user->hasPermission('modify', 'extension/payment/onpay'))
    {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }
}
?>