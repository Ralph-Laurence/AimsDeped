<?php
if(empty($_POST["firstname"])){
$firstname_error = "Please Enter your Firstname";
}
if(empty($_POST["middlename"])){
  $middlename_error = "Please Enter your Middlename";
  }
if(empty($_POST["lastname"])){
  $lastname_error = "Please Enter your Lastname";
}
if(empty($_POST["emailadd"])){
  $email_error = "Please Enter your Email";
}
if(empty($_POST["ph_num"])){
  $_error = "Please Enter your Phone Number";
}
if(empty($_POST["user_name"])){
  $username_error = "Please Enter your username";
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>SDO PROFILE MANAGEMENT</title>
    <!-- MDB icon -->
    <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon" />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <!-- Google Fonts Roboto -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
    />
    <!-- MDB -->
    <link rel="stylesheet" href="css/mdb.min.css" />
  </head>
  <body>
    <!-- Start your project here-->
    <div class="container my-5">
      <div class="card">
        <form method="post" action="" autocomplete="off">
          <!-- Card header -->
          <div class="card-header py-4 px-5 bg-light border-0">
            <h4 class="mb-0 fw-bold">Profile Management</h4>
          </div>
    
          <!-- Card body -->
          <div class="card-body px-5">
            <!-- Account section -->
            <div class="row gx-xl-5">
              <div class="col-md-4">
                <h5>Personal Information</h5>
                </div>
    
              <div class="col-md-8">
                <div class="mb-3">
                  <label for="exampleInput1" class="form-label"
                         >First name</label
                    >
                  <input type="text" class="form-control" id="exampleInput1" style="max-width: 500px;"/>
                <span><?php echo $firstname_error; ?></span>
                </div>
                <div class="mb-3">
                  <label for="exampleInput1" class="form-label"
                         >Middle name</label
                    >
                  <input type="text" class="form-control" id="exampleInput1" style="max-width: 500px;"/>
                  <span><?php echo $middlename_error; ?></span>
                </div>
                <div class="mb-3">
                  <label for="exampleInput1" class="form-label"
                         >Last name</label
                    >
                  <input type="text" class="form-control" id="exampleInput1" style="max-width: 500px;"/>
                  <span><?php echo $lastname_error; ?></span>
                </div>
                <div class="mb-3">
                  <label for="exampleInput2" class="form-label"
                         >Email address</label
                    >
                  <input type="email" class="form-control" id="exampleInput2" style="max-width: 500px;"/>
                  <span><?php echo $email_error; ?></span>
                </div>
                <div class="mb-3">
                  <label for="exampleInput3" class="form-label"
                         >Phone number</label
                    >
                  <input type="tel" maxlength="11" class="form-control" id="exampleInput3" style="max-width: 300px;"/>
                  <span><?php echo $_error; ?></span>
                </div>
              </div>
            </div>
    
            <hr class="my-5" />
    
            <!-- Billing section -->
            <div class="row gx-xl-5">
              <div class="col-md-4">
                <h5>Account</h5>
               </div>
    
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="exampleInput6" class="form-label">Username</label>
                      <input
                             type="text"
                             class="form-control"
                             id="exampleInput6"
                             />
                             <span><?php echo $username_error; ?></span>
                            </div>
                  
                  </div>
    
               
                </div>
              </div>
            </div>
    
            <hr class="my-5" />
    
            <!-- Business address section -->
            <!-- Password section -->
            <div class="row gx-xl-5">
              <div class="col-md-4">
                <h5>Change password</h5>
              </div>
    
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="exampleInput11" class="form-label"
                             >Old password</label
                        >
                      <input
                             type="password"
                             class="form-control"
                             id="exampleInput11"
                             />
                    </div>
                  </div>
    
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="exampleInput12" class="form-label"
                             >New password</label
                        >
                      <input
                             type="password"
                             class="form-control"
                             id="exampleInput12"
                             />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    
          <!-- Card footer -->
          <div class="card-footer text-end py-4 px-5 bg-light border-0">
            <button class="btn btn-link btn-rounded" data-ripple-color="primary">Cancel</button>
            <button type="submit" class="btn btn-primary btn-rounded">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- End your project here-->

    <!-- MDB -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!-- Custom scripts -->
    <script type="text/javascript"></script>
  </body>
</html>
