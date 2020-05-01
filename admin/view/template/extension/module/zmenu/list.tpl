<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
          <div class="container-fluid">
            <div class="pull-right">
                <a onclick="$('#form').submit();" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_save; ?>"><i class="fa fa-save"></i> <?php echo $button_save; ?></a>
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

        <div class="panel panel-default">
            <div class="panel-body">
             <div class="content">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table id="module" class="form">
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_list_name; ?></td>
                    <td>
                        <input type="text" name="name" size="30" value="<?php echo $item['name']; ?>" required />
                    </td>
                </tr>
                <tr>
                    <td><?php echo $text_icons; ?>:</td>
                    <td>
                        <select name="icons">
                            <option value="0" <?php if($item['icons'] == 0) { ?> selected <?php } ?> ><?php echo $text_disabled; ?></option>
                            <option value="1" <?php if($item['icons'] == 1) { ?> selected <?php } ?> ><?php echo $text_enabled; ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $text_icons_size; ?>:</td>
                    <td>
                        <input type="text" name="icons_width" value="<?php echo $item['icons_width']; ?>" size="2" /> X <input type="text" name="icons_height" value="<?php echo $item['icons_height']; ?>" size="2"/>
                    </td>
                </tr>
                <tr>
                    <td width="250" valign="top">
                        <div class="zbox">
                            <div class="arrow"><br /></div>
                            <h3 class="handle"><span><?php echo $text_custom_link; ?></span></h3>
                            <div class="inside">
                                <p>
                                    <label for="custom-menu-item-url">
                                        <span>URL</span>
                                        <input id="custom-menu-item-url" type="text"  value="http://" />
                                    </label>
                                </p>
                                <p>
                                    <label for="custom-menu-item-name">
                                        <span class="fleft"><?php echo $text_title; ?></span>
                                        <span class="fright">
                                            <?php foreach($languages as $lang) { ?>
                                                <span>
                                                    <img src="language/<?php echo $lang['code']; ?>/<?php echo $lang['code']; ?>.png" title="<?php echo $lang['name']; ?>"><input type="text" name="custom-menu-item-name[<?php echo $lang['language_id']; ?>]" id="custom-menu-item-name" data-lang="<?php echo $lang['language_id']; ?>"/>
                                                </span>
                                            <?php } ?>
                                        </span>
                                    </label>
                                </p>
                                <p class="button-controls">
                                    <span class="add-to-menu">
                                        <input type="button" class="js-add-href" value="<?php echo $button_add; ?>" />
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="zbox">
                            <div class="arrow"><br /></div>
                            <h3 class="handle"><span><?php echo $text_custom_html; ?></span></h3>
                            <div class="inside">
                                <p class="button-controls">
                                    <span class="add-to-menu">
                                        <input type="button" class="js-add-html" value="<?php echo $button_add; ?>" />
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="zbox">
                            <div class="arrow"><br /></div>
                            <h3 class="handle"><span><?php echo $text_informations; ?></span></h3>
                            <div class="inside">
                                <div class="inside-informations inside-fff ">
                                    <ul class="informations">
                                        <?php foreach($informations as $info) { ?>
                                            <li>
                                                <label>
                                                    <input type="checkbox" name="information" data-id="<?php echo $info['information_id']; ?>" data-title="<?php echo $info['title']; ?>" data-href="<?php echo $info['href']; ?>"/> <?php echo $info['title']; ?>
                                                </label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <p class="button-controls">
                                    <span class="add-to-menu">
                                        <input type="button" class="js-add-information" value="<?php echo $button_add; ?>" />
                                    </span>
                                </p>
                            </div>
                        </div>


                        <div class="zbox zbox-zindex">
                            <div class="arrow"><br /></div>
                            <h3 class="handle"><span><?php echo $text_categories; ?></span></h3>
                            <div class="inside">
                                <br/>
                                    <div class="form-group">
                                        <div>
                                            <input type="text" name="path" value="" placeholder="<?php echo $text_categories; ?>" id="input-parent" class="form-control" />
                                            <input type="hidden" name="parent_id" value="" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                <p class="button-controls category-childs">
                                    <label for="category-childs">
                                        <input type="checkbox" id="category-childs"  checked name="js-show_subcategories-2"/> <?php echo $text_show_subcategories; ?>
                                    </label>
                                </p>
                                <p class="button-controls">
                                    <span class="add-to-menu">
                                        <input type="button" class="js-add-category-2" value="<?php echo $button_add; ?>" />
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="zbox">
                            <div class="arrow"><br /></div>
                            <h3 class="handle"><span><?php echo $text_categories; ?></span></h3>
                            <div class="inside">
                                <div class="inside-informations inside-fff ">
                                    <ul class="informations">
                                        <?php foreach($categories as $category) { ?>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="category" data-id="<?php echo $category['category_id']; ?>" data-title="<?php echo $category['name']; ?>" data-href="<?php echo $category['href']; ?>" /> <?php echo $category['category_path']; ?>
                                            </label>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <p class="button-controls category-childs">
                                    <label for="category-childs">
                                        <input type="checkbox" id="category-childs"  checked name="js-show_subcategories"/> <?php echo $text_show_subcategories; ?>
                                    </label>
                                </p>
                                <p class="button-controls">
                                    <span class="add-to-menu">
                                        <input type="button" class="js-add-category" value="<?php echo $button_add; ?>" />
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="zbox zbox-zindex">
                            <div class="arrow"><br /></div>
                            <h3 class="handle"><span><?php echo $text_product; ?></span></h3>
                            <div class="inside">
                                <p>
                                    <label for="custom-product">
                                        <span><?php echo $text_product_help; ?></span>
                                        <input id="custom-product" type="text"  value="" name="product"/>
                                    </label>
                                </p>
                            </div>
                        </div>

                        <div class="zbox">
                            <div class="arrow"><br /></div>
                            <h3 class="handle"><span><?php echo $text_manufacturer; ?></span></h3>
                            <div class="inside">
                                <div class="inside-informations inside-fff ">
                                    <ul class="informations manufacturer">
                                        <?php foreach($manufacturers as $manufacturer) { ?>
                                            <li>
                                                <label>
                                                    <input type="checkbox" name="manufacturer" data-id="<?php echo $manufacturer['manufacturer_id']; ?>" data-title="<?php echo $manufacturer['name']; ?>" data-href="<?php echo $manufacturer['href']; ?>"/> <?php echo $manufacturer['name']; ?>
                                                </label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <p class="button-controls">
                                    <span class="add-to-menu">
                                        <input type="button" class="js-add-manufacturer" value="<?php echo $button_add; ?>" />
                                    </span>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td valign="top">
                        <table class="table mytable">
                            <thead>
                                <tr>
                                    <th width="190"><a href="#" class="js-select-all"><?php echo $text_select_all; ?></a> / <a href="#" class="js-unselect-all"><?php echo $text_unselect_all; ?></a></th>
                                    <th width="150"><?php echo $text_action_with_selected; ?> </th>
                                    <th><a href="#" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger js-selected-remove"><i class="fa fa-minus-circle"></i></a></th>
                                </tr>
                            </thead>
                        </table>
                        <ol class="sortable"></ol>
                        <table class="table mytable">
                            <thead>
                                <tr>
                                    <th width="190"><a href="#" class="js-select-all"><?php echo $text_select_all; ?></a> / <a href="#" class="js-unselect-all"><?php echo $text_unselect_all; ?></a></th>
                                    <th width="150"><?php echo $text_action_with_selected; ?> </th>
                                    <th><a href="#" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger js-selected-remove"><i class="fa fa-minus-circle"></i></a></th>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
            </table>
              <input type="hidden" name="json" value="-1" />
              <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />

          </form>
        </div>
            </div>
        </div>
    </div>
  </div>
<script>
    window.token = '<?php echo $token; ?>';
    window.itemJson = '';
    window.languages = <?php echo json_encode($languages); ?> ;
    window.categories = <?php echo json_encode($categories); ?> ;
    window.informations = <?php echo json_encode($informations); ?> ;
    window.manufacturers = <?php echo json_encode($manufacturers); ?> ;
    window.no_image = '<?php echo $no_image; ?>';

    window.default_language = '';
    window.TEXT = {
        information: '<?php echo $text_information; ?>',
        category: '<?php echo $text_category; ?>',
        product: '<?php echo $text_product; ?>',
        custom_link: '<?php echo $text_custom_link; ?>',
        manufacturer: '<?php echo $text_manufacturer; ?>',
        html: 'html'
    };

    for(var i = 0; i < languages.length; i += 1) {
        if(window.languages[i].is_default == 1) {
            window.default_language = window.languages[i];
            break;
        }
    }
    <?php if($item['json']) { ?>
    window.itemJson = <?php echo $item['json']; ?>;
    <?php } ?>
</script>
<script id="item-template" type="text/x-jquery-tmpl">
<li id="${id}">
   <div class="zbox closed">
        <div class="arrow"><br /></div>
        <div class="menu-item-handle">
            <span class="item-cell item-selected"><input type="checkbox" name="selected[]" value="1" /> </span>
            <span class="item-title item-cell">${title}</span>
            <span class="item-controls">
                <span class="item-type">${typeTitle}</span>
             </span>
        </div>
        <div class="inside">
            <p>
                <label for="custom-menu-item-url-${id}">
                    <span>URL</span>
                    <input id="custom-menu-item-url-${id}" type="text"  value="${href}" name="href" />
                </label>
            </p>

            <p>
                <label for="custom-menu-item-name-${id}">
                    <span><?php echo $text_title; ?></span>
                    {{each(index, lang) languages}}
                        <span class="zbox-title">
                            <img src="language/${lang.code}/${lang.code}.png" title="${lang.name}">
                            <input id="custom-menu-item-name-${id}" name="title[]" type="text"  data-lang="${lang.language_id}" value="${titles[lang.language_id]}" />
                        </span>
                    {{/each}}
                </label>
            </p>
             <p>
                <label>
                    <span><?php echo $text_css_class; ?></span>
                    <input id="custom-menu-item-category-css-${id}" name="css_class" type="text" value="${data.css_class}" />
                </label>
            </p>
             <p>
                <span><?php echo $text_image; ?></span>
                <span class="image">
                     <span class="">
                         <a href="" id="thumb-image-${id}" data-toggle="image" class="img-thumbnail">
                         <img src="${data.thumb}" alt="" title="" data-placeholder="${no_image}" /></a>
                          <input type="hidden" name="image" value="${data.image}" id="input-image-${id}" />
                    </span>
                </span>
            </p>
            <p class="button-controls">
                <span class="add-to-menu">
                    <input type="button" class="js-btn-remove" value="<?php echo $button_remove; ?>" name="" />
                </span>
            </p>
        </div>
    </div>
</li>
</script>

<script id="item-html-template" type="text/x-jquery-tmpl">
<li id="${id}">
   <div class="zbox closed">
        <div class="arrow"><br /></div>
        <div class="menu-item-handle">
            <span class="item-cell item-selected"><input type="checkbox" name="selected[]" value="1" /> </span>
            <span class="item-title item-cell">${title}</span>
            <span class="item-controls">
                <span class="item-type">${typeTitle}</span>
             </span>
        </div>
        <div class="inside">
             <p>
                <label>

                    <ul class="nav nav-tabs">
                        {{each(index, lang) languages}}
                            <li {{if index == 0}} class="active" {{/if}}><a href="#tab-html-${id}-${lang.language_id}" data-toggle="tab"> <img src="language/${lang.code}/${lang.code}.png" title="${lang.name}"> ${lang.name}</a></li>
                        {{/each}}
                     </ul>

                    <div class="tab-content">
                        {{each(index, lang) languages}}
                              <div class="tab-pane {{if index == 0}}active {{/if}}" id="tab-html-${id}-${lang.language_id}">
                                    <textarea name="html" class="summernote2" data-lang="${lang.language_id}" >${data.html[lang.language_id]}</textarea>
                              </div>
                        {{/each}}
                    </div>

                </label>
            </p>
            <p class="button-controls">
                <span class="add-to-menu">
                    <input type="button" class="js-btn-remove" value="<?php echo $button_remove; ?>" name="" />
                </span>
            </p>
        </div>
    </div>
</li>
</script>

<script id="item-information-template" type="text/x-jquery-tmpl">
<li id="${id}">
   <div class="zbox closed">
        <div class="arrow"><br /></div>
        <div class="menu-item-handle">
            <span class="item-cell item-selected"><input type="checkbox" name="selected[]" value="1" /> </span>
            <span class="item-title item-cell">${title}</span>
            <span class="item-controls">
                <span class="item-type">${typeTitle}</span>
             </span>
        </div>
        <div class="inside">
            <p>
                <label for="custom-menu-item-name-${id}">
                    <span><?php echo $text_link_title; ?></span>
                     {{each(index, lang) languages}}
                        <span class="zbox-title">
                            <img src="language/${lang.code}/${lang.code}.png" title="${lang.name}">
                            <input id="custom-menu-item-name-${id}-${id}" name="title[]" type="text"  data-lang="${lang.language_id}" value="${titles[lang.language_id]}" />
                        </span>
                    {{/each}}
                </label>
            </p>

             <p class="href-original">
                <?php echo $text_link; ?>: {{if href}}<a href="${href}" target="_blank">{{/if}}${title}{{if href}}</a>{{/if}}
            </p>

             <p>
                <label>
                    <span><?php echo $text_css_class; ?></span>
                    <input id="custom-menu-item-category-css-${id}" name="css_class" type="text" value="${data.css_class}" />
                </label>
            </p>

            {{if type == 'category' || type == 'product' || type == 'manufacturer' }}
             <p>
                <label for="custom-menu-item-category-icon-${id}">&nbsp;&nbsp;
                    <span><?php echo $text_use_default_image; ?></span>&nbsp;&nbsp;
                    <input id="custom-menu-item-category-icon-${id}" name="use_default_image" type="checkbox" value="1" {{if data.use_default_image}} checked{{/if}} />
                </label>
            </p>
            {{/if}}
            <p>
                <span><?php echo $text_image; ?></span>
               <span class="image">
                                    <span class="">
                                        <a href="" id="thumb-image-${id}" data-toggle="image" class="img-thumbnail">
                                        <img src="${data.thumb}" alt="" title="" data-placeholder="${no_image}" /></a>
                                         <input type="hidden" name="image" value="${data.image}" id="input-image-${id}" />
                                   </span>
                               </span>
            </p>
            {{if type == 'category'}}
             <p>
                <label for="custom-menu-item-category-type-${id}">
                    <span><?php echo $text_show_subcategories; ?></span>&nbsp;&nbsp;
                    <input id="custom-menu-item-category-type-${id}" name="show_subcategories" type="checkbox" value="1" {{if data.show_subcategories}} checked{{/if}} />
                </label>
            </p>
              <p>
                <label for="custom-menu-item-category-type2-${id}">
                    <span><?php echo $text_show_all_subcategories; ?></span>&nbsp;&nbsp;
                    <input id="custom-menu-item-category-type2-${id}" name="show_all_subcategories" type="checkbox" value="1" {{if data.show_all_subcategories}} checked{{/if}} />
                </label>
            </p>
            {{/if}}

             <p>
                <label for="custom-menu-item-default-name-${id}">
                    <span><?php echo $text_show_default_title; ?></span>&nbsp;&nbsp;
                    <input id="custom-menu-item-default-name-${id}" name="show_default_title" type="checkbox" value="1" {{if data.show_default_title}} checked{{/if}} />
                </label>
            </p>

            <p class="button-controls">
                <span class="add-to-menu">
                    <input type="button" class="js-btn-remove" value="<?php echo $button_remove; ?>" name="" />
                </span>
            </p>
        </div>
    </div>
</li>
</script>

<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />



<style>


    .image {
        vertical-align: middle;
    }

    .img-thumbnail {
        width: 100px;
          height: 100px;
          vertical-align: middle;
    }

    .mytable th {
        vertical-align: middle !important;
    }

</style>

<?php echo $footer; ?>