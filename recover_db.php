<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div id="alert_container" class="col-md-12">
    <?php 
        if ($msg != null){
          $keys = array_keys($msg);
          $key = $keys[0];
          if ($key == "danger"){
              $status = false;
          }
          else{
              $status = true;
          }
          echo display_msg($msg,$status);
        }
    ?>
  </div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Backup Database</span>
        </strong>
      </div>
      <div class="panel-body">
      <a href="full_recovery.php" class="btn btn-primary"> <span class="glyphicon glyphicon-download-alt"></span> &nbsp; Download Database Backup</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Recover Database</span>
        </strong>
      </div>
      <div class="panel-body">
      <form id="recovery_form">
        <label for="fileInput">Select a file:</label>
        <input type="file" id="fileInput" name="sqlFile" accept=".sql" style="display:none;" onchange="displayFileName()">
        <br/>
        <button class="btn btn-primary" type="button" onclick="document.getElementById('fileInput').click()">Choose File</button>
        <p id="fileName"></p>
      </form>
      <button onclick="send_file()" class="btn btn-primary"> <span class="glyphicon glyphicon-save"></span> &nbsp; Recover Database Backup</button>
      </div>
    </div>
  </div>
</div>

<script>
  function send_file(){
    var alert_container = document.getElementById('alert_container');
    var formData = new FormData();
    formData.append('sqlFile', $('#fileInput')[0].files[0]);
    $.ajax({
      url: 'upload_sql.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        alert_container.innerHTML= response;
        document.getElementById('recovery_form').reset();
      },
      error: function(error) {
        alert_container.innerHTML = response;
      }
    });
  }
  function displayFileName() {
    var input = document.getElementById('fileInput');
    var output = document.getElementById('fileName');
    output.textContent = input.files[0].name;
  }
</script>
<?php include_once('layouts/footer.php'); ?>
