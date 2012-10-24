		<div class="row">
			<div class="span7 clearfix">
				<h2>What's going on in the coliseum</h2>
				
				<!-- <form action="" method="post" accept-charset="utf-8">
					<div class="input-append">
						<input class="span6" id="" type="text" placeholder="Event name" name="search"><button class="btn" type="button">Find Event</button>
					</div>
				</form>
				<div class="clear"></div> -->
				{eventsList}
			</div>
			<div class="span5">
				<h3>Creating an account is easy as 1-2-3.</h3>
				<form class="form-horizontal pull-left" action="login" method="post" accept-charset="utf-8">
				  <div class="control-group">
					<label class="control-label" for="inputFirstName">First name</label>
					<div class="controls">
					  <input type="text" id="inputFirstName" placeholder="First name" class="span2" name="signup_firstname">
					</div>
				  </div>
				  
				  <div class="control-group">
					<label class="control-label" for="inputLastName">Last name</label>
					<div class="controls">
					  <input type="text" id="inputLastName" placeholder="Last name" class="span2" name="signup_lastname">
					</div>
				  </div>
				  
				  <div class="control-group">
					<label class="control-label" for="inputEmail">Email</label>
					<div class="controls">
					  <input type="text" id="inputEmail" placeholder="Email" class="span2" name="signup_email">
					</div>
				  </div>	
				  
				  <div class="control-group">
					<label class="control-label" for="inputPassword">Password</label>
					<div class="controls">
					  <input type="password" id="inputPassword" placeholder="Password" class="span2" name="signup_password">
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