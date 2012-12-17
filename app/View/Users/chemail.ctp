<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Edit Your Email Address'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('email',array('id'=>'sc'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->element('menu'); ?>
<script type='text/javascript'>document.getElementById('sc').focus();</script>