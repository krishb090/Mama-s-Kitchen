<!-- PHP INCLUDES -->

<?php

    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";

    $is_logged_in = isset($_SESSION['customer_email']); // Adjust this based on your login system
    //Getting website settings

    $stmt_web_settings = $con->prepare("SELECT * FROM website_settings");
    $stmt_web_settings->execute();
    $web_settings = $stmt_web_settings->fetchAll();

    $restaurant_name = "";
    $restaurant_email = "";
    $restaurant_address = "";
    $restaurant_phonenumber = "";

    foreach ($web_settings as $option)
    {
        if($option['option_name'] == 'restaurant_name')
        {
            $restaurant_name = $option['option_value'];
        }

        elseif($option['option_name'] == 'restaurant_email')
        {
            $restaurant_email = $option['option_value'];
        }

        elseif($option['option_name'] == 'restaurant_phonenumber')
        {
            $restaurant_phonenumber = $option['option_value'];
        }
        elseif($option['option_name'] == 'restaurant_address')
        {
            $restaurant_address = $option['option_value'];
        }
    }

?>

	<!-- HOME SECTION -->
    <form id="search-form" method="GET" action="search_results.php">
        <input type="text" id="search-query" name="query" placeholder="Search menu items..." />
        <button type="submit">Search</button>
    </form>

	<section class="home-section" id="home">
		<div class="container">
			<div class="row" style="flex-wrap: nowrap;">
				<div class="col-md-6 home-left-section">
					<div style="padding: 100px 0px; color: white;">
						<h1>
							Mama's Kitchen.
						</h1>
						<h2>
							MAKING PEOPLE HAPPY
						</h2>
						<hr>
						<p>
							Italian Pizza With Cherry Tomatoes and Green Basil  
						</p>
						<div style="display: flex;">
							<a href="order_food.php" target="_blank" class="bttn_style_1" style="margin-right: 10px; display: flex;justify-content: center;align-items: center;">
								Order Now
								<i class="fas fa-angle-right"></i>
							</a>
							<a href="#menus" class="bttn_style_2" style="display: flex;justify-content: center;align-items: center;">
								VIEW MENU
								<i class="fas fa-angle-right"></i>
							</a>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>

	<!-- OUR QUALITIES SECTION -->

	<section class="our_qualities" style="padding:100px 0px;">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="our_qualities_column">
	                    <img src="Design/images/quality_food_img.png" >
	                    <div class="caption">
	                        <h3>
	                            Quality Foods
	                        </h3>
	                        <p>
                            Consistent excellence in every bite, ensuring satisfaction with every order.
	                        </p>
	                    </div>
	                </div>
				</div>
				<div class="col-md-4">
					<div class="our_qualities_column">
	                    <img src="Design/images/fast_delivery_img.png" >
	                    <div class="caption">
	                        <h3>
                            Fast Delivery
	                        </h3>
	                        <p>
                            Experience the taste of freshness delivered right to your door in no time.
	                        </p>
	                    </div>
	                </div>
				</div>
				<div class="col-md-4">
					<div class="our_qualities_column">
	                    <img src="Design/images/original_taste_img.png" >
	                    <div class="caption">
	                        <h3>
                            Delicious Meals
	                        </h3>
	                        <p>
                            Your favorite dishes are just a click away, ready to satisfy your cravings.
	                        </p>
	                    </div>
	                </div>
				</div>

			</div>
		</div>
	</section>





    <!-- OFFERS SECTION -->
    <?php if ($is_logged_in): ?>
        <section class="offers-section" id="offers" style="padding: 100px 0;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 30px;">Special Offers</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="offer-box">
                    <img src="Design/images/offer1.jpg" alt="Offer 1" class="offer-image">
                    <h3>Offer 1</h3>
                    <p>Get 20% off on your first order!</p>
                    <p><strong>Valid till:</strong> 31st August 2024</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="offer-box">
                    <img src="Design/images/offer2.jpg" alt="Offer 2" class="offer-image">
                    <h3>Offer 2</h3>
                    <p>Buy one pizza and get the second one free!</p>
                    <p><strong>Valid till:</strong> 30th September 2024</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="offer-box">
                    <img src="Design/images/offer3.jpg" alt="Offer 3" class="offer-image">
                    <h3>Offer 3</h3>
                    <p>Free delivery on orders above $50!</p>
                    <p><strong>Valid till:</strong> 31st December 2024</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


	<!-- OUR MENUS SECTION -->

	<section class="our_menus" id="menus">
		<div class="container">
			<h2 style="text-align: center;margin-bottom: 30px">DISCOVER OUR MENUS</h2>
			<div class="menus_tabs">
				<div class="menus_tabs_picker">
					<ul style="text-align: center;margin-bottom: 70px">
						<?php

	                        $stmt = $con->prepare("Select * from menu_categories");
	                        $stmt->execute();
	                        $rows = $stmt->fetchAll();
	                        $count = $stmt->rowCount();

	                        $x = 0;

	                        foreach($rows as $row)
	                        {
	                        	if($x == 0)
	                        	{
	                        		echo "<li class = 'menu_category_name tab_category_links active_category' onclick=showCategoryMenus(event,'".str_replace(' ', '', $row['category_name'])."')>";
	                        			echo $row['category_name'];
	                        		echo "</li>";

	                        	}
	                        	else
	                        	{
	                        		echo "<li class = 'menu_category_name tab_category_links' onclick=showCategoryMenus(event,'".str_replace(' ', '', $row['category_name'])."')>";
	                        			echo $row['category_name'];
	                        		echo "</li>";
	                        	}

	                        	$x++;
	                     		
	                        }
						?>
					</ul>
				</div>

				<div class="menus_tab">
					<?php
                
                        $stmt = $con->prepare("Select * from menu_categories");
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        $count = $stmt->rowCount();

                        $i = 0;

                        foreach($rows as $row) 
                        {

                            if($i == 0)
                            {

                                echo '<div class="menu_item  tab_category_content" id="'.str_replace(' ', '', $row['category_name']).'" style=display:block>';

                                    $stmt_menus = $con->prepare("Select * from menus where category_id = ?");
                                    $stmt_menus->execute(array($row['category_id']));
                                    $rows_menus = $stmt_menus->fetchAll();

                                    if($stmt_menus->rowCount() == 0)
                                    {
                                        echo "<div style='margin:auto'>No Available Menus for this category!</div>";
                                    }

                                    echo "<div class='row'>";
	                                    foreach($rows_menus as $menu)
	                                    {
	                                        ?>

                                        <div class="col-md-4 col-lg-3 menu-column">
                                            <a href="order_food.php" class="thumbnail" style="cursor:pointer;color: white;">
                                                <?php $source = "admin/Uploads/images/".$menu['menu_image']; ?>
                                                <div class="menu-image">
                                                    <div class="image-preview">
                                                        <div style="background-image: url('<?php echo $source; ?>');"></div>
                                                    </div>
                                                </div>
                                                <div class="caption">
                                                    <h5><?php echo $menu['menu_name']; ?></h5>
                                                    <p><?php echo $menu['menu_description']; ?></p>
                                                    <span class="menu_price"><?php echo "$".$menu['menu_price']; ?></span>
                                                </div>
                                            </a>
                                        </div>

	                                            </div>

	                                        <?php
	                                    }
	                                echo "</div>";

                                echo '</div>';

                            }

                            else
                            {

                                echo '<div class="menus_categories  tab_category_content" id="'.str_replace(' ', '', $row['category_name']).'">';

                                    $stmt_menus = $con->prepare("Select * from menus where category_id = ?");
                                    $stmt_menus->execute(array($row['category_id']));
                                    $rows_menus = $stmt_menus->fetchAll();

                                    if($stmt_menus->rowCount() == 0)
                                    {
                                        echo "<div class = 'no_menus_div'>No Available Menus for this category!</div>";
                                    }

                                    echo "<div class='row'>";
	                                    foreach($rows_menus as $menu)
	                                    {
	                                        ?>

                                        <div class="col-md-4 col-lg-3 menu-column">
                                            <a href="order_food.php" class="thumbnail" style="cursor:pointer;color: white;">
                                                <?php $source = "admin/Uploads/images/".$menu['menu_image']; ?>
                                                <div class="menu-image">
                                                    <div class="image-preview">
                                                        <div style="background-image: url('<?php echo $source; ?>');"></div>
                                                    </div>
                                                </div>
                                                <div class="caption">
                                                    <h5><?php echo $menu['menu_name']; ?></h5>
                                                    <p><?php echo $menu['menu_description']; ?></p>
                                                    <span class="menu_price"><?php echo "$".$menu['menu_price']; ?></span>
                                                </div>
                                            </a>
                                        </div>
<style>
    

</style>

	                                        <?php
	                                    }
	                               	echo "</div>";

                                echo '</div>';

                            }

                            $i++;
                            
                        }
                    
                        echo "</div>";
                
                    ?>
				</div>
			</div>
		</div>
	</section>

	<!-- IMAGE GALLERY -->

	<section class="image-gallery" id="gallery">
		<div class="container">
			<h2 style="text-align: center;margin-bottom: 30px">IMAGE GALLERY</h2>
			<?php
				$stmt_image_gallery = $con->prepare("Select * from image_gallery");
                $stmt_image_gallery->execute();
                $rows_image_gallery = $stmt_image_gallery->fetchAll();

                echo "<div class = 'row'>";

	                foreach($rows_image_gallery as $row_image_gallery)
	                {
	                	echo "<div class = 'col-md-4 col-lg-3' style = 'padding: 15px;'>";
	                		$source = "admin/Uploads/images/".$row_image_gallery['image'];
	                		?>

	                		<div style = "background-image: url('<?php echo $source; ?>') !important;background-repeat: no-repeat;background-position: 50% 50%;background-size: cover;background-clip: border-box;box-sizing: border-box;overflow: hidden;height: 230px;">
	                		</div>

	                		<?php
	                	echo "</div>";
	                }

	            echo "</div>";
			?>
		</div>
	</section>

	<!-- CONTACT US SECTION -->

	<section class="contact-section" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 sm-padding">
                    <div class="contact-info">
                        <h2>
                            Get in touch with us & 
                            <br>send us message today!
                        </h2>
                        <p>
                        We’d love to hear from you! Reach out and send us a message today for any questions, feedback, or to place an order. Your satisfaction is our priority.                        </p>
                        <h3>
                            <?php echo $restaurant_address; ?>
                        </h3>
                        <h4>
                            <span>Email:</span> 
                            <?php echo $restaurant_email; ?>
                            <br> 
                            <span>Phone:</span> 
                            <?php echo $restaurant_phonenumber; ?>
                        </h4>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="contact-form">
                        <div id="contact_ajax_form" class="contactForm">
                            <div class="form-group colum-row row">
                                <div class="col-sm-6">
                                    <input type="text" id="contact_name" name="name" oninput="document.getElementById('invalid-name').innerHTML = ''" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');" class="form-control" placeholder="Name">
                                    <div class="invalid-feedback" id="invalid-name" style="display: block">
                                    	
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <input type="email" id="contact_email" name="email" oninput="document.getElementById('invalid-email').innerHTML = ''" class="form-control" placeholder="Email">
                                    <div class="invalid-feedback" id="invalid-email" style="display: block">
                                    	
                                    </div>
                                </div>
                            </div>
							<div class="form-group row">
                <div class="col-md-12">
                    <input type="text" id="contact_phone" name="contact_phone" oninput="document.getElementById('invalid-phone').innerHTML = ''" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" class="form-control" placeholder="Phone Number">
                    <div class="invalid-feedback" id="invalid-phone" style="display: block"></div>
                </div>
            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input type="text" id="contact_subject" name="subject" oninput="document.getElementById('invalid-subject').innerHTML = ''" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');" class="form-control" placeholder="Subject">
                                    <div class="invalid-feedback" id="invalid-subject" style="display: block">
                                    	
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea id="contact_message" name="message" oninput="document.getElementById('invalid-message').innerHTML = ''" cols="30" rows="5" class="form-control message" placeholder="Message"></textarea>
                                    <div class="invalid-feedback" id="invalid-message" style="display: block">
                                    	
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button id="contact_send" class="bttn_style_2">Send Message</button>
                                </div>
                            </div>
                            <div id="sending_load" style="display: none;">Sending...</div>
                            <div id="contact_status_message"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
