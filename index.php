
<!DOCTYPE html>
<html>
	<head>
		<title>Ecommerce Express</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<style>
		.popover
		{
		    width: 100%;
		    max-width: 800px;
		}

		</style>
	</head>
	<body class="bg-info">
	
	<nav class="navbar navbar-expand-md navbar-light bg-dark text-danger">	
			<h1 class="navbar-brand p-3">Ecommerce Express</h1>
				
			<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
				<span class="navbar-toggler-icon"></span>
			</button>
		
		<div class="collapse navbar-collapse" id="navbarMenu">
				<ul class="navbar-nav">
						<li class="nav-item">
								<a href="index.php" class="nav-link"><i class=" fa fa-home"></i> Home</a>
						</li>
						<li class="nav-item">
								<a href="#about" class="nav-link">About Us</a>
						</li>
						
				</ul>

				<ul class="navbar-nav">
					<li class="nav-item" >
						<a id="cart-popover" class="btn ml-auto" data-placement="bottom" title="Shopping Cart"  >
							<span class="glyphicon glyphicon-shopping-cart"></span>
							<span class="badge"></span>
							<span class="total_price">$ 0.00</span>
						</a>
					</li>
				</ul>
			</div>
	</nav>
	
			<div id="popover_content_wrapper" style="display: none">
				<span id="cart_details"></span>
				<div align="right">
					<a href="#" class="btn btn-primary" id="check_out_cart">
					<span class="glyphicon glyphicon-shopping-cart"></span> Check out
					</a>
					<a href="#" class="btn btn-default" id="clear_cart">
					<span class="glyphicon glyphicon-trash"></span> Clear
					</a>
				</div>
			</div>
			<div id="display_item">


			</div>

<footer>
		<div class="container-fluid">
				<div class="col-md-12 mt-2 p-2">
					<p class=" text-center bg-dark text-white p-2 container-fluid ">Copyright &copy; 2019 All rights reserved</p>
				</div>
		</div>
	</footer>

			
	</body>
</html>

<script>  
$(document).ready(function(){

	load_product();

	load_cart_data();
    
	function load_product()
	{
		$.ajax({
			url:"fetch_item.php",
			method:"POST",
			success:function(data)
			{
				$('#display_item').html(data);
			}
		});
	}

	function load_cart_data()
	{
		$.ajax({
			url:"fetch_cart.php",
			method:"POST",
			dataType:"json",
			success:function(data)
			{
				$('#cart_details').html(data.cart_details);
				$('.total_price').text(data.total_price);
				$('.badge').text(data.total_item);
			}
		});
	}

	$('#cart-popover').popover({
		html : true,
        container: 'body',
        content:function(){
        	return $('#popover_content_wrapper').html();
        }
	});

	$(document).on('click', '.add_to_cart', function(){
		var product_id = $(this).attr("id");
		var product_name = $('#name'+product_id+'').val();
		var product_price = $('#price'+product_id+'').val();
		var product_quantity = $('#quantity'+product_id).val();
		var action = "add";
		if(product_quantity > 0)
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{product_id:product_id, product_name:product_name, product_price:product_price, product_quantity:product_quantity, action:action},
				success:function(data)
				{
					load_cart_data();
					alert("Item has been Added into Cart");
				}
			});
		}
		else
		{
			alert("lease Enter Number of Quantity");
		}
	});

	$(document).on('click', '.delete', function(){
		var product_id = $(this).attr("id");
		var action = 'remove';
		if(confirm("Are you sure you want to remove this product?"))
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{product_id:product_id, action:action},
				success:function()
				{
					load_cart_data();
					$('#cart-popover').popover('hide');
					alert("Item has been removed from Cart");
				}
			})
		}
		else
		{
			return false;
		}
	});

	$(document).on('click', '#clear_cart', function(){
		var action = 'empty';
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:action},
			success:function()
			{
				load_cart_data();
				$('#cart-popover').popover('hide');
				alert("Your Cart has been clear");
			}
		});
	});
    
});

</script>
