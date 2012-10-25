<?php
$editUrl = $this->Html->link($album['Album']['title'], array(
	'plugin' => 'gallery',
	'controller' => 'albums',
	'action' => 'edit',
	$album['Album']['id'],
));
?>
<div class="users index">

	<h2><?php echo sprintf(__d('gallery', 'Manage photos in: %s'), $editUrl); ?></h2>

	<div class="actions">
	<ul>
		<li>
		<?php echo $this->Html->link(__('Albums'), array('action' => 'index')); ?>
		</li>
	</ul>
	</div>

    <div id="upload" class="clearfix">
    </div>
	<div id="return" class="clearfix">
		<?php if(isset($album['Photo'])): ?>
			<?php foreach($album['Photo'] as $photo): ?>
				<?php
				$filename = basename($photo['large']);
				$filename = $this->Html->link(
					$this->Text->truncate($filename, 40),
					'/' . $photo['original'],
					array('target' => '_blank', 'title' => $filename)
				);
				?>
				<div class="album-photo">
					<?php
					echo $this->Html->link(
						$this->Html->image('/'. $photo['small']),
						'/'. $photo['large'],
						array(
							'rel' => 'gallery-' . $photo['album_id'],
							'class' => 'thickbox',
							'escape' => false,
						)
					);
					?>
					<div class="photo-actions">
					<?php
						echo $this->Html->link(__d('gallery', 'remove', true),
							'javascript:;',
							array(
								'rel' => $photo['id'],
								'class' => 'remove',
							)
						);
					?>
					<?php
						echo $this->Html->link('edit', array(
							'controller' => 'photos',
							'action' => 'edit',
							$photo['id'],
							), array(
								'class' => 'edit',
							)
						);
					?>
					<?php
						echo $this->Html->link('up', array(
							'controller' => 'photos',
							'action' => 'moveup',
							$photo['id'],
							), array(
								'class' => 'up',
							)
						);
					?>
					<?php
						echo $this->Html->link('down', array(
							'controller' => 'photos',
							'action' => 'movedown',
							$photo['id'],
							), array(
								'class' => 'up',
							)
						);
					?>
					</div>
					<div class="path">
					<?php echo $filename; ?>
					</div>
					<?php if (!empty($photo['title'])): ?>
					<div class="description">
						<?php echo $this->Html->tag('strong', $this->Text->truncate(strip_tags($photo['title']), 100)); ?>
						<br />
						<?php echo $this->Text->truncate(strip_tags($photo['description']), 120); ?></div>
					<?php endif; ?>
					</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class='pagelist' style='text-align: center;'>
	<?php echo $this->Html->link('prev ', '#', array(
	    'class' => 'gallery-prev',
		    )
	);
	?>
	<span id='count'></span>
	|
	<span id='total'></span>
	<?php echo $this->Html->link(' next', '#', array(
	    'class' => 'gallery-next',
		    )
	);
	?>
	</div>

</div>
<?php echo $this->Html->script('/gallery/js/fileuploader', false);  echo $this->Html->css('/gallery/css/fileuploader', false); ?>
<script>
function createUploader(){            
	var containerTemplate = _.template(
		'<div class="album-photo">' +
		'	<a class="thickbox" rel="gallery-<%= Photo.album_id %>"' +
		'		href="/<%= Photo.large %>">' +
		'		<img src="/<%= Photo.small %>">' +
		'	</a>' +
		'	<div class="photo-actions">' +
		'		<a class="remove" href="javascript:;" rel="<%= Photo.id %>"><%= sRemove %></a>' +
		'		<a class="edit" href="/admin/gallery/photos/edit/<%= Photo.id %>"><%= sEdit %></a>' +
		'		<a class="up" href="/admin/gallery/photos/moveup/<%= Photo.id %>"><%= sUp %></a>' +
		'		<a class="down" href="/admin/gallery/photos/movedown/<%= Photo.id %>"><%= sDown %></a>' +
		'	</div>' +
		'	<div class="path">' +
		'		<a target="_blank" title="<%= Photo.original %>"' +
		'			href="/<%= Photo.original %>">' +
		'			...<%= Photo.original.substr(-40) %>' +
		'		</a>' +
		'	</div>' +
		'</div>'
	);

    var uploader = new qq.FileUploader({
        element: document.getElementById('upload'),
        action: '<?php echo $this->Html->url(array('action' => 'upload_photo', $album['Album']['id'])); ?>',
		onComplete: function(id, fileName, json) {
			$('.qq-upload-fail').fadeOut('fast', function(){
				$(this).remove();
			});
			var sRemove = '<?php echo __d('gallery', 'remove'); ?>';
			var sEdit = '<?php echo __d('gallery', 'edit'); ?>';
			var sUp = '<?php echo __d('gallery', 'up'); ?>';
			var sDown = '<?php echo __d('gallery', 'down'); ?>';
			var args = {
				Photo: json.Photo,
				sRemove: sRemove,
				sEdit: sEdit,
				sUp: sUp,
				sDown: sDown,
			};
			$('#return').append(containerTemplate(args));
			tb_init('a.thickbox');
		}
    });
}

// in your app create uploader as soon as the DOM is ready
// don't wait for the window to load  
$(function(){
	createUploader();
	$('.remove').live('click', function(){
		var obj = $(this);
		$.getJSON('<?php echo $this->Html->url('/admin/gallery/albums/delete_photo/');?>'+obj.attr('rel'), function(r) {
            if (r['status'] == 1) {
				obj.parents('.album-photo').fadeOut('fast', function(){
					$(this).remove();
				});
            } else {
                alert(r['msg']);	
            }
		});
	});

});

</script>
