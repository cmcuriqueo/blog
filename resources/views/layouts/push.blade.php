<script src="{{ asset('js/push.js/push.min.js') }}"></script>
<script type="text/javascript">
	Push.create("{{Session::get('title')}}", {
	    body: "{{Session::get('success')}}",
	    timeout: 6000,
	    onClick: function () {
	        window.focus();
	        this.close();
	    }
	});
</script>
