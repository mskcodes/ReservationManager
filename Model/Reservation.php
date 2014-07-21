<?php
App::uses('ReservationManagerAppModel', 'ReservationManager.Model');
/**
 * Reservation Model
 *
 * @property Room $Room
 */
class Reservation extends ReservationManagerAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'room_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'cliente_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'passengers' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'checkin' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'checkout' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Room' => array(
			'className' => 'ReservationManager.Room',
			'foreignKey' => 'room_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

/**
 * Description
 * @param type $room_id 
 * @param type $left 
 * @param type $right 
 * @return type
 */
	public function getReservationsForRoom($room_id, $left, $right) {
		$this->recursive = 0;
		$options = array('conditions' => array(
			// 'or' => array(
				// array(
					// 'Reservation.checkin >=' => $left . ' 23:59:59',
					'Reservation.checkin <=' => $right . ' 00:00:00',
				// ),
				// array(
					'Reservation.checkout >=' => $left . ' 23:59:59',
					// 'Reservation.checkout <=' => $right . ' 00:00:00'
				// )
			// ),
			'Reservation.room_id' => $room_id
		));
		// print_r($this->find('all', $options));die;
		return $this->find('all', $options);
	}

	public function hasRoomReservationInDate($room, $date) {
		$options = array('conditions' => array(
			'Reservation.room_id' => $room['Room']['id'],
			'Reservation.checkin <=' => $date . ' 23:59:59',
			'Reservation.checkout >=' => $date . ' 00:00:00',
		));
		$this->recursive = 0;
		$reservation = $this->find('first', $options);
		return (!empty($reservation)) ? true : false;
	}


	public function getReservationsForRoomInDate($room, $date, $full = false) {
		$reservation_ids = array();
		$options = array(
			'conditions' => array(
				'Reservation.room_id' => $room['Room']['id'],
				'Reservation.checkin <=' => $date . ' 23:59:59',
				'Reservation.checkout >=' => $date . ' 00:00:00',
			),
			'fields' => array('id')
		);
		$this->recursive = -1;
		$reservations = $this->find('all', $options);

		if ($reservations) {
			return array('id' => $reservations[0]['Reservation']['id'], 'days' => $this->getReservationDaysQty($reservations[0]['Reservation']['id']));
		}
		return 0;
	}

/**
 * change the room state if today is between period of reservation for a given room_id or room object
 * @param int|array $room
 * @param string $checkin 
 * @param string $checkout 
 * @return void
 */
	public function changeRoomStateIfTodayInPeriod($room, $checkin, $checkout) {
		if (!is_array($room) && (is_string($room) || is_int($room))) {
			$room = $this->Room->findById($room);
		}
		$today = date('Y-m-d');
		if ($this->hasRoomReservationInDate($room, $today)) {
			return $this->Room->changeRoomState($room, 2); // 2 = Ocupada
		} else {
			if ($room['Room']['room_state_id'] != 4) {
				return $this->Room->changeRoomState($room, 1);
			}
		}
	}

	public function getReservationDaysQty($reservation) {
		if (!is_array($reservation) && (is_string($reservation) || is_int($reservation))) {
			$reservation = $this->findById($reservation);
		}
		$today = strtotime('today');
		$checkout = strtotime($reservation['Reservation']['checkout']);
		$checkin = strtotime($reservation['Reservation']['checkin']);
		if ($today > $checkin) {
			$checkin = $today;
			AQUI VOY VIENDO QUE LOS DIAS DISPONIBLES SE VEAN BIEN
		}
		$diff = $checkout - $checkin;
		return ($diff / (24*60*60)) + 1;
	}
}
