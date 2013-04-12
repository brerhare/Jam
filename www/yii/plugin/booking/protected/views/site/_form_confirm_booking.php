<script>
function onChangeRoomCount(){
    alert('x');
}
</script>


	<div class='well span5'>
        <table>
        <tbody>
            <tr>
                <th>Rooms</th>
                <th></th>
                <th>Adults</th>
                <th>Children</th>
	            <th></th>
            </tr>
            <tr>
                <td>
                    <select name="numRooms" id="numRooms" style="width: 50px">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </td>
                <td>Room 1</td>
                <td>
                    <select name="numRooms" id="numAdults1" style="width: 50px">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </td>
                <td>
                    <select name="numRooms" id="numChildren1" style="width: 50px">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </td>
                <td class="button">
                    <div class="button_button"><input type="submit" name="ctl05$ctl00$checkAvailability" value="Check Availability" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl05$ctl00$checkAvailability&quot;, &quot;&quot;, true, &quot;availability&quot;, &quot;&quot;, false, false))" id="ctl05_ctl00_checkAvailability" class="input"></div>
                </td>
	        </tr>
        </tbody>
	    </table>
	</div>
