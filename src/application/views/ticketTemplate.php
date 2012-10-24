<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Le styles -->
    <link href="/media/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
	.sep {
		margin-top:20px;
		border-bottom:1px dashed #CCC;
	}
    </style>
   
  </head>

  <body>
	<div class="container">		<div class="row">
			<h4>Valid only for</h4>
			<h2>{event}</h2>
			<h4>{eventDate}</h4>
			<table class="table table-striped">
		
						<thead>
							<tr>
								<th colspan="4"><h3>Ticket Number: {ticketNumber}</h3></th>
							</tr>
						</thead>
						<thead>
							<tr>
								<th>Floor</th>
								<th>Section</th>
								<th>Row</th>
								<th>Seat Number</th>
							</tr>
						</thead>
					
							<tr>
								<td>{floor}</td>
								<td>{section}</td>
								<td>{row}</td>
								<td>{seat}</td>
							</tr>
			</table>
			<p>This document serves as your official ticket.</p>
			<small>Generated using the Cebu Coliseum Online Ticketing System</small>
		</div>		</div>
		
<div class="sep"></div>
  </body>
</html>