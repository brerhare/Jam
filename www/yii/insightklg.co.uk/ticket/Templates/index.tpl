<div id="ShowError" class="ErrorMessage" style="display:none"></div>
<div class="ContentRight">
	<div class="ContentHeader">
	</div>
	<div class="ContentBodyText">

<style>
tr.spaceUnder > td
{
  padding-bottom: 1em;
}
.highlight { background-color:#dcdcdc; }
</style>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script>
$(document).ready(function() {
  document.getElementById('numFriday').selectedIndex = 0;
  calculateTotals();
});

function calculateTotals(){
  // Friday
  var selected = document.getElementById('numFriday').selectedIndex;
  var fridayCount = document.getElementById('numFriday').options[selected].value;

  var dueTotal = 0;
// kim added 3
document.getElementById('FQty').value = fridayCount;
document.getElementById('ShoppingCartAmount').value = 0;
}

function validateInput(){
  var err = "";
  var tickets = document.getElementById('numFriday').selectedIndex;
  var email1 = document.getElementById('Email1').value;
  var email2 = document.getElementById('Email2').value;
  if ((tickets < 1) || (tickets > 40))
    err += "No tickets selected<br>";
  if ((email1 != email2) || (!email1) || (email1.indexOf('.') == -1) || (email1.indexOf('@') == -1))
    err += "Invalid email<br>";

// kim added 1
document.getElementById('Email').value = email1;

  if (err == "") {
    document.getElementById("Form").submit();
  }
  else {
    document.getElementById('ShowError').style.display = 'block'; 
    document.getElementById("ShowError").innerHTML = err;
	// kim remove this
	// alert('Ignoring errors\nRemove after testing'); document.getElementById("Form").submit();	//* @@TODO: remove
  }
}
</script>

		<table border="0" cellspacing="0" cellpadding="5" style="width:100%; color:#ffffff; background-color:#686884">
		<tr><td style="font-weight:bold">
<?
	$dbhandle="";
	_dbinit($dbhandle);
	$sql = "SELECT * FROM event where id = 1";
	$result = mysql_query($sql) or die(mysql_error());
	if (($q = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
		echo $q['title'];
	else
		echo 'Undefined';
	_dbfin($dbhandle);
?>
		</td></tr>
		<tr><td>
<?
	$dbhandle="";
	_dbinit($dbhandle);
	$sql = "SELECT * FROM event where id = 1";
	$result = mysql_query($sql) or die(mysql_error());
	if (($q = mysql_fetch_array($result, MYSQL_ASSOC)) == true)
		echo $q['start_date'];
	else
		echo 'Undefined';
	_dbfin($dbhandle);
?>
		</td></tr>
		</table>

		<p>

		<table border="0" rowspacing="5px" cellspacing="0" cellpadding="5" class="BookingEmphasis" style="width:100%;">
  		<tr class="spaceUnder">
    		<td style="width:20%; font-weight:bold" >Tickets</td>
    		<td style="width:40%;" >Aston Hotel, Dumfries</td>
    		<td style="width:20%;" >
      		<select id="numFriday" onChange="calculateTotals()">
        		<option value="0">0</option selected="selected">
        		<option value="1">1</option>
        		<option value="2">2</option>
      		</select>
    		</td>
    		<td id="fridayTotal" class="highlight" style="width:20%;font-weight:bold;align:right" >Free entry</td>
  		</tr>
		</table>

	</div>	<!-- /ContentBodyText -->

	<input type="hidden" id="FQty" name="FQty" value="0" />
	<input type="hidden" id="Email" name="Email" value="" />
	<input type="hidden" id="ShoppingCartAmount" name="ShoppingCartAmount" value="0" />

</div>
	
<div class="ContentRight">
	<div class="ContentHeader">
		Contact Details
	</div>
    <div class="FormItem">
        <div class="FormLabel">Email Address:</div>
        <div class="FormInput">
            <input id="Email1" value="" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">Again:</div>
        <div class="FormInput">
            <input id="Email2" value="" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">Telephone Number:</div>
        <div class="FormInput">
            <input name="Phone" value="" class="InputTextField" MaxLength="15" style="width:100px"/>
        </div>
    </div>

<p>&nbsp<p>
* By using this service you are agreeing to our <a href="http://www.insightKLG.co.uk/ticket/tandc.html" target="_blank">Terms & Conditions</a> as well as the conditions displayed below
<p><hr><p>

    <input type="button" value="Cancel" style="float:left" onClick="javascript: window.top.location.href = 'http://www.insightklg.co.uk'; "/>
			<input type="submit" value="Reserve" style="float:right;background-color:#cedf7b" onClick="validateInput();return false;" />


<!--	<div class="FormItem">
		<div class="FormSubmitRight">
			<input type="submit" value="Continue to payment" onClick="validateInput();return false;" />
		</div>
	</div>
-->
<!-- <p>&nbsp<p>&nbsp<center><img src="img/PaymentSense_Extended_Plus_645x50.png"></center> -->
<p>&nbsp<p>&nbsp<p><p>
<? echo $q['ticket_text']; ?>


</div>

