<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Employee</a></li>
                                        <li class="breadcrumb-item active">View Employee</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Employee Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline">
                                            <div class="form-group mr-2" style="display:none">
                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                    <option value="">Show all</option>
                                                    <option value="Discharged">Discharged</option>
                                                    <option value="OutPatients">OutPatients</option>
                                                    <option value="InPatients">InPatients</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="10">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Phone</th>
                                                <th>Identity Number</th>
                                                <th>Role</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT 
                                                            qc.Quarantine_camp_Staff_ID,
                                                            qc.Name,
                                                            qc.Gender,
                                                            qc.Phone,
                                                            qc.Identity_Number,
                                                            CASE
                                                                WHEN n.nurse_id IS NOT NULL THEN 'Nurse'
                                                                WHEN s.staff_id IS NOT NULL THEN 'Staff'
                                                                WHEN v.volunteer_id IS NOT NULL THEN 'Volunteer'
                                                                WHEN d.doctor_id IS NOT NULL THEN 'Doctor'
                                                                ELSE 'Unknown Role'
                                                            END AS role
                                                        FROM 
                                                            quarantine_camp_staff qc
                                                        LEFT JOIN 
                                                            nurse n ON qc.quarantine_camp_staff_id = n.quarantine_camp_staff_id
                                                        LEFT JOIN 
                                                            staff s ON qc.quarantine_camp_staff_id = s.quarantine_camp_staff_id
                                                        LEFT JOIN 
                                                            volunteer v ON qc.quarantine_camp_staff_ID = v.quarantine_camp_staff_id
                                                        LEFT JOIN 
                                                            doctor d ON qc.quarantine_camp_staff_ID = d.quarantine_camp_staff_id;";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            while ($row = $res->fetch_object()) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $row->Quarantine_camp_Staff_ID; ?></td>
                                                    <td><?php echo $row->Name; ?></td>
                                                    <td><?php echo $row->Gender; ?></td>
                                                    <td><?php echo $row->Phone; ?></td>
                                                    <td><?php echo $row->Identity_Number; ?></td>
                                                    <td><?php echo $row->role; ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="active">
                                                <td colspan="8">
                                                    <div class="text-right">
                                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div> <!-- end .table-responsive-->
                            </div> <!-- end card-box -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Footable js -->
    <script src="assets/libs/footable/footable.all.min.js"></script>

    <!-- Init js -->
    <script src="assets/js/pages/foo-tables.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>