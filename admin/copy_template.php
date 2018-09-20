<?php

	/*
	================================================
	== Template Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

$pagetitle = '';

	if (isset($_SESSION['username']))
	{

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage')//============================== manage page ===========
		{


		}
		elseif ($do == 'Add')//============================== manage page ===========
        {


		}
		elseif ($do == 'Insert')//============================== manage page ===========
        {


		}
		elseif ($do == 'Edit')//============================== manage page ===========
        {


		}
		elseif ($do == 'Update')//============================== manage page ===========
        {


		}
		elseif ($do == 'Delete')//============================== manage page ===========
        {


		}
		elseif ($do == 'Activate')
        {


		}

		include $tpl . 'footer.php';

	}
	else
	    {

		header('Location: index1.php');

		exit();
	    }

	ob_end_flush(); // Release The Output

?>