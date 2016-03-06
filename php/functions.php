<?php

	require_once("constants.php");

	function query()
	{
		$sql = func_get_arg(0);

		$conn = new mysqli(SERVER, USERNAME, PASSWORD);

		if (!$conn)
		{
			die("Connection failed: " . mysqli_connect_error());
		}
		else
		{
			mysqli_select_db($conn, DATABASE);
			$result = mysqli_query($conn, $sql);
			if($result)
			{
				$array_2d = [];
				while($array = mysqli_fetch_array($result, MYSQL_ASSOC))
				{
					$array_2d[] = $array;
				}
				return $array_2d;
			}
			else
			{			
				print("Query Failed\n");
				return FALSE;
			}
		}
	}

	function render($template, $values = [])
	{
        if (file_exists("../templates/$template"))
        {
            extract($values);

            require("../templates/header.php");

            require("../templates/$template");

            require("../templates/footer.php");
        }
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

    
?>