<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo $site_name; ?> order!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">
	<?php echo $site_name; ?> order infromation
</h2>
You place an order item <?php echo $item_name;?>
<br/>
Amount : <?php echo $order_amount;?>
<br/>
Delivery : <?php echo $delivery_time;?>
<br/>
Service data : <?php echo $order_service_data;?>
<br/>
Notes : <?php echo $order_notes;?>
<br/>
Comments : <?php echo $order_comments;?>
<br/>
Response email : <?php echo $order_responce_email;?>
<br/>
This is an auto generated notification to email: <?php echo $email; ?>.
<br />

Have fun!<br />
The <?php echo $site_name; ?> Team
</td>
</tr>
</table>
</div>
</body>
</html>
