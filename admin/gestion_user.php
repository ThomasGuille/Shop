<?php 
require_once('../include/init.php');

if(!adminConnected()){
  header('location:' . URL . 'index.php');
}

$dataAdmin = $dbConnect->query("SELECT * FROM user WHERE roles = 'admin'");
$dataUser = $dbConnect->query("SELECT * FROM user WHERE roles = 'user'");

$arrayAdmin = $dataAdmin->fetchAll(PDO::FETCH_ASSOC);
$arrayUser = $dataUser->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['action']) && $_GET['action'] == 'delete'){
  $userDelete = $dbConnect->prepare("DELETE FROM user WHERE id_user = :id");
  $userDelete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $userDelete->execute();
  header('location: gestion_user.php');
}

require_once('include/header.php');
?>


<section class="section is-title-bar">
  <div class="level">
    <div class="level-left">
      <div class="level-item">
        <ul>
          <li>Admin</li>
          <li>Utilisateurs</li>
        </ul>
      </div>
    </div>
    <!-- <div class="level-right">
        <div class="level-item">
          <div class="buttons is-right">
            <a
              href="https://github.com/vikdiesel/admin-one-bulma-dashboard"
              target="_blank"
              class="button is-primary"
            >
              <span class="icon"
                ><i class="mdi mdi-github-circle"></i
              ></span>
              <span>GitHub</span>
            </a>
          </div>
        </div>
      </div> -->
  </div>
</section>
<section class="section is-main-section">
  <!-- <div class="notification is-primary">
    <button class="delete"></button>
    Lorem ipsum, dolor sit amet consectetur adipisicing elit.
  </div> -->
  <div class="card has-table">
    <header class="card-header">
      <p class="card-header-title">
        <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
        Clients
      </p>
      <a href="#" class="card-header-icon">
        <span class="icon"><i class="mdi mdi-reload"></i></span>
      </a>
    </header>
    <div class="card-content">
      <div class="b-table has-pagination">
        <div class="table-wrapper has-mobile-cards">
          <table
            class="table is-fullwidth is-striped is-hoverable is-fullwidth">
            <thead>
              <tr>
                <th class="is-checkbox-cell">
                  <label class="b-checkbox checkbox">
                    <input type="checkbox" value="false" />
                    <span class="check"></span>
                  </label>
                </th>
                
                <?php 
                  for($i = 0; $i < $dataUser->columnCount(); $i++): 
                    $dataColumn = $dataUser->getColumnMeta($i);
                    if($dataColumn['name'] != 'id_user' && $dataColumn['name'] != 'password' && $dataColumn['name'] != 'roles'):
                ?>
                <th><?php echo ucfirst($dataColumn['name']); ?></th>
                <?php 
                    endif; 
                  endfor; 
                ?>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($arrayUser as $user): ?>
                <tr>
                  <td class="is-checkbox-cell">
                    <label class="b-checkbox checkbox">
                      <input type="checkbox" value="false" />
                      <span class="check"></span>
                    </label>
                  </td>
                  
                  <?php foreach($user as $key => $value) : if($key != 'id_user' && $key != 'password' && $key != 'roles') : ?>
                    <td><?= $value; ?></td>
                  <?php endif; endforeach; ?>
                  <td>
                    <div class="field is-horizontal">
                      <div class="field-label">
                        <label class="label">User</label>
                      </div>
                      <div class="field-body">
                        <div class="field">
                          <label class="switch is-rounded"><input type="checkbox" value="false" />
                            <span class="check"></span>
                          </label>
                        </div>
                      </div>
                      <span class="control-label">Admin</span>
                    </div>
                  </td>
                  <td class="is-actions-cell">
                    <div class="buttons is-right">
                      <a
                        class="button is-small is-danger jb-modal"
                        data-target="sample-modal-<?= $user['id_user']; ?>"
                        type="button">
                        <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                      </a>
                    </div>
                  </td>
                </tr>
                <div id="sample-modal-<?= $user['id_user']; ?>" class="modal">
                  <div class="modal-background jb-modal-close"></div>
                  <div class="modal-card">
                    <div class="modal-card-head">
                      <p class="modal-card-title">Confirmez la suppression</p>
                      <button class="delete jb-modal-close" aria-label="close"></button>
                    </div>
                    <section class="modal-card-body">
                      <p>Voulez vous vraiment supprimer cet utilisateur ?</p>
                    </section>
                    <div class="modal-card-foot">
                      <button class="button jb-modal-close">Annuler</button>
                      <a href="?action=delete&id=<?= $user['id_user']; ?>" class="button is-danger jb-modal-close">Supprimer</a>
                    </div>
                  </div>
                    <button
                      class="modal-close is-large jb-modal-close"
                      aria-label="close"></button>
                  </div>
                </div>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- <div class="notification">
            <div class="level">
              <div class="level-left">
                <div class="level-item">
                  <div class="buttons has-addons">
                    <button type="button" class="button is-active">
                      1
                    </button>
                    <button type="button" class="button">2</button>
                    <button type="button" class="button">3</button>
                  </div>
                </div>
              </div>
              <div class="level-right">
                <div class="level-item">
                  <small>Page 1 of 3</small>
                </div>
              </div>
            </div>
          </div> -->
      </div>
    </div>
  </div>
