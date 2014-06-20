<div class="row">
	<div class="col-md-12 dates-range btn-group">
		<?php echo $this->Html->link(__('<<'), array('action' => 'index', 'date' => $prev), array('class' => "date left btn btn-primary col-xs-$col_width")); ?>
		<?php foreach($dates as $date): ?>
			<?php echo $this->Html->link($date, array('action' => 'add', 0, $date), array('class' => "date btn btn-" . (($date == date('Y-m-d')) ? 'primary' : 'default') . " col-xs-$col_width")); ?>
		<?php endforeach; ?>
		<?php echo $this->Html->link(__('>>'), array('action' => 'index', 'date' => $next), array('class' => "date right btn btn-primary col-xs-$col_width")); ?>
	</div>
</div>
<?php foreach ($rooms as $room): ?>	
	<div class="row">
		<div class="col-md-12 rooms">
			<div class="room">
				<?php echo $this->Html->link($room['Room']['name'], array('controller' => 'rooms', 'action' => 'state', $room['Room']['id']), array('class' => "btn btn-default col-xs-$col_width " . $room['RoomState']['color'])); ?>
				<?php if ($room['Reservation']): ?>
					<div class="reservations">
						<?php foreach ($room['Reservation'] as $reservation): ?>
							<div class="reservation-item col-xs-offset-<?php echo ($reservation['Reservation']['showed_days']); ?> col-xs-<?php echo $reservation['Reservation']['showed_width']; ?>">
								<?php echo $this->Html->link('
									<div class="progress progress-striped active">
										<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
											<span class="sr-only">Time ocupied</span>
										</div>
									</div>
								', array('action' => 'edit', $reservation['Reservation']['id']), array('escape' => false)); ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>
<div class="reservations">
	<h2><?php echo __('Reservations'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo 'id'; ?></th>
			<th><?php echo 'room_id'; ?></th>
			<th><?php echo 'cliente_id'; ?></th>
			<th><?php echo 'observation'; ?></th>
			<th><?php echo 'passengers'; ?></th>
			<th><?php echo 'checkin'; ?></th>
			<th><?php echo 'checkout'; ?></th>
			<th><?php echo 'created'; ?></th>
			<th><?php echo 'modified'; ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($rooms as $room): ?>
		<?php foreach($room['Reservation'] as $reservation): ?>
			<tr>
				<td><?php echo h($reservation['Reservation']['id']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($reservation['Room']['name'] . ' => ' . $reservation['Reservation']['showed_width'] . ' ' . $reservation['Reservation']['showed_days'], array('controller' => 'rooms', 'action' => 'view', $reservation['Room']['id'])); ?>
				</td>
				<td><?php echo h($reservation['Reservation']['cliente_id']); ?>&nbsp;</td>
				<td><?php echo h($reservation['Reservation']['observation']); ?>&nbsp;</td>
				<td><?php echo h($reservation['Reservation']['passengers']); ?>&nbsp;</td>
				<td><?php echo h($reservation['Reservation']['checkin']); ?>&nbsp;</td>
				<td><?php echo h($reservation['Reservation']['checkout']); ?>&nbsp;</td>
				<td><?php echo h($reservation['Reservation']['created']); ?>&nbsp;</td>
				<td><?php echo h($reservation['Reservation']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $reservation['Reservation']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $reservation['Reservation']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $reservation['Reservation']['id']), array(), __('Are you sure you want to delete # %s?', $reservation['Reservation']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
<?php endforeach; ?>
	</table>
</div>
