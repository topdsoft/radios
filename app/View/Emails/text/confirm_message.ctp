<?php echo $user['User']['message']; ?>,

To activate your account click the following link:
<?php echo $this->Html->url( array('controller'=>'users','action' => 'confirm', $user['User']['hash']),true);?>

If this does not work, copy the link and open it in your web browser.