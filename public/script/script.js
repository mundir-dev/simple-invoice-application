let invoice_items = [];
const update_invoice_item_list = () => {
	let result_area     = $('#invoice_items_area');
	let sub_total       = 0;
	let taxes           = 0;
	let discount        = $('#discount').val();
	let discount_type   = $('input[name=discount_in_percentage]:checked').length > 0 ? '%' : '';
	    $('.invoice_item_row').remove();
	    $.each(invoice_items, function(index, val) {
	         let total_amount           = (val.quantity * val.unit_price);
	         let tax_amount             = (total_amount * val.tax.replace( /\D+/g, '')) / 100;
	         sub_total                  = (sub_total + total_amount); 
	         taxes                      = (taxes + tax_amount);
	         result_area.append(`<tr class="invoice_item_row">
	     	                   <td>${index + 1}</td>
	     	                   <td>${val.item_name}</td>
	     	                   <td>${val.quantity}</td>
	     	                   <td>$${val.unit_price}</td>
	     	                   <td>${val.tax}</td>
	     	                   <td>${(val.description.length > 0) ? val.description : '-'}</td>
	     	                   <td>$${total_amount + tax_amount}</td>
	     	                   <td><button type="button" class="btn-sm btn-danger cursor-pointer small" onclick="remove_item_from_invoice_items(${index})">X</button></td>
	     	                 </tr>`);
	    });

	    let total = (sub_total + taxes);
	    if (invoice_items.length > 0) {
            discount = discount_type === "%" ? (total * discount) / 100 : discount;
	    }
	    else{
	    	discount = 0;
	    }
	    $('#subtotal').text(`$${sub_total}`);
	    $('#taxes').text(`$${taxes}`);
	    $('#total_discount').text(`$${discount}${(discount_type === '%' && discount > 0) ? ` (${$('#discount').val()}%)` : ''}`);
	    $('#total').text(`$${(total - discount)}`);
}

const insert_invoice_item  = event => {
   let item = {};
   $('.invoice_item').each(function(index, el) {
   	 let name     = $(el).attr('name');
   	 let val      = $(el).val();
   	     if(val.length < 1 && $(el).attr('name') !== 'description'){
   	       feedback_area
           .removeClass('alert-success')
           .addClass('alert-warning')
           .html(`${name} field is required.`);
           $('#exampleModal').modal('show');
   	     	return false;
   	     }
   	     item[name] = val;
   });
   $('.invoice_item').val('');
   $('#total_amount').val('');
   invoice_items.push(item);
   update_invoice_item_list();
} 

const remove_item_from_invoice_items = id => {
	      invoice_items = invoice_items.filter((item, index) => {return index !== id});
	      update_invoice_item_list();
}


const generate_invoice = (event) => {
	  if (invoice_items.length < 1) {
	  	   feedback_area
           .removeClass('alert-success')
           .addClass('alert-warning')
           .html('Invoice Item list is empty.');
           $('#exampleModal').modal('show');
           return false;
	  }
      let form          = $(event.target);
      let form_data     = form.serializeArray();
      let button        = $('#generate_invoice');
      let discount      = $('#discount').val() > 0 ? $('#discount').val() : 0;
      let discount_type = $('input[name=discount_in_percentage]:checked').length > 0 ? '%' : '';
      let feedback_area = $('.form_feedback');
      button.attr('disabled', true).text('Generating...');
      feedback_area.html('');

         form_data.push({name:"invoice_items", value: JSON.stringify(invoice_items)});
         form_data.push({name:"discount", value: discount});
         form_data.push({name:"discount_type", value: discount_type});

         $.ajax({
         	url: `${base_url}generate_invoice`,
         	type: 'POST',
         	dataType: 'json',
         	data: form_data,
         })
         .done(function(data) {
         	$('input[name="csrf_test_name"]').val(data.csrf_token);
         	if (data.error) {
                feedback_area
         		.removeClass('alert-success')
         		.addClass('alert-warning')
                .html(data.error);
                $('#exampleModal').modal('show');
         	}
         	else if (data.success) {
         		feedback_area
         		.removeClass('alert-warning')
         		.addClass('alert-success')
         		.html(data.success);
         		form[0].reset();
         		invoice_items = [];
         		update_invoice_item_list();
         		$('#discount').val(0);
                $('#exampleModal').modal('show');
         	}
         	button.removeAttr('disabled').text('Generate Invoice');
         })
         .fail(function() {
         	feedback_area
         	.removeClass('alert-success')
         	.addClass('alert-warning')
            .html("Error, please refresh and try again");
            $('#exampleModal').modal('show');
         	button.text('Error');
         });
         

} 


$(document).ready(function() {

	$('#generate_invoice').click(function(event) {
		$('#save_invoice').click();
	});

	$('#invoice_form').submit(function(event) {
		event.preventDefault();
		generate_invoice(event);
	});;
	$('#invoice_items_form').submit(function(event) {
		event.preventDefault();
		insert_invoice_item(event);
	});

	$('.update_total').on('change, input', function(event) {
		let quantity = $('input[name=quantity]').val() > 0 ? $('input[name=quantity]').val() : 0;
		let unit_price = $('input[name=unit_price]').val() > 0 ? $('input[name=unit_price]').val() : 0;
		let tax = $('select[name=tax]').val().replace( /\D+/g, '') > 0 ? $('select[name=tax]').val().replace( /\D+/g, '') : 0;

		let total = (quantity * unit_price);
		let tax_amount = (total * tax) / 100;
		

		$('#total_amount').val(total + tax_amount);
	});

	$('input[name=discount_in_percentage]').on('change', (e) => {
		let discount_element = $('#discount');
		if (e.target.checked) {
			discount_element
			.attr({
				min: '0',
				max: '100'
			})
			.removeAttr('step');
			if (discount_element.val() > 100) {
               discount_element.val(100);
			}
		}
		else{
            discount_element
			.attr({
				min: '0',
				step: '.01'
			})
			.removeAttr('max');
		}
		if (invoice_items.length > 0) {
			update_invoice_item_list();
		}
	});
	$('#discount').on('change, input', (e) => {
		if (invoice_items.length < 1) {
		   feedback_area
           .removeClass('alert-success')
           .addClass('alert-warning')
           .html('Invoice Item list is empty.');
           $('#exampleModal').modal('show');
           $(e.target).val(0);
           return false;
		}
		update_invoice_item_list()
	});

	$(document).on('input', 'input[type=tel]', function(event) {
          let key = $(this).val();
          $(this).val(key.match(/^\+?\d*/g));
    });
});