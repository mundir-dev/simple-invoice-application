<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($discount) && isset($discount_type) && $discount_type === "%") {
	$discount_percentage = floatval($discount);
	$total_amount        = ($sub_total + $taxes);
	$discount            = ($total_amount * $discount_percentage) / 100;
}
if (isset($total)) {
	$total = ($sub_total + $taxes) - $discount;
}
?>
<div class="form-group bg-dark text-white p-3">
	<div class="d-flex flex-row justify-content-end">
		<table>
			<tr>
				<th>Subtotal</th>
				<td>&nbsp;:&nbsp;</td>
				<td><?=isset($sub_total) ? '$'.$sub_total : '-'?></td>
			</tr>
			<tr>
				<th>Taxes</th>
				<td>&nbsp;:&nbsp;</td>
				<td><?=isset($taxes) ? '$'.$taxes : '-'?></td>
			</tr>
			<tr>
				<th>Discount</th>
				<td>&nbsp;:&nbsp;</td>
				<td><?=isset($discount) ? '$'.$discount : '-'?> <?=isset($discount_percentage) ? '('.$discount_percentage.'%)' : ''?></td>
			</tr>
			<tr>
				<th>Total</th>
				<td>&nbsp;:&nbsp;</td>
				<td><?=isset($total) ? '$'.$total : '-'?></td>
			</tr>
		</table>
	</div>
</div>