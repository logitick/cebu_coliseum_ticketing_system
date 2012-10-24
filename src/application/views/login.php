		<div class="row">
		 {message}
		</div>
		<div class="row">
			<div class="span6 clearfix">
				<h3>Login here</h3>
			<form class="form-horizontal " action="login" method="post" accept-charset="utf-8">			  
			  <div class="control-group">
				  <label class="control-label" for="loginEmail">Email</label>
				<div class="controls">
				  <input type="text" id="loginEmail" placeholder="Email" class="" name="login_email">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="loginPassword">Password</label>
				<div class="controls">
				  <input type="password" id="loginPassword" placeholder="Password" class="" name="login_password">
				</div>
			  </div>
			  <div class="control-group">
				<div class="controls"><button type="submit" class="btn btn-primary">Sign in</button></div>
			  </div>
            </form>

			</div>
			<div class="span6">
				<h3>Need an account?</h3>
				<form class="form-horizontal pull-left" action="login" method="post" accept-charset="utf-8">
				  <div class="control-group">
					<label class="control-label" for="inputFirstName">First name</label>
					<div class="controls">
					  <input type="text" id="inputFirstName"  class="span2" name="signup_firstname" value="{firstname}">
					</div>
				  </div>
				  
				  <div class="control-group">
					<label class="control-label" for="inputLastName">Last name</label>
					<div class="controls">
					  <input type="text" id="inputLastName" class="span2" name="signup_lastname" value="{lastname}">
					</div>
				  </div>
				  
				  <div class="control-group">
					<label class="control-label" for="inputEmail">Email</label>
					<div class="controls">
					  <input type="text" id="inputEmail" class="span2" name="signup_email" value="{email}">
					</div>
				  </div>	
				  
				  <div class="control-group">
					<label class="control-label" for="inputPassword">Password</label>
					<div class="controls">
					  <input type="password" id="inputPassword" class="span2" name="signup_password">
					</div>
				  </div>	
				  <div class="control-group">
					<label class="control-label" for="inputVPassword">Verify Password</label>
					<div class="controls">
					  <input type="password" id="inputVPassword" placeholder="Enter password again" class="span2" name="signup_verify">
					</div>
				  </div> 
				  <div class="control-group">
					<div class="controls"><button type="submit" class="btn" name="action" value="Sign up">Sign up</button></div>
				  </div>
				  
				</form>
			</div>
		</div>	