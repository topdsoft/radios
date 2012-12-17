<div class="users view">
<h2><?php  echo __('User: ').h($user['User']['username']); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Confirmed'); ?></dt>
		<dd>
			<?php echo h($user['User']['confirmed']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php echo $this->element('menu'); ?>
<div class="related">
	<?php if (!empty($user['Comment'])): ?>
	<h3><?php echo __('Users Comments'); ?></h3>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Item Id'); ?></th>
		<th><?php echo __('Text'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Comment'] as $comment): ?>
		<tr>
			<td><?php echo $comment['created']; ?></td>
			<td><?php echo $this->Html->link('Item#'.$comment['item_id'],array('controller'=>'items','action'=>'view',$comment['item_id'])); ?></td>
			<td><?php echo nl2br($comment['text']); ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
<?php //debug($user);  ?>
</div>
<div class="related">
	<?php if (!empty($user['Item'])): ?>
	<h3><?php echo __('Items Viewed'); ?></h3>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Date First Viewed'); ?></th>
		<th><?php echo __('Location'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Item'] as $item): ?>
		<tr>
			<td><?php echo $this->Html->link('Item# '.$item['id'],array('controller'=>'items','action'=>'view',$item['id'])); ?></td>
			<td><?php echo $item['ItemsUser']['created']; ?></td>
			<td><?php echo $item['location']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

</div>
