<!DOCTYPE html>

<html>
<head>
	<title>Products List</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap.css'?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/style.css'?>">
	
</head>
<body>
<div class="container"><br/>
<div class="row">
        <div class="col-md-10">
            <h2>Product list</h2>
        </div>
        <div class="col-md-2">
            <div id="detail_cart">
                
            </div> 
        
        </div>
    </div>
	<hr/>
	<div class="row">
		<div class="col-md-12">
			
			<div class="row">
			<?php foreach ($data as $row) : ?>
				<div class="col-md-4">
					<div class="thumbnail">
						<img width="200" src="<?php echo $row->image; ?>">
						<div class="caption">
							<h4 class="ellipsis"><?php echo $row->title;?></h4>
							<div class="row">
								<div class="col-md-12">
									<h4><?php echo $this->config->item('currency')."".number_format($row->price);?></h4>
								</div>
								
							</div>
							<a href="<?php echo site_url('product/details/'.$row->id);?>" class="btn btn-success btn-block">View Product</a>
						</div>
					</div>
				</div>
			<?php endforeach;?>
				
			</div>

		</div>
		<!--<div class="col-md-4">
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
				<tbody id="detail_cart">

				</tbody>
				
			</table>
		</div>-->
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