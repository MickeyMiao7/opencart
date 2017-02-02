<?php
class ControllerReportSaleOrder extends Controller {
    public function export() {
        $this->load->model('report/sale');


        if (isset($this->request->get['filter_date_start'])) {
            $filter_date_start = $this->request->get['filter_date_start'];
        } else {
            $filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
        }

        if (isset($this->request->get['filter_date_end'])) {
            $filter_date_end = $this->request->get['filter_date_end'];
        } else {
            $filter_date_end = date('Y-m-d');
        }

        if (isset($this->request->get['filter_group'])) {
            $filter_group = $this->request->get['filter_group'];
        } else {
            $filter_group = 'week';
        }

        if (isset($this->request->get['filter_order_status_id'])) {
            $filter_order_status_id = $this->request->get['filter_order_status_id'];
        } else {
            $filter_order_status_id = 0;
        }

        if (isset($this->request->get['filter_customer_name'])) {
            $filter_customer_name = $this->request->get['filter_customer_name'];
        } else {
            $filter_customer_name = '';
        }

        if (isset($this->request->get['filter_referrer_name'])) {
            $filter_referrer_name = $this->request->get['filter_referrer_name'];
        } else {
            $filter_referrer_name = '';
        }



        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }


        $data = array(
            'filter_date_start'	     => $filter_date_start,
            'filter_date_end'	     => $filter_date_end,
            'filter_group'           => $filter_group,
            'filter_order_status_id' => $filter_order_status_id,
            'filter_customer_name'   => $filter_customer_name,
            'filter_referrer_name'   => $filter_referrer_name,
            'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit'                  => $this->config->get('config_admin_limit')
        );

        $results = $this->model_report_sale->getOrders($data);

        foreach ($results as $result) {
            $orders[] = array(
                'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
                'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
                'orders'     => $result['orders'],
                'products'   => $result['products'],
                'tax'        => $this->currency->format($result['tax'], $this->config->get('config_currency')),
                'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
            );
        }





        $orders_data = array();
        $orders_column = array('Date Start', 'Date End', 'No. Orders', 'No. Products', 'Tax', 'Total');
        $orders_data[0] = $orders_column;

        foreach($orders as $orders_row)
        {
            $orders_data[] = $orders_row;
        }


        require_once(DIR_SYSTEM . 'library/excel_xml.php');
        $xls = new Excel_XML('UTF-8', false, 'Sale Orders Report');
        $xls->addArray($orders_data);
        $xls->generateXML('sale_orders_report_'.date('Y-m-d _ H:i:s'));
    }







	public function index() {


		$this->language->load('report/sale_order');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
			$this->data['filter_date_start'] = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
            $this->data['filter_date_start'] = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
            $this->data['filter_date_end'] = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
            $this->data['filter_date_end'] = date('Y-m-d');
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
            $this->data['filter_group'] = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
            $this->data['filter_group'] = 'week';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
            $this->data['filter_order_status_id'] = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
            $this->data['filter_order_status_id'] = 0;
		}

        if (isset($this->request->get['filter_customer_name'])) {
            $filter_customer_name = $this->request->get['filter_customer_name'];
            $this->data['filter_customer_name'] = $this->request->get['filter_customer_name'];
        } else {
            $filter_customer_name = '';
            $this->data['filter_customer_name'] = '';
        }

        if (isset($this->request->get['filter_referrer_name'])) {
            $filter_referrer_name = $this->request->get['filter_referrer_name'];
            $this->data['filter_referrer_name'] = $this->request->get['filter_referrer_name'];
        } else {
            $filter_referrer_name = '';
            $this->data['filter_referrer_name'] = '';
        }

        if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
            $this->data['page'] = $this->request->get['page'];
		} else {
			$page = 1;
            $this->data['page'] = 1;
		}







        $url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}




        if (isset($this->request->get['filter_customer_name'])) {
            $url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
        }


        if (isset($this->request->get['filter_referrer_name'])) {
            $url .= '&filter_referrer_name=' . $this->request->get['filter_referrer_name'];
        }



		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}









   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('report/sale');
		
		$this->data['orders'] = array();
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_group'           => $filter_group,
			'filter_order_status_id' => $filter_order_status_id,
            'filter_customer_name'   => $filter_customer_name,
            'filter_referrer_name'   => $filter_referrer_name,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$order_total = $this->model_report_sale->getTotalOrders($data);
		
		$results = $this->model_report_sale->getOrders($data);


        $tmp = "michael";
        $this->data['tmp'] = $this->model_report_sale->getCustomerId($tmp);





        if (isset($this->request->get['export'])) {
            export_to_csv($results, 'sale_order');
        }



		foreach ($results as $result) {
			$this->data['orders'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'orders'     => $result['orders'],
				'products'   => $result['products'],
				'tax'        => $this->currency->format($result['tax'], $this->config->get('config_currency')),
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
    	$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_products'] = $this->language->get('column_products');
		$this->data['column_tax'] = $this->language->get('column_tax');
		$this->data['column_total'] = $this->language->get('column_total');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_group'] = $this->language->get('entry_group');	
		$this->data['entry_status'] = $this->language->get('entry_status');




        $this->data['entry_customer'] = $this->language->get('entry_customer');
        $this->data['entry_referrer'] = $this->language->get('entry_referrer');





		$this->data['button_filter'] = $this->language->get('button_filter');



		// Button of Export CSV
        $this->data['button_export'] = $this->language->get('button_export');




		
		$this->data['token'] = $this->session->data['token'];



        $this->data['export'] = $this->url->link('report/sale_order/export', 'token=' . $this->session->data['token'], 'SSL');




        $this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['groups'] = array();

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}







        if (isset($this->request->get['filter_customer_name'])) {
            $url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
        }

        if (isset($this->request->get['filter_referrer_name'])) {
            $url .= '&filter_referrer_name=' . $this->request->get['filter_referrer_name'];
        }






		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_order_status_id'] = $filter_order_status_id;




        $this->data['filter_customer_name'] = $filter_customer_name;
        $this->data['filter_referrer_name'] = $filter_referrer_name;





		$this->template = 'report/sale_order.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
}
?>