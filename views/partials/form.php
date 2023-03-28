<div class="card bg-dark text-white">
    <div class="card-header text-center">
        <h2 id="form-title">Add Contact</h2>
    </div>

    <div class="card-body">

        <form method="post" action="contacts.php" enctype="multipart/form-data">
            <?php
                // If an id value has been sent, show it as a hidden field. is for updating the contact
                if(isset($_GET['edit'])) {
                    $id = $_GET['edit'];
                    echo "<input type='hidden' name='id' value='$id'>";
                }
            ?>
            
            <div class="mb-3">
                <input type="text" class="form-control" name="name" value="<?php echo $editCont['name']; ?>" autocomplete="contact Name" placeholder=" Name" autofocus >
            </div>

            <div class="mb-3">
                <input type="text" class="form-control" name="lastname" value="<?php echo $editCont['lastname']; ?>" autocomplete="name" placeholder=" Last Name" >
            </div>
            
            <div div class="mb-3">
                <input type="" id="email" class="form-control" name="email" value="<?php echo $editCont['email']; ?>" autocomplete="email" placeholder=" Email" >
            </div>

            <div class="mb-3">
                <input type="date" class="form-control" name="birth_data" value="<?php echo $editCont['birth_data']; ?>"  title="Birth Date"  >
            </div>

            <div div class="mb-3">
                <input type="text" id="phone" class="form-control" name="phone" value="<?php echo $editCont['phone']; ?>" autocomplete="phone" placeholder=" Phone" >
            </div> 

            <div div class="mb-3">
                <input type="text" id="address" class="form-control" name="address" value="<?php echo $editCont['address']; ?>"autocomplete="Address" placeholder="Address" >
            </div> 

            <div div class="mb-3">
                <input type="number" id="zip" class="form-control" name="zip" value="<?php echo $editCont['zip']; ?>"autocomplete="zip" placeholder="ZIP" >
            </div> 

            <div div class="mb-3">
                <input type="text" id="city" class="form-control" name="city" value="<?php echo $editCont['city']; ?>" autocomplete="City" placeholder="City" >
            </div> 

            <div div class="mb-3">
                <input type="text" id="state" class="form-control" name="state" value="<?php echo $editCont['state']; ?>" autocomplete="State" placeholder="State" >
            </div> 
            
            <div div class="mb-3">
                <input type="text" id="country" class="form-control" name="country" value="<?php echo $editCont['country']; ?>" autocomplete="Country" placeholder="Country" >
            </div> 

            <div class="form-group mb-3">
                <input type="file" class="form-control" name="avatar" value="<?php echo $editCont['avatar']; ?>" >
            </div> 

            <!-- XXX To fix Los datos ton se renderizan en el textarea -->
            <div class="form-group mb-3">
                
                <!-- <input type="tex"  class="form-control" name="small_text" value="<?php echo $editCont['smalltext']; ?>" id="inputText" cols="30" rows="3" placeholder=" Small Note" > -->

                <textarea  class="form-control" name="smalltext" value="<?php echo $editCont['smalltext']; ?>" id="inputText" cols="30" rows="3" placeholder=" Small Note" ></textarea>
            </div>
        
            <div class="col-md-9 offset-md-4">
                <!-- <input  id="submit-btn" type="submit" class="btn btn-primary" value=""> -->
                <input  id="submit-btn" type="submit" class="btn btn-primary" value="<?php echo $val; ?>"  >
            </div>

        </form>

    </div> <div class="card-footer text-muted"></div>
</div>