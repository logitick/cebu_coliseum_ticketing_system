<?php
function getWarning($heading, $message = "") {
		return '
			<div class="alert alert-block">
			  <h4>'.$heading.'</h4>
			  '.$message.'
			</div>';
}
function getError($heading, $message = "") {
		return '
			<div class="alert alert-error">
			  <h4>'.$heading.'</h4>
			  '.$message.'
			</div>';
}
function getSuccess($heading, $message = "") {
		return '
			<div class="alert alert-success">
			  <h4>'.$heading.'</h4>
			  '.$message.'
			</div>';
}