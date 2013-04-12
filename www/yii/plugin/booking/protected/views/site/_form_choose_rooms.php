<script>
function onChangeRoomCount(){
    alert('x');
}
</script>

	<div class='well span5'>

        <table>
            <tbody>
            <tr>
	            <th width=50px></th>
                <th width=50px></th>
                <th width=50px></th>
                <th width=50px></th>
            </tr>
            <tr>
                <td>
                    <div>Rooms
                    <select class="Xinput-small" name="numRooms" id="numRooms" style="width: 50px">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                    </div>
                </td>
                <td>Room 1</td>
                <td>
                    <div>Adults</div>
                        <select name="ctl05$ctl00$adultsServiced1" id="ctl05_ctl00_adultsServiced1">
                            <option value="1">1</option>
                            <option value="2" selected="selected">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                </td>
                <td>
                    <div id="ctl05_ctl00_pnlChildren1">
                        <div class="search_label child_label">Children</div>
                        <div class="search_input child_dropdown">
                            <select name="ctl05$ctl00$childrenServiced1" id="ctl05_ctl00_childrenServiced1">
                                <option value="0" selected="selected">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                </td>
                <td class="colspan_right"></td>
            </tr>
            <tr class="searchbox_row room2">
                <td class="search_item adults2_item rooms_count">Room 2</td>
                <td class="search_item adults2_item">
                    <div class="search_input adults2_dropdown">
                        <select name="ctl05$ctl00$adultsServiced2" id="ctl05_ctl00_adultsServiced2">
                            <option value="1">1</option>
                            <option value="2" selected="selected">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </td>
                <td class="search_item child2_item">
                    <div id="ctl05_ctl00_pnlChildren2">
                        <div class="search_input child2_dropdown">
                            <select name="ctl05$ctl00$childrenServiced2" id="ctl05_ctl00_childrenServiced2">
                                <option value="0" selected="selected">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                </td>
                <td class="colspan_right"></td>
            </tr>
            <tr class="searchbox_row room3">
                <td class="search_item adults2_item rooms_count">Room 3</td>
                <td class="search_item adults3_item">
                    <div class="search_input adults3_dropdown">
                        <select name="ctl05$ctl00$adultsServiced3" id="ctl05_ctl00_adultsServiced3">
                            <option value="1">1</option>
                            <option value="2" selected="selected">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </td>
                <td class="search_item child3_item">
                    <div id="ctl05_ctl00_pnlChildren3">
                        <div class="search_input child3_dropdown">
                            <select name="ctl05$ctl00$childrenServiced3" id="ctl05_ctl00_childrenServiced3">
                                <option value="0" selected="selected">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                </td>
                <td class="colspan_right"></td>
            </tr>
            <tr>
                <td colspan="7" class="button_search_container">
                    <div class="button_button"><input type="submit" name="ctl05$ctl00$checkAvailability" value="Check Availability" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl05$ctl00$checkAvailability&quot;, &quot;&quot;, true, &quot;availability&quot;, &quot;&quot;, false, false))" id="ctl05_ctl00_checkAvailability" class="input"></div>
                </td>
            </tr>
            </tbody>
        </table>

	</div>