
<?php 
	if($uid>1) {
		//regular user
		echo "<strong>Thank You</strong> for helping identify Ken's radios.<br>
To get started click here for a list of items we have found:";
		echo $this->Html->link(__('Item List'),array('controller'=>'items'));
	} elseif($uid==1) {
		//admin
		echo '<h2>Adding Items</h2>';
		echo '<li>To add a new item, click:'.$this->Html->link(__('Add Item'),array('controller'=>'items','action'=>'add'));
		echo '<li>Then enter a location if you want and click "Submit".';
		echo '<li>Once the Item has been created you can add images to it by clicking upload new image.';
		echo '<li>Find the images you want to add and select them.  If they uploaded ok, you should see them now.';
		echo '<h2>Adding Users</h2>';
		echo '<li>To add new users click:'.$this->Html->link(__('Add User'),array('controller'=>'users','action'=>'add'));
		echo '<li>You then need to enter their email address and the message you want them to get in the email';
		echo '<li>Along with your message thew will get a link to click on and complete their registeration by choosing a username and password.';
	} else {
		//not logged in
	}


