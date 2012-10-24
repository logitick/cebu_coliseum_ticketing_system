
		<div class="row">
		 {message}
			<ul class="breadcrumb">
			  <li><a href="/buy/ticket/{event_id}">Select a Floor</a> <span class="divider">></span></li>
			  <li class="active"><a href="/buy/confirm">Pick Seats</a> <span class="divider">></span></li>
			  <li class="active"><a href="#">Process Payment</a> <span class="divider">></span></li>
			  <li class="active">Print Tickets</li>
			</ul>
		</div>
		<div class="row">
			<div class="span5">
				<h3>Payment Processing</h3>
				<form  action="" method="post" accept-charset="utf-8">
					<label>Choose payment method</label>
						<label class="radio">
						  <input type="radio" name="method" id="optionsRadios1" value="visa" {visaSelected}>
						  <img src="/media/images/icon-visa.png">
						</label>
						<label class="radio">
						  <input type="radio" name="method" id="optionsRadios2" value="mastercard" {mastercardSelected}>
						  <img src="/media/images/icon-mastercard.png">
						</label>
					<hr/>
					<label for="cardNumber">Credit Card Number:</label>
					<input type="text" name="cardNumber" id="cardNumber">
					<label for="verify">Verification Code  </label>
					<input type="text" class="span2" name="verify" id="verify">
					<a href="#myModal" data-toggle="modal"><sub><i class="icon-question-sign"></i>What's my verification code?</sub></a>
					<label for="expiry">Expiry Date <small>MM/YY</small></label>
					<input type="text" name="expiry" id="expiry" placeholder="MM/YY">
					<br/>
					<button class="btn btn-primary" name="action" value="Submit">Submit</button>
				</form>
			</div>
			<div class="span7">
				<h3>Summary of the transaction</h3>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Floor</th>
							<th>Section</th>
							<th>Row</th>
							<th>Seat Number</th>
							<th style="text-align:right">Price</th>
						</tr>
					</thead>
					<tbody>
						{summary}
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td><em>Total</em></td>
							<td style="text-align:right">{total}</td>
						</tr>
						
					</tbody>
				</table>
				<hr/>
			</div>
		</div>
<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">Your verification code</h3>
  </div>
  <div class="modal-body">
	<p align="center"><img src="/media/images/creditCardBack.gif"></p>
    <p>The verification code is usually the last three digits on the back of your credit card</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>