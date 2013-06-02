<div class="ContentRight">
	<div class="ContentHeader">
		Posting The Data Securely To The Payment Form
	</div>
	<div class="ContentBodyText">
		<p>
			This page demonstrates how to post the transactional data across to the payment page in a <b>secure</b> 
			manner. The way this page does this is not the only way to achieve this, but if you are using the customer's browser to
			pass the data between your pages (if you are using FORM, URL or COOKIE variables) then your data
			will be susceptable to tampering by the customer if you do not protect it.
		</p>
		<p>
			The worst kinds of customer tampering could be lowering the transaction price (say from £100.00 down
			to £1.00), or making a failed transaction look like an authorised one. This is called a "man-in-the-
			middle" attack.
		</p>
		<p>
			The data in this example is protected by the use of Hashing. Hashing is used to produce a unique
			"signature" for the data being passed (it is generated using not only the data being transmitted, 
			but also secret data that is not transmitted, so the fraudster cannot recreate the hash digest 
			with the data that is passed via their browser). The hash signature is then re-calculated on 
			receipt of the transmitted data, and <b>if it does not match</b> the hash signature that was transmitted 
			with the data, then <b>the data has been tampered with</b>, and the transaction will stop with an 
			error message.
		</p>
		<p>
			If you are not using any client-side variables to transmit this data (e.g. using server side
			session variables, or you're storing the details in a database, and are only transmitting a
			reference to that data, then you will not need to go to the extra trouble of protecting yourself 
			in this way, as no sensitive data will be relayed through the customer's browser. 
		</p>
		<p>
			Having said that....
		</p>
		<div class="InfoMessage">
			<b>IT IS WELL WORTH HAVING A VERY CLOSE LOOK AT WHAT DATA YOU ARE GIVING 
			YOUR CUSTOMERS ACCESS TO AND ENSURING THAT YOU ARE NOT EXPOSED TO ANY KINDS OF FRAUD OF
			THE TYPE DESCRIBED ABOVE</b>
		</div>
		<p>
			This page is analogous to the place in shopping cart where you need to jump across to the payment
			form.
		</p>
	</div>

	<input type="hidden" name="ShoppingCartAmount" value="<?= $szAmount ?>" />
	<input type="hidden" name="ShoppingCartCurrencyShort" value="<?= $szCurrencyShort ?>" />
	<input type="hidden" name="ShoppingCartOrderID" value="<?= $szOrderID ?>" />
	<input type="hidden" name="ShoppingCartOrderDescription" value="<?= $szOrderDescription ?>" />
	<input type="hidden" name="ShoppingCartHashDigest" value="<?= $szHashDigest ?>" />
	
	<div class="FormItem">
		<div class="FormLabel">
			Amount:
		</div>
		<div class="FormInputTextOnly">
			<?= $szDisplayAmount ?>
		</div>
	</div>				
	<div class="FormItem">
		<div class="FormLabel">
			Currency:
		</div>
		<div class="FormInputTextOnly">
			<?= $szCurrencyShort ?>
		</div>
	</div>				
	<div class="FormItem">
		<div class="FormLabel">
			Order ID:
		</div>
		<div class="FormInputTextOnly">
			<?= $szOrderID ?>
		</div>
	</div>				
	<div class="FormItem">
		<div class="FormLabel">
			Order Description:
		</div>
		<div class="FormInputTextOnly">
			<?= $szOrderDescription ?>
		</div>
	</div>				
	<div class="FormItem">
		<div class="FormSubmit">
			<input type="submit" value="Post Data To Payment Form" />
		</div>
	</div>
</div>