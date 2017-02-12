<?php
class ControllerCheckoutReferrer extends Controller {
    public function index() {
        $this->language->load('checkout/checkout');
        $this->data['text_referrer'] = $this->language->get('text_referrer');
        $this->data['button_continue'] = $this->language->get('button_continue');
        if (isset($this->session->data['referrer_id'])) {
            $this->data['referrer_id'] = $this->session->data['referrer_id'];
        } else {
            $this->data['referrer_id'] = '';
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/referrer.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/referrer.tpl';
        } else {
            $this->template = 'default/template/checkout/referrer.tpl';
        }
        $this->response->setOutput($this->render());
    }

    public function validate() {
        $this->language->load('checkout/checkout');
        $this->session->data['referrer_id'] = 0;
        $json = array();
        if (isset($this->request->post['referrer_id'])) {
            $referrer_id = $this->request->post['referrer_id'];
            if (!empty($referrer_id)) {
                if (!is_numeric($referrer_id)) {
                    $json['error']['warning'] = $this->language->get('error_referrer') . ' Must be a number.';
                } else {
                    if (!is_int($referrer_id + 0)) {
                        $json['error']['warning'] = $this->language->get('error_referrer') . ' Must be an integer.';
                    } else {
                        // check whether the referrer id is a valid customer id
                        $this->load->model('account/customer');
                        $result = $this->model_account_customer->getCustomer($referrer_id);
                        if (count($result) == 0 || $referrer_id == '') {
                            $json['error']['warning'] = $this->language->get('error_referrer') . ' No such customer.';
                        } else {
                            if ($this->customer->isLogged() &&
                                $this->customer->getId() == $referrer_id) {
                                $json['error']['warning'] = $this->language->get('error_referrer') . ' Cannot be your own id.';
                            } else {
                                $this->session->data['referrer_id'] = (int) $referrer_id;
                            }
                        }
                    }
                }
            }
        }
        $this->response->setOutput(json_encode($json));
    }
}
?>