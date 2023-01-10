<div class="table-responsive">
    <table class="table align-middle table-hover">
        <thead>
            <tr>
                <th scope="col">Felhasználók száma</th>
                <th scope="col">Események száma</th>
                <th scope="col">Fórumbejegyzések száma</th>
                <th scope="col">Üzenetek száma</th>
            </tr>
        </thead>
        <?php
            $stats = curl('stats', 'GET', NULL, true);
        ?>
        <tbody class="table-group-divider">
            <tr>
                <th><?php echo $stats['users']; ?></th>
                <th><?php echo $stats['events']; ?></th>
                <th><?php echo $stats['posts']; ?></th>
                <th><?php echo $stats['msgs']; ?></th>
            </tr>
        </tbody>
    </table>
</div>