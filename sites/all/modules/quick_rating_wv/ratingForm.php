<html>
	<head>
		<title></title>
	</head>
	<body>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<div class="post">
					<form id="myForm" action="<?php $PHP_SELF ?>" method="post">
						<label>Service, Beratung & Support:</label>
							<select name="service">
								<option value="1">1/6</option>
								<option value="2">2/6</option>
								<option value="3">3/6</option>
								<option value="4">4/6</option>
								<option value="5">5/6</option>
								<option value="6">6/6</option>
							</select>
							<br />
						<label>Banking & Prozesse:</label>
							<select name="prozesse">
								<option value="1">1/6</option>
								<option value="2">2/6</option>
								<option value="3">3/6</option>
								<option value="4">4/6</option>
								<option value="5">5/6</option>
								<option value="6">6/6</option>
							</select>
							<br />
						<label>Bank weiterempfehlen:</label>
							<input type="radio" name="bankJa">Ja</input>
							<input type="radio" name="bankNein">Nein</input>
							<br />
							<input type="submit" name="submit" value="Bewerten"/>
					</form>
				</div><!--Post 1 Ende-->
				<div class="post">
					<?php
						echo $_POST["service"]."<br />";
						echo $_POST["prozesse"]."<br />";
						echo $_POST["bankJa"]."<br />";
						echo $_POST["bankNein"]."<br />";
					?>
				</div>
			</div><!--Tab1 Ende-->
		</div><!--Tab3 Ende-->
	</body>
</html>

