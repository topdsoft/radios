<div class="items form">
<?php echo $this->Form->create('Item'); ?>
	<fieldset>
		<legend><?php echo __('Edit Item').'# '.$this->data['Item']['id']; ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('location');//debug($this->data);
		echo $this->Html->link(__('Upload New Image'),array('controller'=>'images','action'=>'upload',$this->data['Item']['id']));
	?>
	<br><table><tr>
	<?php
		foreach($this->data['Image'] as $image) {
		    //loop for each image
		    echo $this->Html->image($image['filename'],array('width'=>500));
		}
	?>
	</tr></table>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->element('menu'); ?>
