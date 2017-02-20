<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-liqpay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-onpay" class="form-horizontal">

          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?php echo $entry_status; ?>
            </label>
            <div class="col-sm-10">
              <select name="onpay_status" class="form-control">
                <option value="1" <?php echo $onpay_status == 1 ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
                <option value="1" <?php echo $onpay_status == 0 ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="">
              <?php echo $entry_url_api; ?>
            </label>
            <div class="col-sm-10">
              <b style='position: relative; top: 20px;'>http://<?php echo $entry_your_site; ?>/?route=payment/onpay/status</b>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="">
              <?php echo $entry_login; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="onpay_login" value="<?php echo $onpay_login; ?>" class="form-control" />
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="">
              <?php echo $entry_security; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="onpay_security" value="<?php echo $onpay_security; ?>" class="form-control" />
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?php echo $entry_ln; ?>
            </label>
            <div class="col-sm-10">
              <select name="onpay_ln" class="form-control">
                <option value="ru" <?php echo $onpay_ln == 'ru' ? 'selected="selected"' : ''; ?>><?php echo $entry_ln_ru; ?></option>
                <option value="en" <?php echo $onpay_ln == 'en' ? 'selected="selected"' : ''; ?>><?php echo $entry_ln_en; ?></option>
                <option value="zh" <?php echo $onpay_ln == 'zh' ? 'selected="selected"' : ''; ?>><?php echo $entry_ln_zh; ?></option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?php echo $entry_convert; ?>
            </label>
            <div class="col-sm-10">
              <select name="onpay_convert" class="form-control">
                <option value="yes" <?php echo $onpay_convert == 'yes' ? 'selected="selected"' : ''; ?>><?php echo $entry_yes; ?></option>
                <option value="no" <?php echo $onpay_convert == 'no' ? 'selected="selected"' : ''; ?>><?php echo $entry_no; ?></option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?php echo $entry_price_final; ?>
            </label>
            <div class="col-sm-10">
              <select name="onpay_price_final" class="form-control">
                <option value="yes" <?php echo $onpay_price_final == 'yes' ? 'selected="selected"' : ''; ?>><?php echo $entry_yes; ?></option>
                <option value="no" <?php echo $onpay_price_final == 'no' ? 'selected="selected"' : ''; ?>><?php echo $entry_no; ?></option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?php echo $entry_form_id; ?>
            </label>
            <div class="col-sm-10">
              <select name="onpay_form_id" class="form-control">
                <option value="7" <?php echo $onpay_form_id == '7' ? 'selected="selected"' : ''; ?>>7</option>
                <option value="8" <?php echo $onpay_form_id == '8' ? 'selected="selected"' : ''; ?>>8</option>
                <option value="10" <?php echo $onpay_form_id == '10' ? 'selected="selected"' : ''; ?>>10</option>
                <option value="11" <?php echo $onpay_form_id == '11' ? 'selected="selected"' : ''; ?>>11</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="">
              <?php echo $entry_order_status; ?>
            </label>
            <div class="col-sm-10">
              <select name="onpay_order_status_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $onpay_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 