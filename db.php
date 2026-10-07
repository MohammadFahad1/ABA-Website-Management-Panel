<?php
/*এই ফাইলটিকে  মোঃ  ফাহাদ মুন্সী(সহকারী প্রোগ্রামার - আহমেদ বাওয়ানী একাডেমী স্কুল এন্ড কলেজ) দ্বারা রক্ষনাবেক্ষন করা হচ্ছে, অনুগ্রহ পূর্বক অবাঞ্ছিত কোন ছেরছার না করার জন্য অনুরোধ করছি*/

	class DB{
		private $dbhost;
		private $dbname;
		private $dbuser;
		private $dbpass;

		public $pdo;

		public function __construct(){
			$this->dbhost = getenv('DB_HOST') !== false ? getenv('DB_HOST') : "localhost";
			$this->dbname = getenv('DB_NAME') !== false ? getenv('DB_NAME') : "ahmedbawanyacade_sacc";
			$this->dbuser = getenv('DB_USER') !== false ? getenv('DB_USER') : "ahmedbaw_sacc";
			$this->dbpass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "_xAzHN_x3wKJ";

			if(!isset($pdo)){
				try{
					$conn = new PDO("mysql:host=".$this->dbhost.";dbname=".$this->dbname. ";charset=utf8", $this->dbuser, $this->dbpass);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$this->pdo = $conn;
				}catch(PDOException $e){
					die("Failed to connect with the database: " . $e->getMessage());
				}
			}
		}
		
		public function login($password){
			$pass = getenv('ADMIN_PASS') !== false ? getenv('ADMIN_PASS') : "Bangla@123";
			if($password === $pass){
				if (session_status() === PHP_SESSION_NONE) {
					session_start();
				}
				$_SESSION['login'] = true;
				header("Location: upload-notice.php");
				exit;
			} else {
				return "<p class=\"text-danger\">Incorrect Password!</p>";
			}
		}

		public function upload_notice($data, $file){
// 			echo "<pre>";
// 			print_r($file);
// 			echo "</pre>";
			date_default_timezone_set("Asia/Dhaka");
			$title = $data['title'];
			$date = $data['date'];
			$description = $data['description'];
			$files = $file['file'];

			$cfiles = count($file['file']['name']);

			if(!empty($title) && !empty($date)){
				try{
					$this->pdo->beginTransaction();

					$query = $this->pdo->prepare("INSERT INTO `website_notices`(`date`, `title`, `description`, `entry_by`, `entry_time`) VALUES (:date, :title, :description, :entry_by, :entry_time)")->execute([':date' => $date, ':title' => $title, ':description' => $description, 'entry_by' => 1, 'entry_time' => date("Y-m-d h:i:s")]);

					$last_id = $this->pdo->lastInsertId();

					for($i = 0; $i < $cfiles; $i++){
						$name = $files['name'][$i];
						$type = $files['type'][$i];
						$tmp_name = $files['tmp_name'][$i];
						$error = $files['error'][$i];
						$size = $files['size'][$i];

						$fileExt = explode('.', $name);
						$fileActualExt = strtolower(end($fileExt));

						$allowed = array('jpg', 'jpeg', 'pdf', 'png', 'doc', 'docx', 'xlsx', 'xls', 'ppt', 'pptx', 'zip', 'rar');

						if($error === 0){
							if(in_array($fileActualExt, $allowed)){
								if($size < 52278800){
									$fileNewName = md5(uniqid('', true) . $tmp_name) . "NId-".$last_id.".".$fileActualExt;
									$fileDestination = "images/notices/". $fileNewName;
									if(move_uploaded_file($tmp_name, $fileDestination)){
										$query1 = $this->pdo->prepare("INSERT INTO `notice_files`( `notice_id`, `files`) VALUES (:notice_id, :files)")->execute([':notice_id' => $last_id, ':files' => $fileDestination]);
									}
								}else{
									$msg = "<p class=\"text-danger\">Your file is too big, please upload a file smaller than 50 MB</p>";
								}
							}else{
								$msg = "<p class=\"text-danger\">Error with the file, you can only upload 'jpg', 'jpeg', 'pdf', 'png', 'doc', 'docx', 'xlsx', 'xls', 'ppt', 'pptx', 'zip', 'rar'!</p>";
							}
						}else{
							$msg = "<p class=\"text-danger\">Sorry, something went wrong while uploading your file!</p>";
						}

					}

					if(isset($query) && isset($query1) && $query === true && $query1 === true){
						$this->pdo->commit();
						$msg = '<p class="text-success">Notice uploaded successfully!</p>';
					}
				}catch(PDOException $e){
					$this->pdo->rollBack();
					return "Failed to upload notice: " . $e->getMessage();
				}
			}else{
				$msg = '<p class="text-danger">Fields must not be empty!</p>';
			}
			return $msg;
		}

		public function notices($limit, $status){
		    if($status == true){
				if(!empty($limit)){
					$query = $this->pdo->prepare("SELECT * FROM `website_notices` WHERE `status` = 1 ORDER BY `date` DESC LIMIT {$limit} OFFSET 0");
					$query->execute();
					if($query->rowCount() > 0){
						$result = $query->fetchAll(PDO::FETCH_ASSOC);
						return $result;
					}else{
						return [];
					}
				}else{
					$query = $this->pdo->prepare("SELECT * FROM `website_notices` WHERE `status` = 1  ORDER BY `date` DESC");
					$query->execute();
					if($query->rowCount() > 0){
						$result = $query->fetchAll(PDO::FETCH_ASSOC);
						return $result;
					}else{
						return [];
					}
				}
			}else{
    			if(!empty($limit)){
    				$query = $this->pdo->prepare("SELECT * FROM `website_notices` ORDER BY `date` DESC, `id` DESC LIMIT :limit");
    				$query->execute([':limit' => $limit]);
    				if($query->rowCount() > 0){
    					$result = $query->fetchAll(PDO::FETCH_ASSOC);
    					return $result;
    				}else{
    					return [];
    				}
    			}else{
    				$query = $this->pdo->prepare("SELECT * FROM `website_notices` ORDER BY `date` DESC, `id` DESC");
    				$query->execute();
    				if($query->rowCount() > 0){
    					$result = $query->fetchAll(PDO::FETCH_ASSOC);
    					return $result;
    				}else{
    					return [];
    				}
    			}
			}
		}

		public function file($notice_id){
			$query = $this->pdo->prepare("SELECT * FROM `notice_files` WHERE `notice_id` = :notice_id");
			$query->execute([':notice_id' => $notice_id]);
			if($query->rowCount() > 0){
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}else{
				return [];
			}
		}

		public function delete_notice($delid){
		    if($this->noticeById($delid)[0]['status'] == 1){
				return "<p class=\"text-danger\">You have to Inactivate the Notice first in order to delete it!</p>";
			}else{
    			try{
    				$this->pdo->beginTransaction();
    
    				$files = $this->file($delid);
    				foreach($files as $file){
    					unlink($file['files']);
    				}
    
    				$query = $this->pdo->prepare("DELETE FROM `notice_files` WHERE `notice_id` = :nid")->execute([':nid' => $delid]);
    				$query1 = $this->pdo->prepare("DELETE FROM `website_notices` WHERE `id` = :id")->execute(['id' => $delid]);
    
    				if($query === true && $query1 === true){
    					$this->pdo->commit();
    					return '<p class="text-success">Notice deleted successfully!</p>';
    				}
    			}catch(PDOException $e){
    				$this->pdo->rollBack();
    				die("Query Failed: " . $e->getMessage());
    			}
			}
		}
		
		public function front_carousel(){
			$query = $this->pdo->prepare("SELECT * FROM `website_front_carousel` WHERE `id` = 1");
			$query->execute();
			if($query->rowCount() > 0){
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}else{
				return [];
			}
		}

		public function slide_change($data, $files){
		    	$slide = (int)$data['slide'];
				$files = $files['file'];
				
				$title = $data['title'];
				$desc = $data['description'];

				$name = $files['name'];
				$type = $files['type'];
				$tmp_name = $files['tmp_name'];
				$error = $files['error'];
				$size = $files['size'];

				$fileExt = explode('.', $name);
				$fileActualExt = strtolower(end($fileExt));

				$allowed = array('jpg', 'jpeg');

				if($error === 0){
					if(in_array($fileActualExt, $allowed)){
						if($size < 5242880){
							$fileNewName = "0".$slide.".jpg";
							$fileDestination = "images/front-carousel-slider/". $fileNewName;
							if(move_uploaded_file($tmp_name, $fileDestination)){
								$query1 = $this->pdo->prepare("UPDATE `website_front_carousel` SET `slide_{$slide}`=:files, `slide{$slide}_title`=:title, `slide{$slide}_desc`=:descrip WHERE `id` = 1")->execute([':files' => $fileDestination, ':title' => $title, ':descrip' => $desc]);
								$msg = '<p class="text-success">Slide Changed Successfully!</p>';
							}
						}else{
							$msg = "<p class=\"text-danger\">Your file is too big, please upload a file smaller than 5 MB</p>";
						}
					}else{
						$msg = "<p class=\"text-danger\">Error with the file, you can only upload 'jpg', 'jpeg'!</p>";
					}
				}else{
					$query1 = $this->pdo->prepare("UPDATE `website_front_carousel` SET `slide{$slide}_title`=:title, `slide{$slide}_desc`=:descrip WHERE `id` = 1")->execute([':title' => $title, ':descrip' => $desc]);
					if($query1){
						$msg = '<p class="text-success">Slide Title & Description Successfully!</p>';
					}else{
						$msg = "<p class=\"text-danger\">Sorry, something went wrong while uploading your file!</p>";
					}
				}
			return $msg;

		} //Method Ends
		
		// Show Notice by its id
		public function noticeById($nid){
			$query = $this->pdo->prepare("SELECT * FROM `website_notices` WHERE `id` = :id");
			$query->execute([':id' => $nid]);
			if($query->rowCount() > 0){
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}else{
				return [];
			}
		}

		// Change Notice Status
		public function change_status($nid){
			if($this->noticeById($nid)[0]['status'] == 1){
				$query = $this->pdo->prepare("UPDATE `website_notices` SET `status`='0' WHERE `id` = {$nid}")->execute();
				if($query){
					header("Location: notices.php");
				}
			}else{
				$query = $this->pdo->prepare("UPDATE `website_notices` SET `status`='1' WHERE `id` = {$nid}")->execute();
				if($query){
					header("Location: notices.php");
				}
			}
		}
		
		// Add Teacher/Staff
		public function add_teacher_staff($data, $files){
			$teacherName = isset($data['name']) ? $data['name'] : '';
			$sl = isset($data['sl']) ? $data['sl'] : 0;
			$designation = isset($data['designation']) ? $data['designation'] : '';
			$shift = isset($data['shift']) ? $data['shift'] : '';
			$section = isset($data['section']) ? $data['section'] : '';
			$mobile = isset($data['mobile']) ? $data['mobile'] : '';

			$msg = "";
			if(!empty($teacherName)){
				$query1 = $this->pdo->prepare("INSERT INTO `teacher_staff_info`(`name`, `shift`, `section`, `designation`, `sl`, `mobile`) VALUES (:name,:shift,:section,:designation,:sl,:mobile)")->execute([':name'=>$teacherName, ':shift'=> $shift, ':section'=> $section, ':designation'=> $designation, ':sl'=> $sl, ':mobile'=> $mobile]);
				if($query1){
					$msg = '<p class="text-success">Teacher/Staff Added Successfully!</p>';
				}else{
					$msg = '<p class="text-danger">Failed to add Teacher/Staff record.</p>';
				}
			}else{
				$msg = '<p class="text-danger">Name field cannot be empty!</p>';
			}
			return $msg;
		} //Method Ends

		// Get all Teacher/Staff with optional filtering by shift and section
		public function get_all_teachers_staff($shift = '', $section = ''){
			$sql = "SELECT * FROM `teacher_staff_info` WHERE 1=1";
			$params = [];

			if(!empty($shift)){
				$sql .= " AND `shift` = :shift";
				$params[':shift'] = $shift;
			}
			if(!empty($section)){
				$sql .= " AND `section` = :section";
				$params[':section'] = $section;
			}

			$sql .= " ORDER BY `sl` ASC, `id` DESC";

			$query = $this->pdo->prepare($sql);
			$query->execute($params);
			if($query->rowCount() > 0){
				return $query->fetchAll(PDO::FETCH_ASSOC);
			}
			return [];
		}

		// Get single Teacher/Staff record by ID
		public function get_teacher_staff_by_id($id){
			$query = $this->pdo->prepare("SELECT * FROM `teacher_staff_info` WHERE `id` = :id");
			$query->execute([':id' => $id]);
			if($query->rowCount() > 0){
				return $query->fetch(PDO::FETCH_ASSOC);
			}
			return false;
		}

		// Update Teacher/Staff record
		public function update_teacher_staff($id, $data, $files){
			$teacherName = isset($data['name']) ? $data['name'] : '';
			$sl = isset($data['sl']) ? $data['sl'] : 0;
			$designation = isset($data['designation']) ? $data['designation'] : '';
			$shift = isset($data['shift']) ? $data['shift'] : '';
			$section = isset($data['section']) ? $data['section'] : '';
			$mobile = isset($data['mobile']) ? $data['mobile'] : '';

			if(!empty($teacherName)){
				$query = $this->pdo->prepare("UPDATE `teacher_staff_info` SET `name` = :name, `shift` = :shift, `section` = :section, `designation` = :designation, `sl` = :sl, `mobile` = :mobile WHERE `id` = :id");
				$result = $query->execute([
					':name' => $teacherName,
					':shift' => $shift,
					':section' => $section,
					':designation' => $designation,
					':sl' => $sl,
					':mobile' => $mobile,
					':id' => $id
				]);
				if($result){
					return '<p class="text-success">Teacher/Staff details updated successfully!</p>';
				}else{
					return '<p class="text-danger">Failed to update Teacher/Staff record.</p>';
				}
			}else{
				return '<p class="text-danger">Teacher name cannot be empty!</p>';
			}
		}

		// Delete Teacher/Staff record
		public function delete_teacher_staff($id){
			try{
				$query = $this->pdo->prepare("DELETE FROM `teacher_staff_info` WHERE `id` = :id");
				$result = $query->execute([':id' => $id]);
				if($result){
					return '<p class="text-success">Teacher/Staff record deleted successfully!</p>';
				}
			}catch(PDOException $e){
				return '<p class="text-danger">Failed to delete record: ' . htmlspecialchars($e->getMessage()) . '</p>';
			}
		}
		
        public function upload_to_gallery($data, $files){
				$files = $files['file'];
				$title = $data['title'];

				$name = $files['name'];
				$type = $files['type'];
				$tmp_name = $files['tmp_name'];
				$error = $files['error'];
				$size = $files['size'];

				$fileExt = explode('.', $name);
				$fileActualExt = strtolower(end($fileExt));

				$allowed = array('jpg', 'jpeg');

				if($error === 0){
					if(in_array($fileActualExt, $allowed)){
						if($size < 5242880){
							$fileNewName = uniqid("img_", true).".jpg";
							$fileDestination = "images/photo-gallery/". $fileNewName;
							if(move_uploaded_file($tmp_name, $fileDestination)){
                            $query1 = $this->pdo->prepare("INSERT INTO `website_gallery`(`image_url`, `title`) VALUES (:image_url, :title)")->execute([':image_url' => $fileDestination, ':title' => $title]);
                            $msg = '<p class="text-success">Image uploaded successfully!</p>';
							}
						}else{
							$msg = "<p class=\"text-danger\">Your file is too big, please upload a file smaller than 5 MB</p>";
						}
					}else{
						$msg = "<p class=\"text-danger\">Error with the file, you can only upload 'jpg', 'jpeg'!</p>";
					}
				}
			return $msg;

		} //Method Ends
        
        public function get_all_gallery_images(){
			$query = $this->pdo->prepare("SELECT * FROM website_gallery ORDER BY id DESC");
			$query->execute();
			if($query->rowCount() > 0){
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}else{
				return [];
			}
		}

		
		
	}

	$db = new DB();
?>