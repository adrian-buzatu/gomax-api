<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Breadcrumbs -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?=base_url()?>">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="<?=base_url(). controller()?>"><?php echo ___text(controller())?></a></li>
  <li class="breadcrumb-item active"><?php echo ___text('add_banner')?></li>
</ol>

<?php echo form_open(controller(). '/add', 'class="js-banner-form"'); ?>
  <div class="form-group row">
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
  </div>
  <div class="form-group row">
    <label for="name" class="col-2 col-form-label"><?php echo ___text('name')?></label>
    <div class="col-3">
      <input class="form-control" type="text" value="" placeholder="" name="name" required=""  pattern=".{3,255}" title="<?php echo ___text('3_to_255_characters')?>">
    </div>
  </div>

  <div class="form-group row">
    <label for="name" class="col-2 col-form-label"><?php echo ___text('description')?></label>
    <div class="col-3">
      <textarea class="form-control" type="text" value="" placeholder="" name="description" pattern=".{3,255}" title="<?php echo ___text('3_to_255_characters')?>"></textarea>
    </div>
  </div>

  <div class="form-group row">
    <label for="status" class="col-2 col-form-label"><?php echo ___text('active')?></label>
    <div class="checkbox col-2">
      <input type="checkbox" value="1" checked name="status">
    </div>
  </div>

  <div class="form-group row">
    <fieldset>
      <legend>Gallery</legend>
      <!-- <ul class="backend-gallery-tabbed-menu">
        <li>
          Photo
        </li>
        <li>
          Video
        </li>
      </ul> -->
    </fieldset>
  </div>
  <div>
  <div id="dropzone">
    <div class="form-group row dropzone js-banner-file-upload-add">
      <label for="files">Add media</label>
      <!-- <input type="file" name="files" /> -->
      <div class="dz-message needsclick">
        Drop files here or click to upload.<br>
      </div>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-10">
      <input type="submit" value="Save" class="btn btn-primary">
    </div>

  </div>
<?php echo form_close(); ?>
