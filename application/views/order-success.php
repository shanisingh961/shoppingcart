<!DOCTYPE html>

<html>
<head>
	<title>Order Placed</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap.css'?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/style.css'?>">
</head>
<body>
    <div class="container"><br/>
        <div class="row">
            <div class="col-md-12">
                <h2><a href="<?php echo base_url(); ?>" tile="Home" >Home </a>| Order Success</h2>
            </div>
        <?php if(!empty($order)){ ?>
        <div class="col-md-12">
            <div class="alert alert-success">Your order has been placed successfully.</div>
        </div>
    	
        <!-- Order status & shipping info -->
        <div class="row">
            <div  class="col-md-12">
            <div class="hdr">Order Info</div><br>
            <p><b>Reference ID:</b> #<?php echo $order['id']; ?></p>
            <p><b>Total:</b> <?php echo 'Rs.'.$order['grand_total']; ?></p>
            <p><b>Placed On:</b> <?php echo $order['created']; ?></p>
            <p><b>Buyer Name:</b> <?php echo $order['name']; ?></p>
            <p><b>Email:</b> <?php echo $order['email']; ?></p>
            <p><b>Phone:</b> <?php echo $order['phone']; ?></p>
        </div>
        </div>
        </div>
        </div>
    </div>
    <?php } ?>
    
    
</body>
</html>
