<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?> <?php echo $tmp; ?>
      </h1>
    </div>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $entry_date_start; ?>
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" /></td>
          <td><?php echo $entry_date_end; ?>
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" /></td>
          <td><?php echo $entry_group; ?>
            <select name="filter_group">
              <?php foreach ($groups as $groups) { ?>
              <?php if ($groups['value'] == $filter_group) { ?>
              <option value="<?php echo $groups['value']; ?>" selected="selected"><?php echo $groups['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td><?php echo $entry_status; ?>
            <select name="filter_order_status_id">
              <option value="0"><?php echo $text_all_status; ?></option>
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </td>







          <td><?php echo $entry_customer; ?>
            <input type="text" name="filter_customer_name" value="<?php echo $filter_customer_name; ?>">
          </td>

          <td><?php echo $entry_referrer; ?>
            <input type="text" name="filter_referrer_name" value="<?php echo $filter_referrer_name; ?>">
          </td>






          <td style="text-align: right;">
            <a onclick="filter();" class="button"><?php echo $button_filter; ?></a>
            <a href="<?php echo ($export . '&filter_date_start=' . $filter_date_start . '&filter_date_end=' . $filter_date_end . '&filter_group=' . $filter_group . '&filter_order_status_id=' . $filter_order_status_id . '&filter_customer_name=' . $filter_customer_name . '&page=' . $page) ; ?>" class="button">
              <?php echo $button_export; ?>
            </a>
            <?php echo $referrer_name; ?>
          </td>







        </tr>
      </table>
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_date_start; ?></td>
            <td class="left"><?php echo $column_date_end; ?></td>
            <td class="right"><?php echo $column_orders; ?></td>
            <td class="right"><?php echo $column_products; ?></td>
            <td class="right"><?php echo $column_tax; ?></td>
            <td class="right"><?php echo $column_total; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr>
            <td class="left"><?php echo $order['date_start']; ?></td>
            <td class="left"><?php echo $order['date_end']; ?></td>
            <td class="right"><?php echo $order['orders']; ?></td>
            <td class="right"><?php echo $order['products']; ?></td>
            <td class="right"><?php echo $order['tax']; ?></td>
            <td class="right"><?php echo $order['total']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>







<script type="text/javascript"><!--
    //////////////////////////////////////////////////////////
    function exportToCsv () {
        url = 'index.php?route=report/sale_order&token=<?php echo $token; ?>';
        location = document.URL + '&export';
    }
    //--></script>



<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/sale_order&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').attr('value');
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != 0) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}





    var filter_customer_name = $('input[name="filter_customer_name"]').val();
    if (filter_customer_name)
        url += '&filter_customer_name=' + encodeURIComponent(filter_customer_name);


    var filter_referrer_name = $('input[name="filter_referrer_name"]').val();
    if (filter_referrer_name)
        url += '&filter_referrer_name=' + encodeURIComponent(filter_referrer_name);






    location = url;
}
//-->
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});





    $('input[name=\'filter_customer_name\']').catcomplete({
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            category: item.customer_group,
                            label: item.name,
                            value: item.customer_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $(this).val(ui.item.label);
            filter(); // immediately filter once select
        },
        focus: function(event, ui) {
            return false;
        }
    });





    $('input[name=\'filter_referrer_name\']').catcomplete({
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            category: item.customer_group,
                            label: item.name,
                            value: item.customer_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $(this).val(ui.item.label);
            filter(); // immediately filter once select
        },
        focus: function(event, ui) {
            return false;
        }
    });






});
//--></script>









<script type="text/javascript"><!--
    $.widget('custom.catcomplete', $.ui.autocomplete, {
        _renderMenu: function(ul, items) {
            var self = this, currentCategory = '';
            $.each(items, function(index, item) {
                if (item.category != currentCategory) {
                    ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
                    currentCategory = item.category;
                }
                self._renderItem(ul, item);
            });
        }
    });
    //--></script>







<?php echo $footer; ?>