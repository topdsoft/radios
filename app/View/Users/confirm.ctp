<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Confirm Account'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username',array('id'=>'sc','label'=>'Enter your desired Username'));
//		echo $this->Form->input('password');
		echo $this->Form->input('pw1',array('label'=>'Enter your new password:','type'=>'password'));
		echo $this->Form->input('pw2',array('label'=>'Repeat your new password:','type'=>'password'));
		echo $this->Form->input('email');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<script type='text/javascript'>document.getElementById('sc').focus();</script>