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
    	<?php $this->load->view('components/navbar', ['menu' => 1]); ?>
    	<div class="card">
			<div class="card-body">
				<?php if (isset($message) || $this->session->flashdata('messsage') !== null): ?>
					<div class="alert <?=$this->session->flashdata('messsage_class') !== null ? $this->session->flashdata('messsage_class') : 'alert-info'?>">
						<?=isset($message) ? $message : $this->session->flashdata('messsage')?>
					</div>
				<?php endif ?>

				<?php if (isset($invoice_items)): ?>
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>No.</th>
									<th>Invoice Number</span></th>
									<th>Customer Name</th>
									<th>Contact Number</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody id="invoice_items_area">
								<?php foreach ($invoice_items as $value): ?>
									<tr>
										<th><?=$count++?></th>
										<td>E-<?=$value->id?></td>
										<td><?=$value->customer_name?></td>
										<td><a href="tel: <?=$value->contact_number?>" title=""><?=$value->contact_number?></a></td>
										<td>
											<div class="d-flex flex-wrap w-100">
												<a href="<?=base_url('invoice/info/'.$value->id)?>" class="btn-sm btn-primary mx-1" title="View Details"><i data-feather="eye"></i></a>
												<form onsubmit="return confirm('Are you sure you want to delete this item?');" action="<?=base_url('invoice/delete/'.$value->id)?>" method="POST">
													<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
													<button type="submit" class="btn-sm btn-danger mx-1 cursor-pointer"><i data-feather="trash"></i></button>
												</form>
											</div>
										</td>
									</tr>
					            <?php endforeach ?>
							</tbody>
						</table>
					    </div>
				<?php endif ?>
                 
                <?php if (isset($pagination)): ?>
                	<div class="d-flex w-100 justify-content-end">
                		<?=$pagination?>
                	</div>
                <?php endif ?>

			</div>
    	</div>
    </div>
    </div>

    <?php $this->load->view('components/modal'); ?>
    <?php $this->load->view('components/footer'); ?>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
      feather.replace({width: 15})
    </script>
  </body>
</html>