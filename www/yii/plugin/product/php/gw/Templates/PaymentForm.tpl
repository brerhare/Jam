<input name="FormMode" type="hidden" value="<?= $NextFormMode ?>" />
<input name="HashDigest" type="hidden" value="<?= $szHashDigest ?>" />

<div class="ContentRight">
    <div class="ContentHeader">
        Order Details
    </div>
    <div class="FormItem">
        <div class="FormLabel">Amount:</div>
        <div class="FormInputTextOnly"><?= $szDisplayAmount ?></div>
		<input type="hidden" name="Amount" value="<?= $Amount ?>" />
		<input type="hidden" name="CurrencyShort" value="<?= $CurrencyShort ?>" />
	</div>
	<div class="FormItem">
		<div class="FormLabel">Order Description:</div>
		<div class="FormInputTextOnly"><?= $OrderDescription ?></div>
		<input type="hidden" name="OrderID" value="<?= $OrderID ?>" />
		<input type="hidden" name="OrderDescription" value="<?= $OrderDescription ?>" />
	</div>
</div>
<div class="ContentRight">
    <div class="ContentHeader">
        Card Details
    </div>
    <div class="FormItem">
        <div class="FormLabel">Name On Card:</div>
        <div class="FormInput">
            <input name="CardName" value="<?= $CardName ?>" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">Card Number:</div>
        <div class="FormInput">            
            <input name="CardNumber" value="<?= $CardNumber ?>" class="InputTextField" MaxLength="20" />
        </div>
    </div>
	<div class="FormItem">
		<div class="FormLabel">
			Expiry Date:
		</div>
		<div class="FormInput">
			<select name="ExpiryDateMonth" style="width:45px">
				<option></option>
				<?= $lilExpiryDateMonthList->toString() ?>
			</select>
			/
			<select name="ExpiryDateYear" style="width:55px">
				<option></option>
				<?= $lilExpiryDateYearList->toString() ?>
			</select>
		</div>
	</div>
	<div class="FormItem">
	    <div class="FormLabel">
	        Start Date:
	    </div>
	    <div class="FormInput">
	        <select name="StartDateMonth" style="width:45px">
				<option></option>
				<?= $lilStartDateMonthList->toString() ?>
			</select>
			/
			<select name="StartDateYear" style="width:55px">
				<option></option>
				<?= $lilStartDateYearList->toString() ?>
            </select>
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">Issue Number:</div>
        <div class="FormInput">
            <input name="IssueNumber" value="<?= $IssueNumber ?>" class="InputTextField" MaxLength="2" style="width:50px" />
        </div>
        <div class="FormValnameationText"></div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">CV2:</div>
        <div class="FormInput">
            <input name="CV2" value="<?= $CV2 ?>" class="InputTextField" MaxLength="4" style="width:50px" />
<!-- @@NB Added next line -->
			&nbsp(last 3 digits on the back)
        </div>
    </div>
</div>

<div class="ContentRight">
    <div class="ContentHeader">
        Customer Details
    </div>
    <div class="FormItem">
        <div class="FormLabel">Address:</div>
        <div class="FormInput">
            <input name="Address1" value="<?= $Address1 ?>" class="InputTextField" MaxLength="100" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">&nbsp</div>
        <div class="FormInput">
            <input name="Address2" value="<?= $Address2 ?>" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">&nbsp</div>
        <div class="FormInput">
            <input name="Address3" value="<?= $Address3 ?>" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">&nbsp</div>
        <div class="FormInput">
            <input name="Address4" value="<?= $Address4 ?>" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">City:</div>
        <div class="FormInput">
            <input name="City" value="<?= $City ?>" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">State:</div>
        <div class="FormInput">
            <input name="State" value="<?= $State ?>" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">Post Code:</div>
        <div class="FormInput">
            <input name="PostCode" value="<?= $PostCode ?>" class="InputTextField" MaxLength="50" />
        </div>
    </div>
    <div class="FormItem">
        <div class="FormLabel">
            Country:
        </div>
        <div class="FormInput">
            <select name="CountryShort" style="width:200px">
				<option value="-1"></option>
				<?= $lilISOCountryList->toString() ?>
			</select>
		</div>
	</div>
	<div class="FormItem">
		<div class="FormSubmit">
			<input type="submit" value="Submit For Processing" />
	    </div>
   	</div>
</div>
