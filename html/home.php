
		
		<script type="text/javascript">
		$(document).ready(function() {
		
			$("#enggbranches").find("a").click(function(event) {
				var classid = $(event.target).parent().attr("id");
				var branchid = $(event.target).parent().parent().attr("id");
				
				var url="student.php?branch="+branchid+"&class="+classid;
				$(location).attr("href", url);
			});


			$("#staff").find("a").click(function(event) {
				// $(".active").toggleClass("active", false);
				var id = $(event.target).parent().attr("id");
				var url = "staff.php?formid="+id;
				$(location).attr("href", url);
			});
		});
		</script>

		<div id="content">
				<p>Vivekanand Education Society was established in 1959 under the able leadership of <i>Shri. Hashu Advani.</i> It is a matter of considerable pride and gratification that VES, with its excellent track record, has grown from strength to strength and has opened several educational institutions. Today, VES has 3, 75, 000 sq. ft. land housing 12 buildings and 28 Institutions, ranging from a crèche to Ph.D. Centers. It has a teaching and non-teaching staff of over 2000 that takes care of more than 18,000 students passing through the hallowed portals of education, each year.
				</p>
				<p>
					<b>V.E.S. Institute of Technology was established in 1984</b>, with the aim of providing professional education in the field of <u>Engineering</u>. V.E.S. Institute of Technology has been imparting quality education in the field of technology for the last 28 years which has been getting top preference by students in admission. The Institute is recognized by AICTE (All India Council of Technical Education) and affiliated to the Mumbai University.
				</p>
		</div>
		