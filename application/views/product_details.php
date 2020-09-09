<!DOCTYPE html>

<html>
<head>
	<title>Products Details</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap.css'?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/style.css'?>">
	
</head>
<body>
<div class="container"><br/>
    <div class="row">
        <div class="col-md-10">
            <h2><a href="<?php echo base_url(); ?>" tile="Home" >Home </a>| Product Details</h2>
        </div>
        <div class="col-md-2">
            <div id="detail_cart">
                
            </div>
        </div>
	
	</div>
	<hr/>
	<div class="row">
	    <div class="col-md-4">
	        <div class="product_image">
	            <img src="<?php echo $data->image; ?>" alt="Product Image" title="<?php echo $data->title;?>" />
	        </div>
	    </div>
		<div class="col-md-8">
		    <h4><?php echo $data->title;?></h4>
			<div class="row">
			
				<div class="col-md-12">
						<div class="caption">
							<div class="row">
								<div class="col-md-12">
									<h4><?php echo $this->config->item('currency')."".number_format($data->price);?></h4>
								</div>
							</div>
							<div class="row">
							    <div class="col-md-3">
									<input type="number" name="quantity" id="<?php echo $data->id;?>" value="1" class="quantity form-control">
								</div>
								<div class="col-md-9">
								    <button class="add_cart btn btn-success btn-block" data-productid="<?php echo $data->id;?>" data-productname="<?php echo $data->title;?>" data-productprice="<?php echo $data->price;?>">Add To Cart</button>
								</div>
							</div>
							
						</div>
					
				</div>
			
				
			</div>

		</div>
	
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery-3.2.1.js'?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/js/bootstrap.js'?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.add_cart').click(function(){
			var product_id    = $(this).data("productid");
			var product_name  = $(this).data("productname");
			var product_price = $(this).data("productprice");
			var quantity   	  = $('#' + product_id).val();
			$.ajax({
				url : "<?php echo site_url('product/add_to_cart');?>",
				method : "POST",
				data : {product_id: product_id, product_name: product_name, product_price: product_price, quantity: quantity},
				success: function(data){
					$('#detail_cart').html(data);
				}
			});
		});

		
		$('#detail_cart').load("<?php echo site_url('product/load_cart');?>");

		
		$(document).on('click','.romove_cart',function(){
			var row_id=$(this).attr("id"); 
			$.ajax({
				url : "<?php echo site_url('product/delete_cart');?>",
				method : "POST",
				data : {row_id : row_id},
				success :function(data){
					$('#detail_cart').html(data);
				}
			});
		});
	});
	
</script>

</body>
</html>