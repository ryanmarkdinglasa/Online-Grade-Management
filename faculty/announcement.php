<?php
	//error_reporting(0);
	session_start();
	date_default_timezone_set('Asia/Manila');
	include("include/session.php");
	$parentpage=''; $content_right='' ; $parent_link='';
	$currentpage=$page='announcement';
	include("include/header.php");
	include("../include/conn.php");
	include("../include/function.php");
?>
    </head>
	<?php include("include/sidebar.php"); ?>
		<!-- Main content -->
		<div class="main-content" id="panel">
			<?php
				include("include/topnav.php");
				include("include/snackbar.php");
				include "include/breadcrumbs.php"; // Snackbar & Breadcrumbs -->
			?>
			<div class="container-fluid mt--6">
				<div class="card mb-4">
					<!-- Card header -->
					<div class="card-header">
						<h3 class="mb-0 font-weight-bolder text-primary">Create Announcement</h3>
					</div>
					<!-- Card body -->
					<div class="card-body">
						<!-- Form groups used in grid -->
						<form action="announcement_controller.php"role="form" method="POST">
								<div class="col-md-4">
									<div class="form-group">
										<label class="form-control-label" for="exampleFormControlInput1">Announcement Title</label>
										<input name="title" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Announcement Title"required>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-control-label" for="exampleFormControlInput2">Announcement Details</label>
										<textarea class="ckeditor"  name="context" id="exampleFormControlTextarea2" rows="8" resize="none" required></textarea>
									</div>
									<div class="text-left">
										<button type="submit" id="post" name="post" class="btn bg-green text-primary my-2 font-weight-bolder">Post Announcement</button>
									</div>
								</div>
						</form>
					</div>
				</div>
				<div class="row">
					<?php
						try{
							$sql = "SELECT 
								`post`.`id` AS `post_id`,
								`post`.`user_id` AS `post_user`,
								`post`.`title` AS `post_title`,
								`post`.`context` AS `post_context`,
								`post`.`status` AS `post_status`,
								`post`.`created_on` AS `post_on`,
								`user`.`type` AS `user_type`,
								`user`.`profileImage` AS `user_image`,
								`user`.`firstname` AS `user_fname`, `user`.`lastname` AS `user_lname`
								FROM `post` 
								INNER JOIN `user` ON `user`.`id`= `post`.`user_id`
								WHERE `user`.`type`='".$user['type']."' AND `post`.`status`='1' ORDER BY `post_on` DESC";
							$query = $con->query($sql);
							$count=$query->rowCount();
						if($count<1){
					?>
						<div class="col-xl-12">
							<div class="card">
							  <div class="card-header">
								<h6 class="text-black text-uppercase ls-1 mb-1">ANNOUNCEMENT</h6>
								<h5 class="h3 text-uppercase text-primary mb-0"> </h5>
								<span class="text-muted"></span></h5>
							  </div>
							  <div class='card-body'><span class="text-muted">No Data Found</span>
							  </div>
							</div>
						</div>
					<?php } while ($row = $query->fetch(PDO::FETCH_ASSOC)) { ?>
					<div class="col-xl-12">
						<div class="card">
						  <div class="card-header">
							<div class="poster">
							<?php 
								$userphoto = isset($row['user_image']) ? htmlspecialchars($row['user_image'], ENT_QUOTES, 'UTF-8') : '';
								$firstname = isset($row['user_fname']) ? htmlspecialchars($row['user_fname'], ENT_QUOTES, 'UTF-8') : '';
								$lastname = isset($row['user_lname']) ? htmlspecialchars($row['user_lname'], ENT_QUOTES, 'UTF-8') : '';
								$post_on = isset($row['post_on']) ? htmlspecialchars(created_on($row['post_on']), ENT_QUOTES, 'UTF-8') : '';
							if ($userphoto == "" || $userphoto == "NULL") :
								 echo' <img src="img/profile.png" class="avatar rounded-circle mr-3">';
								else : 
								   echo'<img src="img/'.htmlentities($userphoto).'" class="avatar rounded-circle mr-3">';
								endif;
								//Posted By & Posted On
								echo '<h5 class="text-black  mb-0">'.$firstname.' '.$lastname.'<br>';
								echo'<span class="text-muted""><small>';
								echo $post_on;
								echo'</small></span></h5>';
								?>
								<?php
								if($user['user_id']==$row['post_user']){
								?>
								<div class="text-right" style="margin-top:-30px;">
									<div class="dropdown">
										<a class="btn btn-sm btn-icon-only bg-light rounded-circle shadow text-primary" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fas fa-ellipsis-v"></i>
										</a>
									  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<a class="dropdown-item" href="announcement_edit.php?id=<?php echo $row['post_id'] ?>&edit=edit" style="color: black;" type="button"><i class="fas fa-pen text-primary" ></i> Edit post</a>
										<a class="dropdown-item" href="announcement_controller.php?id=<?php echo $row['post_id'] ?>&del=delete" onClick="return confirm('Are you sure you want to clear, <?php echo htmlentities($row['post_title']); ?> ?')"><i class="fas fa-trash text-primary" ></i> Delete post</a>
									  </div>
									</div>
								</div>
								<?php }?>
							</div>
						  </div>
						  <div class='card-body'>
							<h5 class="h3 text-uppercase text-primary mb-0"><?php
							$title = isset($row['post_title']) ? htmlspecialchars($row['post_title'], ENT_QUOTES, 'UTF-8') : '';
							echo $title; ?></h5>
							<?php 
							$context = isset($row['post_context']) ? $row['post_context'] : '';
							echo $context; ?>
						  </div>
						</div>
					</div>
					<?php	
						}//while
					}catch(Exception $e){
						$_SESSION['error']='Something went wrong in accessing annoouncement post.';
					}
					?>
				</div>
				<?php include("include/footer.php"); ?>
			</div>
		</div>
    </body>
</html>
