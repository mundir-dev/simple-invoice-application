<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $this->load->view('components/header'); ?>
  </head>
  <body>
    <div class="app-main-bg">
    <div class="container">
    	<?php $this->load->view('components/navbar', ['menu' => 2]); ?>
    	<div class="card">
			<div class="card-body">
				<?php $this->load->view('components/invoice_form'); ?>
				<?php $this->load->view('components/invoice_items_form'); ?>
				
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend">
						      <span class="input-group-text">Discount</span>
						    </div>
						    <input type="number" name="discount" id="discount" min="0" value="0" step=".01" class="form-control">
						</div>
						<div class="d-flex flex-row mt-2">
							<label class="mr-2 switch">
							  <input type="checkbox" name="discount_in_percentage">
							  <span class="slider round"></span>
							</label>
							<span><b>In Percentage</b></span>
						</div>
					</div>
					<?php $this->load->view('components/invoice_footer'); ?>
					<div class="form-group text-right">
						<button type="buttton" class="btn btn-primary text-uppercase" id="generate_invoice">Generate Invoice</button>
					</div>
			</div>
    	</div>
    </div>
    </div>

    <?php $this->load->view('components/modal'); ?>

    <?php $this->load->view('components/footer'); ?>
  </body>
</html>