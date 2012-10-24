<h2>{heading}</h2>
<div class="Toolbar">
    <ul>
        <a href="/admin/events"><li><img src="/media/images/icon-calendar.png">Events List</li></a>
		<a href="/admin/accounts/"><li><img src="/media/images/icon-list.png">Accounts</li></a>
		</ul>
    <div class="Clear"></div>
</div>
{notification}
<div>
	<form action="" method="post" accept-charset="utf-8" class="EntryForm">
		<div><label>Event: </label> <input type="text" name="event" value="{name}" id="createName"  /></div>		
		<div><label style="float:left;">Description: </label> <textarea name="description"  id="createDescription" rows="5" />{description}</textarea><div class="Clear"></div></div>		
		<div><label>Date: </label> <input type="text" name="date" value="{date}" id="createDate"  /></div>	
		<div>
			{areaPriceEntries}
		</div>
		<input type="submit" value="{buttonValue}" name="action"/>
	</form>
</div>