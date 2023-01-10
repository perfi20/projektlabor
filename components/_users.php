<?php

if (isset($POST['submit'])) {

	$knev = $_POST['knev'];
	$email = $_POST['email'];
	$admin = $_POST['admin'];
	$id = $_POST['id'];

	$postfields = json_encode([
		'knev' => $knev,
		'email' => $email,
		'admin' => $admin,
		'id' => $id
	]);

	$userEdit = curl('user', 'PATCH', $postfields);
	return;
}

?>

<div class="table-responsive">
    <table class="table align-middle table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Felhasználónév</th>
                <th scope="col">Email</th>
                <th scope="col">Típus</th>
                <th scope="col">Műveletek</th>
            </tr>
        </thead>
        <!-- users -->
            <?php

                // api request for users
                $users = curl('users', 'GET', NULL, true);
                foreach($users as $user) :
            ?>
        <tbody class="table-group-divider">
            <tr>
                <th><?php echo $user['id']; ?></th>
                <td><?php echo $user['knev']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php if($user['admine'] == 1){
                    echo 'admin';
                    } else echo 'felhasználó'
                    ?>
                </td>
                <td>
                    <!-- edit modal button -->
                    <button type="button" class="btn btn-outline-dark"
                        data-bs-toggle="modal" data-bs-target="#editModal<?php echo $user['id']; ?>">
                            Szerkesztés
                    </button>

                    <!-- delete modal button-->
                    <button type="button" class="btn btn-outline-dark"
                        data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $user['id']; ?>">
                            Törlés
                    </button>
                    <!-- edit modal -->
                    <div class="modal fade" id="editModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                                        <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editModalLabel">Szerkesztés </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                
                                    <div class="mb-3">
                                        <label for="id" class="col-form-label">ID:</label>
                                        <input type="text" class="form-control" name="id" value="<?php echo $user['id']; ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="col-form-label">Név:</label>
                                        <input type="text" class="form-control" name="knev" value="<?php echo $user['knev']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="col-form-label">Email:</label>
                                        <input type="email" class="form-control" name="email"  value="<?php echo $user['email']; ?>"></input>
                                    </div>
                                    <div class="mb-3">
                                        <label for="access" class="col-form-label">Jogosultság:</label>
                                        <select class="form-control" name="admin">
                                            <option value="0" <?php if($user['admine'] == 0) echo "selected"; ?>>Felhasználó</option>
                                            <option value="1" <?php if($user['admine'] == 1) echo "selected"; ?>>Admin</option>
                                        </select>
                                    </div>
                                
                            </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                                        <button type="submit" name="submit" class="btn btn-primary" >Mentés</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- delete modal -->
                    <div class="modal fade" id="deleteModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="deleteModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" >Save changes</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>