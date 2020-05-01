<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
          <div class="pull-right">
              <a href="<?php echo $add_href; ?>" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_add_menu; ?>"><i class="fa fa-plus"></i> <?php echo $button_add_menu; ?></a>
              <a href="<?php echo $clear_cache_href; ?>" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_clear_cache; ?>"><?php echo $button_clear_cache; ?></a>
              <a href="<?php echo $zmenu_href; ?>" class="btn btn-default" data-toggle="tooltip" title="ZMenu module"> ZMenu module</a>

              <a href="<?php echo $cancel; ?>" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>

          </div>
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

            <?php if ($success) { ?>
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>


        <div class="panel panel-default">
            <div class="panel-heading">
                   <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_lists; ?></h3>
            </div>
            <div class="panel-body">

                <?php if($items) { ?>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td class="left" width="50%"><?php echo $entry_name; ?></td>
                                <td class="right" width="20%"><?php echo $entry_action; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $item) { ?>
                            <tr>
                                <td class="left"><?php echo $item['name']; ?></td>
                                <td class="right">
                                    <a href="<?php echo $item['edit_href']; ?>" class="btn btn-default"><?php echo $button_edit; ?></a>
                                    <a href="<?php echo $item['copy_href']; ?>" class="btn btn-default"><?php echo $button_copy; ?></a>
                                    <a href="<?php echo $item['remove_href']; ?>" onclick="return confirm('<?php echo $button_remove; ?>?');" class="btn btn-default"><?php echo $button_remove; ?></a>
                                </td>

                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
  </div>


<?php echo $footer; ?>