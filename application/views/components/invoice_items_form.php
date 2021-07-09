<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form id="invoice_items_form">
					<div class="form-group bg-dark p-3">
						<h6 class="text-white m-0">INVOICE ITEMS:</h6>
					</div>
					<div class="form-group">
						<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>No.</th>
									<th>Name<span class="text-danger">*</span></th>
									<th>Quantity<span class="text-danger">*</span></th>
									<th>Unit Price<span class="text-danger">*</span><br>(in $)</th>
									<th>Tax<span class="text-danger">*</span></th>
									<th>Description</th>
									<th>Total</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="invoice_items_area">
								<tr>
									<td>#</td>
									<td>
										<input type="text" name="item_name" class="form-control invoice_item" required>
									</td>
									<td>
										<input type="number" name="quantity" class="form-control invoice_item update_total" min="1" max="1000" required>
									</td>
									<td>
										<input type="number" name="unit_price" class="form-control invoice_item update_total" min="1" max="1000" step=".01" required>
									</td>
									<td>
										<select name="tax" class="form-control invoice_item update_total" required>
											<option value="">Select</option>
											<option value="0%">0%</option>
											<option value="5%">5%</option>
											<option value="10%">10%</option>
										</select>
									</td>
									<td>
										<textarea name="description" class="form-control invoice_item" rows="2"></textarea>
									</td>
									<td>
										<input id="total_amount" class="form-control" readonly>
									</td>
									<td><button type="submit" class="btn btn-success text-uppercase insert_item">+</button></td>
								</tr>
							</tbody>
						</table>
					    </div>
					</div>
				</form>