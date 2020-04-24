<?php 

if(isset($_POST['file'])){

			include 'config.php'; //phpmyadmin(database) connection 
	
			$id = $_POST['id'];
			$image= $_FILES["image"]["name"];
			
			//THIS code is use to delete previous image at particular id.

			$query = "SELECT * FROM `file` WHERE `id` = '$id' ";
                       
				$stmt=$conn->prepare($query);
				$stmt->execute();
				$result=$stmt->fetch();
				$old_image=$result['image']; //
				$path = "destFile/$old_image"; //
				unlink($path);
				//======== This code store image by changing name using time in number format. =======//
			$target_dir = "destFile/";
			$temp = explode(".", $_FILES["image"]["name"]);
			$img = round(microtime(true)) . '.' . end($temp);
			$target_file = $target_dir . $img;
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
			    $check = getimagesize($_FILES["image"]["tmp_name"]);
			    if($check !== false) {
			       	 $uploadOk = 1;
			    } else {
			         $uploadOk = 0;
			    }
			}
			// Check if file already exists
			if (file_exists($target_file)) {
			        $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
			        echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
			    } else {
			        echo "Sorry, there was an error uploading your file.";
			    }
			}

			$query = "UPDATE `file` SET `image`='$img' WHERE `id` ='$id'";
	
				 $stmt=$conn->prepare($query);
				 $res=$stmt->execute();

				if($res){
					header('location:index.php?q=Sucess');
						}
				else{
					header('location:index.php?q=error');
				}
		}
 ?>