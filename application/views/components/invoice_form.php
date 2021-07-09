<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form id="invoice_form">
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
					<div class="form-group bg-dark p-3">
						<h6 class="text-white m-0">CUSTOMER INFORMATION:</h6>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="customer_name">Full Name<span class="text-danger">*</span></label>
								<input type="text" name="customer_name" id="customer_name" class="form-control" maxlength="100" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="contact_number">Contact Number<span class="text-danger">*</span></label>
								<input type="tel" name="contact_number" id="contact_number" class="form-control" required>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label for="billing_address">Billing Address<span class="text-danger">*</span></label>
									<textarea name="billing_address" id="billing_address" class="form-control" rows="5" required></textarea>
							</div>
						</div>
					</div>
					<button type="submit" id="save_invoice" hidden></button>
				</form>