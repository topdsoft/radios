<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
	<?php if($this->Session->read('Auth.User.id')==1): ?>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Add New User'), array('controller'=>'users','action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Items'), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Add New Item'), array('controller' => 'items', 'action' => 'add')); ?> </li>
	<?php else: ?>
		<li><?php echo $this->Html->link(__('List Items'), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Change Email'), array('controller' => 'users', 'action' => 'chemail')); ?> </li>
		<li><?php echo $this->Html->link(__('Change Password'), array('controller' => 'users', 'action' => 'chpw')); ?> </li>
	<?php endif; ?>
	</ul>
</div>
