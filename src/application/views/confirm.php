		<div class="row">
		 {message}
		<ul class="breadcrumb">
		  <li><a href="/buy/ticket/{event_id}">Select a Floor</a> <span class="divider">></span></li>
		  <li class="active"><a href="#">Pick Seats</a> <span class="divider">></span></li>
		  <li class="active">Process Payment <span class="divider">></span></li>
		  <li class="active">Print Tickets</li>
		</ul>
		</div>
		<div class="row">
			<form class="form-horizontal" action="" method="post" accept-charset="utf-8">
			<div class="span7">
				<h3>{heading}</h3>
				<p>{text}</p>
				
					{form}
				<button type="submit" name="action" class="btn btn-success pull-right" value="Process Payment">Process Payment</button>
			</div>
			<div class="span4">
				<p>A summary of your tickets</p>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Floor</th>
							<th>Section</th>
							<th>Quantity</th>
							<th>Price</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{floor}</td>
							<td>{section}</td>
							<td>{quantity}</td>
							<td style="text-align:right">{price}</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td><em>Total</em></td>
							<td style="text-align:right">{total}</td>
						</tr>
						
					</tbody>
				</table>
				<div>
					
				</div>
			</div>
			</form>
		</div>	
		