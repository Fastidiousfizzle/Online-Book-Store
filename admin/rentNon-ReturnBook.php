<?php
  session_start();
  if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
    header("location: login.php");
    exit;
  }
  include_once 'php/databaseConnect.php';
  if(isset($_GET['rentId'])){
    $id = intval($_GET['rentId']);
    $status = 1;
    $stmt = $conn->prepare("UPDATE rents set Status = ? WHERE Rent_ID = ? ;");
    $stmt->bind_param("si", $status,$id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
  header("Location:rentNon-ReturnBook.php");
}

if(isset($_POST['refresh'])){
  header("Location:rentNon-ReturnBook.php");
}  
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <link rel="stylesheet" href="css/customer.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
 
</head>
  <body>
    <div class="wrapper">

      <?php include 'php/nav.php';?>

      <div id="content">

        <div>
          <button type="button" id="sidebarCollapse" class="btn btn-dark">
            <i class="fas fa-align-left"></i>
          </button>
          <a href="php/logout.php" class="btn btn-dark" style="float: right;color: white;">Log out</a>
        </div>

        <h1 id="heading">Non-return Rented Books</h1>


        <div class="search-container">
          <form class="form-inline md-form mr-auto mb-4" action="rentNon-ReturnBook.php" method="post">
            <button class="btn btn-outline-dark btn-rounded btn-sm my-0" style="margin-right:10px;" type="submit" name="refresh">
              <i class="fas fa-sync-alt"></i>
            </button>
            <input type="text" name="search_text" id="search_text" placeholder="Search by Rent Details" class="form-control" />
            <input type="text" id="datepicker" placeholder="Search by Date" class="form-control" />
          </form>
        </div>

        <div class="listTable rentTable">
          <table class="table table-responsive-xl table-bordered">
            <thead>
              <tr>
              <th scope="col">#</th>
                <th scope="col">Book Name</th>
                <th scope="col">Book Holder</th>
                <th scope="col">Rent For</th>
                <th scope="col">Rent Date</th>
                <th scope="col">Return Date</th>
                <th scope="col">Late</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody id="result"> </tbody>
          </table>
        </div>
  
      </div>
    </div>
  </body>
</html>
<script src="https://www.jqueryscript.net/demo/Dialog-Modal-Dialogify/dist/dialogify.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<script>
  $(document).ready(function(){

    load_data();
    function load_data(query){
      $.ajax({
        url:"php/fetch_allNonReturnRent.php",
        method:"POST",
        data:{query:query},
        success:function(data){
          $('#result').html(data);
        }
      });
    }
    $('#search_text').keyup(function(){
        var search = $(this).val();
        if(search != ''){
            load_data(search);
        }
        else{
            load_data();
        }
    });
    $('#datepicker').datepicker({
      dateFormat: 'yy-mm-dd',
      onClose: function(date, datepicker) {
          if (date != "") {
            var search = $(this).val();
            if(search != ''){
              load_data(search);
            }
            else{
              load_data();
            }
          }
      }
  });


  $(document).on('click', '.viewUser', function(){
    var id = $(this).attr('id');
    var options = {
     ajaxPrefix: '',
     ajaxData: {id:id},
     ajaxComplete:function(){
      this.buttons([{
       type: Dialogify.BUTTON_PRIMARY
      }]);
     }
    };
    new Dialogify('php/fetch_singleUserInfo.php', options)
     .showModal();
   });

   $(document).on('click', '.viewBook', function(){
    var id = $(this).attr('id');
    var options = {
     ajaxPrefix: '',
     ajaxData: {id:id},
     ajaxComplete:function(){
      this.buttons([{
       type: Dialogify.BUTTON_PRIMARY
      }]);
     }
    };
    new Dialogify('php/fetch_singleBookInfo.php', options)
     .showModal();
   });

  });
</script>


