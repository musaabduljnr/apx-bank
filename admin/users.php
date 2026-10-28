<?php
include_once("./layout/header.php");



?>


<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="page-header">
            <div class="page-title">
                <h3>All Users</h3>
            </div>
        </div>

        <div class="row layout-top-spacing" id="cancel-row">

            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="table-responsive mb-4 mt-4">
                        <table id="default-ordering" class="table table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>NAME</th>
                                <th>ACCOUNT NO</th>
                                <th>ACCOUNT CURRENCY</th>
                                <th>ACCOUNT TYPE</th>
                                <th>ACCOUNT STATUS</th>
                                <th>ACCOUNT EMAIL</th>
                                <!--<th>ACCOUNT COUNTRY</th>-->
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $sql="SELECT * FROM users order by id ASC";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $sn=1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    $fullName = $row['firstname']." ". $row['lastname'];
                            ?>
                            <tr>
                                <td><?= $sn++ ?></td>
                                <td><?= $fullName ?></td>
                                <td><?= $row['acct_no'] ?></td>
                                <td><?= $row['acct_currency'] ?></td>
                                <td><?= $row['acct_type'] ?></td>
                                <td><?= $row['acct_status'] ?></td>
                                <td><?= $row['acct_email'] ?></td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <a href="./view_users.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="./impersonate.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm ml-1" title="Login as User & Diagnose Portal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Portal
                                    </a>
                                </td>
                                
                                
                        <!--<td class="text-center"><form class="section about" method="POST">-->
                        <!--                        <button class="btn btn-primary" name="status_delete">Delete</button></form>-->
                        <!--                    </td>-->
                                            
                                
                                
                            </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                            <!--<tfoot>-->
                            <!--<tr>-->
                            <!--    <th>S/N</th>-->
                            <!--    <th>NAME</th>-->
                            <!--    <th>ACCOUNT NO</th>-->
                            <!--    <th>ACCOUNT CURRENCY</th>-->
                            <!--    <th>ACCOUNT TYPE</th>-->
                            <!--    <th>ACCOUNT STATUS</th>-->
                            <!--    <th>ACCOUNT EMAIL</th>-->
                            <!--    <th>ACCOUNT COUNTRY</th>-->
                            <!--    <th class="invisible"></th>-->
                            <!--</tr>-->
                            <!--</tfoot>-->
                        </table>
                    </div>
                </div>
            </div>





<?php
include_once("./layout/footer.php");
?>
