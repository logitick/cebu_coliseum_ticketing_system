		<div class="row">
		 {message}
		<ul class="breadcrumb">
		  <li><a href="">Select a Floor</a> <span class="divider">></span></li>
		  <li class="active">Pick Seats <span class="divider">></span></li>
		  <li class="active">Process Payment <span class="divider">></span></li>
		  <li class="active">Print Tickets</li>
		</ul>
		</div>

		<div class="row">
			
			<div class="span7">
				<h3>{heading}</h3>
				<a href="/event/about/{event_id}" class="btn btn-info">About the event</a>
				<hr/>
				<table class="table table-striped">
					<thead>
						<tr>
							<th></th>
							<th>Floor</th>
							<th>Price</th>
							<th>Availability</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><span class="img-rounded" style="background:#493727">&nbsp&nbsp&nbsp&nbsp</span></td>
							<td>Lower Box</td>
							<td>{lowerBoxPrice}</td>
							<td>{lowerBoxAvailability}</td>
						</tr>						<tr>
							<td><span class="img-rounded" style="background:#7FB756">&nbsp&nbsp&nbsp&nbsp</span></td>
							<td>Upper Box</td>
							<td>{upperBoxPrice}</td>
							<td>{upperBoxAvailability}</td>
						</tr>						<tr>
							<td><span class="img-rounded" style="background:#4CA3BF">&nbsp&nbsp&nbsp&nbsp</span></td>
							<td>General Admission</td>
							<td>{gaPrice}</td>
							<td>{gaAvailability}</td>
						</tr>
					</tbody>
				</table>
				<hr/>
				<div>
					<img src="/media/images/sections.png">
				</div>
			</div>
			<div class="span4">
				<h3>Pick your floor and section</h3>
				<p>Pick also how many tickets you would like to buy.</p>
				<form class="form-horizontal" action="/buy/confirm/" method="post" accept-charset="utf-8">
				  <div class="control-group">
					<label class="control-label" for="buy_ticket_floor">Floor</label>
					<div class="controls">
						<select id="buy_ticket_floor" name="buy_ticket_floor">
							{floors}
						</select>
					</div>
				  </div>				
				  <div class="control-group">
					<label class="control-label" for="buy_ticket_section">Section</label>
					<div class="controls">
						<select id="buy_ticket_section" name="buy_ticket_section">

<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option>
						</select>
					</div>
				  </div>
				  <div class="control-group">
					<label class="control-label" for="buy_ticket_quantity">Quantity</label>
					<div class="controls">
						<select id="buy_ticket_quantity" name="buy_ticket_quantity">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
					</div>
				  </div>
				  

				  <div class="control-group">

					<div class="controls"><button type="submit" class="btn btn-success" name="action" id="btnBuyTickets"value="Buy Tickets">Pick Seats</button></div>
				  </div>
					<input type="hidden" value="{event_id}" name="event_id">
				</form>
			</div>
		</div>	