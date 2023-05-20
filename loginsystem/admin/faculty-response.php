<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
  header('location:logout.php');
  } else{
    
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin Dashboard | JSSATEN-SIM</title>
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous">
    </script>
</head>

<body class="sb-nav-fixed">
    <?php include_once('includes/navbar.php');?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php');?>
        <div id="layoutSidenav_content">
            <main>

                <div class="p-4 container">
                    <form method="post" style="border: 0;">
                        <table class="table table-hover">
                            <tr>

                                <th> Select Faculty</th>
                                <td>
                                    <select name="faculty" class="form-control">
                                        <option selected>Choose...</option>
                                        <?php
                                    // $id1 = $_SESSION['id'];
                                    global $facultyid;
	$sql=mysqli_query($con,"select * from faculty");
	while($r=mysqli_fetch_array($sql))
	{
        
    echo "<option value='".$r['fname']." ".$r['lname']."'>".$r['fname']." ".$r['lname']."</option>";
	}
		 ?>
                                    </select>
                                </td>
                                <td><input name="sub" type="submit" value="Submit" class="btn btn-success" /></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <div class="container-fluid px-4" id="tblCustomers" style="position:relative; bottom:2.5rem">
                    <h1 class="mt-4">Feeback Report</h1>
                    <hr>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Feedback Report</li>
                    </ol>

                    <?php
if (isset($_POST['sub'])) 
{
    // echo $facultyid;
     
    $facultyname =$_POST['faculty'];
$delimiter = ' ';
$words = explode($delimiter, $facultyname);
 global $fname1;
 global $lname1;
$fname1 = $words[0]." ".$words[1];
$lname1 = $words[2];

    echo "Feedback Report Of 
    <span style='font-size:22px;'>"
    .$facultyname.
    "</span>";
    
}
?>

<div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Feedback Responses
                        </div>
                        <div class="card-body " id="tbl_exporttable_to_xls">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <!-- <th>Sno.</th> -->
                                        <!-- <th>First Name</th> -->
                                        <!-- <th> Last Name</th> -->
                                        <!-- <th> Email Id</th> -->
                                        <!-- <th>Session</th>
                                        <th>Semester Type</th> -->
                                        <th>Year</th>
                                        <th>Semester</th>
                                        <th>Section</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Section Wise<br>(Out Of 5)</th>
                                        <th>CO-1</th>
                                        <th>CO-2</th>
                                        <th>CO-3</th>
                                        <th>CO-4</th>
                                        <th>CO-5</th>
                                        <th>CO-6</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <!-- <th>Sno.</th> -->
                                        <!-- <th>First Name</th>
                                        <th> Last Name</th>
                                        <th> Email Id</th> -->
                                        <!-- <th>Session</th>
                                        <th>Semester Type</th> -->
                                        <th>Year</th>
                                        <th>Semester</th>
                                        <th>Section</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Section Wise<br>(Out Of 5)</th>
                                        <th>CO-1</th>
                                        <th>CO-2</th>
                                        <th>CO-3</th>
                                        <th>CO-4</th>
                                        <th>CO-5</th>
                                        <th>CO-6</th>
                                    </tr>
                                </tfoot>
                                <tbody class="tbodyDisplay">
                                    <?php 
                                     if((!isset($fname1))&& (!isset($lname1)))
                                      {    
                                           echo "<script>document.getElementsByClassName('card-body')[0].style.display='none';</script>";
                                           return  ;
                                      }
                                    $sql2 = "SELECT id from faculty where fname = '$fname1' and lname = '$lname1'";
                                    $result2 = mysqli_query($con, $sql2);
                                    $facultyid1 = mysqli_fetch_assoc($result2);
                                    $facultyid2 = $facultyid1['id'];
                                    // echo $facultyid2;
                                    // $id1=$_SESSION['facultyid'];
                                    // $facultyname = $_POST['faculty'];
                                    $sql1="SELECT subjectalloted.year, subjectalloted.semester, subjectalloted.section, subjectalloted.suballoted, subjects.subject, round((avg(sb1)+avg(sb2)+avg(sb3)+avg(sb4)+avg(sb5))/5,2) as sectionwise, round(avg(co1),2) as co1,round(avg(co2),2) as co2,round(avg(co3),2) as co3,round(avg(co4),2) as co4,round(avg(co5),2) as co5,round(avg(co6),2) as co6, users.section FROM faculty INNER JOIN subjectalloted ON subjectalloted.facultyemail=faculty.email INNER JOIN subjects ON subjects.subjectcode=subjectalloted.suballoted INNER JOIN respone ON respone.facultyemail=faculty.email INNER JOIN users ON respone.usersemail=users.email WHERE faculty.id=$facultyid2 AND users.section=subjectalloted.section;";
                                    // $ret=mysqli_query($con,"select * from faculty where id=$id1");
                                    $ret=mysqli_query($con,$sql1);
                              $cnt=1;
                              while($row=mysqli_fetch_array($ret))
                              {?>
                                    <tr>
                                        <!-- <td><?php echo $cnt;?></td> -->
                                        <!-- <td><?php echo $row['fname'];?></td>
                                        <td><?php echo $row['lname'];?></td>
                                        <td><?php echo $row['email'];?></td> -->
                                        <!-- <td><?php echo $row['session_'];?></td>
                                        <td><?php echo $row['sem_type'];?></td> -->
                                        <td><?php echo $row['year'];?></td>
                                        <td><?php echo $row['semester'];?></td>
                                        <td><?php echo $row['section'];?></td>
                                        <td><?php echo $row['suballoted'];?></td>
                                        <td><?php echo $row['subject'];?></td>
                                        <td><?php echo $row['sectionwise'];?></td>
                                        <td><?php echo $row['co1'];?></td>
                                        <td><?php echo $row['co2'];?></td>
                                        <td><?php echo $row['co3'];?></td>
                                        <td><?php echo $row['co4'];?></td>
                                        <td><?php echo $row['co5'];?></td>
                                        <td><?php echo $row['co6'];?></td>
                                        <!-- <td>

                                            <a href="user-profile.php?uid=<?php echo $row['id'];?>">
                                                <i class="fas fa-edit"></i></a>
                                            <a href="manage-users.php?id=<?php echo $row['id'];?>"
                                                onClick="return confirm('Do you really want to delete');"><i
                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                        </td> -->
                                    </tr>
                                    
                                <?php  }
                                ?>
                                    

                                </tbody>
                               

                            </table>
                            <button id="btnExport" onclick="Export()" type="button" class="btn btn-outline-primary">Download</button>
                            <script type="text/javascript"
                                src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
                            <script type="text/javascript"
                                src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js">
                            </script>
                            <script type="text/javascript">
                            function Export() {
                                html2canvas(document.getElementById('tblCustomers'), {
                                    onrendered: function(canvas) {
                                        var data = canvas.toDataURL();
                                        var docDefinition = {
                                            content: [{
                                                image: data,
                                                width: 500
                                            }]
                                        };
                                        pdfMake.createPdf(docDefinition).download("Feedback Report.pdf");
                                    }
                                });
                            }
                            </script>
                            <!-- <script>

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Feedback Respones.' + (type || 'xlsx')));
        }

    </script> -->
                        </div>
                    </div>


                </div>


            </main>
            <?php include_once('../includes/footer.php'); ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>

</html>
<?php } ?>