<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-account" class="form-horizontal">

            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_module_name; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_module_name; ?>" id="input-name" class="form-control" />
                    <?php if ($error_name) { ?>
                    <div class="text-danger"><?php echo $error_name; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                    <?php foreach ($languages as $language) { ?>
                    <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="names[<?php echo $language['language_id']; ?>]" value="<?php echo isset($names[$language['language_id']]) ? $names[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                    </div>
                    <?php } ?>
                </div>
            </div>


            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_list; ?></label>
                <div class="col-sm-10">
                    <select name="zmenu_id" id="input-status" class="form-control">
                        <?php foreach($lists as $item) { ?>
                            <option value="<?php echo $item['id']; ?>" <?php if($item['id'] == $zmenu_id) { ?> selected <?php } ?>><?php echo $item['name']; ?></option>
                        <?php } ?>
                    </select>
                    <?php if ($error_zmenu_id) { ?>
                        <div class="text-danger"><?php echo $error_zmenu_id; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $text_menu_type; ?></label>
                <div class="col-sm-10">
                    <select name="menu_type" id="input-status" class="form-control">
                        <option value="horizontal" <?php if($menu_type == 'horizontal') { ?> selected="selected" <?php } ?> ><?php echo $text_menu_horizontal; ?></option>
                        <option value="vertical" <?php if($menu_type == 'vertical') { ?> selected="selected" <?php } ?>><?php echo $text_menu_vertical; ?></option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name"><span data-toggle="tooltip" title="<?php echo $text_template_info; ?>"><?php echo $entry_template; ?></span></label>
                <div class="col-sm-10">
                    <input type="text" name="template" value="<?php echo $template; ?>" placeholder="<?php echo $entry_template; ?>" id="input-name" class="form-control" />
                </div>
            </div>



            <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-8" ><?php echo $text_help; ?></div>
            </div>

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>