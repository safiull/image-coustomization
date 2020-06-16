
<?php
	include 'library/config.php';
	include 'library/Database.php';

	$db = new Database();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Image file</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
	<div class="container mt-5">
		<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				// kon type er image gula upload korte parbe oitar 1ta permission dilam.
				$permited = array('jpg', 'jpeg', 'png', 'gif'); 
				// ekhane name ta user korsi image er nam ta jonno.
				$file_name = $_FILES['image']['name'];
				// ekhane size diye image er size ta show korbe.
				$file_size = $_FILES['image']['size'];
				// temp_name diye 1ta temporary loaction set kore dibe.
				$file_temp = $_FILES['image']['tmp_name'];

				// . er por alada korar jonno eta userkorsi.
				$div = explode('.', $file_name);
				// . er por jey extension ta ase oita dhorbe.
				$file_extension = strtolower(end($div));

				// all image er jonno 1ta kore unique name generate korbe.

				// 0 theke 10 cherecter porjonto name er 1ta name generete korbe.
				$unique_img_name = substr(md5(time()), 0, 10).'.'.$file_extension;

				// amra jey folder er vitor kaj korchi oi folder tar nam ekhane dite hobe.
				// ekhane ashole main directory teu image ta chole jabe er jonno amader move_uploaded_file eta o use korte hobe.

				//NOte:: ekhane folder er sathe extra 1ta unique name add hobe.
				$uploded_image = "image-upload".$unique_img_name;

				// if file is empty.
				if (empty($file_name)) {
					echo "<span class='alert alert-danger p-3 d-block'><strong>Error !</strong> Select an image</span>";
					// if file is too large from 1 kb.
				} elseif ($file_name > 1048567) {
					echo "<span class='alert alert-danger p-3 d-block'><strong>Error ! </strong>Image size showd be less than 1 MB</span>";
					// ekhane she file extension and permited file gula check korbe.
				}	elseif (in_array($file_extension, $permited) === false) {
					echo "<span class='alert alert-danger p-3 d-block'><strong>Error !</strong>You can upload only:- ".implode(", ", $permited)." file.</span>";
				}	else {

					move_uploaded_file($file_temp, $uploded_image);
					$query = "INSERT INTO image(image) VALUES('$uploded_image')";
					$inserted_rows = $db->insert($query);
					if ($inserted_rows) {
						echo "<span class='alert alert-success p-3 d-block'><strong>Success !</strong>Image Inserted Successfully</span>";
					} else {
						echo "<span class='alert alert-danger'><strong>Error !</strong>Image Inserted not Successfully</span>";
					}
				}
			}
		?>
		<form class="border p-4 mt-2 shadow" action="index.php" method="post" enctype="multipart/form-data">
			<table class="p-4">
				<tr>
					<td class="font-italic font-weight-bold">Select Image: </td>
					<td><input class="form-control" type="file" name="image"></td>
				</tr>
				<tr>
					<td></td>
					<td><input class="btn btn-outline-success mt-4" type="submit" value="Save image" name="submit"></td>
				</tr>
			</table>
		</form>
		
			
			<table class="table table-bordered table-dark mt-4">
				<tr>
					<th class="text-center">Serial</th>
					<th class="text-center">Image name</th>
					<th class="text-center">Action</th>
				</tr>


				<?php
					// for delete data
					if (isset($_GET['delete'])) {
						$id = $_GET['delete'];
					


						// for delete image from my pc.
						$pcImg = "SELECT * FROM image WHERE id='$id'";
						$getImg = $db->select($pcImg);
							if ($getImg) {
								while ($imgdata = $getImg->fetch_assoc()) {
								$deleteImg = $imgdata['image'];
								unlink($deleteImg);
							}
						}
						



						$query = "DELETE FROM image WHERE id='$id'";
						$delImage = $db->delete($query);
						if ($delImage) {
							echo "<span class='alert alert-success p-3 d-block mt-2'><strong>Success !</strong>Image deleted Successfully</span>";
						} else {
							echo "<span class='alert alert-danger'><strong>Error !</strong>Image not deleted </span>";
						}
					}
				?>
				<?php
					// show image.
					$query = "SELECT * FROM image";
					$getImage = $db->select($query);
					if ($getImage) {
						$i=0;
						while ($result = $getImage->fetch_assoc()) {
							$i++;
				?>

					
					<tr>
						<td class="text-center"><?php echo $i; ?></td>
						<td class="text-center"><img src="<?php echo $result['image']; ?>" height="40px;" width="50px;"></td>
						<td class="text-center"><a class="btn btn-outline-danger" href="?delete=<?php echo $result['id']; ?>">Delete</a></td>
					</tr>
				<?php
						}
					}
				?>
			</table>

	</div>
</body>
</html>