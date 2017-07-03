{{@database bookingbee_uk}}

{{validUser = 0}}
{{@each user filter cookie = sys.cookie.bookingbee_uk, logged_in = 1}}
	{{validUser = 1}}
{{@end}}

{{@each user filter id > 0}}
	{{@skip validUser=1}}
	<script>
	window.onload = function() {
		runJam("login");
	};
	</script>
	<!-- die gracefully -->
	{{@include /jam/sys/html/footer.html}}
	{{@stop}}
{{@end}}

