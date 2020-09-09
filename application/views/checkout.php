<!DOCTYPE html>

<html>
<head>
	<title>Checkout</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap.css'?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/style.css'?>">
</head>
<body>
<div class="container"><br/>
	<div class="row">
	    <div col-md-12>
	        <h3><a href="<?php echo base_url(); ?>" tile="Home" >Home</a></h3>
	    </div>
		<div class="col-md-6 col-md-pull-6">
		            <form method="post" action="#">
                    <!--SHIPPING METHOD-->
                        <div class="form-group">
                            <div class="col-md-12">
                                <h4>CREATE AN ACCOUNT OR CHECKOUT</h4>
                                <hr/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                    <div class="radio">
                                    <label><input type="radio" name="account" value="register"> Register</label>
                                    </div>
                                    <div class="radio">
                                    <label><input type="radio" name="account" value="guest" checked="checked"> Guest Checkout</label>
                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <h4>CUSTOMER DETAILS</h4>
                                <hr/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <strong>Name:</strong>
                                <input type="text" name="name" class="form-control" value="<?php echo !empty($custData['name'])?$custData['name']:''; ?>" />
                                <?php echo form_error('name','<p class="help-block error">','</p>'); ?>
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <div class="col-md-12"><strong>Email Address:</strong></div>
                            <div class="col-md-12"><input type="text" name="email" class="form-control" value="<?php echo !empty($custData['email'])?$custData['email']:''; ?>" />
                            <?php echo form_error('email','<p class="help-block error">','</p>'); ?>
                            </div>
                            
                        </div>
                        
                        <div class="form-group hidden password">
                            <div class="col-md-12"><strong>Password:</strong></div>
                            <div class="col-md-12"><input type="password" name="password" class="form-control" value="" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12"><strong>Phone Number:</strong></div>
                            <div class="col-md-12"><input type="text" name="phone" class="form-control" value="<?php echo !empty($custData['phone'])?$custData['phone']:''; ?>" />
                            <?php echo form_error('phone','<p class="help-block error">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12"><strong>Address:</strong></div>
                            <div class="col-md-12">
                                <input type="text" name="address" class="form-control" value="<?php echo !empty($custData['address'])?$custData['address']:''; ?>" />
                                <?php echo form_error('address','<p class="help-block error">','</p>'); ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <button type="submit" name="placeOrder" class="btn btn-primary btn-submit-fix" value="placeOrder">Place Order</button>
                            </div>
                        </div>
                        </form>
                </div>
		<div class="col-md-5">
			<h4>Shopping Cart</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Items</th>
						<th>Price</th>
						<th>Qty</th>
						<th>Total</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody id="detail_cart1">

				</tbody>
				
			</table>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery-3.2.1.js'?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/js/bootstrap.js'?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$('#detail_cart1').load("<?php echo site_url('product/load_cart_detail');?>");

		$(document).on('change','.itmqty',function(){
			var rowid=$(this).attr("data-id");
			var qty=$(this).val(); 
			$.ajax({
				url : "<?php echo site_url('product/updateItemQty');?>",
				method : "POST",
				data : {rowid : rowid,qty : qty},
				success :function(data){
				    console.log(data);
					$('#detail_cart1').load("<?php echo site_url('product/load_cart_detail');?>");
				}
			});
		});
		
		$(document).on('change','.radio input',function(){
			
			var val=$(this).val(); 
			if(val == 'register'){
			    $('.password').removeClass('hidden');
			    $('.password input').prop('required', true);
			}else{
			    $('.password').addClass('hidden');
			    $('.password input').prop('required', false);
			}
		});
		
		$(document).on('click','.romove_cart',function(){
			var row_id=$(this).attr("id"); 
			$.ajax({
				url : "<?php echo site_url('product/delete_cart');?>",
				method : "POST",
				data : {row_id : row_id},
				success :function(data){
					$('#detail_cart1').load("<?php echo site_url('product/load_cart_detail');?>");
				}
			});
		});
	});
	
</script>

</body>
</html>