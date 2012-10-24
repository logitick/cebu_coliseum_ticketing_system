<h2>{heading}</h2>
<div class="Toolbar">
    <ul>
        <a href="/admin/accounts/"><li><img src="/media/images/icon-list.png">Accounts</li></a>
		<a href="/admin/events"><li><img src="/media/images/icon-calendar.png">Events</li></a>
		</ul>
    <div class="Clear"></div>
</div>
{notification}
<div>
	<form action="" method="post" accept-charset="utf-8" class="EntryForm">
		<div><label>Account Type:</label>
			<ul>
				<li><input type="radio" name="type" value="R" id="createTypeRegular" {rSelected}> <label>Regular</label></li>
				<li><input type="radio" name="type" value="A" id="createTypeAdmin" {aSelected}><label>Admin</label></li>
			</ul>
		</div>
		<div><label>First name: </label> <input type="text" name="firstname" value="{firstname}" id="createFirstname"  /></div>
		<div><label>Last name: </label> <input type="text" name="lastname" value="{lastname}" id="createLastname"  /></div>
		<div><label>Email: </label> <input type="text" name="email" value="{email}" id="id="createEmail"  /></div>
		<div><label>Password: </label> <input type="password" name="password" value="" id="createPassword"  /></div>
		<div><label>Verify Password: </label> <input type="password" name="verifyPassword" value="" id="createVerify"  /></div>
		{etc}
		<input type="submit" value="{buttonValue}" name="action"/>
	</form>
</div>