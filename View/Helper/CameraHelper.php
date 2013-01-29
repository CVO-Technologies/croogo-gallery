<?php

App::uses('AppHelper', 'View/Helper');

class CameraHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
	);

	public function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);
		$this->Html->script('/gallery/js/camera', false, $options);
		$this->Html->css('/gallery/css/camera', false, $options);
		$this->Html->css('/gallery/css/camera-theme', false, $options);
	}

	public function album($album, $photos) {
		$galleryId = 'gallery' . Inflector::camelize($album['Album']['slug']);
		$result = $this->Html->div('camera_wrap camera_azure_skin', $photos, array( 'id' => $galleryId));
		return $result;
	}

	public function photo($album, $photo) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$options = array(
			'data-src' => '/' . $this->base . $photo['original'],
			'data-thumb' => '/' . $this->base . $photo['small'],
		);
		$imgTag = $this->Html->image('/' . $this->base . $photo['original'], array(
			'alt' => 'img',
			'style' => 'width:950px; background:transparent;',
		));
		$imgDes = $this->Html->div('camera_caption fadeFromBottom', $this->base . $photo['description']);
		$results = $this->Html->div('camera_images', $imgDes , $options);
		return $results;
	}

	public function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$galleryId = 'gallery' . Inflector::camelize($album['Album']['slug']);
		$js = sprintf('$(\'#%s\').camera(%s);',
			$galleryId,
			$config
		);

		$js = str_replace('"' . $galleryId . '"', $galleryId, $js);
		$this->Js->buffer($js);
	}

}
