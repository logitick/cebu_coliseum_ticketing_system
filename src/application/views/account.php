		<div class="row">
			{message}
		</div>
		<div class="row">
			<h3>{name}'s Account</h3>
			<span>Joined: {joinDate}</span>
			<hr/>
			<p>
				<h4>Email </h4>
				{email} <br/>
				<a href="#emailModal" role="button" class="btn" data-toggle="modal"><i class="icon-pencil"></i> Edit email</a>
			</p>
			<hr/>
			<p>
				<h4><a href="#passwordModal" title="change password" data-toggle="modal">Change password <i class="icon-pencil"></i></a></h4>
			</p>
			

			
<div class="modal hide" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
  <form action="" method="post" accept-charset="utf-8">
	  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="emailModalLabel">Change Email</h3>
  </div>
  <div class="modal-body">
    <p>
		
		<label>New email</label>
		<input type="text" name="new_email" placeholder="new email address">
	</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary" name="email_action" value="1">Save changes</button>
  </div>
  </form>
</div>

<div class="modal hide" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
  <form action="" method="post" accept-charset="utf-8">
	  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="passwordModalLabel">Change Password</h3>
  </div>
  <div class="modal-body">
    <p>
		<label>Current password</label>
		<input type="password" name="old_password">
		<label>New password</label>
		<input type="password" name="new_password" placeholder="enter your new password">
		<label>Verify new password</label>
		<input type="password" name="verify_password" placeholder="enter new password again">
	</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary" name="password_action" value="1">Save changes</button>
  </div>
  </form>
</div>
		</div>	