<!DOCTYPE html>

<!-- Pacific Northwest National Laboratory, Richland, WA -->
<!-- Copyright 2005, Battelle Memorial Institute         -->

<html>

<!-- This page establishes the DMS web site frame setup  -->
<!-- and invokes the requested page in the content frame -->

<head>
<title>Data Management System</title>

</head>


<!--================================== -->

<!-- define the main frames for the DMS interface -->

<frameset cols="17%,*">
    <frame src="<?= $side_menu_url ?>" name="menuside" >
    <frame src='<?= $page_url ?>' name="display_side"  >
</frameset>

</html>
