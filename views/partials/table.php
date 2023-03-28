 <!-- Table to render the data -->
 <div class="col-9">
            <div class="table-responsive">
                <table class="table table-bordered table-dark table-striped-columns border-success">
                    <thead>
                        <tr>
                            <th scope="col">Avatar</th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Lastname</th>
                            <th scope="col">Email</th>
                            <th scope="col">Birth Date</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th scope="col">Zip</th>
                            <th scope="col">City</th>
                            <th scope="col">State</th>
                            <th scope="col">Country</th>
                            <th scope="col">Note</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Padando la informacion de la DB a la tabla atravez de un ciclo -->
                        <?php foreach ($contacts as $contact) { ?>
                            <tr class="">
                                <!-- XXX TO FIX-->
                                <td> 
                                    <img width="100" src="gallery/uploads/<?php echo $contact['avatar']; ?> " alt="<?php echo $contact['image']; ?> " title=" <?php echo $contact['name']; ?>">
                                </td>
                                    <td> <?php echo $contact['id']; ?> </td>
                                    <td> <?php echo $contact['name']; ?> </td>
                                    <td> <?php echo $contact['lastname']; ?> </td>
                                    <td> <?php echo $contact['email']; ?> </td>
                                    <td> <?php echo $contact['birth_data']; ?> </td>
                                    <td> <?php echo $contact['phone']; ?> </td>
                                    <td> <?php echo $contact['address']; ?> </td>
                                    <td> <?php echo $contact['zip']; ?> </td>
                                    <td> <?php echo $contact['city']; ?> </td>
                                    <td> <?php echo $contact['state']; ?> </td>
                                    <td> <?php echo $contact['country']; ?> </td>
                                    <td> <?php echo $contact['smalltext']; ?> </td>
                                <td> 
                                    <!-- <a class="btn btn-info opt-btn" href="?edit=<?php echo $contact['id']; ?>" role="button">Edit</a>     -->

                                    <!-- <a name="indice" id="edit" class="btn btn-info opt-btn" href="?edit=<?php 
                                        echo $contact['id']; 
                                    ?>" role="button" type="button">
                                        <?php echo htmlspecialchars("Edit"); ?>
                                    </a> -->

                                    <a name=edit"" id="edit" class="btn btn-info opt-btn" href="?edit=<?php 
                                        echo $contact['id']; 
                                    ?>" role="button">Edit
                                    </a>  

                                  
                                    <a name="delete" id="delete" class="btn btn-danger opt-btn" href="?delete=<?php 
                                        echo $contact['id']; 
                                    ?>" role="button">Delete
                                    </a>    


                                </td>
                            </tr>

                        <?php }?>
                    
                    </tbody>
                </table>
            </div>
        </div>