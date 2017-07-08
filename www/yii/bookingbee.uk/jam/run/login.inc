{{@database bookingbee_uk}}

{{@include /jam/run/onload.inc}}

{{validUser = 0}}
{{userData = }}
{{@each user filter cookie = sys.cookie.bookingbee_uk, logged_in = 1}}
	{{validUser = 1}}
	{{userData = user.user_data}}
{{@end}}

{{@each user filter id > 0}}
	{{@skip validUser=1}}
	<script>
	addLoadEvent(function() {
		runJam("login");
	});
	</script>
	<!-- die gracefully -->
	{{@include /jam/sys/html/footer.html}}
	{{@stop}}
{{@end}}

{{@get seller filter sid = userData}}

