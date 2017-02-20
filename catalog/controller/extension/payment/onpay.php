<?php
class ControllerExtensionPaymentOnpay extends Controller 
{
	public function index() 
	{

		$this->load->language('extension/payment/onpay');

  		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		
		$order_id = $this->session->data['order_id'];
		
		$this->model_checkout_order->addOrderHistory($order_id, 1);
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
    
    	$data['pay_for'] = $order_id;
    $data['currency'] = 'RUR';
    $data['url_success'] = $this->url->link('extension/payment/onpay/success', '', 'SSL');
    $data['url_fail'] = $this->url->link('extension/payment/onpay/fail', '', 'SSL');
    $data['pay_mode'] = 'fix';
    
    $data['price'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['price'] = $this->onpay_to_float($data['price']);
    
    $data['user_email'] = $order_info['email'];
    
    $onpay_key = $this->config->get('onpay_security');
    $data['md5'] = md5($data['pay_mode'].";".$data['price'].";" . $data['currency'] . ";" . $data['pay_for'] . ";".$this->config->get('onpay_convert').";$onpay_key");

    $data['pay_url'] = "http://secure.onpay.ru/pay/".$this->config->get('onpay_login').
    "?price_final=".$this->config->get('onpay_price_final').
    "&ln=".$this->config->get('onpay_ln').
    "&f=".$this->config->get('onpay_form_id').
    "&pay_mode=".$data['pay_mode'].
    "&pay_for=".$data['pay_for'].
    "&price=".$data['price'].
    "&ticker=".$data['currency'].
    "&convert=".$this->config->get('onpay_convert').
    "&md5=".$data['md5'].
    "&user_email=".urlencode($data['user_email']).
    "&url_success_enc=".urlencode($data['url_success']).
    "&url_fail_enc=".urlencode($data['url_fail']);
    
    /*echo ($data['pay_mode'].";".$data['price'].";" . $data['currency'] . ";" . $data['pay_for'] . ";".$this->config->get('onpay_convert').";$onpay_key");
    echo '<br>-------------<br>';
    echo $data['md5'];
    echo '<br>-------------<br>';
    echo $data['pay_url'];
    die;
    // developer mode 
    */
    
			return $this->load->view('extension/payment/onpay.tpl', $data);

	}
  
  public function onpay_to_float($sum) {
    $sum = floatval($sum);
		if (strpos($sum, ".")) {
			$sum = round($sum, 2);
		} else {
			$sum = $sum.".0";
		}
		return $sum;
  }
  
  public function onpay_check($request) {
    $check = array(
      'type' => 'check',
      'pay_for' => intval($request['pay_for']),
      'amount' => $this->onpay_to_float($request['amount']),
      'currency' => trim($request['way']),
      'mode' => trim($request['mode']),
      'key' => $this->config->get('onpay_security'),
    );
    $check['signature_string'] = implode(";", $check);
    $check['signature'] = sha1($check['signature_string']);
    $checkOut = array(
      'type' => 'check',
      'status' => 'false',
      'pay_for' => intval($request['pay_for']),
      'key' => $this->config->get('onpay_security'),
    );
    $amount = floatval($request['amount']);
    if($this->onpay_validate($request, $check['signature'])) {
      $pay_for = $request['pay_for'];
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($pay_for);
      
      if( $order_info['order_status_id'] != $this->config->get('onpay_order_status_id')) 
			{
          $checkOut['status'] = 'true';
			}
    }
    $this->onpay_response($checkOut, $request);
  }

  function onpay_pay($request) {
    $_request = $request;
    $pay = array(
      'type' => 'pay',
      'pay_for' => intval($request['pay_for']),
      'payment.amount' => $this->onpay_to_float($request['payment']['amount']),
      'payment.currency' => trim($request['payment']['way']),
      'amount' => $this->onpay_to_float($request['balance']['amount']),
      'currency' => trim($request['balance']['way']),
      'key' => $this->config->get('onpay_security'),
    );
    $pay['signature_string'] = implode(";", $pay);
    $pay['signature'] = sha1($pay['signature_string']);
    $payOut = array(
      'type' => 'pay',
      'status' => 'false',
      'pay_for' => intval($request['pay_for']),
      'key' => $this->config->get('onpay_security'),
    );
    $amount = floatval($request['balance']['amount']);
    if($this->onpay_validate($request, $pay['signature'])) {
      $pay_for = $request['pay_for'];
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($pay_for);
      
      if( $order_info['order_status_id'] != $this->config->get('onpay_order_status_id')) 
			{
					$this->model_checkout_order->addOrderHistory($pay_for, $this->config->get('onpay_order_status_id'));
          $payOut['status'] = 'true';
			}
    }
    $this->onpay_response($payOut, $request);
  }
  
  public function onpay_response($response, $request) {
    $response['signature_string'] = implode(";", $response);
    $response['signature'] = sha1($response['signature_string']);
    $out = "{\"status\":{$response['status']},\"pay_for\":\"{$response['pay_for']}\",\"signature\":\"{$response['signature']}\"}";
    
    header("Content-type: application/json; charset=utf-8");
    echo iconv("cp1251", "utf-8", $out);
    die;
  }
  
  public function onpay_validate($request, $signature) {
    $request['pay_for'] = intval($request['pay_for']);
    if($request['signature'] != $signature) {
      return false;
    }
    return true;
  }

	
  public function status() {

 
		if (isset($this->request->post['type'])) {
			$type = $this->request->post['type'];
			$order_amount = $this->request->post['order_amount'];
			//$amount = $this->request->post['amount'];
			$order_currency = $this->request->post['order_currency'];
			$md5 = $this->request->post['md5'];
			$pay_for = $this->request->post['pay_for'];
			if (isset($this->request->post['onpay_id'])) {
				$onpay_id = $this->request->post['onpay_id'];
			}
			$onpay_security = $this->config->get('onpay_security');
			if ($type == 'check') {
				$result = $this->answer($type, 0, $pay_for, $order_amount, $order_currency, 'OK', $onpay_security);
				echo $result;
				return;
			}
			if ($type == 'pay') {
			}
			
			$crc = strtoupper(md5("pay;$pay_for;$onpay_id;$order_amount;$order_currency;$onpay_security"));
			
			if ($crc == $md5) {

				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($this->request->post['pay_for']);
				$order_id = $this->request->post['pay_for'];

				if (!$order_info) {
					echo 'ERROR:  No this order!';
					return 0;
				}

				if (number_format($this->request->post['paid_amount']) >= number_format($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], FALSE))) {

				if( $order_info['order_status_id'] != $this->config->get('onpay_order_status_id')) 
				{
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('onpay_order_status_id'));
				}

		
					echo $this->answerpay($type, 0, $pay_for, $order_amount, $order_currency, 'OK', $onpay_id, $onpay_security);
				} else {
					echo 'ERROR:  Amount filed!';
				}
			}
		}

	}

