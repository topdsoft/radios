<div class="items index">
	<h2><?php echo __('Items'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('location'); ?></th>
			<th><?php echo $this->Paginator->sort('images'); ?></th>
			<th><?php echo $this->Paginator->sort('comments'); ?></th>
			<th><?php echo $this->Paginator->sort('views'); ?></th>
			<th></th>
	</tr>
<?php //debug($items) ?>
	<?php
	foreach ($items as $item): ?>
	<tr>
		<td><?php echo h($item['Item']['id']); ?>&nbsp;</td>
		<td><?php if(isset($item['Image'][0])) echo $this->Html->image('thumbnails/'.$item['Image'][0]['filename']); ?></td>
		<td><?php echo h($item['Item']['created']); ?>&nbsp;</td>
		<td><?php echo h($item['Item']['location']); ?>&nbsp;</td>
		<td><?php echo h($item['Item']['images']); ?>&nbsp;</td>
		<td><?php echo h($item['Item']['comments']); ?>&nbsp;</td>
		<td><?php echo h($item['Item']['views']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $item['Item']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $item['Item']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $item['Item']['id']), null, __('Are you sure you want to delete # %s?', $item['Item']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php echo $this->element('menu'); ?>