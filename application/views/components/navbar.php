<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <a class="navbar-brand" href="<?=base_url('invoice/list')?>"><h4 class="text-uppercase">Invoice Application</h4></a>
		  <button class="navbar-toggler hide_on_print" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse hide_on_print" id="navbarSupportedContent">
		    <ul class="navbar-nav ml-auto">
		      <li class="nav-item <?=$menu === 1 ? 'active' : ''?>">
		        <a class="nav-link" href="<?=base_url('invoice/list')?>">Invoice List</a>
		      </li>
		      <li class="nav-item <?=$menu === 2 ? 'active' : ''?>">
		        <a class="nav-link" href="<?=base_url('invoice/new_invoice')?>">New Invoice</a>
		      </li>
		    </ul>
		  </div>
		</nav>