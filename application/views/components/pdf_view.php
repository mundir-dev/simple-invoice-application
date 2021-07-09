<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$invoice_footer_data = [
   'sub_total' => 0,
   'taxes' => 0,
   'discount' => $invoice->discount,
   'discount_type' => $invoice->discount_type,
   'total' => 0
];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<style type="text/css">
		<?php echo file_get_contents('https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css') ?>

		@page { margin: 0px; }
        body { margin: 0px; }
	</style>
</head>
<body>
	<div class="card">
			<div class="card-body">
				<div class="card-header show_on_print">
                  <h4 class="m-0 text-uppercase text-center">Invoice Application<br><small>A complete solution</small></h4>
                </div>
				<?php if (isset($message)): ?>
					<div class="alert alert-info">
						<?=$message?>
					</div>
				<?php endif ?>

				<?php if ($invoice): ?>
					<div class="form-group bg-dark p-3">
						<h6 class="text-white m-0">INVOICE INFORMATION:</h6>
					</div>
					<table class="table table-bordered">
							<tr>
								<th>Invoice Number</th>
								<td>E-<?=$invoice->id?></td>
							</tr>
							<tr>
								<th>Generated Date</th>
								<td><?=date('Y-F-d', strtotime($invoice->created_on))?></td>
							</tr>
					</table>
					<div class="form-group bg-dark p-3">
						<h6 class="text-white m-0">CUSTOMER INFORMATION:</h6>
					</div>
					<table class="table table-bordered">
							<tr>
								<th>Customer Name</th>
								<td><?=$invoice->customer_name?></td>
							</tr>
							<tr>
								<th>Contact Number</th>
								<td><?=$invoice->contact_number?></td>
							</tr>
							<tr>
								<th>Billing Address</th>
								<td><?=nl2br($invoice->billing_address)?></td>
							</tr>
					</table>
				<?php endif ?>

				<?php if (isset($invoice_items)): ?>
					<div class="form-group bg-dark p-3">
						<h6 class="text-white m-0">INVOICE ITEMS:</h6>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>No.</th>
									<th>Name</th>
									<th>Quantity</th>
									<th>Unit Price (in $)</th>
									<th>Tax</th>
									<th>Description</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody id="invoice_items_area">
								<?php foreach ($invoice_items as $key => $value): ?>
									<?php 
									     $total = ($value->quantity * $value->unit_price); 
									     $tax   = ($total * $value->tax) / 100;
                                         
                                         $invoice_footer_data['sub_total'] = ($invoice_footer_data['sub_total'] + $total);
                                         $invoice_footer_data['taxes'] = ($invoice_footer_data['taxes'] + $tax);
									?>
									<tr>
										<th><?=$key+1?></th>
										<td><?=$value->item_name?></td>
										<td><?=$value->quantity?></td>
										<td>$<?=$value->unit_price?></td>
										<td><?=$value->tax?>%</td>
										<td><?=!empty($value->description) ? nl2br($value->description) : '-'?></td>
										<td>$<?=($total + $tax)?></td>
									</tr>
					            <?php endforeach ?>
							</tbody>
						</table>
					    </div>

					    <?php $this->load->view('components/invoice_footer', $invoice_footer_data); ?>
				<?php endif ?>

			</div>
    	</div>
</body>
</html>