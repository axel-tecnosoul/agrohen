<?php
	if (isset($_FILES['file'])) {
		echo $_FILES['file']['name'];
	}else{
		echo "no hay imágenes";
	}
?>