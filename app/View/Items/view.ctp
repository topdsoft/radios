<div class="items view">
<?php echo $this->Form->create('Item'); ?>
<h2><?php  echo __('Item').'# '.$item['Item']['id']; ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($item['Item']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($item['Item']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Location'); ?></dt>
		<dd>
			<?php echo h($item['Item']['location']); ?>
			&nbsp;
		</dd>
		<?php if($admin): ?>
		<dt>Viewed by:</dt>
		<dd>
			<?php foreach($item['User'] as $user) echo $user['username'].' on:'.$user['ItemsUser']['created'].'<br>'; ?>
		</dd>
		<?php endif; ?>
	</dl>
	<?php
//debug($item);
		foreach ($item['Image'] as $image) {
			//show each image for this item
			echo $this->Html->image($image['filename'],array('width'=>300,'url'=>'/img/'.$image['filename']));
		}
	?>
</div>
<?php echo $this->element('menu'); ?>
<div class="related">
	<?php if (!empty($item['Comment'])): ?>
	<h3><?php echo __('Item Comments'); ?></h3>
	<?php
		foreach ($item['Comment'] as $comment): ?>
			<strong><?php echo $users[$comment['user_id']]; ?></strong>
			<small><?php echo $comment['created']; ?></small><br>
			<?php echo  nl2br($comment['text']); ?>
			<?php //echo $this->Form->postLink(__('Delete'), array('controller' => 'comments', 'action' => 'delete', $comment['id']), null, __('Are you sure you want to delete # %s?', $comment['id'])); ?>
			<br><br>
	<?php endforeach; ?>
<?php endif; ?>

<?php  
	echo $this->Form->input('Comment.text',array('label'=>'Add Comment:'));
	echo $this->Form->end(__('Submit'));
?>