function clearError(elementId) {
    document.getElementById(elementId).innerHTML = '';
}

function validateForm() {
    let isValid = true;

    const name = document.getElementById('contact_name').value.trim();
    const email = document.getElementById('contact_email').value.trim();
    const phone = document.getElementById('contact_phone').value.trim();
    const subject = document.getElementById('contact_subject').value.trim();
    const message = document.getElementById('contact_message').value.trim();

    if (name === '') {
        document.getElementById('invalid-name').innerHTML = 'Name is required';
        isValid = false;
    }

    if (email === '') {
        document.getElementById('invalid-email').innerHTML = 'Email is required';
        isValid = false;
    } else if (!validateEmail(email)) {
        document.getElementById('invalid-email').innerHTML = 'Enter a valid email address';
        isValid = false;
    }

    if (phone === '') {
        document.getElementById('invalid-phone').innerHTML = 'Phone number is required';
        isValid = false;
    }

    if (subject === '') {
        document.getElementById('invalid-subject').innerHTML = 'Subject is required';
        isValid = false;
    }

    if (message === '') {
        document.getElementById('invalid-message').innerHTML = 'Message is required';
        isValid = false;
    }

    return isValid;
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
</script>
	<!-- OUR QUALITIES SECTION -->
	
	<section class="our_qualities_v2">
		<div class="container">
			<div class="row">
				<div class="col-md-4" style="padding: 10px;">
					<div class="quality quality_1">
						<div class="text_inside_quality">
							<h5>Quality Foods</h5>
						</div>
					</div>
				</div>
				<div class="col-md-4" style="padding: 10px;">
					<div class="quality quality_2">
						<div class="text_inside_quality">
							<h5>Fastest Delivery</h5>
						</div>
					</div>
				</div>
				<div class="col-md-4" style="padding: 10px;">
					<div class="quality quality_3">
						<div class="text_inside_quality">
							<h5>Original Recipes</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- WIDGET SECTION / FOOTER -->

    <section class="widget_section" style="background-color: #222227;padding: 100px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer_widget">
                        <img src="Design/images/restaurant-logo.png" alt="Restaurant Logo" style="width: 150px;margin-bottom: 20px;">
                        <p>
                            Our Restaurnt is one of the bests, provide tasty Menus and Dishes. You can reserve a table or Order food.
                        </p>
                        <ul class="widget_social">
                            <li><a href="#" data-toggle="tooltip" title="Facebook"><i class="fab fa-facebook-f fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Twitter"><i class="fab fa-twitter fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Instagram"><i class="fab fa-instagram fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="LinkedIn"><i class="fab fa-linkedin fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Google+"><i class="fab fa-google-plus-g fa-2x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                     <div class="footer_widget">
                        <h3>Headquarters</h3>
                        <p>
                            <?php echo $restaurant_address; ?>
                        </p>
                        <p>
                            <?php echo $restaurant_email; ?>
                            <br>
                            <?php echo $restaurant_phonenumber; ?>   
                        </p>
                     </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer_widget">
                        <h3>
                            Opening Hours
                        </h3>
                        <ul class="opening_time">
                            <li>Monday - Friday 11:30am - 2:008pm</li>
                            <li>Monday - Friday 11:30am - 2:008pm</li>
                            <li>Monday - Friday 11:30am - 2:008pm</li>
                            <li>Monday - Friday 11:30am - 2:008pm</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer_widget">
                        <h3>Subscribe to our contents</h3>
                        <div class="subscribe_form">
                            <form action="#" class="subscribe_form" novalidate="true">
                                <input type="email" name="EMAIL" id="subs-email" class="form_input" placeholder="Email Address...">
                                <button type="submit" class="submit">SUBSCRIBE</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER BOTTOM  -->

    <?php include "Includes/templates/footer.php"; ?>

    <script type="text/javascript">

$(document).ready(function() {
    $('#contact_send').click(function() {
        var contact_name = $('#contact_name').val();
        var contact_email = $('#contact_email').val();
        var contact_subject = $('#contact_subject').val();
        var contact_message = $('#contact_message').val();
		var contact_phone = $('#contact_phone').val();

        var flag = 0;

        // Validation checks
        if ($.trim(contact_name) == "") {
            $('#invalid-name').text('This is a required field!');
            flag = 1;
        } else {
            if (contact_name.length < 5) {
                $('#invalid-name').text('Length is less than 5 letters!');
                flag = 1;
            }
        }

        if (!ValidateEmail(contact_email)) {
            $('#invalid-email').text('Invalid e-mail!');
            flag = 1;
        }

        if ($.trim(contact_subject) == "") {
            $('#invalid-subject').text('This is a required field!');
            flag = 1;
        }

        if ($.trim(contact_message) == "") {
            $('#invalid-message').text('This is a required field!');
            flag = 1;
        }
		if ($.trim(contact_phone) == "") {
            $('#invalid-phone').text('This is a required field!');
            flag = 1;
        }

        // If no validation errors, send the AJAX request
        if (flag == 0) {
            $('#sending_load').show();

			$.ajax({
    url: "Includes/php-files-ajax/contact.php",
    type: "POST",
    data: {
        name: contact_name,
        email: contact_email,
        subject: contact_subject,
        message: contact_message,
        phone: contact_phone
    },
    success: function(data) {
        var response = JSON.parse(data);
        if (response.status === 'success') {
            $('#contact_status_message').html('<div class="alert alert-success">' + response.message + '</div>');
            $('#contact_name').val('');
            $('#contact_email').val('');
            $('#contact_subject').val('');
            $('#contact_message').val('');
            $('#contact_phone').val('');
        } else {
            $('#contact_status_message').html('<div class="alert alert-danger">' + response.message + '</div>');
        }
    },
    beforeSend: function() {
        $('#sending_load').show();
    },
    complete: function() {
        $('#sending_load').hide();
    },
    error: function(xhr, status, error) {
        alert("Internal ERROR has occurred, please try later!");
    }
});
        }
    });
});
	</script>


<style>
/* offers-section styles */
.offers-section {
    background-color: #f8f9fa;
    border-top: 2px solid #ddd;
    padding: 60px 0;
}

.offer-box {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 20px;
    margin: 10px 0;
    text-align: center;
}

.offer-box h3 {
    margin-bottom: 15px;
    font-size: 1.5rem;
}

.offer-box p {
    margin: 0;
}

.offer-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 15px;
}
#search-form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    
}

#search-form input[type="text"] {
    width: 80%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

#search-form button {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background-color: #ffc851;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}

#search-form button:hover {
    background-color: #0056b3;
}

</style>