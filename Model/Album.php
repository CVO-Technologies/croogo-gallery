<?php

/**
 * Album
 *
 *
 * @category Model
 * @package  Croogo
 * @version  1.3
 * @author   Edinei L. Cipriani <phpedinei@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.edineicipriani.com.br
 */
class Album extends GalleryAppModel {

	public $actsAs = array(
		'Params',
	);

	public $validate = array(
		'slug' => array(
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Slug is already in use.',
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
			),
		),
	);

/**
 * Model associations: hasMany
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'Photo' => array(
			'className' => 'Gallery.Photo',
			'foreignKey' => 'album_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'Photo.title ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

}
