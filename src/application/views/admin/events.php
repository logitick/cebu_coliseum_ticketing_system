<h2>Events</h2>
<div class="Toolbar">
    <ul>
        <a href="/admin/events/create"><li><img src="/media/images/icon-plus.png">Create new Event</li></a>
    </ul>
    <div class="Clear"></div>
</div>
<div id="eventModal" class="RoundedBorder">
	<a href="#" class="modal_close" style="float:right"><img src="/media/images/icon-cross.png"></a>
	<div id="loadingDiv"><img src="/media/images/loading.gif"></div>
	<div id="modalContent"></div>
</div>
{list}
    <script src="/media/js/jquery.min.js"></script>
    <script src="/media/js/jquery.leanModal.min.js"></script>
	<script>
		
		$(".modalTrigger").leanModal({ top : 200, overlay : 0.4, closeButton: ".modal_close" });
		$(".modalTrigger").click(function (){
			$("#modalContent").html("");
			$("#loadingDiv").show();
	
			$("#modalContent").load("/event/about/"+this.id+"/a", function(){
				
				$("#loadingDiv").hide();
			});
		});
	</script>