//функция выдает ответ для сервиса onpay в формате XML на чек запрос
	private function answer($type, $code, $pay_for, $order_amount, $order_currency, $text, $private_code) {
		$md5 = strtoupper(md5("$type;$pay_for;$order_amount;$order_currency;$code;" . $private_code));
		return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
		"<result>\n".
		"<code>$code</code>\n".
		"<pay_for>$pay_for</pay_for>\n".
		"<comment>$text</comment>\n".
		"<md5>$md5</md5>\n".
		"</result>";
	}
//функция выдает ответ для сервиса onpay в формате XML на pay запрос
	private function answerpay($type, $code, $pay_for, $order_amount, $order_currency, $text, $onpay_id, $private_code) {
		$md5 = strtoupper(md5("$type;$pay_for;$onpay_id;$pay_for;$order_amount;$order_currency;$code;" . $private_code));
		return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
		"<result>\n".
		"<code>$code</code>\n".
		"<comment>$text</comment>\n".
		"<onpay_id>$onpay_id</onpay_id>\n".
		"<pay_for>$pay_for</pay_for>\n".
		"<order_id>$pay_for</order_id>\n".
		"<md5>$md5</md5>\n".
		"</result>";
	}

	public function fail()
	{
		$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
		
		return TRUE;
	}
	
	public function success()
	{
		$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		
		return TRUE;
	}
}
?>