</section>

<section class="section is-main-section">
  <!-- <div class="notification is-primary">
    <button class="delete"></button>
    Lorem ipsum, dolor sit amet consectetur adipisicing elit.
  </div> -->
  <div class="card has-table">
    <header class="card-header">
      <p class="card-header-title">
        <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
        Administrateurs
      </p>
      <a href="#" class="card-header-icon">
        <span class="icon"><i class="mdi mdi-reload"></i></span>
      </a>
    </header>
    <div class="card-content">
      <div class="b-table has-pagination">
        <div class="table-wrapper has-mobile-cards">
          <table
            class="table is-fullwidth is-striped is-hoverable is-fullwidth">
            <thead>
              <tr>
                <th class="is-checkbox-cell">
                  <label class="b-checkbox checkbox">
                    <input type="checkbox" value="false" />
                    <span class="check"></span>
                  </label>
                </th>
                
                <?php 
                  for($i = 0; $i < $dataAdmin->columnCount(); $i++): 
                    $dataColumn = $dataAdmin->getColumnMeta($i);
                    if($dataColumn['name'] != 'id_user' && $dataColumn['name'] != 'password' && $dataColumn['name'] != 'roles'):
                ?>
                <th><?php echo ucfirst($dataColumn['name']); ?></th>
                <?php 
                    endif; 
                  endfor; 
                ?>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($arrayAdmin as $admin): ?>
                <tr>
                  <td class="is-checkbox-cell">
                    <label class="b-checkbox checkbox">
                      <input type="checkbox" value="false" />
                      <span class="check"></span>
                    </label>
                  </td>
                  
                  <?php foreach($admin as $key => $value) : if($key != 'id_user' && $key != 'password' && $key != 'roles') : ?>
                    <td><?= $value; ?></td>
                  <?php endif; endforeach; ?>

                  <td>
                    <div class="field is-horizontal">
                      <div class="field-label">
                        <label class="label">User</label>
                      </div>
                      <div class="field-body">
                        <div class="field">
                          <label class="switch is-rounded"><input type="checkbox" value="true" checked />
                            <span class="check"></span>
                          </label>
                        </div>
                      </div>
                      <span class="control-label">Admin</span>
                    </div>
                  </td>

                  <td class="is-actions-cell">
                    <div class="buttons is-right">
                      <!-- <a
                        class="button is-small is-primary"
                        href="?action=update&id=<?= $admin['id_user']; ?>">
                        <span class="icon"><i class="mdi mdi-pencil"></i></span>
                      </a> -->
                      <a
                        class="button is-small is-danger jb-modal"
                        data-target="sample-modal-<?= $admin['id_user']; ?>"
                        type="button">
                        <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                      </a>
                    </div>
                  </td>
                </tr>
                <div id="sample-modal-<?= $admin['id_user']; ?>" class="modal">
                  <div class="modal-background jb-modal-close"></div>
                  <div class="modal-card">
                    <div class="modal-card-head">
                      <p class="modal-card-title">Confirmez la suppression</p>
                      <button class="delete jb-modal-close" aria-label="close"></button>
                    </div>
                    <section class="modal-card-body">
                      <p>Voulez vous vraiment supprimer cet administateur ?</p>
                    </section>
                    <div class="modal-card-foot">
                      <button class="button jb-modal-close">Annuler</button>
                      <a href="?action=delete&id=<?= $admin['id_user']; ?>" class="button is-danger jb-modal-close">Supprimer</a>
                    </div>
                  </div>
                    <button
                      class="modal-close is-large jb-modal-close"
                      aria-label="close"></button>
                  </div>
                </div>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- <div class="notification">
            <div class="level">
              <div class="level-left">
                <div class="level-item">
                  <div class="buttons has-addons">
                    <button type="button" class="button is-active">
                      1
                    </button>
                    <button type="button" class="button">2</button>
                    <button type="button" class="button">3</button>
                  </div>
                </div>
              </div>
              <div class="level-right">
                <div class="level-item">
                  <small>Page 1 of 3</small>
                </div>
              </div>
            </div>
          </div> -->
      </div>
    </div>
  </div>
</section>


<?php require_once('include/footer.php'); ?>