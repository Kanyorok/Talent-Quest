<?php 

 
require_once "db.php";

if (isset($_POST["deleteTenant"])) {
  //collecting data
	$tenid = $_POST["tenID"];
  
  $sq_tenants="DELETE FROM `players` WHERE `players`.`playerID`='$tenid'";

  $mysqli ->autocommit(FALSE);
  $status =true;

      //EXECUTE QUERRIES
  $mysqli->query($sq_tenants)?null: $status=false;

if ($status) {
                  #successful, commit changes
                  $mysqli ->commit();

                        //head to index and report as an error state.
                   header('Location:../tenants.php?deleted');
              }
            else
              {
                      #rollback changes
                    $mysqli -> rollback();
                    //return back to page with error state
                    header('Location:../tenants.php?del_error');
              }

}
else {
	header('Location:../tenants.php?del_error');
}

	

?>