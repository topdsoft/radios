<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Edit Your Email Address'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('password',array('id'=>'sc','label'=>'Enter your old password'));
		echo $this->Form->input('pw1',array('type'=>'password','label'=>'Enter a new password'));
		echo $this->Form->input('pw2',array('type'=>'password','label'=>'Repeat your new password'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->element('menu'); ?>
<script type='text/javascript'>document.getElementById('sc').focus();</script>